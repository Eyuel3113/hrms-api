<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use App\Http\Requests\Holiday\HolidayStoreRequest;
use App\Http\Requests\Holiday\HolidayUpdateRequest;
use Illuminate\Http\Request;

class HolidayController extends Controller
{

/**
     * List Holidays (Paginated + Search)
     * 
     * Get a paginated list of holidays with optional search.
     * 
     * @group Holidays
     * @queryParam search string Search by name, Ethiopian date, description, or type.
     * @queryParam limit int Items per page. Default 10.
     * @queryParam sort string Sort field (e.g., date, name). Default 'date'.
     * @queryParam order string Sort order (asc/desc). Default 'desc'.
     * @response 200 {
     *   "message": "Holidays fetched successfully",
     *   "data": [ ... ],
     *   "pagination": { ... }
     * }
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $limit  = $request->query('limit', 10);
        $sort   = $request->query('sort', 'date');
        $order  = $request->query('order', 'desc');

        $query = Holiday::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('ethiopian_date', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%");
            });
        }

        $holidays = $query->orderBy($sort, $order)->paginate($limit);

        return response()->json([
            'message'    => 'Holidays fetched successfully',
            'data'       => $holidays->items(),
            'pagination' => [
                'total'        => $holidays->total(),
                'per_page'     => $holidays->perPage(),
                'current_page' => $holidays->currentPage(),
                'last_page'    => $holidays->lastPage(),
            ]
        ]);
    }


    /**
     * All Active Holidays (No Pagination)
     * 
     * Get all active holidays — perfect for dropdowns, attendance logic, etc.
     * 
     * @group Holidays
     * @queryParam search string Optional search by name or Ethiopian date.
     * @response 200 {
     *   "message": "Active holidays fetched successfully",
     *   "data": [ ... ]
     * }
     */
    public function active(Request $request)
    {
        $search = $request->query('search');

        $query = Holiday::where('is_active', true);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('ethiopian_date', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return response()->json([
            'message' => 'Active holidays fetched successfully',
            'data'    => $query->orderBy('date')->get()
        ]);
    }

 /**
     * Create Holiday
     * 
     * Create a new holiday.
     * 
     * @group Holidays
     * @bodyParam name string required Holiday name (e.g., "Meskel").
     * @bodyParam date date required Gregorian date (e.g., "2025-09-27").
     * @bodyParam ethiopian_date string required Ethiopian date (e.g., "መስከረም 17, 2018 ዓ.ም").
     * @bodyParam type string required Type: national, religious, or company.
     * @bodyParam is_recurring boolean Recurring every year. Default true.
     * @bodyParam description string nullable Optional description.
     * @response 201 {
     *   "message": "Holiday created successfully",
     *   "data": { ... }
     * }
     */

    public function store(HolidayStoreRequest $request)
{
    $holiday = Holiday::create([
        'id'           => (string) \Illuminate\Support\Str::uuid(),
        'is_active'    => true,
    ] + $request->validated());

    return response()->json([
        'message' => 'Holiday created successfully',
        'data'    => $holiday
    ], 201);
}
/**
     * Update Holiday
     * 
     * Update holiday details.
     * 
     * @group Holidays
     * @urlParam id string required Holiday UUID.
     * @bodyParam name string optional
     * @bodyParam date date optional
     * @bodyParam ethiopian_date string optional
     * @bodyParam type string optional
     * @bodyParam is_recurring boolean optional
     * @bodyParam description string optional
     * @response 200 {
     *   "message": "Holiday updated successfully",
     *   "data": { ... }
     * }
     */
    public function update(HolidayUpdateRequest $request, $id)
    {
        $holiday = Holiday::findOrFail($id);
        $holiday->update($request->validated());

        return response()->json([
            'message' => 'Holiday updated successfully',
            'data'    => $holiday
        ]);
    }
/**
     * Toggle Holiday Status
     * 
     * Toggle between active/inactive.
     * 
     * @group Holidays
     * @urlParam id string required Holiday UUID.
     * @response 200 {
     *   "message": "Holiday status updated successfully",
     *   "is_active": true,
     *   "data": { ... }
     * }
     */
    public function toggleStatus($id)
    {
        $holiday = Holiday::findOrFail($id);
        $holiday->is_active = !$holiday->is_active;
        $holiday->save();

        return response()->json([
            'message'   => 'Holiday status updated successfully',
            'is_active' => $holiday->is_active,
            'data'      => $holiday
        ]);
    }
    /**
     * Delete Holiday
     * 
     * Permanently delete a holiday.
     * 
     * @group Holidays
     * @urlParam id string required Holiday UUID.
     * @response 200 {
     *   "message": "Holiday deleted successfully"
     * }
     */

    public function destroy($id)
    {
        $holiday = Holiday::findOrFail($id);
        $holiday->delete();

        return response()->json([
            'message' => 'Holiday deleted successfully'
        ]);
    }
}