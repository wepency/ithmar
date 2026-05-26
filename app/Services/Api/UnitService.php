<?php

namespace App\Services\Api;

use App\Contracts\Services\UnitServiceInterface;
use App\Models\BookingUnit;
use Illuminate\Support\Collection;

class UnitService implements UnitServiceInterface
{
    public function listForUser(int $userId): Collection
    {
        return BookingUnit::whereHas('unit.user', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })
            ->active()
            ->with('unit', 'unit.beach', 'unit.sector')
            ->orderBy('id', 'DESC')
            ->get();
    }

    public function findForUser(int $unitId, int $userId): ?object
    {
        return BookingUnit::whereHas('unit.user', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })
            ->active()
            ->with('unit', 'unit.beach', 'unit.sector', 'unitGallery')
            ->find($unitId);
    }
}
