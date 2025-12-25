<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Training;
use App\Models\TrainingAttendee;
use App\Http\Requests\Training\TrainingStoreRequest;
use App\Http\Requests\Training\TrainingUpdateRequest;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;
use App\Notifications\SystemNotification;

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
            $created = TrainingAttendee::firstOrCreate(
                ['training_id' => $training->id, 'employee_id' => $employeeId],
                ['id' => (string) \Illuminate\Support\Str::uuid(), 'status' => 'registered']
            );

            if ($created->wasRecentlyCreated) {
                // Notify Employee
                $employee = Employee::find($employeeId);
                $employee->notify(new SystemNotification(
                    'New Training Assignment',
                    "You have been assigned to the training: {$training->title}",
                    'info',
                    "/trainings/{$training->id}"
                ));
            }
        }

        return response()->json(['message' => 'Employees assigned to training successfully']);
    }


    /**
 * Assign All Employees to Training
 *
 * Assign all active employees to a training (useful for mandatory trainings).
 *
 * @group Training Management
 * @urlParam id string required The UUID of the training.
 *
 * @param string $id
 * @return \Illuminate\Http\JsonResponse
 */
public function assignAllEmployees($id)
{
    $training = Training::findOrFail($id);

    // Get all active employees
    $employees = Employee::where('status', 'active')->get();

    $assignedCount = 0;

    foreach ($employees as $employee) {
        $created = TrainingAttendee::firstOrCreate(
            ['training_id' => $training->id, 'employee_id' => $employee->id],
            ['id' => (string) \Illuminate\Support\Str::uuid(), 'status' => 'registered']
        );

        if ($created->wasRecentlyCreated) {
            $assignedCount++;

            // Notify Employee
            $employee->notify(new SystemNotification(
                'New Training Assignment',
                "You have been assigned to the training: {$training->title}",
                'info',
                "/trainings/{$training->id}"
            ));
        }
    }

    return response()->json([
        'message' => "All active employees assigned to training",
        'assigned_count' => $assignedCount,
        'total_employees' => $employees->count()
    ]);
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
/**
 * Employee Training History by ID
 *
 * Get all trainings an employee participated in with attendance status.
 *
 * @group Training Management
 * @urlParam employeeId string required The UUID of the employee.
 * @queryParam search string optional Search by training title or description. Example: Laravel
 * @queryParam limit integer optional Items per page. Default 10. Example: 20
 * @queryParam page integer Page number for pagination. Example: 2
 *
 * @param string $employeeId
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
public function employeeTrainingHistory(Request $request, $employeeId)
{
    $search = $request->query('search');
    $limit  = $request->query('limit', 10);

    $employee = Employee::with('personalInfo')->findOrFail($employeeId);

    $query = $employee->trainings()
        ->withPivot('status', 'attended_at', 'feedback');

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    $trainings = $query->orderBy('trainings.start_date', 'desc')
                       ->paginate($limit);

    $history = $trainings->map(function ($training) {
        return [
            'training_id' => $training->id,
            'title' => $training->title,
            'description' => $training->description,
            'start_date' => $training->start_date,
            'end_date' => $training->end_date,
            'trainer_name' => $training->trainer_name,
            'location' => $training->location,
            'type' => $training->type,
            'is_mandatory' => $training->is_mandatory,
            'incentive_amount' => $training->incentive_amount,
            'has_incentive' => $training->has_incentive,
            'status' => $training->pivot->status,
            'attended_at' => $training->pivot->attended_at,
            'feedback' => $training->pivot->feedback,
        ];
    });

    return response()->json([
        'message' => 'Employee training history fetched successfully',
        'employee' => [
            'id' => $employee->id,
            'full_name' => $employee->personalInfo->first_name . ' ' . $employee->personalInfo->last_name,
            'email' => $employee->personalInfo->email,
            'phone' => $employee->personalInfo->phone,
        ],
        'total_trainings' => $trainings->total(),
        'data' => $history,
        'pagination' => [
            'total'        => $trainings->total(),
            'per_page'     => $trainings->perPage(),
            'current_page' => $trainings->currentPage(),
            'last_page'    => $trainings->lastPage(),
        ]
    ]);
}


/**
 * List Attendees/Employee in a Training
 *
 * Display all employees assigned to a specific training with pagination, search, and status filter.
 *
 * @group Training Management
 * @urlParam id string required The UUID of the training.
 * @queryParam search string optional Search by employee name, email, or phone. Example: Abebe
 * @queryParam status string optional Filter by attendance status (registered, attended, absent, certified). Example: attended
 * @queryParam limit integer optional Items per page. Default 10. Example: 20
 *
 * @param Request $request
 * @param string $id
 * @return \Illuminate\Http\JsonResponse
 */
public function attendees(Request $request, $id)
{
    $search = $request->query('search');
    $status = $request->query('status');
    $limit  = $request->query('limit', 10);

    $training = Training::findOrFail($id);

    $query = $training->employees()
        ->join('employee_personal_infos', 'employees.id', '=', 'employee_personal_infos.employee_id')
        ->select(
            'employees.id',
            'employee_personal_infos.first_name',
            'employee_personal_infos.last_name',
            'employee_personal_infos.email',
            'employee_personal_infos.phone',
            'training_attendees.status',
            'training_attendees.attended_at',
            'training_attendees.feedback'
        );

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('employee_personal_infos.first_name', 'like', "%{$search}%")
              ->orWhere('employee_personal_infos.last_name', 'like', "%{$search}%")
              ->orWhere('employee_personal_infos.email', 'like', "%{$search}%")
              ->orWhere('employee_personal_infos.phone', 'like', "%{$search}%");
        });
    }

    if ($status) {
        $query->where('training_attendees.status', $status);
    }

    $attendees = $query->orderBy('employee_personal_infos.first_name')
                       ->paginate($limit);

    return response()->json([
        'message' => 'Training attendees fetched successfully',
        'training' => [
            'id' => $training->id,
            'title' => $training->title,
            'total_attendees' => $attendees->total(),
        ],
        'data' => $attendees->items(),
        'pagination' => [
            'total'        => $attendees->total(),
            'per_page'     => $attendees->perPage(),
            'current_page' => $attendees->currentPage(),
            'last_page'    => $attendees->lastPage(),
        ]
    ]);
}


}