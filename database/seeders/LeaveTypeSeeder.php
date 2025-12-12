<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    public function run(): void
    {
        $leaveTypes = [
            [
                'name' => 'Annual Leave',
                'default_days' => 20,
                'is_paid' => true,
                'requires_approval' => true,
                'description' => 'Regular annual vacation leave',
            ],
            [
                'name' => 'Sick Leave',
                'default_days' => 15,
                'is_paid' => true,
                'requires_approval' => false,
                'description' => 'Medical or health-related leave',
            ],
            [
                'name' => 'Maternity Leave',
                'default_days' => 120,
                'is_paid' => true,
                'requires_approval' => true,
                'description' => 'Maternity leave for female employees',
            ],
            [
                'name' => 'Paternity Leave',
                'default_days' => 5,
                'is_paid' => true,
                'requires_approval' => true,
                'description' => 'Paternity leave for male employees',
            ],
            [
                'name' => 'Bereavement Leave',
                'default_days' => 7,
                'is_paid' => true,
                'requires_approval' => false,
                'description' => 'Leave for family member death',
            ],
            [
                'name' => 'Unpaid Leave',
                'default_days' => 30,
                'is_paid' => false,
                'requires_approval' => true,
                'description' => 'Leave without pay',
            ],
            [
                'name' => 'Study Leave',
                'default_days' => 10,
                'is_paid' => true,
                'requires_approval' => true,
                'description' => 'Leave for educational purposes',
            ],
            [
                'name' => 'Compassionate Leave',
                'default_days' => 5,
                'is_paid' => true,
                'requires_approval' => true,
                'description' => 'Leave for personal emergencies',
            ],
        ];

        foreach ($leaveTypes as $leaveType) {
            LeaveType::create($leaveType);
        }
    }
}
