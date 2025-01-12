<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Exception\AuthException;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected $auth;

    public function __construct(FirebaseAuth $auth)
    {
        $this->auth = $auth;
    }

    // Show the login form
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    // Handle login request
    public function login(Request $request)
    {
        $idToken = $request->bearerToken();  // Get Firebase token from Authorization header

        try {
            // Verify the Firebase token
            $verifiedIdToken = $this->auth->verifyIdToken($idToken);

            // Get Firebase user ID using claims() instead of getClaim()
            $uid = $verifiedIdToken->claims()->get('sub');  // Get Firebase user ID

            // Find the user in your Laravel database based on the Firebase UID
            $user = User::where('firebase_uid', $uid)->first();

            if ($user && $user->is_admin) {
                // Log in the user as an admin
                Auth::login($user);

                // Log the successful login
                Log::info('Admin login successful', ['uid' => $uid, 'email' => $user->email]);

                return response()->json([
                    'success' => true,
                    'message' => 'Logged in as admin',
                    'redirect_url' => route('dashboard') // Provide a redirect URL if needed
                ], 200);
            }

            // Log the failed login attempt due to non-admin
            Log::warning('Login attempt by non-admin user', ['uid' => $uid]);

            return response()->json([
                'success' => false,
                'error' => 'Not an admin',
            ], 403);
        } catch (AuthException $e) {
            // Log the error for debugging
            Log::error('Firebase token verification failed', [
                'error' => $e->getMessage(),
                'token' => $idToken
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Invalid Firebase token',
            ], 401);
        } catch (\Exception $e) {
            // Catch any other unexpected errors and log them
            Log::critical('Unexpected error during login', [
                'error' => $e->getMessage(),
                'token' => $idToken
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }
    
    public function logout(Request $request)
    {
        try {
            Auth::logout();
            Log::info('User logged out', ['uid' => Auth::id()]);
            return redirect()->route('login')->with('message', 'Successfully logged out');
        } catch (\Exception $e) {
            Log::error('Error during logout', [
                'error' => $e->getMessage()
            ]);
            return redirect()->route('dashboard')->with('error', 'Something went wrong. Please try again later.');
        }
    }
    

}
