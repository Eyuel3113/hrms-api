<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\DesignationController;
use App\Http\Controllers\Api\EmployeeController;


Route::prefix('v1')->name('auth.')->group(function () {

    Route::post('/auth/login', [AuthController::class, 'login'])->name('login');
    Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
    Route::post('/auth/reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');

    // PROTECTED ROUTES – require auth:sanctum + HttpOnly cookie
    Route::middleware('auth:sanctum')->group(function () {

        // Auth
        Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::get('/auth/me', [AuthController::class, 'me'])->name('auth.me');
        Route::get('/auth/refresh', function (Request $request) {
            $user = $request->user();
            $user->tokens()->delete(); // kill all old tokens

            $newToken = $user->createToken('web-login', ['*'], now()->addHours(2))->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Token refreshed',
                'user' => $user->only(['id', 'name', 'email']),
            ])->cookie(
                'token',
                $newToken,
                60 * 24 * 365,
                '/',
                null,
                env('APP_ENV') === 'production',
                true,
                false,
                'lax'
            );
        })->name('auth.refresh');   
    });

});
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {

    // ── DEPARTMENTS 
    Route::prefix('departments')->name('departments.')->group(function () {
        Route::get('/', [DepartmentController::class, 'index'])->name('index');
        Route::post('/', [DepartmentController::class, 'store'])->name('store');
        Route::get('/{id}', [DepartmentController::class, 'show'])->name('show');
        Route::patch('/{id}', [DepartmentController::class, 'update'])->name('update');
        Route::patch('/{id}/status', [DepartmentController::class, 'toggleStatus'])->name('toggle-status');
    });

    // ── DESIGNATIONS 
    Route::prefix('designations')->name('designations.')->group(function () {
        Route::get('/', [DesignationController::class, 'index'])->name('index');
        Route::post('/', [DesignationController::class, 'store'])->name('store');
        Route::get('/{id}', [DesignationController::class, 'show'])->name('show');
        Route::patch('/{id}', [DesignationController::class, 'update'])->name('update');
        Route::delete('/{id}', [DesignationController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/status', [DesignationController::class, 'toggleStatus'])->name('toggle-status');
    });

    // ── EMPLOYEES 
    Route::prefix('employees')->name('employees.')->group(function () {
        Route::get('/', [EmployeeController::class, 'index'])->name('index');
        Route::post('/', [EmployeeController::class, 'store'])->name('store');
        Route::get('/{id}', [EmployeeController::class, 'show'])->name('show');
        Route::patch('/{id}', [EmployeeController::class, 'update'])->name('update');
        Route::delete('/{id}', [EmployeeController::class, 'destroy'])->name('destroy');

        // Photo routes
        Route::post('/{id}/photo', [EmployeeController::class, 'uploadPhoto'])->name('upload-photo');
        Route::delete('/{id}/photo', [EmployeeController::class, 'deletePhoto'])->name('delete-photo');

        // Status toggle
        Route::patch('/{id}/status', [EmployeeController::class, 'toggleStatus'])->name('toggle-status');
    });
});