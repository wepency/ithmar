<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\v1\CashRequestResource;
use App\Models\BankingInfo;
use App\Models\BookingInvoice;
use App\Models\CashRequest;
use App\Models\Wallet;
use App\Traits\generateAPI;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    use generateAPI;

    public array $downpayment = [
        'booking_downpayment_locked',
        'booking_downpayment_total',
        'booking_downpayment',

        'booking_cancelled_locked_with-commission',
        'booking_cancelled_locked_without-commission',
        'booking_cancelled_withdrawable_with-commission',
        'booking_cancelled_withdrawable_without-commission'
    ];

    public function index(){
        $wallet =
        clone $added_credit =
        clone $cashback =
        clone $total =
        clone $locked = Wallet::where('user_id', auth()->id());

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

        return $this->success([
            'statics' => [
                'credit_total' => $credit_total,
                'added_credit' => $added_credit,
                'locked_credit' => $locked_credit,
                'withdrawable_credit' => $withdrawable_credit
            ],
            'credits' => $this->creditCollection($credits_obj),
            "current_page" => $credits->currentPage(),
            "total" => $credits->lastPage(),
            "next_page_url" => $credits->nextPageUrl(),
            "previous_page_url" => $credits->previousPageUrl()
        ]);
    }

    private function creditCollection($credits_obj) : array
    {
        $collection = [];

        foreach ($credits_obj as $key => $credit){
            $credit_plain = $credit->credit ?? 0;

            $collection[$key]['color'] = $this->getColor($credit);
            $collection[$key]['credit_plain'] = currency($credit_plain);
            $collection[$key]['type'] = !is_null($credit->type) ? trans('validation.wallet_types.' . $credit->type) : 'غير معروف';

            if ($credit->type == 'investor_contract' || $credit->type == 'contract') {
                if ($credit->contract)
                    $collection[$key]['sub_type'] = $credit->contract->code;

            } elseif (in_array($credit->type, $this->downpayment)) {
                if($credit->type == 'booking_downpayment_locked' && $credit->credit < 0)
                    $collection[$key]['sub_type'] = 'تم استخدام الرصيد في دفع فاتورة رقم '.pad_code($credit->model_id);
                else
                    $collection[$key]['sub_type'] = 'حجز رقم '.pad_code($credit->model_id);
            }

            $collection[$key]['time'] = $credit->created_at->diffForHumans(\Carbon\Carbon::now());
            $collection[$key]['time_format'] = $credit->created_at->format('d/m/Y H:i:s');
        }

        return $collection;
    }

    private function getColor($credit){
        if (in_array($credit->type, $this->downpayment)) {
            return match ($credit->type) {
                'booking_downpayment_locked' => '#9b59b6',
                'booking_downpayment_total' => '#2ecc71',
//                'booking_downpayment' => '#3498db',
                default => '#3498db'
            };

        }else {
            return $credit->credit > 0 ? '#2ecc71' : '#e74c3c';
        }
    }

    public function bank(){
//        if(!checkIfThursday())
//            return $this->error(['error' => trans('validation.thursday')]);

        return $this->success([
            BankingInfo::where('user_id', \auth()->id())->first()
        ]);
    }

    public function history(){
        $requests = CashRequest::where('user_id', auth()->id())->orderBy('id', 'DESC')->paginate();

        return $this->success([
            'data' => CashRequestResource::collection($requests),
            "current_page" => $requests->currentPage(),
            "total" => $requests->lastPage(),
            "next_page_url" => $requests->nextPageUrl(),
            "previous_page_url" => $requests->previousPageUrl()
        ]);
    }

    public function withdrawRequest(Request $request)
    {
//        if(!checkIfThursday())
//            return $this->error(['error' => 'يتم استلام طلبات التسييل يوم الخميس فقط.']);

        $user = auth()->id();
        $credit = clone $credit_sum = clone $locked_credit = Wallet::where('user_id', auth()->id());

        $credit_sum = $credit_sum->withdrawableCredit()->sum('credit');

        $bookings_total = clone $profit = BookingInvoice::whereHas('booking.unit.user', function ($q){
            $q->where('id', auth()->id());
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
            return $this->success(['message' => 'تم ارسال الطلب بنجاح.']);
        }

        return $this->error(['error' => 'هناك مشكلة في تقديم طلبك برجاء المحاولة لاحقا.']);
    }

    public function addBalance(Request $request){
        if ($request->result === 'Successful') {
            Wallet::create([
                'credit' => $request->amount,
                'user_id' => auth()->id(),
                'type' => 'investor_add'
            ]);

            return $this->success(['message' => 'تم اضافة الرصيد بنجاح.']);
        }

        return $this->error(['error' => 'حدثت مشكلة ما اثناء الدفع، برجاء المحاولة مرة أخرى..']);
    }
}
