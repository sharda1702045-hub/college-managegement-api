<?php

namespace App\Repositories;

interface EnrollmentRepositoryInterface extends BaseRepositoryInterface
{
    public function findDuplicate(int $studentId, int $courseId);
}
