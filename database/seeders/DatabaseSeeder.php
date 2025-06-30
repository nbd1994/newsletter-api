<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'is_admin' => true,
            'password' => bcrypt('adminpass'), // Using bcrypt
        ]);
    // Normal user
    User::create([
        'name' => 'user',
        'email' => 'user@example.com',
        'is_admin' => false,
        'password' => bcrypt('password123'), // Using bcrypt
    ]);

    }
}
