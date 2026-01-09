<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Bookings;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function pay(Request $request, $booking_id)
    {
        $booking_id = base64_encode(base64_encode($booking_id));
        $booking = Bookings::findOrFail($request->booking_id);

        return $this->success([
            'apple_pay' => [
                'status' => true,
                'to_pay' => $booking->down_payment
            ],
            'credit_card' => [
                'status' => true,
                'url' => route('api.payment.urway', $booking_id)
            ]
        ]);
    }
}
