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
        $yesterday = today()->subDay()->toDateString();

        $employees = Employee::with(['personalInfo', 'shift'])->get();
        $attendances = Attendance::where(function($query) use ($today, $yesterday) {
                $query->where('date', $today)
                      ->orWhere(function($subQuery) use ($yesterday) {
                          $subQuery->where('date', $yesterday)->whereNull('check_out');
                      });
            })
            ->get()
            ->groupBy('employee_id');

        $defaultShift = Shift::where('is_default', true)->first();

        $data = $employees->map(function ($employee) use ($attendances, $defaultShift) {
            $sessions = $attendances->get($employee->id, collect());
            $shift = $employee->shift ?? $defaultShift;

            $dailyStatus = 'absent';
            $morningSession = null;
            $afternoonSession = null;

            if ($sessions->isNotEmpty()) {
                if ($shift && $shift->type === 'split') {
                    $breakStart = \Carbon\Carbon::parse($shift->break_start_time);
                    $breakEnd = \Carbon\Carbon::parse($shift->break_end_time);

                    $morningSession = $sessions->first(function ($s) use ($breakStart) {
                        return \Carbon\Carbon::parse($s->check_in)->lessThan($breakStart->setDateFrom(\Carbon\Carbon::parse($s->check_in)));
                    });

                    $afternoonSession = $sessions->first(function ($s) use ($breakEnd) {
                        return \Carbon\Carbon::parse($s->check_in)->greaterThanOrEqualTo($breakEnd->setDateFrom(\Carbon\Carbon::parse($s->check_in)));
                    });

                    $morningOk = $morningSession && in_array($morningSession->status, ['present', 'late']);
                    $afternoonOk = $afternoonSession && in_array($afternoonSession->status, ['present', 'late']);

                    if ($morningOk && $afternoonOk) {
                        $dailyStatus = 'present';
                    } elseif ($morningOk || $afternoonOk) {
                        $dailyStatus = 'half_day';
                    }
                } else {
                    // Regular Shift
                    $session = $sessions->first();
                    $dailyStatus = $session->status;
                }
            }

            return [
                'employee' => [
                    'id' => $employee->id,
                    'full_name' => $employee->personalInfo->first_name . ' ' . $employee->personalInfo->last_name,
                    'employee_code' => $employee->employee_code,
                ],
                'sessions' => $sessions,
                'daily_status' => $dailyStatus,
            ];
        });

        return response()->json([
            'success' => true,
            'date'    => $today,
            'ethiopian_date' => EthiopianCalendar::format($today),
            'total'   => $employees->count(),
            'present' => $data->where('daily_status', 'present')->count(),
            'late'    => $data->where('daily_status', 'late')->count(),
            'half_day' => $data->where('daily_status', 'half_day')->count(),
            'absent'  => $data->where('daily_status', 'absent')->count(),
            'data'    => $data
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

        // Check for holiday
        if (Holiday::active()->where('date', $today)->exists()) {
            return response()->json(['message' => 'Today is a holiday. Attendance not allowed.'], 400);
        }

        // Only check if there's an OPEN session (not checked out yet)
        $activeSession = Attendance::where('employee_id', $employeeid)
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
            'message' => $employee->personalInfo->first_name . ' checked in at ' . now()->format('h:i A'),
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

        // Find the latest active check-in (regardless of date, to support night shifts)
        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereNotNull('check_in')
            ->whereNull('check_out')
            ->latest('check_in')
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
            'message'      => $employee->personalInfo->first_name . ' checked out at ' . now()->format('h:i A'),
            'check_in'     => $checkInTime,
            'check_out'    => now()->format('h:i A'),
            'worked_hours' => $this->calculateWorkedHours($checkInTime, $now),  
            'ethiopian'    => EthiopianCalendar::format($attendance->date),
        ]);
    }


    /**
 * Employee Attendance History by ID
 *
 * Get all attendance records for a specific employee with pagination, search by date, and status filter.
 *
 * @group Attendance
 * @urlParam employeeId string required The UUID of the employee.
 * @queryParam start_date string optional Filter from date (YYYY-MM-DD). Example: 2025-12-01
 * @queryParam end_date string optional Filter to date (YYYY-MM-DD). Example: 2025-12-31
 * @queryParam status string optional Filter by status (present, late, absent, half_day). Example: late
 * @queryParam limit integer optional Items per page. Default 10. Example: 30
 *
 * @param Request $request
 * @param string $employeeId
 * @return \Illuminate\Http\JsonResponse
 */
