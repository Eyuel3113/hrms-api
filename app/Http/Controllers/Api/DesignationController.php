<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use App\Http\Requests\Designation\StoreDesignationRequest;
use App\Http\Requests\Designation\UpdateDesignationRequest;

class DesignationController extends Controller
{
    

    /**
     * List Designations
     * 
     * Get a paginated list of designations.
     * 
     * @group Designations
     * @queryParam search string Search by title or description.
     * @queryParam status string Filter by status.
     * @queryParam limit int Items per page. Default 10.
     * @response 200 {
     *  "data": [ ... ],
     *  "pagination": { ... }
     * }
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

      public function all()
{
    $search = request()->query('search');

    $query = Designation::where('status', 'active');

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    return response()->json([
        'message' => 'Active Designations fetched successfully',
        'data'    => $query->get()
    ]);
}

    
    /**
     * Create Designation
     * 
     * Create a new designation.
     * 
     * @group Designations
     * @bodyParam title string required Title of the designation.
     * @bodyParam department_id uuid required Department ID.
     * @response 201 {
     *  "message": "Designation created successfully",
     *  "designation": { ... }
     * }
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
    /**
     * Get Designation
     * 
     * Get designation details by ID.
     * 
     * @group Designations
     * @response 200 {
     *  "message": "Designation retrieved successfully",
     *  "data": { ... }
     * }
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
    /**
     * Update Designation
     * 
     * Update designation details.
     * 
     * @group Designations
     * @response 200 {
     *  "message": "Designation updated successfully",
     *  "data": { ... }
     * }
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


    /**
     * Toggle Status
     * 
     * Toggle designation status.
     * 
     * @group Designations
     * @response 200 {
     *  "message": "Designation status updated successfully",
     *  "status": "active"
     * }
     */
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
