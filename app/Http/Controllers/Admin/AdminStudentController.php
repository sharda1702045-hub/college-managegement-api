<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentStoreRequest;
use App\Services\StudentService;
use Illuminate\Http\Request;

class AdminStudentController extends Controller
{
    protected StudentService $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $page = (int) $request->query('page', 1);
        $limit = 10;

        $students = $this->studentService->searchAndPaginateStudents($search, $limit, $page);

        return view('admin.students.index', compact('students', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.students.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StudentStoreRequest $request)
    {
        $this->studentService->createStudent($request->validated());

        return redirect()->route('admin.students.index')
            ->with('success', 'Student registered successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $student = $this->studentService->getStudentById($id);
        
        // Load enrollments with course details
        $student->load('enrollments.course');

        return view('admin.students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $student = $this->studentService->getStudentById($id);
        return view('admin.students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StudentStoreRequest $request, int $id)
    {
        $this->studentService->updateStudent($id, $request->validated());

        return redirect()->route('admin.students.index')
            ->with('success', 'Student updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $this->studentService->deleteStudent($id);

        return redirect()->route('admin.students.index')
            ->with('success', 'Student deleted successfully.');
    }
}
