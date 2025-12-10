<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\EthiopianCalendar;
use App\Models\Shift;
use App\Models\Holiday;

class AttendanceController extends Controller
{
    /**
     * Today's Attendance
     * 
     * Get attendance overview for the current day.
     * 
     * @group Attendance
     * @response 200 {
     *  "success": true,
     *  "date": "2023-10-27",
     *  "ethiopian_date": "Tikimt 16, 2016",
     *  "total": 10,
     *  "present": 8,
     *  "late": 2,
     *  "absent": 5,
     *  "data": [ ... ]
     * }
     */
    public function today()
    {
        $today = today()->toDateString();

        $attendances = Attendance::with(['employee.personalInfo'])
            ->where('date', $today)
            ->orderBy('check_in')
            ->get();

        return response()->json([
            'success' => true,
            'date'    => $today,
            'ethiopian_date' => EthiopianCalendar::format($today),
            'total'   => $attendances->count(),
            'present' => $attendances->where('status', 'present')->count(),
            'late'    => $attendances->where('status', 'late')->count(),
            'absent'  => Employee::count() - $attendances->count(),
            'half_day' => $attendances->where('status','half_day')->count(), 
            'data'    => $attendances
        ]);
    }

    /**
     * Check In
     * 
     * Record employee check-in.
     * 
     * @group Attendance
     * @bodyParam employee_id uuid required The employee's ID.
     * @bodyParam lat float required Latitude.
     * @bodyParam lng float required Longitude.
     * @response 200 {
     *  "success": true,
     *  "message": "John checked in at 08:30",
     *  ...
     * }
     */
    public function checkIn(Request $request)
    {
        $employeeid = $request->employee_id; 

        if (!$employeeid) {
            return response()->json(['message' => 'employee_id required'], 400);
        }

        $employee = Employee::with('personalInfo')->where('id', $employeeid)->first();

        if (!$employee) {
            return response()->json(['message' => 'Employee not found: ' . $employeeid], 404);
        }

        $today = today()->toDateString();

        $activeSession = Attendance::where('employee_id', $employeeid)
            ->where('date', $today)
            ->whereNull('check_out')
            ->first();

        if ($activeSession) {
            return response()->json(['message' => 'Already checked in. Please check out first.'], 400);
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

        $attendance = Attendance::create([
            'employee_id'      => $employee->id,  
            'date'             => $today,
            'ethiopian_date'   => EthiopianCalendar::format($today),
            'check_in'         => now()->format('H:i:s'),
            'check_in_ip'      => $request->ip(),
            'check_in_device'  => $request->header('User-Agent') ? 'Web' : 'ZKTeco',
            'latitude'         => $lat,
            'longitude'        => $lng,
            'status'           => 'present',
        ]);
        $this->calculateAttendanceStatus($attendance);

        return response()->json([
            'success' => true,
            'message' => $employee->personalInfo->first_name . ' checked in at ' . now()->format('H:i'),
            'employee_code' => $employee->employee_code,
            'ethiopian' => EthiopianCalendar::format($today),
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

    /**
     * Check Out
     * 
     * Record employee check-out.
     * 
     * @group Attendance
     * @bodyParam employee_id uuid required The employee's ID.
     * @bodyParam lat float required Latitude.
     * @bodyParam lng float required Longitude.
     * @response 200 {
     *  "success": true,
     *  "message": "John checked out at 17:30",
     *  ...
     * }
     */
    public function checkOut(Request $request)
    {
        $employeeid = $request->employee_id;

        if (!$employeeid) {
            return response()->json(['message' => 'employee_id required'], 400);
        }

        $employee = Employee::with('personalInfo')->where('id', $employeeid)->first();

        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        $today = today()->toDateString();
        // Holiday check
        if (Holiday::active()->where('date', $today)->exists()) {
            return response()->json(['message' => 'Today is a holiday. Attendance not allowed.'], 400);
        }
        $now = now()->format('H:i:s');  

        $attendance = Attendance::where('employee_id', $employee->id)
            ->where('date', $today)
            ->whereNotNull('check_in')
            ->whereNull('check_out')
            ->latest()
            ->first();

        if (!$attendance) {
            return response()->json(['message' => 'No active check-in found to check out from.'], 400);
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
        $this->calculateAttendanceStatus($attendance);

        return response()->json([
            'success'      => true,
            'message'      => $employee->personalInfo->first_name . ' checked out at ' . $now,
            'check_in'     => $checkInTime,
            'check_out'    => $now,
            'worked_hours' => $this->calculateWorkedHours($checkInTime, $now),  
            'ethiopian'    => EthiopianCalendar::format($today),
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


// private function calculateAttendanceStatus(Attendance $attendance)
// {
//     $employee = $attendance->employee;
//     $shift = $employee->shift ?? Shift::default()->first();

//     // Fallback if no shift
//     if (!$shift) {
//         $shift = (object)[
//             'start_time' => '09:00:00',
//             'end_time'   => '17:30:00',
//             'late_threshold_minutes' => 15,
//             'half_day_minutes' => 240,
//         ];
//     }

//     // Cast attributes are already Carbon instances (or strings if raw). Handle both.
//     $checkIn = $attendance->check_in;
//     if ($checkIn && !($checkIn instanceof \Carbon\Carbon)) {
//         $checkIn = \Carbon\Carbon::parse($checkIn);
//     }

//     $checkOut = $attendance->check_out;
//     if ($checkOut && !($checkOut instanceof \Carbon\Carbon)) {
//         $checkOut = \Carbon\Carbon::parse($checkOut);
//     }

//     $officeStart = \Carbon\Carbon::createFromFormat('H:i:s', $shift->start_time);
//     $officeEnd   = \Carbon\Carbon::createFromFormat('H:i:s', $shift->end_time);

//     $status = 'absent';
//     $lateMinutes = 0;
//     $earlyLeaveMinutes = 0;
//     $workedMinutes = 0;
//     $overtimeMinutes = 0;

//     if ($checkIn && $checkOut) {
//         if ($checkOut->lessThan($checkIn)) $checkOut->addDay();

//         $workedMinutes = $checkIn->diffInMinutes($checkOut);

//         $lateThresholdTime = $officeStart->copy()->addMinutes($shift->late_threshold_minutes);
//         if ($checkIn->greaterThan($lateThresholdTime)) {
//             $lateMinutes = $checkIn->diffInMinutes($officeStart);
//         }

//         if ($checkOut->lessThan($officeEnd)) {
//             $earlyLeaveMinutes = $officeEnd->diffInMinutes($checkOut);
//         }

//         if ($checkOut->greaterThan($officeEnd)) {
//             $overtimeMinutes = $checkOut->diffInMinutes($officeEnd);
//         }

//         if ($workedMinutes < $shift->half_day_minutes) {
//             $status = 'half_day';
//         } elseif ($lateMinutes > 0) {
//             $status = 'late';
//         } else {
//             $status = 'present';
//         }
//     } elseif ($checkIn) {
//         $status = 'present';
//     }

//     $attendance->update([
//         'status'               => $status,
//         'late_minutes'         => $lateMinutes,
//         'early_leave_minutes'  => $earlyLeaveMinutes,
//         'worked_minutes'       => $workedMinutes,
//         'overtime_minutes'     => $overtimeMinutes,
//     ]);
// }

private function calculateAttendanceStatus(Attendance $attendance)
{
    $employee = $attendance->employee;
    $shift = $employee->shift ?? Shift::default()->first();

    if (!$shift) {
        $shift = (object) [
            'start_time' => '09:00:00',
            'end_time'   => '17:30:00',
            'late_threshold_minutes' => 15,
            'half_day_minutes' => 240,
            'overtime_rate' => 1.50,
        ];
    }

    $checkIn  = $attendance->check_in ? \Carbon\Carbon::createFromFormat('H:i:s', $attendance->check_in) : null;
    $checkOut = $attendance->check_out ? \Carbon\Carbon::createFromFormat('H:i:s', $attendance->check_out) : null;

    $officeStart = \Carbon\Carbon::createFromFormat('H:i:s', $shift->start_time);
    $officeEnd   = \Carbon\Carbon::createFromFormat('H:i:s', $shift->end_time);

    $status = 'absent';
    $lateMinutes = 0;
    $earlyLeaveMinutes = 0;
    $workedMinutes = 0;
    $overtimeMinutes = 0;

    if ($checkIn && $checkOut) {
        if ($checkOut->lessThan($checkIn)) $checkOut->addDay();

        $workedMinutes = $checkIn->diffInMinutes($checkOut);

        $lateThresholdTime = $officeStart->copy()->addMinutes($shift->late_threshold_minutes);
        if ($checkIn->greaterThan($lateThresholdTime)) {
            $lateMinutes = $checkIn->diffInMinutes($officeStart);
        }

        if ($checkOut->lessThan($officeEnd)) {
            $earlyLeaveMinutes = $officeEnd->diffInMinutes($checkOut);
        }

        if ($checkOut->greaterThan($officeEnd)) {
            $overtimeMinutes = $checkOut->diffInMinutes($officeEnd);
        }

        if ($workedMinutes < $shift->half_day_minutes) {
            $status = 'half_day';
        } elseif ($lateMinutes > 0) {
            $status = 'late';
        } else {
            $status = 'present';
        }
    } elseif ($checkIn) {
        $status = 'present';
    }

    $attendance->update([
        'status'               => $status,
        'late_minutes'         => $lateMinutes,
        'early_leave_minutes'  => $earlyLeaveMinutes,
        'worked_minutes'       => $workedMinutes,
        'overtime_minutes'     => $overtimeMinutes,
    ]);
}




}