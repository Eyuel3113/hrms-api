<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    /**
     * Get Activity Logs
     * 
     * Get a list of system activity logs.
     * 
     * @group Activity Logs
     * @queryParam subject_type string Filter by subject type (e.g., Employee).
     * @queryParam event string Filter by event type (created, updated, deleted).
     * @response 200 {
     *  "message": "Activity logs fetched successfully",
     *  "data": [ ... ]
     * }
     */
public function index(Request $request)
{
    $query = Activity::query()->latest();

    if ($request->has('subject_type')) {
        $query->where('subject_type', $request->subject_type);
    }

    if ($request->has('causer_id')) {
        $query->where('causer_id', $request->causer_id);
    }

    if ($request->has('event')) {
        $query->where('event', $request->event);
    }

    $logs = $query->paginate(20);

    // CLEAN RESPONSE — NO PAGINATION GARBAGE WHEN EMPTY
    if ($logs->isEmpty()) {
        return response()->json([
            'message' => 'No activity logs found',
            'data' => []
        ]);
    }

    return response()->json([
        'message' => 'Activity logs fetched successfully',
        'total' => $logs->total(),
        'data' => $logs->items(),
        'pagination' => [
            'current_page' => $logs->currentPage(),
            'last_page' => $logs->lastPage(),
            'per_page' => $logs->perPage(),
        ]
    ]);
}
}
