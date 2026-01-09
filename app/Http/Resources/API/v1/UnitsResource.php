<?php

namespace App\Http\Resources\API\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class UnitsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'unit_name' => ($this->unit->unit_number ?? '') .' - '. ($this->unit->beach->beach ?? '') .' - '. ($this->unit->sector->sector_name ?? '')
        ];
    }
}
