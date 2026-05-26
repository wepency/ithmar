<?php

namespace App\Services\Api;

use App\Contracts\Services\ContractStepServiceInterface;
use App\Enums\ContractStepType;
use App\Models\Contract;
use App\Models\History;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ContractStepService implements ContractStepServiceInterface
{
    public function record(Contract $contract, string $stepType, ?array $extra = null, ?int $userId = null): object
    {
        $userId = $userId ?? auth()->id();

        return History::create([
            'hismodel_id' => $contract->id,
            'hismodel_type' => Contract::class,
            'user_id' => $userId,
            'type' => $stepType,
            'extra' => $extra ? json_encode($extra) : null,
        ]);
    }

    public function getStepsForContract(Contract $contract): Collection
    {
        return $contract->steps()->with('user')->orderBy('created_at', 'asc')->get();
    }

    public function getStepsForContractPaginated(Contract $contract, int $perPage = 15): LengthAwarePaginator
    {
        return $contract->steps()->with('user')->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Decode extra JSON for a step.
     */
    public static function decodeExtra(?string $extra): array
    {
        if ($extra === null || $extra === '') {
            return [];
        }
        $decoded = json_decode($extra, true);
        return is_array($decoded) ? $decoded : [];
    }
}
