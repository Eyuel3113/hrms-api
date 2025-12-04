<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // THIS LINE WAS MISSING — ADD IT!
        $this->call([
            AdminUserSeeder::class,   // ← this creates the admin
            // Add other seeders later if you want
        ]);

        // Optional: create test user
        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);
    }
}