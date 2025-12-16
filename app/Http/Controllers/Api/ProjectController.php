<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Http\Requests\Project\ProjectStoreRequest;
use App\Http\Requests\Project\ProjectUpdateRequest;
use Illuminate\Http\Request;



/**
 * Controller for managing Projects.
 *
 * @group Project Management
 * APIs for managing projects, employee assignment, rating, and performance scoring.
 */
class ProjectController extends Controller
{

    /**
     * List Projects
     *
     * Display a paginated list of all projects with optional search.
     *
     * @group Project Management
     * @queryParam search string optional Filter by title or description. Example: HRMS
     * @queryParam limit integer optional Items per page. Default 10. Example: 20
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
 
    public function index(Request $request)
    {
        $search = $request->query('search');
        $limit  = $request->query('limit', 10);

        $query = Project::withCount('employees');

        if ($search) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $projects = $query->orderBy('start_date', 'desc')->paginate($limit);

        return response()->json([
            'message'    => 'Projects fetched successfully',
            'data'       => $projects->items(),
            'pagination' => [
                'total'        => $projects->total(),
                'per_page'     => $projects->perPage(),
                'current_page' => $projects->currentPage(),
                'last_page'    => $projects->lastPage(),
            ]
        ]);
    }


    /**
     * List Active Projects
     *
     * Display a paginated list of active projects with optional search.
     *
     * @group Project Management
     * @queryParam search string optional Filter by title or description. Example: Mobile
     * @queryParam limit integer optional Items per page. Default 10. Example: 15
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function active(Request $request)
    {
        $search = $request->query('search');
        $limit  = $request->query('limit', 10);

        $query = Project::where('is_active', true)->withCount('employees');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $projects = $query->orderBy('start_date', 'desc')->paginate($limit);

        return response()->json([
            'message'    => 'Active projects fetched successfully',
            'data'       => $projects->items(),
            'pagination' => [
                'total'        => $projects->total(),
                'per_page'     => $projects->perPage(),
                'current_page' => $projects->currentPage(),
                'last_page'    => $projects->lastPage(),
            ]
        ]);
    }


    /**
     * List Inactive Projects
     *
     * Display a paginated list of inactive projects with optional search.
     *
     * @group Project Management
     * @queryParam search string optional Filter by title or description. Example: Old
     * @queryParam limit integer optional Items per page. Default 10. Example: 10
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */



    public function inactive(Request $request)
    {
        $search = $request->query('search');
        $limit  = $request->query('limit', 10);

        $query = Project::where('is_active', false)->withCount('employees');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $projects = $query->orderBy('start_date', 'desc')->paginate($limit);

        return response()->json([
            'message'    => 'Inactive projects fetched successfully',
            'data'       => $projects->items(),
            'pagination' => [
                'total'        => $projects->total(),
                'per_page'     => $projects->perPage(),
                'current_page' => $projects->currentPage(),
                'last_page'    => $projects->lastPage(),
            ]
        ]);
    }
     
    /**
     * Get Project by ID
     *
     * Display the specified project with members and average rating.
     *
     * @group Project Management
     * @urlParam id string required The UUID of the project. Example: d89ce8a1-d119-4d27-9ce2-9a6a7b04dfd2
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */


    public function show($id)
    {
        $project = Project::with(['employees.personalInfo'])->findOrFail($id);

        return response()->json([
            'message' => 'Project retrieved successfully',
            'data'    => $project,
            'average_rating' => $project->average_rating
        ]);
    }

/**
     * Create Project
     *
     * Store a newly created project.
     *
     * @group Project Management
     * @bodyParam title string required Project title. Example: HRMS Mobile App
     * @bodyParam description string required Project description.
     * @bodyParam start_date date required Start date (YYYY-MM-DD).
     * @bodyParam end_date date required End date (YYYY-MM-DD).
     * @bodyParam status string optional planning, in_progress, completed, on_hold, cancelled. Default: planning
     *
     * @param ProjectStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function store(ProjectStoreRequest $request)
    {
        $project = Project::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'is_active' => true,
        ] + $request->validated());

        return response()->json([
            'message' => 'Project created successfully',
            'data'    => $project
        ], 201);
    }


/**
     * Update Project
     *
     * Update the specified project.
     *
     * @group Project Management
     * @urlParam id string required The UUID of the project.
     * @bodyParam title string optional
     * @bodyParam description string optional
     * @bodyParam start_date date optional
     * @bodyParam end_date date optional
     * @bodyParam status string optional planning, in_progress, completed, on_hold, cancelled
     * @bodyParam is_active boolean optional
     *
     * @param ProjectUpdateRequest $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */


