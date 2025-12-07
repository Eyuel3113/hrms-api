<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function today()
    {
        $today = today()->toDateString();

        $attendances = Attendance::with(['employee:id,first_name,last_name,employee_code,photo'])
            ->where('date', $today)
            ->orderBy('check_in')
            ->get();

        return response()->json([
            'success' => true,
            'date'    => $today,
            'ethiopian_date' => formatEthiopian($today),
            'total'   => $attendances->count(),
            'present' => $attendances->where('status', 'present')->count(),
            'late'    => $attendances->where('status', 'late')->count(),
            'absent'  => Employee::count() - $attendances->count(),
            'data'    => $attendances
        ]);
    }

public function checkIn(Request $request)
{
    $employeeid = $request->employee_id; 

    if (!$employeeid) {
        return response()->json(['message' => 'employee_id required'], 400);
    }

    
    $employee = Employee::where('id', $employeeid)->first();

    if (!$employee) {
        return response()->json(['message' => 'Employee not found: ' . $employeeid], 404);
    }

    $today = today()->toDateString();

    $existing = Attendance::where('employee_id', $employeeid)
        ->where('date', $today)
        ->first();

    if ($existing?->check_in) {
        return response()->json(['message' => 'Already checked in today'], 400);
    }

    // GEOFENCING 
    $lat = $request->lat;
    $lng = $request->lng;

    if ($lat && $lng) {
        $distance = $this->calculateDistance($lat, $lng, env('GEOFENCE_LAT'), env('GEOFENCE_LNG'));
        if ($distance > env('GEOFENCE_RADIUS', 100)) {
            return response()->json([
                'message' => 'Outside office location',
                'distance_meters' => round($distance, 2),
            ], 403);
        }
    } else {
        return response()->json(['message' => 'Location required'], 400);
    }

    Attendance::create([
        'employee_id'      => $employee->id,  
        'date'             => $today,
        'ethiopian_date'   => formatEthiopian($today),
        'check_in'         => now()->format('H:i:s'),
        'check_in_ip'      => $request->ip(),
        'check_in_device'  => $request->header('User-Agent') ? 'Web' : 'ZKTeco',
        'latitude'         => $lat,
        'longitude'        => $lng,
        'status'           => 'present',
    ]);

    return response()->json([
        'success' => true,
        'message' => $employee->first_name . ' checked in at ' . now()->format('H:i'),
        'employee_code' => $employee->employee_code,
        'ethiopian' => formatEthiopian($today),
    ]);
}

private function calculateDistance($lat1, $lon1, $lat2, $lon2)
{
    $earthRadius = 6371000; // meters

    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a = sin($dLat/2) * sin($dLat/2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon/2) * sin($dLon/2);

    $c = 2 * atan2(sqrt($a), sqrt(1-$a));

    return $earthRadius * $c;
}

public function checkOut(Request $request)
{
    $employeeid = $request->employee_id;

    if (!$employeeid) {
        return response()->json(['message' => 'employee_id required'], 400);
    }

    $employee = Employee::where('id', $employeeid)->first();

    if (!$employee) {
        return response()->json(['message' => 'Employee not found'], 404);
    }

    $today = today()->toDateString();
    $now = now()->format('H:i:s');  

    $attendance = Attendance::where('employee_id', $employee->id)
        ->where('date', $today)
        ->whereNotNull('check_in')
        ->first();

    if (!$attendance) {
        return response()->json(['message' => 'No check-in found today'], 400);
    }

    if ($attendance->check_out) {
        return response()->json(['message' => 'Already checked out'], 400);
    }

    // GEOFENCING
    $lat = $request->lat;
    $lng = $request->lng;

    if ($lat && $lng) {
        $distance = $this->calculateDistance($lat, $lng, env('GEOFENCE_LAT'), env('GEOFENCE_LNG'));
        if ($distance > env('GEOFENCE_RADIUS', 100)) {
            return response()->json(['message' => 'Cannot check out — outside office'], 403);
        }
    } else {
        return response()->json(['message' => 'Location required'], 400);
    }


$checkInTime = $attendance->check_in;  

$now = now()->format('H:i:s');

$attendance->update([
    'check_out'        => $now,
    'check_out_ip'     => $request->ip(),
    'check_out_device' => $request->header('User-Agent') ? 'Web' : 'ZKTeco',
    'latitude'         => $lat,
    'longitude'        => $lng,
]);

return response()->json([
    'success'      => true,
    'message'      => $employee->first_name . ' checked out at ' . $now,
    'check_in'     => $checkInTime,
    'check_out'    => $now,
    'worked_hours' => $this->calculateWorkedHours($checkInTime, $now),  
    'ethiopian'    => formatEthiopian($today),
]);
}

private function calculateWorkedHours($checkIn, $checkOut)
{
    
    $in  = \Carbon\Carbon::today()->setTimeFromTimeString($checkIn);
    $out = \Carbon\Carbon::today()->setTimeFromTimeString($checkOut);

    // Handle overnight shift
    if ($out->lessThan($in)) {
        $out->addDay();
    }

    $diff = $in->diff($out);

    return $diff->h . 'h ' . $diff->i . 'm';
}

}