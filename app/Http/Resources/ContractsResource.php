<?php

namespace App\Http\Resources;

use App\Models\Bookings;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => $this->user,
            'code' => $this->code,
            'is_cancelled' => $this->is_cancelled,
            'unit_name' => $this->unit->unit_number ?? '',
            'sector_id' => $this->sector_id,
            'sector_name' => $this->sector->sector_name ?? '',
            'beach_name' => $this->beach->beach ?? '',
            'user_name' => $this->user->name ?? '',
            'tenant_name' => $this->tenant_name,
            'tenant_name_code' => $this->tenant_name_code,
            'tenant_barcode' => $this->get_image($this->attachment_1),
            'with_tenant_barcode' => $this->get_image($this->attachment_2),
            'attachment' => asset('uploads/'.$this->attachment),
            'with_tenant_name' => $this->with_tenant_name,
            'with_tenant_name_code' => $this->with_tenant_name_code,
            'tenant_nationality' => $this->tenant_nationality,
            'with_tenant_nationality' => $this->with_tenant_nationality,
            'insurance_value' => currency($this->insurance_value),
            'phonenumber' => $this->phonenumber,
            'host_phonenumber' => $this->unit->user->phonenumber,
            'from' => Carbon::parse($this->from)->format(get_format()),
            'to' => Carbon::parse($this->to)->format(get_format()),
            'rent_value' => currency($this->rent_value),
            'since' => $this->created_at->diffForHumans(Carbon::now()),
            'qr_code' => env('APP_URL').'/contract/check/'.qr_code_encode($this),
            'status' => $this->get_status(),
            'badge' => $this->get_badge(),
            'table_type' => $this->get_table(),
            'share_link' => url('contract/'.$this->code.'/'.$this->token),
            'draft_share_link' => getDraftLink($this),
            'verify_phone' => verifyPhone($this),
            'vat' => number_format($this->vat, 2).' %',
            'price' => $this->price,
            'total_amount' => total_amount($this->price, $this->total),
            'balance' => $this->total,
            'cars' => $this->cars,
            'services' => $this->services ? unserialize($this->services->service_data) : [],
            'tenant_title' => trans('admin.'.$this->tenant_title),
            'with_tenant_title' => trans('admin.'.$this->with_tenant_title),
            'reservation_status' => is_admin() ? 1 : $this->getStatus(),
            'is_reservation' => !is_null($this?->reservation),
            'remaining_payment' => $this->get_remaining(),
            'upload_image_link' => route('upload.invoice', deep_encode($this->id, $this->created_at))
        ];
    }

    private function getStatus(){
        if (!is_null($this->reservation_id)){
            $reservation = $this?->reservation?->status;

            if ($reservation < 4)
                return 'down_payment';
            else
                return 'fully_paid';
        }
    }

    private function get_remaining(){
        if (!is_null($this?->reservation)){
            $booking = Bookings::find($this->reservation_id);
            return $booking->total - $booking->down_payment;
        }

//        if ($this->getStatus() == 'down_payment'){
//
//
//            if (!is_null($booking)){
//                return currency($booking->total - $booking->down_payment);
//            }
//        }

        return 0;
    }

    private function get_status(){
        if ($this->from > Carbon::now()){
            return "انتهي";
        }else{
            return "فعال";
        }
    }
    private function get_badge(){
        if ($this->from > Carbon::now()){
            return "<span class='badge badge-danger'>انتهى</span>";
        }else{
            return "<span class='badge badge-success'>قعال</span>";
        }
    }
    private function get_table(){
        if ($this->from > Carbon::now()){
            return "table-danger";
        }else{
            return "";
        }
    }

    private function get_image($file_name){
        if (file_exists(public_path('uploads/'.$file_name)) && !is_null($file_name)) {
            return asset('uploads/'.$file_name);
        }

        return false;
    }
}
