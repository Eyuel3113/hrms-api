<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Employee\EmployeeStoreRequest;
use App\Http\Requests\Employee\EmployeeUpdateRequest;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Notifications\SystemNotification;

class EmployeeController extends Controller
{
    // GET ALL
    /**
     * List Employees
     * 
     * Get a paginated list of employees.
     * 
     * @group Employees
     * @queryParam search string Search by name, email, phone, or employee code.
     * @queryParam status string Filter by status (active/inactive).
     * @queryParam department_id uuid Filter by department.
     * @queryParam designation_id uuid Filter by designation.
     * @queryParam sort string Sort field (e.g., created_at, first_name).
     * @queryParam order string Sort order (asc/desc).
     * @queryParam limit int Items per page. Default 10.
     * @response 200 {
     *  "data": [ ... ],
     *  "pagination": { ... }
     * }
     */
    public function index(Request $request)
    {
        $query = Employee::with(['personalInfo', 'professionalInfo.department', 'professionalInfo.designation']);

        // Search
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('employee_code', 'like', "%$search%")
                  ->orWhereHas('personalInfo', function ($qq) use ($search) {
                    $qq->where('first_name', 'like', "%$search%")
                       ->orWhere('last_name', 'like', "%$search%")
                       ->orWhere('email', 'like', "%$search%")
                       ->orWhere('phone', 'like', "%$search%");
                });
            });
        }

        // Filter by Status
        $query->when($request->status, fn($q, $s) => $q->where('status', $s));

        // Filter by Department and Designation
        $query->when($request->department_id, fn($q, $d) => $q->whereHas('professionalInfo', fn($qq) => $qq->where('department_id', $d)))
              ->when($request->designation_id, fn($q, $d) => $q->whereHas('professionalInfo', fn($qq) => $qq->where('designation_id', $d)));

        // Sorting
        $sort = $request->query('sort', 'created_at');
        $order = $request->query('order', 'desc');

        if (in_array($sort, ['first_name', 'last_name', 'email'])) {
            $query->join('employee_personal_infos', 'employees.id', '=', 'employee_personal_infos.employee_id')
                  ->orderBy('employee_personal_infos.' . $sort, $order)
                  ->select('employees.*');
        } else {
            $query->orderBy($sort, $order);
        }

        $employees = $query->paginate($request->limit ?? 10);

        return response()->json([
            'message' => 'Employees fetched successfully',
            'data' => $employees->items(),
            'pagination' => [
                'total' => $employees->total(),
                'limit' => $employees->perPage(),
                'current_page' => $employees->currentPage(),
                'last_page' => $employees->lastPage(),
            ]
        ]);
    }
/**
 * Get All Employees (No Pagination)
 * 
 * Returns all employees with full filtering and search.
 * Used for dropdowns, reports, ZKTeco sync, etc.
 * 
 * @group Employees
 * @queryParam search string Search by name, email, phone, employee code
 * @queryParam status string active/inactive
 * @queryParam department_id uuid Filter by department
 * @queryParam designation_id uuid Filter by designation
 * @queryParam sort string e.g., first_name, created_at
 * @queryParam order string asc/desc
 */
