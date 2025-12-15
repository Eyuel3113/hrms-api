<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Job;
use App\Models\Employee;
use App\Models\EmployeePersonalInfo;
use App\Models\EmployeeProfessionalInfo;
use App\Http\Requests\Candidate\CandidateStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

/**
 * Controller for managing Candidate applications.
 *
 * @group Candidate Management
 * APIs for managing candidates, applications, and hiring.
 */
class CandidateController extends Controller
{
    /**
     * List Candidates
     *
     * Display a listing of candidates with filtering and pagination.
     *
     * @queryParam search string Filter by name or email.
     * @queryParam status string Filter by status (new, reviewed, etc).
     * @queryParam job_id string Filter by job UUID.
     * @queryParam limit integer Items per page. Default 10.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $job_id = $request->query('job_id');
        $limit  = $request->query('limit', 10);

        $query = Candidate::with(['job.department', 'job.designation']);

        if ($search) {
            $query->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($job_id) {
            $query->where('job_id', $job_id);
        }

        $candidates = $query->orderBy('created_at', 'desc')->paginate($limit);

        return response()->json([
            'message'    => 'Candidates fetched successfully',
            'data'       => $candidates->items(),
            'pagination' => [
                'total'        => $candidates->total(),
                'per_page'     => $candidates->perPage(),
                'current_page' => $candidates->currentPage(),
                'last_page'    => $candidates->lastPage(),
            ]
        ]);
    }

    /**
 * Get Candidate by ID
 *
 * Display the specified candidate with job details.
 *
 * @group Candidate Management
 * @urlParam id string required The UUID of the candidate. Example: 45f0064d-b0de-4f82-9240-47c41b0fc122
 *
 * @param string $id
 * @return \Illuminate\Http\JsonResponse
 */
public function show($id)
{
    $candidate = Candidate::with(['job.department', 'job.designation'])
                          ->findOrFail($id);

    return response()->json([
        'message' => 'Candidate retrieved successfully',
        'data'    => $candidate,
        'cv_url'  => $candidate->cv_path ? asset('storage/' . $candidate->cv_path) : null
    ]);
}

    /**
     * Submit Application
     *
     * Store a newly created candidate application.
     *
     * @bodyParam job_id string required The UUID of the job.
     * @bodyParam full_name string required Candidate's full name.
     * @bodyParam email string required Candidate's email address.
     * @bodyParam phone string required Candidate's phone number.
     * @bodyParam cv file required The CV/Resume file (pdf, doc, docx). Max 10MB.
     * @bodyParam cover_letter string optional Cover letter text.
     *
     * @param \App\Http\Requests\Candidate\CandidateStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CandidateStoreRequest $request)
    {
        $cvPath = null;

        if ($request->hasFile('cv')) {
            $file = $request->file('cv');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('candidates/cv', $filename, 'public');
            $cvPath = $path; 
        }

        $candidate = Candidate::create([
            'id'            => (string) Str::uuid(),
            'job_id'        => $request->job_id,
            'full_name'     => $request->full_name,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'cv_path'       => $cvPath,
            'cover_letter'  => $request->cover_letter,
            'status'        => 'new',
        ]);

        return response()->json([
            'message' => 'Application submitted successfully',
            'data'    => $candidate->load('job'),
            'cv_url'  => $cvPath ? asset('storage/' . $cvPath) : null
        ], 201);
    }

    /**
     * Update Status
     *
     * Update the status of a specific candidate.
     *
     * @bodyParam status string required The new status (new, reviewed, interview, shortlisted, rejected, hired).
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, $id)
    {
        $candidate = Candidate::findOrFail($id);

        $request->validate([
            'status' => 'required|in:new,reviewed,interview,shortlisted,rejected,hired'
        ]);

        $candidate->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Candidate status updated',
            'data'    => $candidate
        ]);
    }

    /**
     * Hire Candidate
     *
     * Hire a candidate and convert them to an employee.
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function hire(Request $request, $id)
    {
        $candidate = Candidate::findOrFail($id);

        if ($candidate->status === 'hired' || $candidate->hired_as_employee_id) {
            return response()->json([
                'message' => 'This candidate has already been hired.',
                //'employee_id' => $candidate->hired_as_employee_id
            ], 400); // Bad Request
        }

        $candidate->update(['status' => 'hired']);

        $employee = Employee::create([
            'id'            => (string) Str::uuid(),
            'employee_code' => 'EMP-' . strtoupper(substr($candidate->full_name, 0, 3)) . '-' . rand(1000, 9999),
            'status'        => 'active',
        ]);

        $employee->personalInfo()->create([
            'first_name' => explode(' ', $candidate->full_name)[0] ?? '',
            'last_name'  => explode(' ', $candidate->full_name)[1] ?? '',
            'email'      => $candidate->email,
            'phone'      => $candidate->phone,
        ]);

        $employee->professionalInfo()->create([
            'department_id'  => $candidate->job->department_id,
            'designation_id' => $candidate->job->designation_id,
            'joining_date'   => now()->format('Y-m-d'),
            'employment_type'=> 'full-time',
            'basic_salary'   => 0,
        ]);

        $candidate->update([
            'hired_at' => now(),
            'hired_as_employee_id' => $employee->id,
        ]);

        return response()->json([
            'message' => 'Candidate hired and converted to employee!',
            'employee_id' => $employee->id,
            'data' => $employee->load(['personalInfo', 'professionalInfo'])
        ]);
    }
}