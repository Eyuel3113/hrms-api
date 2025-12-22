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




but i want to make the 
- present for the employee that come on time and leave ontime on both start time/ckeck-in ,breake time/check-in and also breake time/check-out, closure time /check-out 
and all include the grace time allso the both check in and check out for both normal and break time  
-absent for the empployee totally doesnot check-in in the day and the man who check-in and check out at all and also the man whose worked hour less then half day minutes
-half day for the employee who check-in and check-out but the total worked hour is greater than the half day minutes defined in the shift but less than the full day minutes  and also 
-late for the employee who check-in after the grace time defined in the shift for both normal and breake time check-in but check-out on time 
-ealry leave for the employee who check-out before the grace time defined in the shift for both normal and breake time check-out