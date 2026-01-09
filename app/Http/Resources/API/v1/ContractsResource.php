<?php

namespace App\Http\Resources\API\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class ContractsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $invalid = ['phone', 'accepted', 'rejected', 'unpaid'];
        $accepted  = $this->is_accepted;
        $cancelled = $this->is_cancelled;

        return [
            'id' => $this->id,
            'code' => $this->code,
            'label' => $this->reservation_id ? 'بواسطة حجوزات الدرة' : null,
            'unit' => [
                'id' => $this?->unit?->id,
                'unit_name' => $this?->unit?->unit_number,
                'sector_name' => $this?->unit?->sector?->sector_name,
                'beach_name' => $this?->unit?->beach?->beach,
            ],
            'from' => $this->from,
            'to' => $this->to,

            'status' => get_api_contract_status_badge($this),

            'buttons' => [
                'verify_number' => $this->payment_type == 'phone' ? url('contract/'.contractMix($this).'/verifyPhone') : null,
                'pay_contract' => $this->payment_type == 'phone' && ($this->is_accepted && !$this->status && !$this->pay_later && !$this->is_cancelled) ? true : null,
                'show_contract' => $this->status && $this->is_accepted ? route('contract.by.token', [$this->code, $this->token]) : null,
                'edit_contract' => !in_array($this->payment_type, $invalid) && acceptedNotCancelled($accepted, $cancelled) && is_valid($this) ? true : null,
                'cancel_contract' => is_valid($this) && is_null($this->is_cancelled) && is_null($this->reservation_id) ? true : null
            ]
        ];
    }
}
