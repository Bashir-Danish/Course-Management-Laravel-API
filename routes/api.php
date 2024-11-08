<?php

use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// Public admin routes
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/register', [AdminAuthController::class, 'register']);

// Protected admin routes
Route::middleware(['auth:admin', 'api'])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout']);
        Route::get('me', [AdminAuthController::class, 'me']);
        Route::post('refresh', [AdminAuthController::class, 'refresh']);
    });

    // Branch routes
    Route::apiResource('branches', BranchController::class);

    // Department routes
    Route::apiResource('departments', DepartmentController::class);

    // Teacher routes
    Route::apiResource('teachers', TeacherController::class);

    // Course routes
    Route::apiResource('courses', CourseController::class);

    // Student routes
    Route::apiResource('students', StudentController::class);

    // Registration routes
    Route::post('registrations', [RegistrationController::class, 'store']);
    Route::get('students/{id}/registrations', [RegistrationController::class, 'getStudentRegistrations']);
    Route::put('registrations/{id}', [RegistrationController::class, 'update']);
    Route::delete('registrations/{id}', [RegistrationController::class, 'destroy']);

    // Report routes
    Route::post('reports/generate', [ReportController::class, 'generate']);
});
