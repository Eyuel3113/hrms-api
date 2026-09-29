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

        // Test user
        \App\Models\User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]
        );
    }
}