<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // THIS LINE WAS MISSING — ADD IT!
        $this->call([
            AdminUserSeeder::class,
            ShiftSeeder::class,
            HolidaySeeder::class,
            LeaveTypeSeeder::class,
        ]);

        // Optional: create test user
        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);
    }
}