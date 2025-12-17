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
                'type' => 'regular',
                'start_time' => '09:00:00',
                'end_time' => '17:30:00',
                'break_start_time' => '12:00:00',
                'break_end_time' => '13:00:00',
                'late_threshold_minutes' => 15,
                'grace_period_minutes' => 15,
                'half_day_minutes' => 240,
                'overtime_rate' => 1.50,
                'is_default' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Split Shift',
                'type' => 'split',
                'start_time' => '08:00:00',
                'end_time' => '18:00:00',
                'break_start_time' => '12:00:00',
                'break_end_time' => '14:00:00',
                'late_threshold_minutes' => 15,
                'grace_period_minutes' => 15,
                'half_day_minutes' => 240,
                'overtime_rate' => 1.50,
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Night Shift',
                'type' => 'regular',
                'start_time' => '22:00:00',
                'end_time' => '06:00:00',
                'late_threshold_minutes' => 15,
                'grace_period_minutes' => 15,
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
