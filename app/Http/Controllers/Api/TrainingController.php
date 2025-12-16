<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Training;
use App\Models\TrainingAttendee;
use App\Http\Requests\Training\TrainingStoreRequest;
use App\Http\Requests\Training\TrainingUpdateRequest;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
/**
 * List Trainings
 *
 * Display a listing of trainings with pagination and filtering.
 *
 * @group Training Management
 * @queryParam search string Filter by title or description. Example: Laravel
 * @queryParam date string Filter trainings that occur on this date (YYYY-MM-DD). Example: 2025-12-20
 * @queryParam start_date string Filter trainings starting on or after this date. Example: 2025-12-01
 * @queryParam end_date string Filter trainings ending on or before this date. Example: 2025-12-31
 * @queryParam limit integer Items per page. Default 10. Example: 10
 * @queryParam page integer Page number for pagination. Example: 2
 *
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
public function index(Request $request)
{
    $search     = $request->query('search');
    $date       = $request->query('date');        // exact date (training overlaps)
    $startDate  = $request->query('start_date');  // from this date
    $endDate    = $request->query('end_date');    // to this date
    $limit      = $request->query('limit', 10);

    $query = Training::withCount('employees');

    if ($search) {
        $query->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
    }

    // Filter by exact date (training overlaps this date)
    if ($date) {
        $query->where(function ($q) use ($date) {
            $q->whereDate('start_date', '<=', $date)
              ->whereDate('end_date', '>=', $date);
        });
    }

    // Filter by start date (trainings starting after)
    if ($startDate) {
        $query->whereDate('start_date', '>=', $startDate);
    }

    // Filter by end date (trainings ending before)
    if ($endDate) {
        $query->whereDate('end_date', '<=', $endDate);
    }

    $trainings = $query->orderBy('start_date', 'desc')->paginate($limit);

    return response()->json([
        'message'    => 'Trainings fetched successfully',
        'data'       => $trainings->items(),
        'pagination' => [
            'total'        => $trainings->total(),
            'per_page'     => $trainings->perPage(),
            'current_page' => $trainings->currentPage(),
            'last_page'    => $trainings->lastPage(),
        ]
    ]);
}


/**
 * List Active Trainings
 *
 * Display a listing of active trainings with pagination and filtering.
 *
 * @group Training Management
 * @queryParam search string Filter by title or description. Example: Laravel
 * @queryParam date string Filter trainings that occur on this date (YYYY-MM-DD). Example: 2025-12-20
 * @queryParam start_date string Filter trainings starting on or after this date. Example: 2025-12-01
 * @queryParam end_date string Filter trainings ending on or before this date. Example: 2025-12-31
 * @queryParam limit integer Items per page. Default 10. Example: 10
 * @queryParam page integer Page number for pagination. Example: 2
 *
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
public function active(Request $request)
{
    $search     = $request->query('search');
    $date       = $request->query('date');        // exact date (training overlaps)
    $startDate  = $request->query('start_date');  // from this date
    $endDate    = $request->query('end_date');    // to this date
    $limit      = $request->query('limit', 10);

    $query = Training::where('is_active', true)->withCount('employees');
// serch active only
  if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    // Filter by exact date (training overlaps this date)
    if ($date) {
        $query->where(function ($q) use ($date) {
            $q->whereDate('start_date', '<=', $date)
              ->whereDate('end_date', '>=', $date);
        });
    }

    // Filter by start date (trainings starting after)
    if ($startDate) {
        $query->whereDate('start_date', '>=', $startDate);
    }

    // Filter by end date (trainings ending before)
    if ($endDate) {
        $query->whereDate('end_date', '<=', $endDate);
    }

    $trainings = $query->orderBy('start_date', 'desc')->paginate($limit);

    return response()->json([
        'message'    => 'Acive trainings fetched successfully',
        'data'       => $trainings->items(),
        'pagination' => [
            'total'        => $trainings->total(),
            'per_page'     => $trainings->perPage(),
            'current_page' => $trainings->currentPage(),
            'last_page'    => $trainings->lastPage(),
        ]
    ]);
}

/**
 * Get Training by ID
 *
 * Display the specified training with employee count and details.
 *
 * @group Training Management
 * @urlParam id string required The UUID of the training. Example: d89ce8a1-d119-4d27-9ce2-9a6a7b04dfd2
 *
 * @param string $id
 * @return \Illuminate\Http\JsonResponse
 */
public function show($id)
{
    $training = Training::with(['employees' => function ($query) {
        $query->withPivot('status', 'attended_at', 'feedback');
    }])->withCount('employees')->findOrFail($id);

    return response()->json([
        'message' => 'Training retrieved successfully',
        'data'    => $training
    ]);
}

