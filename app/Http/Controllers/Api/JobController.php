<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Http\Requests\Job\JobStoreRequest;
use App\Http\Requests\Job\JobUpdateRequest;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $limit  = $request->query('limit', 10);

        $query = Job::with(['department', 'designation']);

        if ($search) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $jobs = $query->orderBy('created_at', 'desc')->paginate($limit);

        return response()->json([
            'message'    => 'Jobs fetched successfully',
            'data'       => $jobs->items(),
            'pagination' => [
                'total'       => $jobs->total(),
                'per_page'    => $jobs->perPage(),
                'current_page'=> $jobs->currentPage(),
                'last_page'   => $jobs->lastPage(),
            ]
        ]);
    }

    public function active()
    {
        return response()->json([
            'message' => 'Active jobs fetched',
            'data'    => Job::active()->with(['department', 'designation'])->get()
        ]);
    }

    public function store(JobStoreRequest $request)
    {
        $job = Job::create($request->validated() + [
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'is_active' => true,
        ]);

        return response()->json([
            'message' => 'Job posted successfully',
            'data'    => $job->load(['department', 'designation'])
        ], 201);
    }

    public function update(JobUpdateRequest $request, $id)
    {
        $job = Job::findOrFail($id);
        $job->update($request->validated());

        return response()->json([
            'message' => 'Job updated successfully',
            'data'    => $job
        ]);
    }

    public function destroy($id)
    {
        $job = Job::findOrFail($id);
        $job->delete();

        return response()->json(['message' => 'Job deleted']);
    }
}