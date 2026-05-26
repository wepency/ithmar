<?php

namespace App\Contracts\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface WalletServiceInterface
{
    public function getBalanceForUser(int $userId): array;

    public function getTransactionsForUser(int $userId, int $perPage = 20): LengthAwarePaginator;

    public function getTransaction(int $id, int $userId): ?object;

    public function addBalance(int $userId, float $amount, string $type = 'investor_add', ?string $modelType = null, ?int $modelId = null): object;

    public function getWithdrawableBalance(int $userId): float;

    public function getBankInfo(int $userId): ?object;

    public function createWithdrawRequest(int $userId, array $data): object;
}
