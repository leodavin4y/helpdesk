<?php

namespace App\Repositories\Interfaces;

interface RequestHistoryRepositoryInterface
{
    public function store(array $params): bool;
}