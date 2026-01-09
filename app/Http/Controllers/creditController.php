<?php

namespace App\Http\Controllers;

use App\Models\BankingInfo;
use App\Models\BookingInvestorInvoice;
use App\Models\BookingInvoice;
use App\Models\Bookings;
use App\Models\CashRequest;
use App\Models\Contract;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class creditController extends Controller
{

    public function index()
    {
        $page_title = 'المحفظة';

        $wallet = clone $added_credit = clone $cashback = clone $total = clone $locked = Wallet::where('user_id', auth()->id());

        $credit_total = $total->where('request_id', '!=', '')->where('type', '!=','booking_downpayment')->sum('credit');

        $credits = $wallet->where('credit', '!=', '0')->orderBy('id', 'DESC')->paginate(20);

        $credits_obj = collect([]);

        foreach ($credits as $credit) {

            $credit = clone $new_credit = $credit;

            $credits_obj->push($credit);

            if ($credit->type == 'booking_downpayment_locked') {

                $get_total = Wallet::where('model_id', $credit->model_id)->where('credit', '>', 0)->get();
                $total = 0;

                foreach ($get_total as $tot) {
                    $total += $tot->credit;
                }

                if ($total > 0){
                    $new_credit->id = $credit->id * 5;
                    $new_credit->credit = $total;
                    $new_credit->type = 'booking_downpayment_total';

                    $credits_obj->push($new_credit);
                }
            }
        }

        $added_credit = $added_credit->addedCredit()->sum('credit');

        $withdrawable_credit = $cashback->withdrawableCredit()->sum('credit');

        $locked_credit = $locked->lockedCredit()->sum('credit');

        return view('Credit.index', [
            'page_title' => $page_title,
            'credit_total' => $credit_total,
            'credits' => $credits,
            'credits_obj' => $credits_obj,
            'added_credit' => $added_credit,
            'withdrawable_credit' => $withdrawable_credit,
            'locked_credit' => $locked_credit
        ]);
    }

    public function payByCredit(Request $request){
        $contract = Contract::where('code', $request->contract)->first();
        $sum = auth()->user()->credit()->addedCredit()->sum('credit');
        $toPay = $contract->total + $contract->services_total;

        if ($toPay <= $sum){
            if (!is_null($contract)){
                return DB::transaction(function () use($contract, $toPay){
                    //
//                    $wallet_arr[] = [
//                        'user_id' => auth()->id(),
//                        'credit' => '-'.($toPay),
//                        'type' => 'contract',
//                        'model_id' => $contract->id
//                    ];
//
//                    if ($contract->reservation_id != ''){
//                        $wallet_arr[] = [
//                            'user_id' => auth()->id(),
//                            'credit' => 20,
//                            'type' => 'cashback',
//                            'model_id' => $contract->id
//                        ];
//                    }

                    $wallet_arr = $this->creditArray($contract, $toPay);

//                    Wallet::create([
//                        'user_id' => auth()->id(),
//                        'credit' => '-'.($toPay),
//                        'type' => '',
//                        'model_id' => ''
//                    ]);

                    if (!empty($wallet_arr)) {
                        Wallet::insert($wallet_arr);

//                    Wallet::create([
//                        'user_id' => auth()->id(),
//                        'credit' => '-'.($toPay),
//                    ]);

                        $contract->update([
                            'payment_type' => 'paid',
                            'status' => 1
                        ]);

                        cache()->forget('credit-' . Auth::id());
                        session()->put('payment_success', 1);
                        return response()->json('تم بنجاح.');
                    }

                });
            }
        }else{
            return response()->json('عفوا الرصيد لا يكفي.', 500);
        }

        return response()->json('هذا العقد غير موجود.', 500);
    }

    public function payByCreditBulk(Request $request){
        $decode = @base64_decode($request->contract) ?? '';
        $contracts_array = explode(',', $decode);
        $contracts = Contract::whereIn('id', $contracts_array);
        $toPay = number_format($contracts->sum(\DB::raw('total + services_total')), 2, '.', '');
        $sum = \auth()->user()->credit()->sum('credit');
        $contracts = $contracts->get();

        if($toPay <= $sum){
            if (!empty($contracts)){
                return DB::transaction(function () use($contracts, $toPay){

//                    Wallet::create([
//                        'user_id' => auth()->id(),
//                        'credit' => '-'.($toPay),
//                        'type' => 'contract',
//                        'model_id' => $contract->id
//                    ]);

                    foreach ($contracts as $contract){
                        $price = number_format(($contract->total + $contract->services_total), 2, '.', '');
                        $wallet_arr = $this->creditArray($contract, $price);

                        Wallet::insert($wallet_arr);

                        $contract->update([
                            'payment_type' => 'paid',
                            'status' => 1
                        ]);
                    }

                    cache()->forget('credit-' . Auth::id());
                    session()->put('payment_success', 1);
                    return response()->json('تم بنجاح.');
                });
            }
        }else{
            return response()->json('عفوا الرصيد لا يكفي.', 500);
        }

        return response()->json('هذا العقد غير موجود.', 500);
    }

    private function creditArray($contract, $toPay){
        $wallet_arr = [];

        $can_be_paid = Wallet::where('user_id', auth()->id())->addedCredit()->sum('credit');

        if ($can_be_paid >= $toPay) {
            $wallet_arr[] = [
                'user_id' => auth()->id(),
                'credit' => '-'.($toPay),
                'type' => 'investor_contract',
                'model_id' => $contract->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];

            if ($contract->reservation_id != ''){
                $wallet_arr[] = [
                    'user_id' => auth()->id(),
                    'credit' => 20,
                    'type' => 'cashback',
                    'model_id' => $contract->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }
        }


        return $wallet_arr;
    }

    public function payInvoiceByCredit(Request $request){
        $invoice = base64_decode($request->invoice);

//        $credit = auth()->user()->credit()->sum('credit');
        $credit = locked_credit();

        $invoice = BookingInvestorInvoice::findOrFail($invoice);

        $toPay = investor_to_pay($invoice) - $invoice->locked_paid;

//        if ($toPay > $credit){
//            return response()->json('الرصيد غير كافي.', 500);
//        }

        return DB::transaction(function() use ($invoice, $credit, $toPay){

//            Wallet::create([
//                'user_id' => auth()->id(),
//                'credit' => '-'.($toPay),
//            ]);

            Wallet::create([
                'user_id' => auth()->id(),
                'credit' => '-'.min($toPay, $credit),
                'type' => 'booking_downpayment_locked',
                'model_id' => $invoice->id
            ]);

            @create_booking_history_record($invoice->id, 'BookingInvestorInvoice', 'paid_from_locked', min($toPay, $credit));

            if ($credit >= $toPay) {
                $invoice->status = 1;
                $invoice->locked_paid = null;

                if ($invoice->save())
                    return response()->json('تم الدفع بنجاح.');

            }else {
                $invoice->locked_paid = $credit + $invoice->locked_paid;

                if ($invoice->save())
                    return response()->json('تم الدفع من المحجوز برجاء استكمال باقي المبلغ عن طريق الدفع بمدى.');
            }

            return response()->json('الرصيد غير كافي.', 500);
        });
    }

    public function withdraw(){

//        if(!$this->checkIfThursday())
//            return redirect()->back()->withError('مسموح بسحب المبالغ فقط يوم الخميس من الساعة 00:00 الى الساعة 23:59.');

        $bank_info = BankingInfo::where('user_id', \auth()->id())->first();

        return view('Credit.Withdraw', [
            'page_title' => 'طلب التسييل',
            'bank_info' => $bank_info
        ]);
    }

    public function requestCredit(Request $request){

        if(!$this->checkIfThursday())
            return redirect()->back()->withError('مسموح بسحب المبالغ فقط يوم الخميس من الساعة 00:00 الى الساعة 23:59.');

        $user = auth()->id();
        $credit = clone $credit_sum = clone $locked_credit = Wallet::where('user_id', auth()->id());

        $credit_sum = $credit_sum->withdrawableCredit()->sum('credit');

        $invoices = clone $bookings_total = clone $profit = BookingInvoice::whereHas('booking.unit.user', function ($q){
            $q->where('id', \auth()->id());
        });

        $bookings_total = $bookings_total->sum('total');
        $profit         = $profit->sum('booking_profit');

        $locked_credit = $locked_credit->lockedCredit()->sum('credit');

        $data = [
            'bookings_total' => $bookings_total,
            'profit_total' => $profit,
            'locked_total' => $locked_credit,
            'withdrawable_total' => $credit_sum,
        ];

        $cash_create = CashRequest::create([
            'user_id' => $user,
            'amount' => $credit_sum,
            'holder_name' => $request->holder_name,
            'bank_name' => $request->bank_name,
            'bank_account' => $request->bank_account,
            'iban' => $request->iban,
            'extra' => serialize($data)
        ]);

        foreach ($credit->withdrawableCredit()->get() as $cred) {
            $cred->update([
                'request_id' => $cash_create->id
            ]);
        }

        if ($cash_create) {
            return redirect()->to(investor_url('credit/history'))->with('success', 'تم ارسال الطلب بنجاح.');
        }

        return redirect()->back()->withError('هناك مشكلة في تقديم طلبك برجاء المحاولة لاحقا.');

    }

    public function history(){
        $requests = CashRequest::where('user_id', auth()->id())->orderBy('id', 'DESC')->paginate();
        return view('Credit.History', compact('requests'));
    }

    private function checkIfThursday(){
//        return true;
        return (new Carbon())->dayOfWeek == Carbon::THURSDAY;
    }
}