public function all(Request $request)
{
    $query = Employee::with(['personalInfo', 'professionalInfo', 'professionalInfo.department', 'professionalInfo.designation', 'shift', 'socialLinks']);

    // SEARCH
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('employee_code', 'like', "%{$search}%")
              ->orWhereHas('personalInfo', function($qq) use ($search) {
                  $qq->where('first_name', 'like', "%{$search}%")
                     ->orWhere('last_name',  'like', "%{$search}%")
                     ->orWhere('email',      'like', "%{$search}%")
                     ->orWhere('phone',      'like', "%{$search}%");
              });
        });
    }

    // FILTERS
    $query->when($request->status,          fn($q, $s) => $q->where('status', $s));
    $query->when($request->department_id,   fn($q, $d) => $q->whereHas('professionalInfo', fn($qq) => $qq->where('department_id', $d)));
    $query->when($request->designation_id,  fn($q, $d) => $q->whereHas('professionalInfo', fn($qq) => $qq->where('designation_id', $d)));

    // SORTING (same logic as index)
    $sort  = $request->query('sort', 'created_at');
    $order = $request->query('order', 'desc');

    if (in_array($sort, ['first_name', 'last_name', 'email', 'phone'])) {
        $query->join('employee_personal_infos as pi', 'employees.id', '=', 'pi.employee_id')
              ->orderBy("pi.{$sort}", $order)
              ->select('employees.*'); // avoid column conflict
    } else {
        $query->orderBy($sort, $order);
    }

    $employees = $query->get(); // ← NO paginate() → returns ALL

    return response()->json([
        'message' => 'All employees fetched successfully',
        'total'   => $employees->count(),
        'data'    => $employees
    ]);
}
    // CREATE
    /**
     * Create Employee
     * 
     * Create a new employee with personal and professional info.
     * 
     * @group Employees
     * @bodyParam first_name string required First name.
     * @bodyParam last_name string required Last name.
     * @bodyParam email string required Email address.
     * @bodyParam phone string Phone number.
     * @bodyParam photo file Profile photo (image).
     * @bodyParam date_of_birth date required Date of birth (YYYY-MM-DD).
     * @bodyParam gender string required Gender (male/female).
     * @bodyParam department_id uuid required Department ID.
     * @bodyParam designation_id uuid required Designation ID.
     * @bodyParam joining_date date required Joining date.
     * @bodyParam basic_salary number required Basic salary.
     * @response 201 {
     *  "message": "Employee created successfully",
     *  "data": { ... }
     * }
     */
    public function store(EmployeeStoreRequest $request)
{
    \DB::beginTransaction();
    try {
        $employee = Employee::create();

        // PHOTO HANDLING — CONVERT TO WEBP & STORE CORRECTLY
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('photo'));

            // Optional: resize if too big (recommended)
            $image->scale(width: 800); // keeps aspect ratio

            $encoded = $image->toWebp(90);

            // Generate unique filename
            $filename = \Str::random(40) . '.webp';
            $path = 'employees/' . $filename;

            // Save to storage/app/public/employees/xyz.webp
            Storage::disk('public')->put($path, (string) $encoded);

            $photoPath = $path; // ← THIS IS WHAT GOES TO DB
        }

        // Get validated data
        $validated = $request->validated();

        // Personal Info
        $personalInfoData = [
            'first_name'      => $validated['first_name'],
            'last_name'       => $validated['last_name'],
            'email'           => $validated['email'],
            'phone'           => $validated['phone'] ?? null,
            'photo'           => $photoPath, 
            'date_of_birth'   => $validated['date_of_birth'],
            'gender'          => $validated['gender'],
            'marital_status'  => $validated['marital_status'] ?? null,
            'nationality'     => $validated['nationality'] ?? null,
            'address'         => $validated['address'] ?? null,
            'city'            => $validated['city'] ?? null,
            'state'           => $validated['state'] ?? null,
            'zip_code'        => $validated['zip_code'] ?? null,
        ];

        // Professional Info
        $professionalInfoData = [
            'department_id'        => $validated['department_id'],
            'designation_id'       => $validated['designation_id'],
            'joining_date'         => $validated['joining_date'],
            'ending_date'          => $validated['ending_date'] ?? null,
            'employment_type'      => $validated['employment_type'],
            'basic_salary'         => $validated['basic_salary'],
            'salary_currency'      => $validated['salary_currency'] ?? 'USD',
            'bank_name'            => $validated['bank_name'] ?? null,
            'bank_account_number'  => $validated['bank_account_number'] ?? null,
            'tax_id'               => $validated['tax_id'] ?? null,
        ];

        $employee->personalInfo()->create($personalInfoData);
        $employee->professionalInfo()->create($professionalInfoData);

        \DB::commit();

        return response()->json([
            'message' => 'Employee created successfully',
            'data'    => $employee->load([
                'personalInfo',
                'professionalInfo.department',
                'professionalInfo.designation'
            ])
        ], 201);

    } catch (\Exception $e) {
        \DB::rollBack();
        return response()->json([
            'message' => 'Failed to create employee',
            'error'   => $e->getMessage()
        ], 422);
    }
}

    // SHOW
    /**
     * Get Employee
     * 
     * Get employee details by ID.
     * 
     * @group Employees
     * @response 200 {
     *  "message": "Employee retrieved successfully",
     *  "data": { ... }
     * }
     */

