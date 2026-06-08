<?php

namespace App\Services;

use App\Repositories\CourseRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class CourseService
{
    protected CourseRepositoryInterface $courseRepository;

    public function __construct(CourseRepositoryInterface $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function getAllCourses()
    {
        if (!Cache::has('courses_cache_version')) {
            Cache::put('courses_cache_version', 1, 86400);
        }

        $version = Cache::get('courses_cache_version', 1);
        $cacheKey = "courses_list_v{$version}";

        return Cache::remember($cacheKey, 3600, function () {
            return $this->courseRepository->all();
        });
    }

    public function getCourseById(int $id)
    {
        return Cache::remember("course_{$id}", 3600, function () use ($id) {
            return $this->courseRepository->find($id);
        });
    }

    public function createCourse(array $data)
    {
        $course = $this->courseRepository->create($data);
        $this->clearCache();
        return $course;
    }

    public function updateCourse(int $id, array $data)
    {
        $course = $this->courseRepository->update($id, $data);
        $this->clearCache($id);
        return $course;
    }

    public function deleteCourse(int $id)
    {
        $result = $this->courseRepository->delete($id);
        $this->clearCache($id);
        return $result;
    }

    protected function clearCache(?int $id = null): void
    {
        Cache::increment('courses_cache_version');

        if ($id) {
            Cache::forget("course_{$id}");
        }
    }
}
