<?php

namespace App\Http\Controllers\Reservations;

use App\Http\Controllers\Controller;
use App\Models\BookingDate;
use App\Models\BookingInvoice;
use App\Models\Bookings;
use App\Models\BookingUser;
use App\Models\Contract;
use App\Models\refund;
use App\Models\Sector;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class onlineReservationsController extends Controller
{
    public function index()
    {
        $bookings = clone $all_bookings = clone $waiting = clone $waiting_ver = clone $verified = Bookings::with('user')->whereHas('unit.unit.user', function ($q){
            $q->where('id', auth()->id());
        });

        $bookings = $bookings->orderBy('id', 'DESC')->where(function($q){
            $q->whereNotNull('status')->orWhere(function ($q){
                $q->whereNull('status')->where('created_at', '>', Carbon::now()->subHours(5));
            });
        })->where('is_ready', 1)->paginate();

        return view('Reservations.Booking.index', [
            'page_title' => 'حجوزات أونلاين',
            'rows' => $bookings,
            'all_bookings' => $all_bookings->count(),
            'waiting_payment_count' => $waiting->where('status', 1)->count(),
            'waiting_verification_count' => $waiting_ver->where('status', 3)->count(),
            'verified_count' => $verified->where('status', 4)->count()
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

        $token = $reservation->unit->unit->user->tokens->get()->pluck('token');

        if (!empty($token))
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
            return redirect()->back()->with('success', 'تم تأكيد دفع العربون بنجاح.');

        return redirect()->back()->with('error', 'هناك مشكلة في تأكيد دفع العربون ، برجاء التأكد من الدعم الفني.');
    }

    public function acceptReservation($reservation_id){
        $reservation = Bookings::findOrFail($reservation_id);
        $booking_user = $reservation->booking_user_id;

        if ($reservation->unit->unit->user_id != auth()->id())
            abort(404);

        $update = $reservation->update([
            'status' => 4
        ]);

        $phone_number = $reservation->phonenumber;
        $body = 'تم الموافقة الحجز واظهار عقد الدخول كاملا عبر صفحة حجوزاتي في حسابكم.';
        sendSMSBody($phone_number, $body);

        if ($update){
            @create_booking_history_record($reservation->id, 'Bookings', 'validate');

            $check_invoice = BookingInvoice::where('booking_id', $reservation->id)->first();

            if (is_null($check_invoice)){
                BookingInvoice::create([
                    'booking_id' => $reservation_id,
                    'subtotal' => $reservation->sub_total,
                    'total' => $reservation->total,
                    'down_payment_percentage' => $reservation->down_payment_percent,
                    'down_payment' => $reservation->down_payment,
                    'profit_percentage' => $reservation->unit->profit_percentage,
                    'booking_profit' => (($reservation->total * $reservation->unit->profit_percentage) / 100)
                ]);

                BookingUser::find($booking_user)->update([
                    'amount_paid' => $reservation->total
                ]);
            }

            return redirect()->back()->with('success', 'تم تأكيد الحجز بنجاح.');
        }

        return redirect()->back()->with('error', 'هناك مشكلة في تأكيد الحجز ، برجاء التأكد من الدعم الفني.');
    }

    public function uploadInvoice($code){
        $code = deep_decode($code);
        $contract = Contract::findOrFail($code);

        if (!$contract->reservation || $contract->reservation->status != 2)
            abort(404);

        return view('Reservations.upload', [
            'page_title' => 'رفع صورة الحوالة',
            'reservation_id' => deep_encode($contract->reservation->id, $contract->created_at)
        ]);
    }

    public function uploadInvestorInvoiceForm($code){
        $reservation = Bookings::findOrFail(deep_decode($code));

        if (!$reservation->has_changed || $reservation->unit->unit->user_id != auth()->id())
            abort(404);

        return view('Reservations.Booking.refund-form', [
            'page_title' => 'رفع صورة التحويل',
            'code' => $code,
            'reservation' => $reservation
        ]);
    }

    public function postInvestorInvoice(Request $request, $code){
        $request->validate([
            'file' => 'required|mimes:jpg,png,jpeg,gif,pmb,svg,webp'
        ]);

        return DB::transaction(function () use ($request, $code){
            $reservation = Bookings::findOrFail(deep_decode($code));

            if ($request->has('file')){
                $file = $request->file('file');
                $file_name = Str::slug($file->getClientOriginalName()).time().'-'.$file->getClientOriginalExtension();
                $file->move('uploads/verification', $file_name);

                $create = refund::create([
                    'reservation_id' => $reservation->id,
                    'verification_image' => $file_name
                ]);

                $reservation->update([
                    'refund_id' => $create->id
                ]);

                return response()->json();
            }

            return response()->json('', 403);
        });
    }

    public function generateContract($id){

//        $sectors = Sector::whereHas('unit', function (Builder $q){
//            $q->where('user_id', auth()->id())->where('status', 1);
//        })->orderBy('id', 'DESC')->get();

        $code = deep_decode($id);
        $refused = Unit::where('status', 2)->where('user_id', auth()->id())->get();

        $expired = Unit::where(function ($q){
            $q->where('valid_to', '')->orWhere('valid_to', '<', Carbon::today());
        })->where('type', 'investor')->where('user_id', auth()->id())->get();

        $reservation = Bookings::findOrFail($code);

        return view('Reservations.Booking.contract', [
            'page_title' => 'انشاء عقد للحجز',
            'refused' => $refused,
            'expired' => $expired,
            'reservation' => $reservation,
            'allowed_cars' => $reservation->unit->unit->beach->allowed_cars ?? 1
        ]);
    }
}
