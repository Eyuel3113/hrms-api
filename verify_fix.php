<?php
// verify_fix.php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AttendanceController;
use Illuminate\Support\Facades\Auth;

echo "--- Ethiopian Calendar Verification ---\n";
$dates = [
    '2023-09-11' => 'Sep 11, 2023 (Should be Pagume 6 or Meskerem 1?)',
    '2023-09-12' => 'Sep 12, 2023 (New Year 2016)',
    '2024-09-11' => 'Sep 11, 2024 (New Year 2017)',
    now()->toDateString() => 'Today'
];

foreach ($dates as $d => $label) {
    echo "$label [$d] => " . formatEthiopian($d) . "\n";
}

echo "\n--- Attendance Flow Verification ---\n";

// Mock Auth
$employee = Employee::first();
if (!$employee) {
    // Create dummy
    $employee = Employee::create([
        'first_name' => 'Test',
        'last_name' => 'User',
        'employee_code' => 'TEST_' . rand(1000,9999),
        // Add other fields if necessary, assuming minimal requirement
    ]);
}
echo "Using Employee: " . $employee->first_name . " (ID: $employee->id)\n";

// Get Geofence from env
$lat = env('GEOFENCE_LAT');
$lng = env('GEOFENCE_LNG');
echo "Geofence Env: $lat, $lng\n";

if (!$lat) {
    echo "Warning: GEOFENCE_LAT not set. Using dummy values 9.0, 38.0\n";
    $lat = 9.0; $lng = 38.0;
}

$controller = new AttendanceController();

// Clear today's attendance for this user
Attendance::where('employee_id', $employee->id)->where('date', today())->delete();

echo "\n1. Check In 1 (Should Success)\n";
$req = Request::create('/api/check-in', 'POST', [
    'employee_id' => $employee->id,
    'lat' => $lat,
    'lng' => $lng
]);
$req->server->set('REMOTE_ADDR', '127.0.0.1');
$req->headers->set('User-Agent', 'TestScript');

$res = $controller->checkIn($req);
echo "Status: " . $res->getStatusCode() . " " . ($res->getStatusCode() == 200 ? 'OK' : 'FAIL') . "\n";
if ($res->getStatusCode() != 200) echo "Response: " . $res->getContent() . "\n";

echo "\n2. Check In 2 (Should Fail - Already Checked In)\n";
$res = $controller->checkIn($req);
echo "Status: " . $res->getStatusCode() . " " . ($res->getStatusCode() == 400 ? 'OK' : 'FAIL') . "\n";
if ($res->getStatusCode() != 400) echo "Response: " . $res->getContent() . "\n";

echo "\n3. Check Out 1 (Should Success)\n";
$res = $controller->checkOut($req);
echo "Status: " . $res->getStatusCode() . " " . ($res->getStatusCode() == 200 ? 'OK' : 'FAIL') . "\n";

echo "\n4. Check In 2 (Should Success - New Session)\n";
$res = $controller->checkIn($req);
echo "Status: " . $res->getStatusCode() . " " . ($res->getStatusCode() == 200 ? 'OK' : 'FAIL') . "\n";
if ($res->getStatusCode() != 200) echo "Response: " . $res->getContent() . "\n";

echo "\n5. Check Out 2 (Should Success)\n";
$res = $controller->checkOut($req);
echo "Status: " . $res->getStatusCode() . " " . ($res->getStatusCode() == 200 ? 'OK' : 'FAIL') . "\n";

echo "\nDone.\n";
