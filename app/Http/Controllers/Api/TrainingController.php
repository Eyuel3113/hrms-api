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
     * @group Training Management
     * @queryParam search string Filter by title or description
     * @queryParam limit integer Items per page. Default 10
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $limit  = $request->query('limit', 10);

        $query = Training::withCount('employees');

        if ($search) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
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
}