<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\History;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserWalletController extends Controller
{
    public function index($user_id){

        $wallet = clone $added_credit = clone $cashback = clone $total = clone $locked = Wallet::where('user_id', $user_id);

        $user = User::findOrFail($user_id);

        $credit_total = $total->sum('credit');

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

//        dd($credits_obj);
        $added_credit = $added_credit->addedCredit()->sum('credit');

        $withdrawable_credit = $cashback->withdrawableCredit()->sum('credit');

        $locked_credit = $locked->lockedCredit()->sum('credit');

        return view('admin.users.show', [
            'page_title' => 'عرض محفظة '.$user->name,
            'user' => $user,
            'credit_total' => $credit_total,
            'credits' => $credits,
            'credits_obj' => $credits_obj,
            'added_credit' => $added_credit,
            'withdrawable_credit' => $withdrawable_credit,
            'locked_credit' => $locked_credit
        ]);
    }

    public function store(Request $request, $user_id){
        return DB::transaction(function () use ($request, $user_id){
            $credit = abs($request->credit);
            $type = 'admin_add';

            if ($request->credit_type == 'sub') {
                $credit = $credit * -1;
                $type = 'admin_sub';
            }

            $wallet = Wallet::create([
                'user_id' => $user_id,
                'credit' => $credit,
                'type' => $type
            ]);

            $hist = History::create([
                'hismodel_id' => $wallet->id,
                'hismodel_type' => 'App\Models\Wallet',
                'type' => 'update',
                'user_id' => auth()->id(),
                'created_at' => Carbon::now(),
                'updated_at' => null
            ]);

            if ($hist)
                return redirect()->back()->withSuccess('تم اضافة المبلغ بنجاح.');
            else
                return redirect()->back()->withError('هناك مشكلة ما في تعديل الرصيد.');
        });
    }
}
