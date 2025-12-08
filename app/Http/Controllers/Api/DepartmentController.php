<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Http\Requests\Department\DepartmentStoreRequest;
use App\Http\Requests\Department\DepartmentUpdateRequest;

class DepartmentController extends Controller
{
 
    public function index()
{
    $search = request()->query('search');
    $status = request()->query('status');
    $limit  = request()->query('limit', 10);
    $sort   = request()->query('sort', 'created_at');
    $order  = request()->query('order', 'desc');

    $query = Department::query()
        ->withCount('employees')      
        ->withCount('designations');   

    // Search
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('code', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    // Status filter
    if ($status) {
        $query->where('status', $status);
    }

    // Pagination
    $departments = $query->orderBy($sort, $order)->paginate($limit);

    // Optional: Add 5 recent employees preview (you already had this)
    $departments->getCollection()->transform(function ($department) {
        $department->employees = $department->employees()
            ->with('personalInfo')
            ->take(5)
            ->get();
        return $department;
    });

    return response()->json([
        'message'    => 'Departments fetched successfully',
        'data'       => $departments->items(),
        'pagination' => [
            'total'       => $departments->total(),
            'per_page'    => $departments->perPage(),
            'current_page'=> $departments->currentPage(),
            'last_page'   => $departments->lastPage(),
        ]
    ]);
}
public function all()
{
    $search = request()->query('search');

    $query = Department::where('status', 'active');

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('code', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    return response()->json([
        'message' => 'Active departments fetched successfully',
        'data'    => $query->get()
    ]);
}
    public function store(DepartmentStoreRequest $request)
    {
        $department = Department::create($request->validated());

        return response()->json([
            'message' => 'Department created successfully',
            'data' => $department
        ], 201);
    }

    public function show($id)
    {
        $department = Department::withCount('designations')->findOrFail($id);
        
        $department->setRelation('employees', 
            $department->employees()
                ->with('personalInfo')
                ->take(5)
                ->get()
        );

        return response()->json($department);
    }

    public function update(DepartmentUpdateRequest $request, $id)
    {
        $department = Department::findOrFail($id);
        $department->update($request->validated());

        return response()->json([
            'message' => 'Department updated successfully',
            'data' => $department
        ]);
    }

       public function toggleStatus($id)
{
    $department = Department::findOrFail($id);

    $department->status = $department->status === 'active' ? 'inactive' : 'active';
    $department->save();

    return response()->json([
        'message' => 'Department status updated successfully',
        'status' => $department->status,
        'department' => $department
    ]);
}


}