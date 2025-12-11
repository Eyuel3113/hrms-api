<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $limit  = $request->query('limit', 10);

        $query = Leave::with(['employee.personalInfo', 'leaveType']);

        if ($search) {
            $query->whereHas('employee.personalInfo', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $leaves = $query->orderBy('created_at', 'desc')->paginate($limit);

        return response()->json([
            'message'    => 'Leave requests fetched',
            'data'       => $leaves->items(),
            'pagination' => [
                'total'        => $leaves->total(),
                'per_page'     => $leaves->perPage(),
                'current_page' => $leaves->currentPage(),
                'last_page'    => $leaves->lastPage(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id'   => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after_or_equal:start_date',
            'reason'        => 'nullable|string',
        ]);

        $leave = Leave::create([
            'id'            => (string) \Illuminate\Support\Str::uuid(),
            'employee_id'   => $validated['employee_id'],
            'leave_type_id' => $validated['leave_type_id'],
            'start_date'    => $validated['start_date'],
            'end_date'       => $validated['end_date'],
            'reason'        => $validated['reason'],
            'status'        => 'pending',
        ]);

        return response()->json([
            'message' => 'Leave request created by HR',
            'data'    => $leave->load('employee.personalInfo', 'leaveType')
        ], 201);
    }

public function approve($id)
{
    $leave = Leave::findOrFail($id);

    // Get the logged in HR Admin's Employee UUID
    $adminEmployee = auth()->user()->employee;  // assuming User has employee relationship

    $leave->update([
        'status'      => 'approved',
        'approved_by' => $adminEmployee?->id,  // ← UUID, not integer
        'approved_at' => now(),
    ]);

    return response()->json(['message' => 'Leave approved']);
}

    public function reject($id)
    {
        $leave = Leave::findOrFail($id);
        $leave->update(['status' => 'rejected']);

        return response()->json(['message' => 'Leave rejected']);
    }
}