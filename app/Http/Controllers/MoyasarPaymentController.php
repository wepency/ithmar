<?php

namespace App\Http\Controllers;

use App\Models\BookingInvestorInvoice;
use App\Models\Contract;
use App\Models\Later;
use App\Models\MoyasarPayment;
use App\Models\Wallet;
use App\Services\Payment\MoyasarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MoyasarPaymentController extends Controller
{
    protected MoyasarService $moyasarService;

    public function __construct(MoyasarService $moyasarService)
    {
        $this->moyasarService = $moyasarService;
    }

    public function callback(Request $request)
    {
        $paymentId = $request->query('id');
        $invoiceId = $request->query('invoice_id');
        $status = $request->query('status');
        $message = $request->query('message');

        if (!$paymentId && !$invoiceId) {
            return redirect('/')->with('error', 'Invalid payment session.');
        }

        // 1. Find the local record by invoice_id first (since that's what we saved)
        $moyasarPayment = null;
        if ($invoiceId) {
            $moyasarPayment = MoyasarPayment::where('moyasar_id', $invoiceId)->first();
        }
        
        if (!$moyasarPayment && $paymentId) {
            $moyasarPayment = MoyasarPayment::where('moyasar_id', $paymentId)->first();
        }

        if (!$moyasarPayment) {
            Log::error('Moyasar Callback: Record not found. ID: ' . $paymentId . ', Invoice ID: ' . $invoiceId);
            return redirect('/')->with('error', 'Payment record not found.');
        }

        // If status from query is not paid, we can short circuit but better verify with API
        if ($status && $status !== 'paid') {
             $moyasarPayment->update(['status' => '2']);
             return $this->redirectWithResult($moyasarPayment, false, $message ?? 'Payment failed');
        }

        // 2. Fetch and Verify from Moyasar API
        try {
            // Fetch payment details using paymentId
            $payment = $this->moyasarService->fetchPayment($paymentId);
            
            if ($payment['status'] !== 'paid') {
                $moyasarPayment->update(['status' => '2']); // 2 for failed
                return $this->redirectWithResult($moyasarPayment, false, $payment['source']['message'] ?? 'Payment failed');
            }

            // 3. Update Status
            $moyasarPayment->update(['status' => '1']); // 1 for paid

            // 4. Process Success Logic
            return $this->processSuccess($moyasarPayment, $payment);

        } catch (\Exception $e) {
            Log::error('Moyasar Callback Error: ' . $e->getMessage());
            return redirect('/')->with('error', 'Error verifying payment: ' . $e->getMessage());
        }
    }

    protected function processSuccess(MoyasarPayment $moyasarPayment, array $payment)
    {
        $userId = $moyasarPayment->user_id;
        $amount = $moyasarPayment->amount / 100;

        switch ($moyasarPayment->model_type) {
            case 'wallet':
                Wallet::create([
                    'credit' => $amount,
                    'user_id' => $userId,
                    'type' => 'investor_add'
                ]);
                return $this->redirectWithResult($moyasarPayment, true, 'تم إضافة الرصيد بنجاح.');

            case 'contract':
                $contract = Contract::find($moyasarPayment->model_id);
                if ($contract) {
                    $contract->payment_type = 'paid';
                    $contract->status = 1;
                    $contract->save();

                    if ($contract->reservation_id != '') {
                        Wallet::create([
                            'user_id' => $contract->user_id,
                            'credit' => 20,
                            'type' => 'cashback'
                        ]);
                    }
                }
                return $this->redirectWithResult($moyasarPayment, true, 'تم دفع العقد بنجاح.');

            case 'invoice':
                $invoice = BookingInvestorInvoice::find($moyasarPayment->model_id);
                if ($invoice) {
                    $invoice->status = 1;
                    $invoice->save();

                    $to_pay = investor_to_pay($invoice) - $invoice->locked_paid;
                    @create_booking_history_record($invoice->id, 'BookingInvestorInvoice', 'paid', $to_pay, 'App\Models\ResUser', $userId);
                }
                return $this->redirectWithResult($moyasarPayment, true, 'تم دفع الفاتورة بنجاح.');

            case 'later':
                $later = Later::find($moyasarPayment->model_id);
                if ($later) {
                    $contracts = Contract::whereIn('id', unserialize($later->contracts))->get();
                    foreach ($contracts as $contract) {
                        $contract->payment_type = 'paid';
                        $contract->save();
                    }
                }
                return $this->redirectWithResult($moyasarPayment, true, 'تمت عملية دفع العقود الآجلة بنجاح.');

            default:
                return $this->redirectWithResult($moyasarPayment, true, 'Payment successful.');
        }
    }

    protected function redirectWithResult(MoyasarPayment $moyasarPayment, bool $success, string $message)
    {
        $statusKey = $success ? 'success' : 'error';
        
        switch ($moyasarPayment->model_type) {
            case 'wallet':
                return redirect()->to(investor_url('credit'))->with($statusKey, $message);
            case 'contract':
                // For contracts, they might have a specific view for success/fail as seen in original code
                if ($success) {
                    $contracts = Contract::find($moyasarPayment->model_id);
                    $page_title = '';
                    return view('MyFatoorah.success', compact('contracts', 'page_title'));
                } else {
                    $page_title = '';
                    return view('MyFatoorah.fail', compact('page_title'))->with('error', $message);
                }
            case 'invoice':
                return redirect()->to('invoices')->with($statusKey, $message);
            case 'later':
                return redirect()->to(investor_url('contracts'))->with($statusKey, $message);
            default:
                return redirect('/')->with($statusKey, $message);
        }
    }
}
