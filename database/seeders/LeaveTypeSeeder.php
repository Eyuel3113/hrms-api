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
            ],
            [
                'name' => 'Sick Leave',
                'default_days' => 15,
                'is_paid' => true,
                'requires_approval' => false,
            ],
            [
                'name' => 'Maternity Leave',
                'default_days' => 120,
                'is_paid' => true,
                'requires_approval' => true,
            ],
            [
                'name' => 'Paternity Leave',
                'default_days' => 5,
                'is_paid' => true,
                'requires_approval' => true,
            ],
            [
                'name' => 'Bereavement Leave',
                'default_days' => 7,
                'is_paid' => true,
                'requires_approval' => false,
            ],
            [
                'name' => 'Unpaid Leave',
                'default_days' => 30,
                'is_paid' => false,
                'requires_approval' => true,
            ],
            [
                'name' => 'Study Leave',
                'default_days' => 10,
                'is_paid' => true,
                'requires_approval' => true,
            ],
            [
                'name' => 'Compassionate Leave',
                'default_days' => 5,
                'is_paid' => true,
                'requires_approval' => true,
            ],
        ];

        foreach ($leaveTypes as $leaveType) {
            LeaveType::create($leaveType);
        }
    }
}
