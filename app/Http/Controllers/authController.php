<?php

namespace App\Http\Controllers;

use App\Models\IP;
use App\Models\twoFactorToken;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class authController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function loginForm(){
        return view('login');
    }

    public function login(Request $request){
//        $user = User::where('phonenumber', $request->phonenumber)->first();

        //            if ($this->checkIP()){
//                \auth()->user()->update([
//                    'ip' => $this->checkIP()
//                ]);
//            }

        $user = false;

        if (is_numeric($request->get('phonenumber'))){
            $user = Auth::attempt(['phonenumber' => $request->phonenumber, 'password' => $request->password]);
        }else if (filter_var($request->phonenumber, FILTER_VALIDATE_EMAIL)){
            $user = Auth::attempt(['email' => $request->phonenumber, 'password' => $request->password]);
        }

        if ($user) {

            if (\auth()->user()->two_factor) {
                auth()->user()->update([
                    'factor_validated' => null
                ]);

                twoFactorToken::where('user_id', auth()->id())->delete();

                $code = rand(1111, 9999);

                twoFactorToken::create([
                    'user_id' => auth()->id(),
                    'code' => $code
                ]);

    
                $phonenumber = '966'.ltrim(auth()->user()->phonenumber, (auth()->user()->phonenumber[0] ?? ''));
                @sendSMSBody($phonenumber, "{$code}كود تسجيل الدخول لحساب المستثمر على درة العروس: ");

                return redirect()->to('validate');
            }

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

        return redirect()->back()->with('error', 'هناك خطأ في بيانات تسجيل الدخول.');
    }

    public function checkIP(){
        $ip = $_SERVER['REMOTE_ADDR'];
        $url = 'http://ip-api.com/json/'.$ip;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $response = json_decode($response, true); //because of true, it's in an array
        return $response['countryCode'] != 'SA' ? $response['countryCode'] : false;
    }

    public function registerForm(){
        return view('register');
    }
}
