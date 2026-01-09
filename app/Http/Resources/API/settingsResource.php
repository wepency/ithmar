<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class settingsResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'price_before' => $this->price_before_vat,
            'price_after' => $this->price_after_vat,
            'name' => $this->name,
            'email' => $this->email,
            'phonenumber' => $this->phonenumber,
            'website' => $this->website,
            'vat' => $this->vat,
            'vat_percent' => vat_percent($this->price_before_vat, $this->price_after_vat),
            'total_amount' => total_amount($this->price_before_vat, $this->price_after_vat),
            'balance' => $this->price_after_vat
        ];
    }
}
