<?php

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Shift;
use App\Http\Controllers\Api\AttendanceController;
use Illuminate\Http\Request;
use Carbon\Carbon;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Ensure a default shift exists
if (!Shift::where('is_default', true)->exists()) {
    echo "Creating default shift...\n";
    Shift::create([
        'name' => 'Day Shift',
        'type' => 'regular',
        'start_time' => '09:00:00',
        'end_time' => '17:30:00',
        'late_threshold_minutes' => 15,
        'grace_period_minutes' => 15,
        'half_day_minutes' => 240,
        'is_default' => true,
        'is_active' => true,
    ]);
}

// Find or Create Test Employee
$email = 'debug_status@test.com';
$employee = Employee::where('email', $email)->first();
if (!$employee) {
    $employee = Employee::create([
        'first_name' => 'Debug',
        'last_name' => 'Status',
        'email' => $email,
        // Add other required fields if necessary based on your model
    ]);
}

echo "Testing with Employee ID: " . $employee->id . "\n";

// Helper to simulate CheckIn/CheckOut
function testScenario($label, $checkInTime, $checkOutTime = null) {
    global $employee;
    
    echo "\n--- Scenario: $label ---\n";
    
    // Clear previous attendance for today
    Attendance::where('employee_id', $employee->id)->delete();

    $controller = new AttendanceController();

    // Mock Request for CheckIn
    // We need to fake the time. Since controller uses now(), we might need to modify the controller to accept time OR use Carbon::setTestNow()
    
    Carbon::setTestNow(Carbon::parse($checkInTime));
    
    $reqIn = new Request([
        'employee_id' => $employee->id,
        'lat' => 9.0, // assumption: valid lat
        'lng' => 38.0 // assumption: valid lng
    ]);
    
    // We might need to mock calculateDistance or ensure lat/lng are within range if geofencing is strict
    // Assuming geofence env vars are set or we can bypass. 
    // Actually, let's just create the Attendance record manually and call calculateAttendanceStatus if private? 
    // No, I can't call private method easily. 
    // I will use the controller method but I need to be careful about Geofence.
    // If Geofence fails, I'll see it in response.
    
    // For specific time testing, direct controller call is best if I can manipulate now().
    
    $resIn = $controller->checkIn($reqIn);
    $dataIn = $resIn->getData(true);
    
    if (!$dataIn['success'] && isset($dataIn['message'])) {
        echo "CheckIn Failed: " . $dataIn['message'] . "\n";
        if (strpos($dataIn['message'], 'Outside office') !== false) {
             echo "NOTE: Geofencing blocking. Please disable geofence or provide valid coords.\n";
        }
        return;
    }
    
    $att = Attendance::where('employee_id', $employee->id)->latest()->first();
    echo "CheckIn at: " . $att->check_in . " | Status: " . $att->status . "\n";

    if ($checkOutTime) {
        Carbon::setTestNow(Carbon::parse($checkOutTime));
        $reqOut = new Request([
            'employee_id' => $employee->id,
            'lat' => 9.0,
            'lng' => 38.0
        ]);
        $resOut = $controller->checkOut($reqOut);
        
        $att->refresh();
        echo "CheckOut at: " . $att->check_out . " | Status: " . $att->status . " | Worked: " . $att->worked_minutes . "m | Late: " . $att->late_minutes . "m\n";
    }
}

// Default Shift is 09:00 - 17:30. Grace 15 mins.

// 1. On Time
testScenario("On Time (08:55)", "2023-01-01 08:55:00", "2023-01-01 17:35:00");

// 2. Late but within grace
testScenario("Grace Period (09:10)", "2023-01-01 09:10:00", "2023-01-01 17:35:00");

// 3. Late
testScenario("Late (09:20)", "2023-01-01 09:20:00", "2023-01-01 17:35:00");

// 4. Half Day (Short duration)
testScenario("Half Day (Left Early)", "2023-01-01 09:00:00", "2023-01-01 12:00:00");
