<?php

namespace App\Http\Resources\API\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class ContractDetailResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'token' => $this->token,
            'from' => $this->from,
            'to' => $this->to,
            'status' => $this->status,
            'is_accepted' => $this->is_accepted,
            'is_cancelled' => $this->is_cancelled,
            'payment_type' => $this->payment_type,
            'phonenumber' => $this->phonenumber,
            'rent_value' => (float) $this->rent_value,
            'price' => (float) ($this->price ?? 0),
            'vat' => (float) ($this->vat ?? 0),
            'total' => (float) ($this->total ?? 0),
            'services_total' => (float) ($this->services_total ?? 0),
            'insurance_value' => (float) ($this->insurance_value ?? 0),
            'tenant_title' => $this->tenant_title,
            'tenant_name' => $this->tenant_name,
            'with_tenant_name' => $this->with_tenant_name,
            'unit' => $this->whenLoaded('unit', fn () => [
                'id' => $this->unit->id,
                'unit_number' => $this->unit->unit_number,
                'sector_name' => $this->unit->sector->sector_name ?? null,
                'beach_name' => $this->unit->beach->beach ?? null,
            ]),
            'beach' => $this->whenLoaded('beach'),
            'sector' => $this->whenLoaded('sector'),
            'cars' => $this->whenLoaded('cars', fn () => $this->cars->map(fn ($c) => [
                'id' => $c->id,
                'car_type' => $c->car_type,
                'car_serial' => $c->car_serial,
                'sort_order' => $c->sort_order,
            ])),
            'companions' => $this->whenLoaded('companions', fn () => $this->companions->map(fn ($c) => [
                'id' => $c->id,
                'title' => $c->title,
                'name' => $c->name,
                'id_number' => $c->id_number,
                'nationality' => $c->nationality,
                'barcode_image' => $c->barcode_image,
                'sort_order' => $c->sort_order,
            ])),
        ];
    }
}
