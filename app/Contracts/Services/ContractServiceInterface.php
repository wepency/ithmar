<?php

namespace App\Contracts\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ContractServiceInterface
{
    public function listForUser(int $userId, ?string $type = null, ?string $search = null, int $perPage = 15): array;

    public function findForUser(int $contractId, int $userId): ?object;

    public function create(int $userId, array $data): object;

    public function update(int $contractId, int $userId, array $data): object;

    public function cancel(int $contractId, int $userId): bool;
}
