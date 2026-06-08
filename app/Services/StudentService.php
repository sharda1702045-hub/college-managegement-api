<?php

namespace App\Services;

use App\Repositories\StudentRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class StudentService
{
    protected StudentRepositoryInterface $studentRepository;

    public function __construct(StudentRepositoryInterface $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    public function getAllStudents()
    {
        return $this->studentRepository->all();
    }

    public function searchAndPaginateStudents(?string $search, int $limit, int $page)
    {
        // Get cache version or initialize it
        if (!Cache::has('students_cache_version')) {
            Cache::put('students_cache_version', 1, 86400);
        }
        
        $version = Cache::get('students_cache_version', 1);
        $cacheKey = "students_v{$version}_page_{$page}_limit_{$limit}_search_" . ($search ?? 'all');

        return Cache::remember($cacheKey, 3600, function () use ($search, $limit, $page) {
            return $this->studentRepository->searchAndPaginate($search, $limit, $page);
        });
    }

    public function getStudentById(int $id)
    {
        return Cache::remember("student_{$id}", 3600, function () use ($id) {
            return $this->studentRepository->find($id);
        });
    }

    public function createStudent(array $data)
    {
        $student = $this->studentRepository->create($data);
        $this->clearCache();
        return $student;
    }

    public function updateStudent(int $id, array $data)
    {
        $student = $this->studentRepository->update($id, $data);
        $this->clearCache($id);
        return $student;
    }

    public function deleteStudent(int $id)
    {
        $result = $this->studentRepository->delete($id);
        $this->clearCache($id);
        return $result;
    }

    protected function clearCache(?int $id = null): void
    {
        // Increment version to invalidate list queries
        Cache::increment('students_cache_version');

        if ($id) {
            Cache::forget("student_{$id}");
        }
    }
}