/**
 * List Inactive Trainings
 *
 * Display a listing of inactive trainings with pagination and filtering.
 *
 * @group Training Management
 * @queryParam search string Filter inactive trainings by title or description. Example: Laravel
 * @queryParam date string Filter inactive trainings that occur on this date (YYYY-MM-DD). Example: 2025-12-20
 * @queryParam start_date string Filter inactive trainings starting on or after this date. Example: 2025-12-01
 * @queryParam end_date string Filter inactive trainings ending on or before this date. Example: 2025-12-31
 * @queryParam limit integer Items per page. Default 10. Example: 10
 * @queryParam page integer Page number for pagination. Example: 2
 *
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
public function inactive(Request $request)
{
    $search     = $request->query('search');
    $date       = $request->query('date');
    $startDate  = $request->query('start_date');
    $endDate    = $request->query('end_date');
    $limit      = $request->query('limit', 10);

    // Base query — ONLY INACTIVE TRAININGS
    $query = Training::where('is_active', false)->withCount('employees');

    // SEARCH — only in inactive
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    // DATE FILTERS — only in inactive
    if ($date) {
        $query->where(function ($q) use ($date) {
            $q->whereDate('start_date', '<=', $date)
              ->whereDate('end_date', '>=', $date);
        });
    }

    if ($startDate) {
        $query->whereDate('start_date', '>=', $startDate);
    }

    if ($endDate) {
        $query->whereDate('end_date', '<=', $endDate);
    }

    $trainings = $query->orderBy('start_date', 'desc')->paginate($limit);

    return response()->json([
        'message'    => 'Inactive trainings fetched successfully', // ← fixed typo "Acive" → "Inactive"
        'data'       => $trainings->items(),
        'pagination' => [
            'total'        => $trainings->total(),
            'per_page'     => $trainings->perPage(),
            'current_page' => $trainings->currentPage(),
            'last_page'    => $trainings->lastPage(),
        ]
    ]);
}
    /**
     * Create Training
     *
     * @group Training Management
     * @bodyParam title string required Training title
     * @bodyParam description string required Description
     * @bodyParam start_date date required Start date
     * @bodyParam end_date date required End date
     * @bodyParam trainer_name string optional
     * @bodyParam location string optional
     * @bodyParam incentive_amount decimal optional Required if has_incentive=true
     * @bodyParam has_incentive boolean optional
     * @bodyParam type string required internal, external, certification
     * @bodyParam is_mandatory boolean optional
     */
    public function store(TrainingStoreRequest $request)
    {
        $training = Training::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'is_active' => true,
        ] + $request->validated());

        return response()->json([
            'message' => 'Training created successfully',
            'data'    => $training
        ], 201);
    }

    /**
     * Update Training
     *
     * @group Training Management
     */
    public function update(TrainingUpdateRequest $request, $id)
    {
        $training = Training::findOrFail($id);
        $training->update($request->validated());

        return response()->json([
            'message' => 'Training updated successfully',
            'data'    => $training
        ]);
    }

    /**
     * Assign Employees to Training
     *
     * @group Training Management
     * @bodyParam employee_ids array required Array of employee UUIDs
     */
    public function assignEmployees(Request $request, $id)
    {
        $training = Training::findOrFail($id);

        $request->validate([
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id',
        ]);

        foreach ($request->employee_ids as $employeeId) {
            TrainingAttendee::firstOrCreate(
                ['training_id' => $training->id, 'employee_id' => $employeeId],
                ['id' => (string) \Illuminate\Support\Str::uuid(), 'status' => 'registered']
            );
        }

        return response()->json(['message' => 'Employees assigned to training successfully']);
    }

    /**
     * Mark Attendance
     *
     * @group Training Management
     * @bodyParam status string required registered, attended, absent, certified
     * @bodyParam feedback string optional
     */
    public function markAttendance(Request $request, $trainingId, $employeeId)
    {
        $attendee = TrainingAttendee::where('training_id', $trainingId)
                                    ->where('employee_id', $employeeId)
                                    ->firstOrFail();

        $request->validate([
            'status' => 'required|in:registered,attended,absent,certified',
             'feedback' => 'nullable|string',
        ]);

        $attendee->update([
            'status' => $request->status,
            'attended_at' => in_array($request->status, ['attended', 'certified']) ? now() : null,
            'feedback' => $request->feedback,
        ]);

        return response()->json(['message' => 'Attendance marked successfully']);
    }

    /**
 * Toggle Training Active/Inactive
 *
 * Toggle the visibility of a training (show/hide from list).
 *
 * @group Training Management
 * @urlParam id string required The UUID of the training.
 *
 * @param string $id
 * @return \Illuminate\Http\JsonResponse
 */
public function toggleStatus($id)
{
    $training = Training::findOrFail($id);
    $training->is_active = !$training->is_active;
    $training->save();

    return response()->json([
        'message'   => 'Training status updated successfully',
        'is_active' => $training->is_active,
        'data'      => $training
    ]);
}
}