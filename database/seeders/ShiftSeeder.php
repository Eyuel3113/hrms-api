<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    public function run(): void
    {
        $shifts = [
            [
                'name' => 'Day Shift',
                'start_time' => '09:00:00',
                'end_time' => '17:30:00',
                'late_threshold_minutes' => 15,
                'half_day_minutes' => 240, // 4 hours
                'overtime_rate' => 1.50,
                'is_default' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Morning Shift',
                'start_time' => '06:00:00',
                'end_time' => '14:00:00',
                'late_threshold_minutes' => 15,
                'half_day_minutes' => 240,
                'overtime_rate' => 1.50,
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Evening Shift',
                'start_time' => '14:00:00',
                'end_time' => '22:00:00',
                'late_threshold_minutes' => 15,
                'half_day_minutes' => 240,
                'overtime_rate' => 1.75,
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Night Shift',
                'start_time' => '22:00:00',
                'end_time' => '06:00:00',
                'late_threshold_minutes' => 15,
                'half_day_minutes' => 240,
                'overtime_rate' => 2.00,
                'is_default' => false,
                'is_active' => true,
            ],
        ];

        foreach ($shifts as $shift) {
            Shift::create($shift);
        }
    }
}
