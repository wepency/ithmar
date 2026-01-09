<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BookingDate;
use App\Models\Contract;
use App\Models\Token;
use App\Models\User;
use App\Notifications\contractNotification;
use Illuminate\Http\Request;

class validateContract extends Controller
{
    public function sms($phone_number){
        $code = rand(111111, 999999);
        $phone_number = getPhoneNumber($phone_number);

        try{
            Token::where('phonenumber', $phone_number)->delete();
        }catch (\Exception $e){

        }

        $token = Token::create([
            'phonenumber' => $phone_number,
            'token' => $code
        ]);

        if($token){
            sendSMS($phone_number, $code);
            return response()->json('done');
        }
    }

        public function validateCode(Request $request){
        $phonenumber = getPhoneNumber($request->phonenumber);
        $token = $request->token;

        $token = Token::where('token', $token)->where('phonenumber', $phonenumber)->first();

//        return response()->json($token ,500);

//        return response()->json(contractMixDecode($request->code),500);

        if(!is_null($token)){
            $code = contractMixDecode($request->code);

//            return response()->json($code ,500);

            $contract = Contract::where('code', $code)
                                ->whereNull('phonenumber')
                                ->where('status', 0)
                                ->whereNull('is_cancelled')
                                ->first();

//            return response()->json($contract ,500);

            if (!is_null($contract)){
                $type = 'unpaid';
                $status = 0;
                $user = User::findOrFail($contract->user_id);

                if ($user->user_free){
                    $type = 'pay_later';
                    $status = 1;
                }

                if ($user->user_exempt){
                    $type = 'exempt';
                    $status = 1;
                }

                cache()->forget('contracts-count');

                $contract->update([
                    'phonenumber' => $phonenumber,
                    'payment_type' => $type,
                    'status' => $status
                ]);

                if ($status == 1){
                    if ($contract->unit->unit){
                        BookingDate::create([
                            'unit_id ' => $contract->unit->unit->id,
                            'from' => $contract->from,
                            'to' => $contract->to,
                            'type' => 'contract'
                        ]);
                    }
                }

                return response()->json();
            }
        }

        return response()->json('هذا الكود غير صحيح ، برجاء التأكد من الكود المرسل.', 500);
    }

    public function validateContract(Request $request, $contract){
        $phonenumber = $request->phonenumber;
        $code = $request->verification_code;

        $contract = Contract::where('code', $contract)->first();

        $user = auth()->user();

        $type = 'unpaid';
        $status = 0;

        if ($user->user_free){
            $type = 'pay_later';
            $status = 1;
        }

        if ($user->user_exempt){
            $type = 'exempt';
            $status = 1;
        }

        $contract->phonenumber = $phonenumber;
        $contract->payment_type = $type;
        $contract->status = $status;
        $contract->save();

        cache()->forget('contracts-count');

        $token = Token::where('phonenumber', $phonenumber)->delete();

        $users = User::where('role', 'admin')->get();

//        foreach ($users as $user){
//            $user->notify(new contractNotification($contract));
//        }

        return redirect()->to(investor_url('contracts?type=signed'))->with('message', 'تم اضافة العقد بنجاح و بانتظار الموافقة.');
    }
}
