<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use App\Http\Requests\Shift\ShiftStoreRequest;
use App\Http\Requests\Shift\ShiftUpdateRequest;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
 /**
     * List Shifts (Paginated + Search)
     * 
     * Get a paginated list of shifts with optional search.
     * 
     * @group Shifts
     * @queryParam search string Search by shift name.
     * @queryParam limit int Items per page. Default 10.
     * @queryParam sort string Sort field (e.g., name, created_at). Default 'created_at'.
     * @queryParam order string Sort order (asc/desc). Default 'desc'.
     * @response 200 {
     *   "message": "Shifts fetched successfully",
     *   "data": [ ... ],
     *   "pagination": { ... }
     * }
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $limit  = $request->query('limit', 10);

        $query = Shift::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $shifts = $query->orderBy('created_at', 'desc')->paginate($limit);

        return response()->json([
            'message'    => 'Shifts fetched successfully',
            'data'       => $shifts->items(),
            'pagination' => [
                'total'        => $shifts->total(),
                'per_page'     => $shifts->perPage(),
                'current_page' => $shifts->currentPage(),
                'last_page'    => $shifts->lastPage(),
            ]
        ]);
    }

/**
     * All Active Shifts (No Pagination)
     * 
     * Get all active shifts — perfect for dropdowns and attendance logic.
     * 
     * @group Shifts
     * @queryParam search string Optional search by name.
     * @response 200 {
     *   "message": "Active shifts fetched successfully",
     *   "data": [ ... ]
     * }
     */
    public function active(Request $request)
    {
        $search = $request->query('search');

        $query = Shift::where('is_active', true);

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        return response()->json([
            'message' => 'Active shifts fetched successfully',
            'data'    => $query->orderBy('name')->get()
        ]);
    }

   /**
     * Create Shift
     * 
     * Create a new shift. Only one shift can be default.
     * 
     * @group Shifts
     * @bodyParam name string required Shift name (e.g., "Morning Shift").
     * @bodyParam start_time string required Start time (e.g., "09:00:00").
     * @bodyParam end_time string required End time (e.g., "17:30:00").
     * @bodyParam late_threshold_minutes integer required Grace period in minutes.
     * @bodyParam half_day_minutes integer required Minimum minutes for full day.
     * @bodyParam overtime_rate numeric required Overtime multiplier (e.g., 1.5).
     * @bodyParam is_default boolean Set as default shift.
     * @response 201 {
     *   "message": "Shift created successfully",
     *   "data": { ... }
     * }
     */
    public function store(ShiftStoreRequest $request)
    {
        // Only one default shift can be default
        if ($request->boolean('is_default')) {
            Shift::where('is_default', true)->update(['is_default' => false]);
        }

        $shift = Shift::create([
            'id'           => (string) \Illuminate\Support\Str::uuid(),
            'is_active'    => true,
            'is_default'   => $request->boolean('is_default', false),
        ] + $request->validated());

        return response()->json([
            'message' => 'Shift created successfully',
            'data'    => $shift
        ], 201);
    }
/**
     * Update Shift
     * 
     * Update shift details. Only one can be default.
     * 
     * @group Shifts
     * @urlParam id string required Shift UUID.
     * @bodyParam name string optional
     * @bodyParam start_time string optional
     * @bodyParam end_time string optional
     * @bodyParam late_threshold_minutes integer optional
     * @bodyParam half_day_minutes integer optional
     * @bodyParam overtime_rate numeric optional
     * @bodyParam is_default boolean optional
     * @response 200 {
     *   "message": "Shift updated successfully",
     *   "data": { ... }
     * }
     */
    public function update(ShiftUpdateRequest $request, $id)
    {
        $shift = Shift::findOrFail($id);

        if ($request->filled('is_default') && $request->boolean('is_default')) {
            Shift::where('is_default', true)->update(['is_default' => false]);
        }

        $shift->update($request->validated());

        return response()->json([
            'message' => 'Shift updated successfully',
            'data'    => $shift
        ]);
    }

/**
     * Toggle Shift Status
     * 
     * Toggle between active/inactive. Default shift cannot be deactivated.
     * 
     * @group Shifts
     * @urlParam id string required Shift UUID.
     * @response 200 {
     *   "message": "Shift status updated",
     *   "is_active": false
     * }
     */
    public function toggleStatus($id)
    {
        $shift = Shift::findOrFail($id);

        if ($shift->is_default) {
            return response()->json(['message' => 'Cannot deactivate default shift'], 400);
        }

        $shift->is_active = !$shift->is_active;
        $shift->save();

        return response()->json([
            'message'   => 'Shift status updated',
            'is_active' => $shift->is_active,
            'data'      => $shift
        ]);
    }
/**
     * Delete Shift
     * 
     * Permanently delete a shift. Default shift cannot be deleted.
     * 
     * @group Shifts
     * @urlParam id string required Shift UUID.
     * @response 200 {
     *   "message": "Shift deleted successfully"
     * }
     */
    public function destroy($id)
    {
        $shift = Shift::findOrFail($id);

        if ($shift->is_default) {
            return response()->json(['message' => 'Cannot delete default shift'], 400);
        }

        $shift->delete();

        return response()->json(['message' => 'Shift deleted successfully']);
    }
}