<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@hrm.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password123'),
            ]
        );
    }
}
