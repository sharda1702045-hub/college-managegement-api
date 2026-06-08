<?php

namespace App\Repositories;

interface StudentRepositoryInterface extends BaseRepositoryInterface
{
    public function searchAndPaginate(?string $search, int $limit, int $page);
}
