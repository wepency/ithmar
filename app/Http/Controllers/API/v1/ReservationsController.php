<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\v1\BookingsResource;
use App\Models\BookingInvoice;
use App\Models\Bookings;
use App\Models\refund;
use App\Traits\generateAPI;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReservationsController extends Controller
{
    use generateAPI;

    public function index(){
        $bookings = clone $all_bookings = clone $waiting = clone $waiting_ver = clone $verified = Bookings::with('user')->whereHas('unit.unit.user', function ($q){
            $q->where('id', auth()->id());
        });

        $bookings = $bookings->orderBy('id', 'DESC')->where(function($q){
            $q->whereNotNull('status')->orWhere(function ($q){
                $q->whereNull('status')->where('created_at', '>', Carbon::now()->subHours(5));
            });
        })->get();

        return $this->success([
            'total_bookings' => $all_bookings->count(),
            'waiting_payment_count' => $waiting->where('status', 1)->count(),
            'waiting_verification_count' => $waiting_ver->where('status', 3)->count(),
            'verified_count' => $verified->where('status', 4)->count(),
            'data' => BookingsResource::collection($bookings)
        ]);
    }

    public function acceptDownPayment($reservation_id){
        $reservation = Bookings::findOrFail($reservation_id);

        if ($reservation->unit->unit->user_id != auth()->id())
            abort(404);

        $update = $reservation->update([
            'status' => 2
        ]);

        $phone_number = $reservation->phonenumber;
        $body = 'تم الموافقة على الحوالة للحجز رقم '.pad_code($reservation->id);
        sendSMSBody($phone_number, $body);

        $token = $reservation->unit->unit->user->fcm_token;

        if ($token != '')
            @push_single_notification([$token], 'يمكنك اصدار عقد', 'يمكنك الأن إصدار العقد للحجز رقم '.pad_code($reservation->id).' من قائمه حجوزات اون لاين');

        @create_booking_history_record($reservation->id, 'Bookings', 'accept_down');

        BookingInvoice::create([
            'booking_id' => $reservation_id,
            'subtotal' => $reservation->sub_total,
            'total' => $reservation->total,
            'down_payment_percentage' => $reservation->down_payment_percent,
            'down_payment' => $reservation->down_payment,
            'profit_percentage' => $reservation->unit->profit_percentage,
            'booking_profit' => (($reservation->total * $reservation->unit->profit_percentage) / 100)
        ]);

        if ($update)
            return $this->success(null,  'تم تأكيد دفع العربون بنجاح.');

        return $this->error(null, 'هناك مشكلة في تأكيد دفع العربون ، برجاء التأكد من الدعم الفني.');
    }

    public function acceptTotal($booking_id){
        $reservation = Bookings::findOrFail($booking_id);

        if ($reservation->unit->unit->user_id != auth()->id())
            abort(404);

        $update = $reservation->update([
            'status' => 4
        ]);

        $phone_number = $reservation->phonenumber;
        $body = 'تم الموافقة على اكمال المبلغ واظهار عقد الدخول كاملا عبر صفحة حجوزاتي في حسابكم.';
        sendSMSBody($phone_number, $body);

        if ($update){
            @create_booking_history_record($reservation->id, 'Bookings', 'validate');

            $check_invoice = BookingInvoice::where('booking_id', $reservation->id)->first();

            if (is_null($check_invoice)){
                BookingInvoice::create([
                    'booking_id' => $booking_id,
                    'subtotal' => $reservation->sub_total,
                    'total' => $reservation->total,
                    'down_payment_percentage' => $reservation->down_payment_percent,
                    'down_payment' => $reservation->down_payment,
                    'profit_percentage' => $reservation->unit->profit_percentage,
                    'booking_profit' => (($reservation->total * $reservation->unit->profit_percentage) / 100)
                ]);
            }

            return $this->success(null, 'تم تأكيد الحجز بنجاح.');
        }

        return $this->error(null, 'هناك مشكلة في تأكيد الحجز ، برجاء التأكد من الدعم الفني.');
    }

    public function uploadTransaction(Request $request, $booking_id){

        try {

            $request->validate([
                'image' => 'required'
            ]);

            return DB::transaction(function () use ($request, $booking_id){
                $reservation = Bookings::findOrFail($booking_id);

                if ($request->has('image')){
                    $file = $request->file('image');
                    $file_name = Str::slug($file->getClientOriginalName()).time().'-'.$file->getClientOriginalExtension();
                    $file->move('uploads/verification', $file_name);

                    $create = refund::create([
                        'reservation_id' => $reservation->id,
                        'verification_image' => $file_name
                    ]);

                    $reservation->update([
                        'refund_id' => $create->id
                    ]);

                    return $this->success(null, 'تم رفع الصورة بنجاح.');
                }
            });

        }catch (\Exception $e) {
            return $this->error(null, $e->getMessage(), $e->getCode());
        }
    }
}
