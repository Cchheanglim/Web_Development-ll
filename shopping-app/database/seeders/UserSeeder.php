<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seeds only the admin account. There's no self-registration path for
     * admins (see AuthController — signup only offers buyer/seller), so
     * this is the one account that has to exist from the start. Everyone
     * else (buyers, sellers, and their products/photos) should be created
     * through the app itself: /register, then "+ Post product".
     */
    public function run(): void
    {
        User::create([
            'name' => 'Site Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ADMIN,
        ]);
    }
}
