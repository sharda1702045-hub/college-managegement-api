<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentStoreRequest;
use App\Services\StudentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    protected StudentService $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    public function index(Request $request): JsonResponse
    {
        $search = $request->query('search');
        $limit = (int) $request->query('limit', 10);
        $page = (int) $request->query('page', 1);

        // Limit can be bounded to prevent huge loads
        if ($limit < 1) {
            $limit = 10;
        }
        if ($page < 1) {
            $page = 1;
        }

        $students = $this->studentService->searchAndPaginateStudents($search, $limit, $page);

        return response()->json([
            'success' => true,
            'message' => 'Students retrieved successfully',
            'data' => [
                'current_page' => $students->currentPage(),
                'total_records' => $students->total(),
                'total_pages' => $students->lastPage(),
                'data' => $students->items(),
            ],
        ]);
    }

    public function store(StudentStoreRequest $request): JsonResponse
    {
        $student = $this->studentService->createStudent($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Student created successfully',
            'data' => $student,
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $student = $this->studentService->getStudentById($id);

        return response()->json([
            'success' => true,
            'message' => 'Student retrieved successfully',
            'data' => $student,
        ]);
    }

    public function update(StudentStoreRequest $request, int $id): JsonResponse
    {
        $student = $this->studentService->updateStudent($id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Student updated successfully',
            'data' => $student,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->studentService->deleteStudent($id);

        return response()->json([
            'success' => true,
            'message' => 'Student deleted successfully',
            'data' => new \stdClass(),
        ]);
    }
}
