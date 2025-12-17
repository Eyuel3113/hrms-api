<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\DesignationController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\HolidayController;
use App\Http\Controllers\Api\ShiftController;
use App\Http\Controllers\Api\LeaveController;
use App\Http\Controllers\Api\LeaveTypeController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\CandidateController;
use App\Http\Controllers\Api\TrainingController;
use App\Http\Controllers\Api\ProjectController;


Route::prefix('v1')->group(function () {

    // PUBLIC ROUTES — NO AUTH NEEDED
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/auth/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('/auth/refresh', [AuthController::class, 'refresh']);
    // AUTHENTICATED ROUTES
    Route::middleware('auth:sanctum')->group(function () {

        // AUTH ENDPOINTS
        Route::prefix('auth')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/me', [AuthController::class, 'me']);

        });

        // DEPARTMENTS
        Route::prefix('departments')->group(function () {
            Route::get('/', [DepartmentController::class, 'index']);
            Route::post('/', [DepartmentController::class, 'store']);
            Route::get('/all', [DepartmentController::class, 'all']);
            Route::get('/{id}', [DepartmentController::class, 'show']);
            Route::patch('/{id}', [DepartmentController::class, 'update']);
            Route::patch('/{id}/status', [DepartmentController::class, 'toggleStatus']);
            
        });

        // DESIGNATIONS
        Route::prefix('designations')->group(function () {
            Route::get('/', [DesignationController::class, 'index']);
            Route::post('/', [DesignationController::class, 'store']);
            Route::get('/all', [DesignationController::class, 'all']);
            Route::get('/{id}', [DesignationController::class, 'show']);
            Route::patch('/{id}', [DesignationController::class, 'update']);
            Route::delete('/{id}', [DesignationController::class, 'destroy']);
            Route::patch('/{id}/status', [DesignationController::class, 'toggleStatus']);
        });

        // EMPLOYEES
        Route::prefix('employees')->group(function () {
            Route::get('/', [EmployeeController::class, 'index']);
            Route::post('/', [EmployeeController::class, 'store']);
            Route::get('/all', [EmployeeController::class, 'all']);
            Route::get('/{id}', [EmployeeController::class, 'show']);
            Route::patch('/{id}', [EmployeeController::class, 'update']);
            Route::delete('/{id}', [EmployeeController::class, 'destroy']);
            Route::post('/{id}/photo', [EmployeeController::class, 'uploadPhoto']);
            Route::delete('/{id}/photo', [EmployeeController::class, 'deletePhoto']);
            Route::patch('/{id}/status', [EmployeeController::class, 'toggleStatus']);
        });
    

         //ATTENDANCE
    
    Route::prefix('attendance')->name('attendance.')->group(function () {
         Route::get('/today', [AttendanceController::class, 'today'])->name('today');
         Route::post('/check-in', [AttendanceController::class, 'checkIn'])->name('checkin');
         Route::post('/check-out', [AttendanceController::class, 'checkOut'])->name('checkout');
    });
          // HOLIDAYS
    Route::prefix('holidays')->group(function () {
    Route::get('/', [HolidayController::class, 'index']);
    Route::get('/active', [HolidayController::class, 'active']);
    Route::post('/', [HolidayController::class, 'store']);
    Route::patch('/{id}', [HolidayController::class, 'update']);
    Route::patch('/{id}/status', [HolidayController::class, 'toggleStatus']);
    Route::delete('/{id}', [HolidayController::class, 'destroy']); 
    });
    
    // SHIFTS
    Route::prefix('shifts')->group(function () {
    Route::get('/', [ShiftController::class, 'index']);           
    Route::get('/active', [ShiftController::class, 'active']);          
    Route::post('/', [ShiftController::class, 'store']);
    Route::patch('/{id}', [ShiftController::class, 'update']);
    Route::patch('/{id}/status', [ShiftController::class, 'toggleStatus']);
    Route::delete('/{id}', [ShiftController::class, 'destroy']);
});

//LEAVES
Route::prefix('leaves')->group(function () {
    Route::get('/', [LeaveController::class, 'index']);
    Route::get('/{id}', [LeaveController::class, 'show']); // Added
    Route::post('/', [LeaveController::class, 'store']);
    Route::patch('/{id}/approve', [LeaveController::class, 'approve']);
    Route::patch('/{id}/reject', [LeaveController::class, 'reject']);
});

// LEAVE TYPES
Route::prefix('leave-types')->group(function () {
    Route::get('/', [LeaveTypeController::class, 'index']);
    Route::get('/active', [LeaveTypeController::class, 'active']);
    Route::get('/{id}', [LeaveTypeController::class, 'show']); // Added
    Route::post('/', [LeaveTypeController::class, 'store']);
    Route::patch('/{id}', [LeaveTypeController::class, 'update']);
    Route::patch('/{id}/status', [LeaveTypeController::class, 'toggleStatus']);
    Route::delete('/{id}', [LeaveTypeController::class, 'destroy']);
});

// RECRUITMENT

Route::prefix('recruitment')->group(function () {
    Route::get('/jobs', [JobController::class, 'index']);
    Route::get('/jobs/active', [JobController::class, 'active']);
    Route::get('/jobs/inactive', [JobController::class, 'inactive']);
    Route::post('/jobs', [JobController::class, 'store']);
    Route::get('/jobs/{id}', [JobController::class, 'show']);
    Route::patch('/jobs/{id}', [JobController::class, 'update']);
    Route::patch('/jobs/{id}/status', [JobController::class, 'toggleStatus']);
    Route::delete('/jobs/{id}', [JobController::class, 'destroy']);

    Route::post('/candidates', [CandidateController::class, 'store']);
    Route::get('/candidates', [CandidateController::class, 'index']);
    Route::get('/candidates/{id}', [CandidateController::class, 'show']);
    Route::patch('/candidates/{id}/status', [CandidateController::class, 'updateStatus']);
    Route::post('/candidates/{id}/hire', [CandidateController::class, 'hire']); 
});

// TRAININGS

Route::prefix('trainings')->group(function () {
    Route::get('/', [TrainingController::class, 'index']);
    Route::post('/', [TrainingController::class, 'store']);
    Route::get('/active', [TrainingController::class, 'active']);
    Route::get('/inactive', [TrainingController::class, 'inactive']);
    Route::get('/employee/{employeeId}', [TrainingController::class, 'employeeTrainingHistory']);
    Route::get('/{id}/attendees', [TrainingController::class, 'attendees']);
    Route::get('/{id}', [TrainingController::class, 'show']);
    Route::patch('/{id}', [TrainingController::class, 'update']);
    Route::patch('/{id}/status', [TrainingController::class, 'toggleStatus']);
    Route::post('/{id}/assign', [TrainingController::class, 'assignEmployees']);
    Route::post('/{id}/assign-all', [TrainingController::class, 'assignAllEmployees']);
    Route::patch('/{trainingId}/attend/{employeeId}', [TrainingController::class, 'markAttendance']);

});

// PROJECTS

Route::prefix('projects')->group(function () {
    Route::get('/', [ProjectController::class, 'index']);
    Route::get('/active', [ProjectController::class, 'active']);
    Route::get('/inactive', [ProjectController::class, 'inactive']);
    Route::get('/{id}', [ProjectController::class, 'show']);
    Route::post('/', [ProjectController::class, 'store']);
    Route::patch('/{id}', [ProjectController::class, 'update']);
    Route::get('/{id}/members', [ProjectController::class, 'members']);
    Route::patch('/{id}/status', [ProjectController::class, 'toggleStatus']);
    Route::post('/{id}/assign', [ProjectController::class, 'assignEmployees']);
    Route::post('/{id}/assign-all', [TrainingController::class, 'assignAllEmployees']);
    Route::patch('/{projectId}/rate/{employeeId}', [ProjectController::class, 'rateEmployee']);
    Route::get('/employee/{employeeId}/performance', [ProjectController::class, 'employeePerformance']);
    Route::get('/employees/{employeeId}', [ProjectController::class, 'employeeProjectHistory']);

});

    
});

});