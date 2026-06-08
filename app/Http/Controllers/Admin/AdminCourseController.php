<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseStoreRequest;
use App\Services\CourseService;
use Illuminate\Http\Request;

class AdminCourseController extends Controller
{
    protected CourseService $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $page = (int) $request->query('page', 1);
        $limit = 10;

        $courses = $this->courseService->searchAndPaginateCourses($search, $limit, $page);

        return view('admin.courses.index', compact('courses', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.courses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CourseStoreRequest $request)
    {
        $this->courseService->createCourse($request->validated());

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $course = $this->courseService->getCourseById($id);
        
        // Load enrollments with student details
        $course->load('enrollments.student');

        return view('admin.courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $course = $this->courseService->getCourseById($id);
        return view('admin.courses.edit', compact('course'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CourseStoreRequest $request, int $id)
    {
        $this->courseService->updateCourse($id, $request->validated());

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $this->courseService->deleteCourse($id);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course deleted successfully.');
    }
}
