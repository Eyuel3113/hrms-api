<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$emp = App\Models\Employee::whereHas('shift', fn($q) => $q->where('type', 'split'))->first();

// Clean up previous test
App\Models\Attendance::where('employee_id', $emp->id)->where('date', today())->delete();

echo "Testing Full Day for: " . $emp->id . PHP_EOL;

$controller = new App\Http\Controllers\Api\AttendanceController();
$reflection = new ReflectionClass($controller);
$method = $reflection->getMethod('calculateAttendanceStatus');
$method->setAccessible(true);

// 1. Morning Session (8-12)
$morning = App\Models\Attendance::create([
    'employee_id' => $emp->id,
    'date' => today(),
    'check_in' => '08:00:00'
]);
$morning->update(['check_out' => '12:00:00']);
$morning = $morning->fresh();
$method->invokeArgs($controller, [$morning]);
echo "Morning Status: " . $morning->fresh()->status . PHP_EOL;

// 2. Afternoon Session (14-18)
$afternoon = App\Models\Attendance::create([
    'employee_id' => $emp->id,
    'date' => today(),
    'check_in' => '14:00:00'
]);
$afternoon->update(['check_out' => '18:00:00']);
$afternoon = $afternoon->fresh();
$method->invokeArgs($controller, [$afternoon]);
echo "Afternoon Status: " . $afternoon->fresh()->status . PHP_EOL;

// 3. Check Aggregated Status via today() logic
// We can't call today() directly easily as it returns JSON response, so let's copy the logic or inspect the response
// Actually, let's just run the code snippet from today()
$today = today()->toDateString();
$attendances = App\Models\Attendance::where('employee_id', $emp->id)->where('date', $today)->get();

$morningSession = $attendances->first(function ($session) {
    $checkIn = \Carbon\Carbon::parse($session->check_in);
    return $checkIn->lessThan(\Carbon\Carbon::parse('12:00:00')->setDateFrom($checkIn));
});

$afternoonSession = $attendances->first(function ($session) {
    $checkIn = \Carbon\Carbon::parse($session->check_in);
    return $checkIn->greaterThanOrEqualTo(\Carbon\Carbon::parse('13:00:00')->setDateFrom($checkIn));
});

$morningOk = $morningSession && in_array($morningSession->status, ['present', 'late']);
$afternoonOk = $afternoonSession && in_array($afternoonSession->status, ['present', 'late']);

$dailyStatus = 'absent';
if ($morningOk && $afternoonOk) {
    $dailyStatus = 'present';
} elseif ($morningOk || $afternoonOk) {
    $dailyStatus = 'half_day';
}

echo "Aggregated Daily Status: " . $dailyStatus . PHP_EOL;