public function show($id)
{
    $employee = Employee::with([
        'personalInfo',
        'professionalInfo.department',
        'professionalInfo.designation',
        'shift',
        'socialLinks'
    ])->findOrFail($id);

    return response()->json([
        'message' => 'Employee retrieved successfully',
        'data' => $employee
    ]);
}

    // UPDATE
    /**
     * Update Employee
     * 
     * Update employee details.
     * 
     * @group Employees
     * @bodyParam first_name string First name.
     * @bodyParam email string Email address.
     * @response 200 {
     *  "message": "Employee updated successfully",
     *  "data": { ... }
     * }
     */
    public function update(EmployeeUpdateRequest $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $validated = $request->validated();

        // Separate data
        $personalInfoData = array_intersect_key($validated, array_flip([
            'first_name', 'last_name', 'email', 'phone', 'photo', 'date_of_birth', 
            'gender', 'marital_status', 'nationality', 'address', 'city', 'state', 'zip_code'
        ]));

        $professionalInfoData = array_intersect_key($validated, array_flip([
            'department_id', 'designation_id', 'joining_date', 'ending_date', 
            'employment_type', 'basic_salary', 'salary_currency', 'bank_name', 
            'bank_account_number', 'tax_id'
        ]));

        if (!empty($personalInfoData)) {
            $employee->personalInfo()->update($personalInfoData);
        }

        if (!empty($professionalInfoData)) {
            $employee->professionalInfo()?->update($professionalInfoData);
        }

        // Fix: Update main employee table (shift_id, status, employee_code)
        $mainEmployeeData = array_intersect_key($validated, array_flip(['shift_id', 'status', 'employee_code']));
        if (!empty($mainEmployeeData)) {
            $employee->update($mainEmployeeData);
        }

        // Log activity for employee update with detailed information
        $changedFields = [];
        if (!empty($personalInfoData)) {
            $changedFields['personal_info'] = array_keys($personalInfoData);
        }
        if (!empty($professionalInfoData)) {
            $changedFields['professional_info'] = array_keys($professionalInfoData);
        }
        if (!empty($mainEmployeeData)) {
            $changedFields['main_data'] = array_keys($mainEmployeeData);
        }

        if (!empty($changedFields)) {
            activity('employee')
                ->performedOn($employee)
                ->causedBy(auth()->user())
                ->withProperties([
                    'employee_code' => $employee->employee_code,
                    'employee_name' => $employee->personalInfo->first_name . ' ' . $employee->personalInfo->last_name,
                    'changed_fields' => $changedFields,
                    'personal_info_updates' => $personalInfoData ?? [],
                    'professional_info_updates' => $professionalInfoData ?? [],
                    'main_data_updates' => $mainEmployeeData ?? [],
                ])
                ->log('Employee profile updated');
        }

        // Send email notification to employee about profile update with specific details
        $notificationMessage = "Your employee profile has been updated.";
        
        if (!empty($personalInfoData) && !empty($professionalInfoData)) {
            $notificationMessage = "Your personal and professional information has been updated.";
        } elseif (!empty($personalInfoData)) {
            $notificationMessage = "Your personal information has been updated.";
        } elseif (!empty($professionalInfoData)) {
            $notificationMessage = "Your professional information has been updated.";
        } elseif (!empty($mainEmployeeData)) {
            if (isset($mainEmployeeData['status'])) {
                $notificationMessage = "Your employment status has been changed to {$mainEmployeeData['status']}.";
            } elseif (isset($mainEmployeeData['shift_id'])) {
                $notificationMessage = "Your work shift has been updated.";
            }
        }
        
        $employee->notify(new SystemNotification(
            'Profile Updated',
            $notificationMessage,
            'info',
            'Employee',
            $employee->id
        ));

        return response()->json([
            'message' => 'Employee updated successfully',
            'data' => $employee->fresh()->load([
                'personalInfo',
                'professionalInfo.department',
                'professionalInfo.designation',
                'shift',
                'socialLinks'
            ])
        ]);
    }

    // UPLOAD PHOTO ONLY
    /**
     * Upload Photo
     * 
     * Upload or update employee profile photo.
     * 
     * @group Employees
     * @bodyParam photo file required Image file (jpeg, png, jpg, max 5MB).
     * @response 200 {
     *  "message": "Photo uploaded successfully",
     *  "photo_url": "http://..."
     * }
     */
    public function uploadPhoto(Request $request, $id)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:5120'
        ]);

        $employee = Employee::findOrFail($id);

        // Delete old photo
        if ($employee->personalInfo?->photo) {
            Storage::disk('public')->delete($employee->personalInfo->photo);
        }

        // Convert to WebP
        $manager = new ImageManager(new Driver());
        $image = $manager->read($request->file('photo'));
        $encoded = $image->toWebp(90);
        $filename = pathinfo($request->file('photo')->hashName(), PATHINFO_FILENAME) . '.webp';
        Storage::disk('public')->put('employees/' . $filename, (string) $encoded);
        $path = 'employees/' . $filename;

        $employee->personalInfo()->update(['photo' => $path]);

        return response()->json([
            'message' => 'Photo uploaded successfully',
            'photo_url' => asset('storage/' . $path)
        ]);
    }

    // DELETE PHOTO
    /**
     * Delete Photo
     * 
     * Remove employee profile photo.
     * 
     * @group Employees
     * @response 200 {
     *  "message": "Photo removed"
     * }
     */
    public function deletePhoto($id)
    {
        $employee = Employee::findOrFail($id);

        if ($employee->personalInfo?->photo) {
            Storage::disk('public')->delete($employee->personalInfo->photo);
            $employee->personalInfo()->update(['photo' => null]);
        }

        return response()->json(['message' => 'Photo removed']);
    }

    // DESTROY
    /**
     * Delete Employee
     * 
     * Delete an employee record.
     * 
     * @group Employees
     * @response 200 {
     *  "message": "Employee deleted successfully"
     * }
     */
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);

        if ($employee->personalInfo?->photo) {
            Storage::disk('public')->delete($employee->personalInfo->photo);
        }

        $employee->delete();

        return response()->json([
            'message' => 'Employee deleted successfully'
        ]);
    }

    // TOGGLE STATUS
    /**
     * Toggle Status
     * 
     * Toggle employee status (active/inactive).
     * 
     * @group Employees
     * @response 200 {
     *  "message": "Status updated",
     *  "status": "inactive"
     * }
     */
    public function toggleStatus($id)
    {
        $employee = Employee::findOrFail($id);
        $oldStatus = $employee->status;
        $employee->status = $employee->status === 'active' ? 'inactive' : 'active';
        $employee->save();

        // Send email notification to employee about status change
        $statusMessage = $employee->status === 'active' 
            ? 'Your employee account has been activated. You can now access all system features.' 
            : 'Your employee account has been deactivated. Please contact HR if you have any questions.';
        
        $employee->notify(new SystemNotification(
            'Account Status Changed',
            $statusMessage,
            $employee->status === 'active' ? 'success' : 'warning',
            'Employee',
            $employee->id
        ));

        return response()->json([
            'message' => 'Status updated',
            'status' => $employee->status
        ]);
    }

    // DOCUMENT MANAGEMENT
    /**
     * Upload Document
     * 
     * Upload or replace employee document (PDF only, max 2MB).
     * 
     * @group Employees
     * @bodyParam document file required PDF file (max 2MB).
     */
    public function uploadDocument(Request $request, $id)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf|max:2048'
        ]);

        $employee = Employee::findOrFail($id);

        // Delete old document if exists
        if ($employee->professionalInfo?->document_path) {
            Storage::disk('public')->delete($employee->professionalInfo->document_path);
        }

        // Store new document
        $file = $request->file('document');
        $filename = $employee->id . '.pdf';
        $path = $file->storeAs('employees/documents', $filename, 'public');

        // Update professional info
        $employee->professionalInfo()->update([
            'document_path' => $path,
            'document_uploaded_at' => now()
        ]);

        return response()->json([
            'message' => 'Document uploaded successfully',
            'document_url' => asset('storage/' . $path),
            'uploaded_at' => now()
        ]);
    }

    /**
     * Download Document
     * 
     * Download employee document.
     * 
     * @group Employees
     */
    public function downloadDocument($id)
    {
        $employee = Employee::findOrFail($id);

        if (!$employee->professionalInfo?->document_path) {
            return response()->json(['message' => 'No document found'], 404);
        }

        $path = storage_path('app/public/' . $employee->professionalInfo->document_path);

        if (!file_exists($path)) {
            return response()->json(['message' => 'Document file not found'], 404);
        }

        return response()->download($path, $employee->employee_code . '_document.pdf');
    }

    /**
     * Delete Document
     * 
     * Delete employee document.
     * 
     * @group Employees
     */
    public function deleteDocument($id)
    {
        $employee = Employee::findOrFail($id);

        if (!$employee->professionalInfo?->document_path) {
            return response()->json(['message' => 'No document to delete'], 404);
        }

        // Delete file
        Storage::disk('public')->delete($employee->professionalInfo->document_path);

        // Update database
        $employee->professionalInfo()->update([
            'document_path' => null,
            'document_uploaded_at' => null
        ]);

        return response()->json(['message' => 'Document deleted successfully']);
    }
}