<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Ensure we have test employees for each shift
$shifts = App\Models\Shift::all()->keyBy('name');

$testEmployees = [
    'regular' => ['name' => 'Regular Employee', 'code' => 'EMP001', 'shift' => 'Day Shift'],
    'split'   => ['name' => 'Split Employee',   'code' => 'EMP002', 'shift' => 'Split Shift'],
    'night'   => ['name' => 'Night Employee',   'code' => 'EMP003', 'shift' => 'Night Shift'],
];

foreach ($testEmployees as $key => $data) {
    $shift = $shifts[$data['shift']] ?? null;
    if ($shift) {
        // Check if exists
        $emp = App\Models\Employee::where('employee_code', $data['code'])->first();
        if (!$emp) {
            $emp = App\Models\Employee::create([
                'employee_code' => $data['code'],
                'shift_id' => $shift->id,
                // Add minimal required fields if any (assuming nullable or has defaults, checking migration...)
                // Checking 2025_11_28_123541_create_employees_table.php:
                // Needs: nothing mandatory? let's check.
                // It usually needs personal info.
            ]);
            // Create dummy Personal Info
            $emp->personalInfo()->create([
                'first_name' => explode(' ', $data['name'])[0],
                'last_name' => explode(' ', $data['name'])[1],
                'email' => strtolower(str_replace(' ', '.', $data['name'])) . '@example.com',
                // Add other required fields if any. Assuming seeders handle this usually.
            ]);
        }
    }
}

echo "---DATA_START---" . PHP_EOL;
echo json_encode([
    'shifts' => App\Models\Shift::all(['id', 'name', 'type']),
    'employees' => App\Models\Employee::with('shift')->get()->map(function($e) {
        return [
            'id' => $e->id,
            'name' => $e->personalInfo->first_name . ' ' . $e->personalInfo->last_name,
            'code' => $e->employee_code,
            'shift' => $e->shift ? $e->shift->name : 'None',
            'shift_type' => $e->shift->type ?? 'regular'
        ];
    })
], JSON_PRETTY_PRINT);
echo PHP_EOL . "---DATA_END---";
