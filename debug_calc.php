<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$emp = App\Models\Employee::whereHas('shift', fn($q) => $q->where('type', 'split'))->first();
if (!$emp) {
    die("No split shift employee found.");
}

echo "Testing with Employee: " . $emp->id . PHP_EOL;

// Create Attendance
$att = App\Models\Attendance::create([
    'employee_id' => $emp->id,
    'date' => today(),
    'check_in' => '08:00:00', // Start time
    'status' => 'present' // default
]);

echo "Created Attendance ID: " . $att->id . PHP_EOL;

// Simulate Check Out after 4 hours
$att->update(['check_out' => '12:00:00']);
$att = $att->fresh();

// Calculate
$controller = new App\Http\Controllers\Api\AttendanceController();
// We can't call private method directly, so we use reflection or copy logic. 
// Actually, let's just inspect what checkOut() does. checkOut calls calculations.
// But checkOut is an HTTP method. 
// We should expose calculateAttendanceStatus as public or use reflection for testing.

// Using Reflection to call private method
$reflection = new ReflectionClass($controller);
$method = $reflection->getMethod('calculateAttendanceStatus');
$method->setAccessible(true);
$method->invokeArgs($controller, [$att]);

$att = $att->fresh();
echo "Status: " . $att->status . PHP_EOL;
echo "Worked Minutes: " . $att->worked_minutes . PHP_EOL;
echo "Half Day Threshold: " . ($emp->shift->half_day_minutes / 2) . PHP_EOL;
