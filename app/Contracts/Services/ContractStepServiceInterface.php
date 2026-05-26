<?php

namespace App\Contracts\Services;

use App\Models\Contract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ContractStepServiceInterface
{
    /**
     * Record a step for a contract.
     */
    public function record(Contract $contract, string $stepType, ?array $extra = null, ?int $userId = null): object;

    /**
     * Get all steps for a contract (chronological).
     */
    public function getStepsForContract(Contract $contract): Collection;

    /**
     * Get paginated steps for a contract.
     */
    public function getStepsForContractPaginated(Contract $contract, int $perPage = 15): LengthAwarePaginator;
}
