<?php

namespace App\Services;

use App\Repositories\EnrollmentRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class EnrollmentService
{
    protected EnrollmentRepositoryInterface $enrollmentRepository;

    public function __construct(EnrollmentRepositoryInterface $enrollmentRepository)
    {
        $this->enrollmentRepository = $enrollmentRepository;
    }

    public function getAllEnrollments()
    {
        if (!Cache::has('enrollments_cache_version')) {
            Cache::put('enrollments_cache_version', 1, 86400);
        }

        $version = Cache::get('enrollments_cache_version', 1);
        $cacheKey = "enrollments_list_v{$version}";

        return Cache::remember($cacheKey, 3600, function () {
            return $this->enrollmentRepository->all();
        });
    }

    public function enrollStudent(array $data)
    {
        $duplicate = $this->enrollmentRepository->findDuplicate(
            (int) $data['student_id'],
            (int) $data['course_id']
        );

        if ($duplicate) {
            throw ValidationException::withMessages([
                'student_id' => ['This student is already enrolled in this course.'],
            ]);
        }

        $enrollment = $this->enrollmentRepository->create($data);
        $this->clearCache();
        return $enrollment;
    }

    public function removeEnrollment(int $id)
    {
        $result = $this->enrollmentRepository->delete($id);
        $this->clearCache();
        return $result;
    }

    protected function clearCache(): void
    {
        Cache::increment('enrollments_cache_version');
    }
}
