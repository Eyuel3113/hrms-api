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
            ]
           
        ];

        foreach ($shifts as $shift) {
            Shift::create($shift);
        }
    }
}







// StatusConditionPresentEmployee does 4 check-ins/check-outs correctly:
// 1. Morning check-in (on time or within grace)
// 2. Morning check-out (break start time ± grace)
// 3. Afternoon check-in (break end time ± grace)
// 4. Afternoon check-out (shift end time or after)Absent1. No check-in at all
// 2. Check-in but no check-out at all
// 3. Worked minutes < half_day_minutes (even if some check-in)Half DayHas check-in and check-out, but total worked minutes ≥ half_day_minutes but < full day expectedLateMorning or afternoon check-in after grace period
