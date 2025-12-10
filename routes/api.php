<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\DesignationController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\HolidayController;

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
    
});

});