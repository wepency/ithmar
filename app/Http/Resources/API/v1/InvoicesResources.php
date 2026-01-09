<?php

namespace App\Http\Resources\API\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoicesResources extends JsonResource
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
            "name" => $this->unit?->unit?->unit_number.' - '.($this->unit?->unit?->beach?->beach ?? '').' - '.($this->unit?->unit?->sector?->sector_name ?? ''),
            "investor_id" => $this->investor_id,
            "booking_unit_id" => $this->booking_unit_id,
            "total" => $this->total,
            "profit_percent" => $this->profit_percent,
            "profit" => $this->profit,
            "tax" => $this->tax,
            "violations" => $this->violations,
            "price_to_pay" => $this->profit+$this->tax+$this->violations,
            "status" => $this->status_text(),
            "status_color" => $this->color(),
            "start_date" => $this->start_date,
            "end_date" => $this->end_date,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "buttons" => [
                "show_payment" => $this->status() == "waiting",
            ],
            "links" => [
                "payment" => route('invoice.pay', deep_encode($this->id, $this->created_at)),
                "view_invoice" => route('invoice.show', deep_encode($this->id, $this->created_at)),
            ]
        ];
    }

    public function status(){
        if($this->status)
            return "paid";
        elseif($this->total || $this->violations)
            return "waiting";
        else
            return "no_need";

    }

    public function status_text(){
        $status = $this->status();

        if ($status == 'paid')
            return "مدفوع";
        elseif($status == 'waiting')
            return "بانتظار الدفع";
        else
            return "لا يوجد مبلغ لسدادة";
    }

    public function color(){
        $status = $this->status();

        if ($status == 'paid')
            return "#2ecc71";
        elseif($status == 'waiting')
            return "#f1c40f";
        else
            return "#e74c3c";
    }
}
