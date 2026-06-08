<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $totalStudents = Student::count();
        $totalCourses = Course::count();
        $totalEnrollments = Enrollment::count();

        $latestStudents = Student::latest()->take(5)->get();
        $latestEnrollments = Enrollment::with(['student', 'course'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalCourses',
            'totalEnrollments',
            'latestStudents',
            'latestEnrollments'
        ));
    }
}
