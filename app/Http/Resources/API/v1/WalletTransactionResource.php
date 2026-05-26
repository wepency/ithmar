<?php

namespace App\Http\Resources\API\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class WalletTransactionResource extends JsonResource
{
    public function toArray($request): array
    {
        $amount = $this->amount ?? $this->credit ?? 0;

        return [
            'id' => $this->id,
            'type' => $this->type,
            'amount' => (float) $amount,
            'model_type' => $this->model_type,
            'model_id' => $this->model_id,
            'status' => $this->status,
            'created_at' => $this->created_at?->toIso8601String(),
            'created_at_human' => $this->created_at?->diffForHumans(),
        ];
    }
}