    public function update(ProjectUpdateRequest $request, $id)
    {
        $project = Project::findOrFail($id);
        $project->update($request->validated());

        return response()->json([
            'message' => 'Project updated successfully',
            'data'    => $project
        ]);
    }



    /**
     * Toggle Project Status
     *
     * Toggle active/inactive status of a project.
     *
     * @group Project Management
     * @urlParam id string required The UUID of the project.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleStatus($id)
    {
        $project = Project::findOrFail($id);
        $project->is_active = !$project->is_active;
        $project->save();

        return response()->json([
            'message'   => 'Project status toggled',
            'is_active' => $project->is_active,
            'data'      => $project
        ]);
    }


    /**
     * Assign Employees to Project
     *
     * Assign multiple employees to a project.
     *
     * @group Project Management
     * @urlParam id string required The UUID of the project.
     * @bodyParam employee_ids array required Array of employee UUIDs.
     * @bodyParam employee_ids.* string required Employee UUID.
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function assignEmployees(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $request->validate([
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id',
        ]);

        foreach ($request->employee_ids as $employeeId) {
            ProjectMember::firstOrCreate(
                ['project_id' => $project->id, 'employee_id' => $employeeId],
                ['id' => (string) \Illuminate\Support\Str::uuid()]
            );
        }

        return response()->json(['message' => 'Employees assigned to project']);
    }


/**
 * Assign All Employees to Project
 *
 * Assign all active employees to a project (useful for company-wide projects).
 *
 * @group Project Management
 * @urlParam id string required The UUID of the project.
 *
 * @param string $id
 * @return \Illuminate\Http\JsonResponse
 */
public function assignAllEmployees($id)
{
    $project = Project::findOrFail($id);

    // Get all active employees
    $employees = Employee::where('status', 'active')->get();

    $assignedCount = 0;

    foreach ($employees as $employee) {
        $created = ProjectMember::firstOrCreate(
            ['project_id' => $project->id, 'employee_id' => $employee->id],
            ['id' => (string) \Illuminate\Support\Str::uuid()]
        );

        if ($created->wasRecentlyCreated) {
            $assignedCount++;
        }
    }

    return response()->json([
        'message' => 'All active employees assigned to project successfully',
        'assigned_count' => $assignedCount,
        'total_employees' => $employees->count()
    ]);
}

    /**
     * Rate Employee in Project
     *
     * Rate an employee's performance in a project (1-5).
     *
     * @group Project Management
     * @urlParam projectId string required The UUID of the project.
     * @urlParam employeeId string required The UUID of the employee.
     * @bodyParam rating integer required Rating from 1 to 5.
     * @bodyParam feedback string optional Feedback comment.
     *
     * @param Request $request
     * @param string $projectId
     * @param string $employeeId
     * @return \Illuminate\Http\JsonResponse
     */

    public function rateEmployee(Request $request, $projectId, $employeeId)
    {
        $member = ProjectMember::where('project_id', $projectId)
                               ->where('employee_id', $employeeId)
                               ->firstOrFail();

        $request->validate([
            'rating'    => 'required|integer|min:1|max:5',
            'feedback'  => 'nullable|string',
        ]);

        $member->update([
            'rating'    => $request->rating,
            'feedback'  => $request->feedback,
            'rated_at'  => now(),
        ]);

        return response()->json([
            'message' => 'Employee rated successfully',
            'data'    => $member
        ]);
    }

/**
     * Employee Overall Performance Score
     *
     * Get the average rating of an employee across all projects.
     *
     * @group Project Management
     * @urlParam employeeId string required The UUID of the employee.
     *
     * @param string $employeeId
     * @return \Illuminate\Http\JsonResponse
     */


    public function employeePerformance($employeeId)
    {
        $average = ProjectMember::where('employee_id', $employeeId)
                                ->whereNotNull('rating')
                                ->avg('rating') ?? 0;

        return response()->json([
            'message' => 'Employee performance score',
            'employee_id' => $employeeId,
            'overall_rating' => round($average, 2),
            'out_of' => 5
        ]);
    }
}