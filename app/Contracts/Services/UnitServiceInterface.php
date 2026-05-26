<?php

namespace App\Contracts\Services;

use Illuminate\Support\Collection;

interface UnitServiceInterface
{
    /**
     * List booking units (investor units) for the authenticated user.
     */
    public function listForUser(int $userId): Collection;

    /**
     * Find a single booking unit by id for the user.
     */
    public function findForUser(int $unitId, int $userId): ?object;
}
