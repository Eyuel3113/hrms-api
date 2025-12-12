<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\LeaveType;
use Illuminate\Http\Request;

/**
 * @group Leave Management
 * @subgroup Leave Requests
 *
 * APIs for managing employee leave requests and approvals.
 */
class LeaveController extends Controller
{
    /**
     * List Leave Requests
     *
     * Fetch a paginated list of leave requests.
     *
     * @queryParam search string Search by employee name. Example: John
     * @queryParam status string Filter by status (pending, approved, rejected). Example: pending
     * @queryParam limit int Number of items per page. Example: 10
     */
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

    /**
     * Get Leave Request
     *
     * Get details of a specific leave request.
     *
     * @urlParam id string required The UUID of the leave request.
     */
    public function show($id)
    {
        $leave = Leave::with(['employee.personalInfo', 'leaveType'])->find($id);

        if (!$leave) {
            return response()->json(['message' => 'Leave request not found'], 404);
        }

        return response()->json([
            'message' => 'Leave request retrieved',
            'data'    => $leave
        ]);
    }

    /**
     * Create Leave Request
     *
     * Submit a new leave request for an employee.
     *
     * @bodyParam employee_id string required The UUID of the employee.
     * @bodyParam leave_type_id string required The UUID of the leave type.
     * @bodyParam start_date date required Format: YYYY-MM-DD. Example: 2024-01-01
     * @bodyParam end_date date required Format: YYYY-MM-DD. Example: 2024-01-05
     * @bodyParam reason string nullable Reason for leave. Example: Family vacation
     */
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

    /**
     * Approve Leave Request
     *
     * Approve a pending leave request.
     *
     * @urlParam id string required The UUID of the leave request.
     */
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

    /**
     * Reject Leave Request
     *
     * Reject a pending leave request.
     *
     * @urlParam id string required The UUID of the leave request.
     */
    public function reject($id)
    {
        $leave = Leave::findOrFail($id);
        $leave->update(['status' => 'rejected']);

        return response()->json(['message' => 'Leave rejected']);
    }
}