<?php

namespace App\Http\Resources\API\v1;

use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;

class BookingsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    public function toArray($request)
    {
        $tax = ($this->booking_profit*15) / 100;
        $status = check_refund_status($this);

        $vertime = cache()->get('vertime');

        return [
            "id" => $this->id,
            "booking_id" => Route::is('api.reservations') ? pad_code($this->id) : pad_code($this->booking_id),
            'unit_name' => $this->get_unit(),
            "client_name" => $this->user?->name,
            "subtotal" => numbers_api($this->subtotal),
            "total" => numbers_api($this->total),
            'discount_text' => $this->when($this->coupon, function (){
                $out = "قام المستخدم باستخدام كوبون ". $this->coupon;
                $out .= " وحصل على خصم ".currency($this->sub_total - $this->total);
                $out .= "ستخصم من الفاتورة الشهرية";
                return $out;
            }),
            "down_payment_percentage" => $this->down_payment_percentage,
            "down_payment" => numbers_api($this->down_payment),
            "profit_percentage" => $this->profit_percentage,
            "booking_profit_before" => numbers_api($this->booking_profit),
            "booking_profit_after" => numbers_api($this->booking_profit),
            "commission_tax" => numbers_api(number_format($tax, 2)),
            "price_to_pay" => numbers_api($this->booking_profit + $tax),
            "old_reservation_id" => $this->old_reservation_id,
            "new_reservation_id" => $this->new_reservation_id,
            "status" => $this->get_text(),
            'color' => $this->get_color(),
            'cancelled_text' => $this->get_cancelled_text(),
            "created_at" => $this->created_at->diffForHumans(Carbon::now()),
            $this->mergeWhen(Route::is('api.reservations'), [
                'booking_status' => $this->get_reservation_status(),
                'background' => get_status($this) == 'cancelled' ? '#bdc3c7' : null,
                'from' => \Carbon\Carbon::parse($this->from)->format('Y-m-d'),
                'to' => \Carbon\Carbon::parse($this->to)->format('Y-m-d'),
                'down_payment_picture' => is_null($this->verification_image) ? null : asset(env('RESERVATION_APP_URL').'/uploads/verifications/'.$this->verification_image),
                'full_payment_picture' => is_null($this->final_verification_image) ? null : asset(env('RESERVATION_APP_URL').'/uploads/verifications/'.$this->final_verification_image),
//                'timer' => is_null($this->status) ? $this->created_at->addHours(5)->format('M d, Y H:i:s') : null,
                'timer_text' => is_null($this->status)  ? 'المهلة المتبقية لدفع العربون' : null,
                'timer' => is_null($this->status) ? $this->created_at->addMinutes($vertime)->toDateTimeString() : null,
                'timer_seconds' => is_null($this->status) ? strtotime($this->created_at->addMinutes($vertime)->toDateTimeString()) : null,
                'buttons' => [
                    'confirm_down_payment' => $this->status === 1 ? $this->id : null,
                    'confirm_full_payment' => $this->status === 1 ? $this->id : null,
//                    'upload_transaction_confirmation' => $status && $status == 'refund-request' ?  $this->id : null,
                    'upload_transaction_confirmation' => $this->id,
                    'contact_client' => $this->status > 1 && $this->status < 5 && get_status($this) != 'expired' && !$status ? 'https://wa.me/+966'.$this->phonenumber.'?text='.client_message() : null
                ],
                'contract_buttons' => [
                    'create_contract' => !$this->contract ? route('reservation.contract.generate', deep_encode($this->id, $this->created_at)) : null,
                    'contract_link' => $this->contract ? route('contract.by.token', ['code' => $this->contract->code, 'token' => $this->contract->token]) : null,
                    'pay_contract' => $this->contract && $this->contract?->status != 1 && !$this->contract?->pay_later && !$this->contract?->is_cancelled ? route('invoice.pay', deep_encode($this->id, $this->created_at)) : null,
                ]
            ])
        ];
    }

    private function get_status(){
        if ($this->is_cancelled){
            return 'cancelled';
        }else{
            return 'verified';
        }
    }

    public function get_unit(){
        $unit  = $this->unit?->unit?->unit_number;
        $unit .= ' - ';
        $unit .= $this->unit?->unit?->beach?->beach;
        $unit .= ' - ';
        $unit .= $this->unit?->unit?->sector?->sector_name;

        return $unit;
    }

    private function get_color(){
        $status = $this->get_status();

        if ($status == 'cancelled'){
            return '#e74c3c';
        }

        return '#27ae60';
    }

    private function get_text(){
        $status = $this->get_status();

        if ($status == 'cancelled'){
            return 'ملغي';
        }else {
            return 'مؤكد';
        }
    }

    private function get_cancelled_text(){
        $status = $this->get_status();

        if ($status == 'cancelled'){
            if ($this->type == 'with-commission'){
                return 'مع احتساب عمولة';
            }

            return 'بدون احتساب عمولة';
        }

        return '';
    }

    // Reservations Functions
    private function get_reservation_status(){
        $status = check_refund_status($this);

        $status_arr =  [];

//        if($status){
            switch($status){
                case 'refund-request':
                    $status_arr['text'] =  'طلب استرداد';
                    $status_arr['color'] = '#e74c3c';
//                    $status_arr['action_text'] = 'رفع صورة التحويل';
//                    $status_arr['action_link'] = route('upload.investor.refund.form', deep_encode($this->id, $this->created_at));
                    $status_arr['action_text'] = null;
                    $status_arr['action_color'] = null;
                break;

                case 'waiting-approval':
                    $status_arr['text'] =  'بانتظار مراجعة طلب الاسترداد';
                    $status_arr['color'] = '#e74c3c';
                    $status_arr['action_text'] = null;
                    $status_arr['action_color'] = null;
                break;

                case 'converted-reservation':
                    $status_arr['text'] =  'حجز محول';
                    $status_arr['color'] = '#e74c3c';
                    $status_arr['action_text'] = null;
                    $status_arr['action_color'] = null;
                break;

                default:
                    $status_arr['text'] =  strip_tags(get_badge($this));
                    $status_arr['color'] = '#e74c3c';
                    $status_arr['action_text'] = null;
                    $status_arr['action_color'] = null;
                break;
            }
//        }

        return $status_arr;
    }
}
