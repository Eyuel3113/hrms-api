<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LeaveType;
use Illuminate\Http\Request;

/**
 * @group Leave Management
 * @subgroup Leave Types
 *
 * APIs for managing leave types (Annual, Sick, etc.)
 */
class LeaveTypeController extends Controller
{
    /**
     * List Leave Types
     *
     * Fetch a paginated list of leave types.
     *
     * @queryParam search string Filter by name. Example: Annual
     * @queryParam limit int Number of items per page. Example: 10
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $limit  = $request->query('limit', 10);

        $query = LeaveType::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $types = $query->orderBy('name')->paginate($limit);

        return response()->json([
            'message'    => 'Leave types fetched successfully',
            'data'       => $types->items(),
            'pagination' => [
                'total'        => $types->total(),
                'per_page'     => $types->perPage(),
                'current_page' => $types->currentPage(),
                'last_page'    => $types->lastPage(),
            ]
        ]);
    }

    /**
     * List Active Leave Types
     *
     * Fetch all active leave types (useful for dropdowns).
     *
     * @response 200 {
     *   "message": "Active leave types fetched",
     *   "data": [
     *     {
     *       "id": "7bd6f6fe-9fc3-4f64-944f-3481cd1d3ec4",
     *       "name": "Annual Leave",
     *       "default_days": 20,
     *       "is_active": true
     *     }
     *   ]
     * }
     */
    public function active()
    {
        return response()->json([
            'message' => 'Active leave types fetched',
            'data'    => LeaveType::where('is_active', true)->orderBy('name')->get()
        ]);
    }

    /**
     * Create Leave Type
     *
     * Add a new leave type definition.
     *
     * @bodyParam name string required The name of the leave type. Example: Casual Leave
     * @bodyParam default_days int required Default days allowed per year. Example: 10
     * @bodyParam is_paid boolean Whether the leave is paid. Example: true
     * @bodyParam requires_approval boolean Whether approval is required. Example: true
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|unique:leave_types,name',
            'default_days'   => 'required|integer|min:0',
            'is_paid'        => 'boolean',
            'requires_approval' => 'boolean',
        ]);

        $type = LeaveType::create([
            'id'                 => (string) \Illuminate\Support\Str::uuid(),
            'is_active'          => true,
            'requires_approval'  => $validated['requires_approval'] ?? true,
            'is_paid'            => $validated['is_paid'] ?? true,
        ] + $validated);

        return response()->json([
            'message' => 'Leave type created',
            'data'    => $type
        ], 201);
    }

    /**
     * Update Leave Type
     *
     * Update an existing leave type.
     *
     * @urlParam id string required The UUID of the leave type.
     * @bodyParam name string The name of the leave type. Example: Casual Leave v2
     */
    public function update(Request $request, $id)
    {
        $type = LeaveType::findOrFail($id);

        $validated = $request->validate([
            'name'           => 'sometimes|required|string|unique:leave_types,name,' . $id,
            'default_days'   => 'sometimes|required|integer|min:0',
            'is_paid'        => 'sometimes|boolean',
            'requires_approval' => 'sometimes|boolean',
        ]);

        $type->update($validated);

        return response()->json([
            'message' => 'Leave type updated',
            'data'    => $type
        ]);
    }

    /**
     * Toggle Leave Type Status
     *
     * Activate or deactivate a leave type.
     *
     * @urlParam id string required The UUID of the leave type.
     */
    public function toggleStatus($id)
    {
        $type = LeaveType::findOrFail($id);
        $type->is_active = !$type->is_active;
        $type->save();

        return response()->json([
            'message'   => 'Leave type status updated',
            'is_active' => $type->is_active,
            'data'      => $type
        ]);
    }

    /**
     * Delete Leave Type
     *
     * Permanently delete a leave type.
     *
     * @urlParam id string required The UUID of the leave type.
     */
    public function destroy($id)
    {
        $type = LeaveType::findOrFail($id);
        $type->delete();

        return response()->json(['message' => 'Leave type deleted']);
    }
}