<?php

namespace App\Repositories;

use App\Models\Enrollment;

class EnrollmentRepository extends BaseRepository implements EnrollmentRepositoryInterface
{
    public function __construct(Enrollment $model)
    {
        parent::__construct($model);
    }

    public function findDuplicate(int $studentId, int $courseId)
    {
        return $this->model->where('student_id', $studentId)
                           ->where('course_id', $courseId)
                           ->first();
    }

    public function all()
    {
        return $this->model->with(['student', 'course'])->get();
    }
}
