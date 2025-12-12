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

class CandidateController extends Controller
{
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

    public function hire(Request $request, $id)
    {
        $candidate = Candidate::findOrFail($id);

        if ($candidate->status !== 'hired') {
            $candidate->update(['status' => 'hired']);
        }

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
            'employment_type'=> 'permanent',
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