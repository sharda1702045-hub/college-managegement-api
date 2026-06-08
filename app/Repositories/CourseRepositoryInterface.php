<?php

namespace App\Repositories;

interface CourseRepositoryInterface extends BaseRepositoryInterface
{
    public function searchAndPaginate(?string $search, int $limit, int $page);
}
