<?php

namespace App\Http\Controllers;

use App\Models\BankingInfo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankInfoController extends Controller
{
    public function getBank(){
        $user = auth()->user();

        return view('auth.banks.edit', [
            'page_title' => 'تعديل بيانات الحسابات البنكية',
            'user' => $user
        ]);
    }

    public function updateBank(Request $request){
        $request->validate([
            'holder_name.*' => 'required',
            'bank_name.*' => 'required',
            'bank_account.*' => 'required',
            'iban.*' => 'required',
            'down_payment' => 'required|numeric'
        ]);

        $bank_info = [];

        foreach ($request->bank_name as $key => $bank_name){
            $bank_info[$key]['user_id'] = auth()->id();
            $bank_info[$key]['holder_name'] = $request->holder_name[$key];
            $bank_info[$key]['bank_name'] = $bank_name;
            $bank_info[$key]['bank_account'] = $request->bank_account[$key];
            $bank_info[$key]['iban'] = $request->iban[$key];
        }

        return DB::transaction(function () use ($bank_info, $request){
            BankingInfo::where('user_id', auth()->id())->delete();
            $updated = BankingInfo::insert($bank_info);

            User::findOrFail(auth()->id())->update([
                'down_payment' => $request->down_payment
            ]);

            return $this->messages($updated);
        });
    }
}
