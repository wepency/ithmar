<?php

namespace App\Http\Controllers;

use App\Models\IP;
use App\Models\twoFactorToken;

use Carbon\Carbon;
use Illuminate\Http\Request;

class TwoFactorController extends Controller
{
    public function validateCode(Request $request){

        $code = implode('', $request->code);

        $token = twoFactorToken::where('user_id', auth()->id())->where('code', $code)->first();

        if (!is_null($token)) {
            auth()->user()->update([
                'factor_validated' => Carbon::now()
            ]);

            // Check if user's ip is registered
            $ip = IP::where('user_id', auth()->id())->where('ip', $request->ip())->first();

            if (is_null($ip)){

                IP::create([
                    'user_id' => auth()->id(),
                    'ip' => $request->ip(),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);

                session()->flash('new-login', trans('messages.new_login'));
            }

            session()->flash('old-login', trans('messages.new_login'));

            if (session()->has('url.intended')){
                $url = session('url.intended');
                session()->forget('url.intended');
                return redirect($url);
            }

            return redirect()->to('/');
        }

        return redirect()->back()->withError('عفوا الكود غير صحيح.');
    }

    public function resend(){
        twoFactorToken::where('user_id', auth()->id())->delete();

        $code = rand(1111, 9999);

        twoFactorToken::create([
            'user_id' => auth()->id(),
            'code' => $code
        ]);

        $phonenumber = '966'.ltrim(auth()->user()->phonenumber, (auth()->user()->phonenumber[0] ?? ''));
      
        @sendSMSBody($phonenumber, "{$code}كود تسجيل الدخول لحساب المستثمر على درة العروس: ");
        return redirect()->to('validate')->withSuccess('تم اعادة ارسال الكود بنجاح.');
    }
}
