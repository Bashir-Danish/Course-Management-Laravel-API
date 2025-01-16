<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return redirect()->route('login');
})->middleware('guest:admin');

require __DIR__.'/auth.php';

Route::middleware(['auth:admin'])->group(function () {
    // Routes accessible by both roles
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('teachers', TeacherController::class);
    Route::resource('students', StudentController::class);
    Route::get('students/manage', [StudentController::class, 'manage'])->name('students.manage');
    Route::resource('courses', CourseController::class);
    Route::get('/courses/list', [CourseController::class, 'list'])->name('courses.list');
    Route::resource('departments', DepartmentController::class);
    Route::resource('registrations', RegistrationController::class);
    Route::get('/students/{student}/registrations', [RegistrationController::class, 'studentRegistrations'])->name('registrations.student');
    Route::resource('registrations', RegistrationController::class)->only(['store', 'update', 'destroy']);
    Route::resource('branches', BranchController::class);
    
    // Reports routes accessible by both roles
    Route::get('/reports/weekly', [ReportController::class, 'weekly'])->name('reports.weekly');
    Route::get('/reports/monthly', [ReportController::class, 'monthly'])->name('reports.monthly');
    Route::get('/reports/yearly', [ReportController::class, 'yearly'])->name('reports.yearly');
    Route::get('/reports/students', [ReportsController::class, 'students'])->name('reports.students');
    Route::get('/reports/teachers', [ReportsController::class, 'teachers'])->name('reports.teachers');
    Route::get('/reports/courses', [ReportsController::class, 'courses'])->name('reports.courses');
    Route::get('/reports/departments', [ReportsController::class, 'departments'])->name('reports.departments');

    // Routes only accessible by super_admin
    Route::middleware([\App\Http\Middleware\CheckAdminRole::class.':super_admin'])->group(function () {
        Route::get('/backup', [BackupController::class, 'index'])->name('backup');
        Route::post('/backup/create', [BackupController::class, 'create'])->name('backup.create');
        Route::get('/api/profile', [ProfileController::class, 'getProfile'])->name('profile.get');
        Route::post('/api/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    });
});