public function employeeAttendanceHistory(Request $request, $employeeId)
{
    $startDate = $request->query('start_date');
    $endDate   = $request->query('end_date');
    $status    = $request->query('status');
    $limit     = $request->query('limit', 10);

    $employee = Employee::with('personalInfo')->findOrFail($employeeId);

    $query = Attendance::where('employee_id', $employeeId);

    if ($startDate) {
        $query->whereDate('date', '>=', $startDate);
    }

    if ($endDate) {
        $query->whereDate('date', '<=', $endDate);
    }

    if ($status) {
        $query->where('status', $status);
    }

    $attendances = $query->orderBy('date', 'desc')
                         ->paginate($limit);

    return response()->json([
        'message' => 'Employee attendance history fetched successfully',
        'employee' => [
            'id' => $employee->id,
            'full_name' => $employee->personalInfo->first_name . ' ' . $employee->personalInfo->last_name,
            'email' => $employee->personalInfo->email,
            'phone' => $employee->personalInfo->phone,
        ],
        'total_records' => $attendances->total(),
        'data' => $attendances->items(),
        'summary' => [
            'present' => Attendance::where('employee_id', $employeeId)
                ->when($startDate, fn($q) => $q->whereDate('date', '>=', $startDate))
                ->when($endDate, fn($q) => $q->whereDate('date', '<=', $endDate))
                ->where('status', 'present')->count(),
            'late' => Attendance::where('employee_id', $employeeId)
                ->when($startDate, fn($q) => $q->whereDate('date', '>=', $startDate))
                ->when($endDate, fn($q) => $q->whereDate('date', '<=', $endDate))
                ->where('status', 'late')->count(),
            'absent' => Attendance::where('employee_id', $employeeId)
                ->when($startDate, fn($q) => $q->whereDate('date', '>=', $startDate))
                ->when($endDate, fn($q) => $q->whereDate('date', '<=', $endDate))
                ->where('status', 'absent')->count(),
            'half_day' => Attendance::where('employee_id', $employeeId)
                ->when($startDate, fn($q) => $q->whereDate('date', '>=', $startDate))
                ->when($endDate, fn($q) => $q->whereDate('date', '<=', $endDate))
                ->where('status', 'half_day')->count(),
        ],
        'pagination' => [
            'total'        => $attendances->total(),
            'per_page'     => $attendances->perPage(),
            'current_page' => $attendances->currentPage(),
            'last_page'    => $attendances->lastPage(),
        ]
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
            // Fallback default shift object if none exists
            $shift = (object) [
                'type' => 'regular',
                'start_time' => '09:00:00',
                'end_time'   => '17:30:00',
                'break_start_time' => '12:00:00',
                'break_end_time' => '13:00:00',
                'late_threshold_minutes' => 15,
                'grace_period_minutes' => 15, // New field fallback
                'half_day_minutes' => 240,
                'overtime_rate' => 1.50,
            ];
        }

        // Parse Check In/Out
        $checkIn  = $attendance->check_in;
        if ($checkIn && !($checkIn instanceof \Carbon\Carbon)) {
            $checkIn = \Carbon\Carbon::parse($checkIn);
        }

        $checkOut = $attendance->check_out;
        if ($checkOut && !($checkOut instanceof \Carbon\Carbon)) {
            $checkOut = \Carbon\Carbon::parse($checkOut);
        }

        if (!$checkIn) {
            $attendance->update(['status' => 'absent']);
            return;
        }

        // Align Shift Dates
        $shiftStart = \Carbon\Carbon::parse($shift->start_time)->setDateFrom($checkIn);
        $shiftEnd   = \Carbon\Carbon::parse($shift->end_time)->setDateFrom($checkIn);
        
        // Handle Overnight Shift
        if ($shiftEnd->lessThan($shiftStart)) {
            $shiftEnd->addDay();
        }

        $status = 'present';
        $lateMinutes = 0;
        $earlyLeaveMinutes = 0;
        $workedMinutes = 0;
        $overtimeMinutes = 0;

        // --- SPLIT SHIFT LOGIC ---
        if (isset($shift->type) && $shift->type === 'split') {
            $breakStart = \Carbon\Carbon::parse($shift->break_start_time)->setDateFrom($checkIn);
            $breakEnd   = \Carbon\Carbon::parse($shift->break_end_time)->setDateFrom($checkIn);

            // Determine Session
            // Morning: Before Break Start
            // Afternoon: After Break End
            $isMorning   = $checkIn->lessThan($breakStart); 
            $isAfternoon = $checkIn->greaterThanOrEqualTo($breakEnd);
            
            // Note: If checked in BETWEEN break_start and break_end, it's ambiguous. 
            // We treat it as Late Afternoon usually, or Late Morning return? 
            // Let's assume Afternoon if closest to Break End.
            if (!$isMorning && !$isAfternoon) {
                 $isAfternoon = true; // Late return case
            }

            if ($isMorning) {
                // Expected: Shift Start -> Break Start
                $sessionStart = $shiftStart;
                $sessionEnd   = $breakStart;
            } else { // Afternoon
                // Expected: Break End -> Shift End
                $sessionStart = $breakEnd;
                $sessionEnd   = $shiftEnd;
            }

            // 1. Late Calculation
            // Use grace_period_minutes if available, else late_threshold
            $grace = $shift->grace_period_minutes ?? $shift->late_threshold_minutes;
            $lateThreshold = $sessionStart->copy()->addMinutes($grace);

            if ($checkIn->greaterThan($lateThreshold)) {
                $lateMinutes = abs($checkIn->diffInMinutes($sessionStart));
                $status = 'late';
            }

            // 2. Worked & Early Leave & Overtime (Requires CheckOut)
            if ($checkOut) {
                 if ($checkOut->lessThan($checkIn)) $checkOut->addDay();
                 
                 $workedMinutes = abs($checkIn->diffInMinutes($checkOut));

                 // Early Leave
                 if ($checkOut->lessThan($sessionEnd)) {
                     // Check if flexible? No, strict for now.
                     // Allow small buffer? using grace period for early leave too?
                     // Let's stay strict: checkOut < sessionEnd IS early leave.
                     $earlyLeaveMinutes = abs($sessionEnd->diffInMinutes($checkOut));
                 }

                 // Overtime (Only for Afternoon session usually? Or both?)
                 // Usually overtime is only after day end.
                 if ($isAfternoon && $checkOut->greaterThan($sessionEnd)) {
                     // Only count if > min_overtime_minutes (if field existed, else 0)
                     $overtimeMinutes = abs($checkOut->diffInMinutes($sessionEnd));
                 }

                 // Half Day Check per session? 
                 // If worked < 50% of THIS session duration?
                 $sessionDuration = $sessionStart->diffInMinutes($sessionEnd);
                 if ($workedMinutes < ($sessionDuration / 2)) {
                     $status = 'half_day';
                 }
            }

        } 
        // --- REGULAR SHIFT LOGIC ---
        else {
             // 1. Late
             $grace = $shift->grace_period_minutes ?? $shift->late_threshold_minutes;
             $lateThreshold = $shiftStart->copy()->addMinutes($grace);

             if ($checkIn->greaterThan($lateThreshold)) {
                 $lateMinutes = abs($checkIn->diffInMinutes($shiftStart));
                 $status = 'late';
             }

             // 2. Out Logic
             if ($checkOut) {
                 if ($checkOut->lessThan($checkIn)) $checkOut->addDay();

                 $workedMinutes = abs($checkIn->diffInMinutes($checkOut));

                 // Deduct Break? 
                 // If break times are defined for regular shift, we can deduct them if worked through them?
                 // Simple logic for now: Gross worked minutes.
                 
                 // Early Leave
                 if ($checkOut->lessThan($shiftEnd)) {
                     $earlyLeaveMinutes = abs($shiftEnd->diffInMinutes($checkOut));
                 }

                 // Overtime
                 if ($checkOut->greaterThan($shiftEnd)) {
                     $overtimeMinutes = abs($checkOut->diffInMinutes($shiftEnd));
                 }

                 // Half Day
                 if ($workedMinutes < $shift->half_day_minutes) {
                     $status = 'half_day'; 
                 }
             }
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
