<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Services\MoyasarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class MoyasarPaymentController extends Controller
{
    /**
     * Demo “order” resolver:
     * Replace this with your Order/Booking model lookup.
     */
    private function loadOrder($orderId): array
    {
        // Example: amount in major units (SAR), convert in blade to minor (halalas)
        return [
            'id' => (string) $orderId,
            'reference' => 'ORDER-' . $orderId,
            'amount_major' => 1410.00,
            'currency' => config('services.moyasar.currency', 'SAR'),
            'description' => 'Payment for Order #' . $orderId,
        ];
    }
    
    public function checkout(Request $request, $order)
    {
        $orderData = $this->loadOrder($order);

        return view('payments.moyasar.checkout', [
            'order' => $orderData,
            'publishableKey' => config('services.moyasar.publishable_key', env('MOYASAR_PUBLISHABLE_KEY')),
            'callbackUrl' => route('payments.moyasar.callback'),
        ]);
    }

    /**
     * Called from JS `on_completed(payment)` to save payment id before redirecting to 3DS.
     * Moyasar recommends saving payment id (optional but recommended).  [oai_citation:4‡Moyasar](https://docs.moyasar.com/guides/card-payments/basic-integration)
     */
    public function save(Request $request)
    {
        $data = $request->validate([
            'payment_id' => ['required', 'string'],
            'order_id' => ['required', 'string'],
            'amount_minor' => ['required', 'integer', 'min:1'],
            'currency' => ['required', 'string', 'size:3'],
        ]);

        // Store expected data for callback verification (use DB in real apps).
        $key = $this->cacheKey($data['payment_id']);
        Cache::put($key, [
            'order_id' => $data['order_id'],
            'amount_minor' => $data['amount_minor'],
            'currency' => strtoupper($data['currency']),
        ], now()->addHours(6));

        return response()->json(['ok' => true]);
    }

    /**
     * Moyasar redirects to callback_url with ?id=... (and sometimes status/message).  [oai_citation:5‡Moyasar](https://docs.moyasar.com/guides/card-payments/basic-integration)
     * Always verify by fetching payment from API.  [oai_citation:6‡Moyasar](https://docs.moyasar.com/guides/card-payments/basic-integration)
     */
    public function callback(Request $request)
    {
        $paymentId = (string) $request->query('id', '');
        if ($paymentId === '') {
            return redirect()
                ->route('payments.moyasar.checkout', ['order' => 'unknown'])
                ->with('error', 'Missing payment id.');
        }

        $expected = Cache::get($this->cacheKey($paymentId));
        if (!$expected) {
            // You can still fetch the payment, but you won’t be able to compare amount/currency reliably.
            return view('payments.moyasar.result', [
                'ok' => false,
                'title' => 'Payment received but not recorded',
                'message' => 'We could not find the saved payment session. Please contact support with Payment ID: ' . e($paymentId),
                'payment' => null,
            ]);
        }

        $moyasar = MoyasarService::make();

        try {
            $payment = $moyasar->fetchPayment($paymentId);
        } catch (\Throwable $e) {
            return view('payments.moyasar.result', [
                'ok' => false,
                'title' => 'Verification failed',
                'message' => $e->getMessage(),
                'payment' => null,
            ]);
        }

        $check = $moyasar->verify(
            $payment,
            (int) $expected['amount_minor'],
            (string) $expected['currency']
        );

        if ($check['ok']) {
            // ✅ Mark order as paid in your DB هنا
            // Order::where('id',$expected['order_id'])->update([...]);

            // Cleanup
            Cache::forget($this->cacheKey($paymentId));

            return view('payments.moyasar.result', [
                'ok' => true,
                'title' => 'Payment successful',
                'message' => 'Your payment has been verified successfully.',
                'payment' => $payment,
            ]);
        }

        return view('payments.moyasar.result', [
            'ok' => false,
            'title' => 'Payment not verified',
            'message' => "Status: {$check['status']} | Amount/Currency mismatch or not paid.",
            'payment' => $payment,
        ]);
    }

    private function cacheKey(string $paymentId): string
    {
        return 'moyasar:payment:' . Str::lower($paymentId);
    }
}