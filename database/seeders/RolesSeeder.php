<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Kreait\Firebase\Auth;
use App\Models\User;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Role::create(['name' => 'admin']);
        // Role::create(['name' => 'user']);

        $auth = app('firebase.auth');
        $user = $auth->createUserWithEmailAndPassword('admin@example.com', 'password');
        $userModel = User::create(['email' => 'admin@example.com']);
        $userModel->assignRole('admin');
    }
}
