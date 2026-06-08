<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\EnrollmentController;
use Illuminate\Support\Facades\Route;

// Public authentication routes
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (Sanctum authentication & rate limiting)
Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Students CRUD (Spatie permission checking)
    Route::get('/students', [StudentController::class, 'index'])->middleware('permission:view-students');
    Route::post('/students', [StudentController::class, 'store'])->middleware('permission:manage-students');
    Route::get('/students/{student}', [StudentController::class, 'show'])->middleware('permission:view-students');
    Route::put('/students/{student}', [StudentController::class, 'update'])->middleware('permission:manage-students');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->middleware('permission:manage-students');

    // Courses CRUD (Spatie permission checking)
    Route::get('/courses', [CourseController::class, 'index'])->middleware('permission:view-courses');
    Route::post('/courses', [CourseController::class, 'store'])->middleware('permission:manage-courses');
    Route::get('/courses/{course}', [CourseController::class, 'show'])->middleware('permission:view-courses');
    Route::put('/courses/{course}', [CourseController::class, 'update'])->middleware('permission:manage-courses');
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->middleware('permission:manage-courses');

    // Enrollments CRUD (Spatie permission checking)
    Route::get('/enrollments', [EnrollmentController::class, 'index'])->middleware('permission:view-enrollments');
    Route::post('/enrollments', [EnrollmentController::class, 'store'])->middleware('permission:manage-enrollments');
    Route::delete('/enrollments/{enrollment}', [EnrollmentController::class, 'destroy'])->middleware('permission:manage-enrollments');
});
