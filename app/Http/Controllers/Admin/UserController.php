<?php

namespace App\Http\Controllers\Admin;
use Kreait\Firebase\Factory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Kreait\Firebase\Exception\AuthException;
use Kreait\Firebase\Auth;

class UserController extends Controller
{

    protected $auth;

    public function __construct()
    {
        $credentialsPath = storage_path('firebase/petcareapp-f62db-firebase-adminsdk-aol6n-3b08edf0a2.json');
    
        if (!file_exists($credentialsPath)) {
            die('Service account file not found at ' . $credentialsPath);
        }
        
        $this->auth = (new Factory)
            ->withServiceAccount($credentialsPath) // Use the correct path
            ->createAuth();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = $this->auth->listUsers(); // Fetch all users
            $userList = [];
            
            foreach ($users as $user) {
                $userList[] = [
                    'uid' => $user->uid,
                    'email' => $user->email,
                ];
            }
            return view('admin.users.index', ['users' => $userList]);
        } catch (\Kreait\Firebase\Exception\AuthException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // try {
        //     // Create a user with email and password
        //     $user = $this->firebaseAuth->createUserWithEmailAndPassword($request->email, $request->password);
    
        //     // Store user information in Firebase Database (optional)
        //     $postData = [
        //         'fname' => $request->first_name,
        //         'lname' => $request->last_name,
        //         'phone' => $request->phone,
        //         'email' => $request->email,
        //         'password' => $request->password,
        //     ];
    
        //     $postRef = $this->database->getReference($this->tablename)->push($postData);
    
        //     return redirect()->route('users.index')->with('status', 'User added successfully');
        // } catch (\Exception $e) {
        //     \Log::error('User Creation Error: ' . $e->getMessage());
        //     return redirect()->route('users.index')->with('status', 'User not added successfully');
        // }
    }
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($uid)
    {
        try {
            $user = $this->auth->getUser($uid); 

            return view('admin.users.edit', ['user' => $user]); 
        } catch (AuthException $e) {
            return redirect()->route('users.index')->with('error', 'User not found or Firebase error.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $uid)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            $updatedUser = $this->auth->updateUser($uid, [
                'email' => $request->email,
            ]);

            return redirect()->route('users.index')->with('status', 'User updated successfully.');
        } catch (AuthException $e) {
            return redirect()->route('users.index')->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    /**
     * Delete the user from Firebase.
     */
    public function destroy($uid)
    {
        try {
            $this->auth->deleteUser($uid); 

            return redirect()->route('users.index')->with('status', 'User deleted successfully.');
        } catch (AuthException $e) {
            return redirect()->route('users.index')->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

}
