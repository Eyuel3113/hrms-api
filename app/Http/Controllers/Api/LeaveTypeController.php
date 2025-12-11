<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    /**
     * List Leave Types — Paginated + Search
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
     * All Active Leave Types (for dropdowns)
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
     * Toggle Active/Inactive
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
     */
    public function destroy($id)
    {
        $type = LeaveType::findOrFail($id);
        $type->delete();

        return response()->json(['message' => 'Leave type deleted']);
    }
}