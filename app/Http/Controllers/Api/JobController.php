<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Http\Requests\Job\JobStoreRequest;
use App\Http\Requests\Job\JobUpdateRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\SystemNotification;

/**
 * Controller for managing Job postings.
 *
 * @group Job Management
 * APIs for managing job postings, including creation, updates, and retrieval.
 */
class JobController extends Controller
{
/**
 * List Jobs
 *
 * Display a listing of the jobs with pagination and filtering.
 *
 * @group Job Management
 * @queryParam search string Filter jobs by title or description. Example: Developer
 * @queryParam department_id string Filter by department UUID. Example: a1b2c3d4-e5f6-7890-g1h2-i3j4k5l6m7n8
 * @queryParam designation_id string Filter by designation UUID. Example: b2c3d4e5-f6g7-8901-h2i3-j4k5l6m7n8o9
 * @queryParam limit integer Items per page. Default 10. Example: 10
 * @queryParam page integer Page number for pagination. Example: 2
 *
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
public function index(Request $request)
{
    $search         = $request->query('search');
    $departmentId   = $request->query('department_id');
    $designationId  = $request->query('designation_id');
    $limit          = $request->query('limit', 10);

    $query = Job::with(['department', 'designation']);

    if ($search) {
        $query->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
    }

    if ($departmentId) {
        $query->where('department_id', $departmentId);
    }

    if ($designationId) {
        $query->where('designation_id', $designationId);
    }

    $jobs = $query->orderBy('created_at', 'desc')->paginate($limit);

    return response()->json([
        'message'    => 'Jobs fetched successfully',
        'data'       => $jobs->items(),
        'pagination' => [
            'total'        => $jobs->total(),
            'per_page'     => $jobs->perPage(),
            'current_page' => $jobs->currentPage(),
            'last_page'    => $jobs->lastPage(),
        ]
    ]);
}

/**
 * List Active Jobs
 *
 * Display a listing of active jobs (for public view) with optional filtering.
 *
 * @group Job Management
 * @queryParam search string Filter active jobs by title or description. Example: Developer
 * @queryParam department_id string Filter active jobs by department UUID. Example: a1b2c3d4-e5f6-7890-g1h2-i3j4k5l6m7n8
 * @queryParam designation_id string Filter active jobs by designation UUID. Example: b2c3d4e5-f6g7-8901-h2i3-j4k5l6m7n8o9
 * @queryParam limit integer Items per page. Default 10. Example: 10
 * @queryParam page integer Page number for pagination. Example: 2
 *
 * @return \Illuminate\Http\JsonResponse
 */
public function active(Request $request)
{
    $search         = $request->query('search');
    $departmentId   = $request->query('department_id');
    $designationId  = $request->query('designation_id');
    $limit          = $request->query('limit', 10);

    $query = Job::active() // only active + open status
                ->with(['department', 'designation']);

    if ($search) {
        $query->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
    }

    if ($departmentId) {
        $query->where('department_id', $departmentId);
    }

    if ($designationId) {
        $query->where('designation_id', $designationId);
    }

    $jobs = $query->orderBy('created_at', 'desc')->paginate($limit);

    return response()->json([
        'message' => 'Active jobs fetched successfully',
        'data'    => $jobs->items(),
            'pagination' => [
            'total'        => $jobs->total(),
            'per_page'     => $jobs->perPage(),
            'current_page' => $jobs->currentPage(),
            'last_page'    => $jobs->lastPage(),
        ]
    ]);
}

/**
 * List Inactive Jobs
 *
 * Display a listing of Inactive jobs (for public view) with optional filtering.
 *
 * @group Job Management
 * @queryParam search string Filter active jobs by title or description. Example: Developer
 * @queryParam department_id string Filter active jobs by department UUID. Example: a1b2c3d4-e5f6-7890-g1h2-i3j4k5l6m7n8
 * @queryParam designation_id string Filter active jobs by designation UUID. Example: b2c3d4e5-f6g7-8901-h2i3-j4k5l6m7n8o9
 * @queryParam limit integer Items per page. Default 10. Example: 10
 * @queryParam page integer Page number for pagination. Example: 2
 *
 * @return \Illuminate\Http\JsonResponse
 */
public function inactive(Request $request)
{
    $search         = $request->query('search');
    $departmentId   = $request->query('department_id');
    $designationId  = $request->query('designation_id');
    $limit          = $request->query('limit', 10);

    $query = Job::where('is_active',false) // only inactive + open status
                ->with(['department', 'designation']);

    if ($search) {
        $query->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
    }

    if ($departmentId) {
        $query->where('department_id', $departmentId);
    }

    if ($designationId) {
        $query->where('designation_id', $designationId);
    }

    $jobs = $query->orderBy('created_at', 'desc')->paginate($limit);

    return response()->json([
        'message' => 'Inactive jobs fetched successfully',
        'data'    => $jobs->items(),
            'pagination' => [
            'total'        => $jobs->total(),
            'per_page'     => $jobs->perPage(),
            'current_page' => $jobs->currentPage(),
            'last_page'    => $jobs->lastPage(),
        ]
    ]);
}

/**
     * Get Job by ID
     *
     * Display the specified job with department and designation details.
     *
     * @group Job Management
     * @urlParam id string required The UUID of the job. Example: 45f0064d-b0de-4f82-9240-47c41b0fc122
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $job = Job::with(['department', 'designation'])->findOrFail($id);

        return response()->json([
            'message' => 'Job retrieved successfully',
            'data'    => $job
        ]);
    }


    /**
     * Create Job
     *
     * Store a newly created job.
     *
     * @bodyParam title string required The job title. Example: Senior Laravel Developer
     * @bodyParam department_id string required The UUID of the department.
     * @bodyParam designation_id string optional The UUID of the designation.
     * @bodyParam description string required The job description.
     * @bodyParam vacancy integer required Number of vacancies. Example: 2
     * @bodyParam deadline date required The application deadline (YYYY-MM-DD). Must be a future date. Example: 2025-12-31
     *
     * @param \App\Http\Requests\Job\JobStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(JobStoreRequest $request)
    {
        $job = Job::create($request->validated() + [
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'is_active' => true,
        ]);

        // Notify HR (Placeholder: first user)
        $admin = User::first();
        if ($admin) {
            $admin->notify(new SystemNotification(
                'New Job Posted',
                "A new job {$job->title} posted.",
                'info',
                "/recruitment/jobs/{$job->id}"
            ));
        }

        return response()->json([
            'message' => 'Job posted successfully',
            'data'    => $job->load(['department', 'designation'])
        ], 201);
    }

    /**
     * Update Job
     *
     * Update the specified job.
     *
     * @bodyParam title string optional The job title.
     * @bodyParam department_id string optional The UUID of the department.
     * @bodyParam description string optional The job description.
     * @bodyParam vacancy integer optional Number of vacancies.
     * @bodyParam deadline date optional The application deadline (YYYY-MM-DD). Must be a future date.
     * @bodyParam status string optional Status of the job (open, closed, on_hold). Example: open
     *
     * @param \App\Http\Requests\Job\JobUpdateRequest $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(JobUpdateRequest $request, $id)
    {
        $job = Job::findOrFail($id);
        $job->update($request->validated());

        return response()->json([
            'message' => 'Job updated successfully',
            'data'    => $job
        ]);
    }

/**
     * Toggle Job Active/Inactive
     *
     * Toggle the active status of a job (show/hide from public).
     *
     * @group Job Management
     * @urlParam id string required The UUID of the job.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleStatus($id)
    {
        $job = Job::findOrFail($id);
        $job->is_active = !$job->is_active;
        $job->save();

        return response()->json([
            'message'   => 'Job visibility updated successfully',
            'is_active' => $job->is_active,
            'data'      => $job
        ]);
    }




    /**
     * Delete Job
     *
     * Remove the specified job from storage.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $job = Job::findOrFail($id);
        $job->delete();

        return response()->json(['message' => 'Job deleted']);
    }
}