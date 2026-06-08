<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EnrollmentStoreRequest;
use App\Services\EnrollmentService;
use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AdminEnrollmentController extends Controller
{
    protected EnrollmentService $enrollmentService;

    public function __construct(EnrollmentService $enrollmentService)
    {
        $this->enrollmentService = $enrollmentService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $enrollments = $this->enrollmentService->getAllEnrollments();

        return view('admin.enrollments.index', compact('enrollments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $selectedStudentId = $request->query('student_id');
        $selectedCourseId = $request->query('course_id');

        $students = Student::orderBy('first_name')->orderBy('last_name')->get();
        $courses = Course::orderBy('course_name')->get();

        return view('admin.enrollments.create', compact('students', 'courses', 'selectedStudentId', 'selectedCourseId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EnrollmentStoreRequest $request)
    {
        try {
            $this->enrollmentService->enrollStudent($request->validated());

            return redirect()->route('admin.enrollments.index')
                ->with('success', 'Student enrolled successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $this->enrollmentService->removeEnrollment($id);

        return redirect()->back()
            ->with('success', 'Enrollment removed successfully.');
    }
}
