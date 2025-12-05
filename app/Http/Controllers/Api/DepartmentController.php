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
        $search =request()->query('search');
        $status =request()->query('status');
        $limit =request()->query('limit', 10);
        $sort =request()->query('sort', 'created_at');
        $order =request()->query('order', 'desc');
        $query = Department::query();

        if($search){
            $query->where('name', 'like', "%$search%")
                  ->orWhere('code', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
        }

        if($status){
            $query->where('status', $status);
        }

        $departments = $query->orderBy($sort,$order)->paginate($limit);

        return response()->json([
        'message' => 'Departments fetched successfully',
        'data' => $departments->items(),
        'pagination' => [
            'total' => $departments->total(),
            'per_page' => $departments->perPage(),
            'current_page' => $departments->currentPage(),
            'last_page' => $departments->lastPage(),
        ]
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
        return response()->json(Department::findOrFail($id));
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