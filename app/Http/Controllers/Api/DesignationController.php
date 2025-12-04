<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use App\Http\Requests\Designation\StoreDesignationRequest;
use App\Http\Requests\Designation\UpdateDesignationRequest;

class DesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
       
    {
        $search =request()->query('search');
        $limit =request()->query('limit', 10);
        $sort =request()->query('sort', 'created_at');
        $order =request()->query('order', 'desc');
        $status =request()->query('status');
        $query = Designation::with('department');

        if($search){
            $query->where('title', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
        }
        if($status){
            $query->where('status', $status);
        }

        // if($status){
        //     $query->where('status', $status);
        // }

        $designation = $query->orderBy($sort,$order)->paginate($limit);

        return response()->json([
        'message' => 'Designation fetched successfully',
        'data' => $designation->items(),
        'pagination' => [
            'total' => $designation->total(),
            'per_page' => $designation->perPage(),
            'current_page' => $designation->currentPage(),
            'last_page' => $designation->lastPage(),
        ]
    ]);
    }

    /**
     * Store a newly created resource in storage.
     */
 public function store(StoreDesignationRequest $request)
    {
        $designation = Designation::create($request->validated());

        return response()->json([
            'message'     => 'Designation created successfully',
            'designation' => $designation
        ], 201);
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $designation = Designation::with('department')->findOrFail($id);

        return response()->json([
            'message' => 'Designation retrieved successfully',
            'data'    => $designation
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
 public function update(UpdateDesignationRequest $request, $id)
    {
        $designation = Designation::findOrFail($id);
        $designation->update($request->validated());

        return response()->json([
            'message' => 'Designation updated successfully',
            'data'    => $designation->fresh()
        ]);
    }


     public function toggleStatus($id)
{
    $designation = Designation::findOrFail($id);

    $designation->status = $designation->status === 'active' ? 'inactive' : 'active';
    $designation->save();

    return response()->json([
        'message' => 'Department status updated successfully',
        'status' => $designation->status,
        'department' => $designation
    ]);
}

 
}
