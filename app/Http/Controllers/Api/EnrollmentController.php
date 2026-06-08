<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EnrollmentStoreRequest;
use App\Services\EnrollmentService;
use Illuminate\Http\JsonResponse;

class EnrollmentController extends Controller
{
    protected EnrollmentService $enrollmentService;

    public function __construct(EnrollmentService $enrollmentService)
    {
        $this->enrollmentService = $enrollmentService;
    }

    public function index(): JsonResponse
    {
        $enrollments = $this->enrollmentService->getAllEnrollments();

        return response()->json([
            'success' => true,
            'message' => 'Enrollments retrieved successfully',
            'data' => $enrollments,
        ]);
    }

    public function store(EnrollmentStoreRequest $request): JsonResponse
    {
        $enrollment = $this->enrollmentService->enrollStudent($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Student enrolled successfully',
            'data' => $enrollment,
        ], 201);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->enrollmentService->removeEnrollment($id);

        return response()->json([
            'success' => true,
            'message' => 'Enrollment removed successfully',
            'data' => new \stdClass(),
        ]);
    }
}
