<?php

namespace App\Http\Resources\API\v1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ViolationsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "reason" => $this->reason,
            "price" => numbers_api($this->price),
            "created_at" => $this->created_at->diffForHumans(Carbon::now())
        ];
    }
}
