<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $attendances = Attendance::with('employee:id,first_name,last_name,employee_id')
            ->when($request->date, fn($q) => $q->where('date', $request->date))
            ->when($request->employee_id, fn($q) => $q->where('employee_id', $request->employee_id))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderByDesc('date')
            ->paginate(50);

        return response()->json([
            'success' => true,
            'data'    => $attendances
        ]);
    }

    public function today()
    {
        $today = today()->toDateString();

        $attendances = Attendance::with('employee:id,first_name,last_name,employee_id')
            ->where('date', $today)
            ->orderBy('check_in')
            ->get();

        return response()->json([
            'success' => true,
            'date'    => $today,
            'data'    => $attendances
        ]);
    }

    public function checkIn(Request $request)
    {
        $employee = Auth::user(); // or find by employee_id if from device

        $today = today()->toDateString();

        $attendance = Attendance::updateOrCreate(
            [
                'employee_id' => $employee->id,
                'date'        => $today,
            ],
            [
                'check_in'         => now()->format('H:i:s'),
                'check_in_ip'      => $request->ip(),
                'check_in_device'  => $request->header('User-Agent') ? 'Web' : 'ZKTeco',
                'status'           => 'present',
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Check-in successful',
            'data'    => $attendance->load('employee')
        ], 201);
    }

    public function checkOut(Request $request)
    {
        $employee = Auth::user();

        $attendance = Attendance::where('employee_id', $employee->id)
            ->where('date', today())
            ->whereNotNull('check_in')
            ->firstOrFail();

        $attendance->update([
            'check_out'        => now()->format('H:i:s'),
            'check_out_ip'     => $request->ip(),
            'check_out_device' => $request->header('User-Agent') ? 'Web' : 'ZKTeco',
        ]);

        // Future: calculate worked minutes, overtime, late, etc. here

        return response()->json([
            'success' => true,
            'message' => 'Check-out successful',
            'data'    => $attendance->load('employee')
        ]);
    }
}