<?php

namespace App\Http\Resources\API\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class CashRequestResource extends JsonResource
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
            "id" => $this->id,
            "user_id" => $this->user_id,
            "amount" => currency($this->amount),
            "holder_name" => $this->holder_name,
            "bank_name" => $this->bank_name,
            "bank_account" => $this->bank_account,
            "iban" => $this->iban,
            "status" => trans('messages.'.$this->status()),
            "status_bgcolor" => trans('messages.colors.'.$this->status()),
        ];
    }

    private function status(){
        return match($this->status) {
            1 => 'approved',
            2 => 'cancelled',
            default => 'pending'
        };
    }
}
