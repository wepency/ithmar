<?php

namespace App\Http\Resources\API\v1;

use App\Enums\ContractStepType;
use App\Services\Api\ContractStepService;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractStepResource extends JsonResource
{
    public function toArray($request): array
    {
        $extra = ContractStepService::decodeExtra($this->extra);

        return [
            'id' => $this->id,
            'type' => $this->type,
            'type_label' => ContractStepType::label($this->type),
            'extra' => $extra,
            'user_id' => $this->user_id,
            'user_name' => $this->whenLoaded('user', fn () => $this->user?->name),
            'created_at' => $this->created_at?->toIso8601String(),
            'created_at_human' => $this->created_at?->diffForHumans(),
        ];
    }
}
