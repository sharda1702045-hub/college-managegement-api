<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseStoreRequest;
use App\Services\CourseService;
use Illuminate\Http\JsonResponse;

class CourseController extends Controller
{
    protected CourseService $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    public function index(): JsonResponse
    {
        $courses = $this->courseService->getAllCourses();

        return response()->json([
            'success' => true,
            'message' => 'Courses retrieved successfully',
            'data' => $courses,
        ]);
    }

    public function store(CourseStoreRequest $request): JsonResponse
    {
        $course = $this->courseService->createCourse($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Course created successfully',
            'data' => $course,
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $course = $this->courseService->getCourseById($id);

        return response()->json([
            'success' => true,
            'message' => 'Course retrieved successfully',
            'data' => $course,
        ]);
    }

    public function update(CourseStoreRequest $request, int $id): JsonResponse
    {
        $course = $this->courseService->updateCourse($id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Course updated successfully',
            'data' => $course,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->courseService->deleteCourse($id);

        return response()->json([
            'success' => true,
            'message' => 'Course deleted successfully',
            'data' => new \stdClass(),
        ]);
    }
}
