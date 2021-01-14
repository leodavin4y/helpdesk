<?php

namespace App\Repositories;

use App\Models\RequestHistory;
use App\Repositories\Interfaces\RequestHistoryRepositoryInterface;

class RequestHistoryRepository implements RequestHistoryRepositoryInterface
{
    public function store(array $params): bool
    {
        return (new RequestHistory())->fill($params)->save();
    }
}