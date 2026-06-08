<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminStudentController;
use App\Http\Controllers\Admin\AdminCourseController;
use App\Http\Controllers\Admin\AdminEnrollmentController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminRolePermissionController;

Route::redirect('/', 'http://3.26.196.155/admin/dashboard');

Route::get('/api/documentation', function () {
    return view('swagger');
});

// Admin Authentication Routes
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Protected Admin Panel Routes
Route::middleware(['auth:web'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Students CRUD
    Route::get('/students', [AdminStudentController::class, 'index'])->name('admin.students.index');
    Route::get('/students/create', [AdminStudentController::class, 'create'])->name('admin.students.create')->middleware('permission:manage-students|students.*');
    Route::post('/students', [AdminStudentController::class, 'store'])->name('admin.students.store')->middleware('permission:manage-students|students.*');
    Route::get('/students/{student}', [AdminStudentController::class, 'show'])->name('admin.students.show');
    Route::get('/students/{student}/edit', [AdminStudentController::class, 'edit'])->name('admin.students.edit')->middleware('permission:manage-students|students.*');
    Route::put('/students/{student}', [AdminStudentController::class, 'update'])->name('admin.students.update')->middleware('permission:manage-students|students.*');
    Route::delete('/students/{student}', [AdminStudentController::class, 'destroy'])->name('admin.students.destroy')->middleware('permission:manage-students|students.*');

    // Courses CRUD
    Route::get('/courses', [AdminCourseController::class, 'index'])->name('admin.courses.index');
    Route::get('/courses/create', [AdminCourseController::class, 'create'])->name('admin.courses.create')->middleware('permission:manage-courses|courses.*');
    Route::post('/courses', [AdminCourseController::class, 'store'])->name('admin.courses.store')->middleware('permission:manage-courses|courses.*');
    Route::get('/courses/{course}', [AdminCourseController::class, 'show'])->name('admin.courses.show');
    Route::get('/courses/{course}/edit', [AdminCourseController::class, 'edit'])->name('admin.courses.edit')->middleware('permission:manage-courses|courses.*');
    Route::put('/courses/{course}', [AdminCourseController::class, 'update'])->name('admin.courses.update')->middleware('permission:manage-courses|courses.*');
    Route::delete('/courses/{course}', [AdminCourseController::class, 'destroy'])->name('admin.courses.destroy')->middleware('permission:manage-courses|courses.*');

    // Enrollments CRUD
    Route::get('/enrollments', [AdminEnrollmentController::class, 'index'])->name('admin.enrollments.index');
    Route::get('/enrollments/create', [AdminEnrollmentController::class, 'create'])->name('admin.enrollments.create')->middleware('permission:manage-enrollments|enrollments.*');
    Route::post('/enrollments', [AdminEnrollmentController::class, 'store'])->name('admin.enrollments.store')->middleware('permission:manage-enrollments|enrollments.*');
    Route::delete('/enrollments/{enrollment}', [AdminEnrollmentController::class, 'destroy'])->name('admin.enrollments.destroy')->middleware('permission:manage-enrollments|enrollments.*');

    // Admins CRUD
    Route::get('/admins', [AdminUserController::class, 'index'])->name('admin.admins.index')->middleware('permission:admins.*');
    Route::get('/admins/create', [AdminUserController::class, 'create'])->name('admin.admins.create')->middleware('permission:admins.*');
    Route::post('/admins', [AdminUserController::class, 'store'])->name('admin.admins.store')->middleware('permission:admins.*');
    Route::get('/admins/{admin}/edit', [AdminUserController::class, 'edit'])->name('admin.admins.edit')->middleware('permission:admins.*');
    Route::put('/admins/{admin}', [AdminUserController::class, 'update'])->name('admin.admins.update')->middleware('permission:admins.*');
    Route::delete('/admins/{admin}', [AdminUserController::class, 'destroy'])->name('admin.admins.destroy')->middleware('permission:admins.*');

    // Roles & Permissions Listing
    Route::get('/roles', [AdminRolePermissionController::class, 'roles'])->name('admin.roles.index');
    Route::get('/permissions', [AdminRolePermissionController::class, 'permissions'])->name('admin.permissions.index');
});

