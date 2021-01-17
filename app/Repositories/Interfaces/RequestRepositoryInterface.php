<?php

namespace App\Repositories\Interfaces;

interface RequestRepositoryInterface
{
    public function update(int $id, array $params): bool;

    public function updateWithHistory(int $id, array $params): void;

    public function reportCountByMonth(): array;

    public function reportCountByYear(): array;

    public function reportCountLastWeek(): array;
}