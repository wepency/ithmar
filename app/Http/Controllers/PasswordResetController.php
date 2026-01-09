<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function getForm(){
        return view('auth.reset');
    }

    public function postReset(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users'
        ], [
            'email' => 'البريد الإلكتروني'
        ]);

        if ($validator->fails()){
            return redirect()->to('/?error-form=password')
                ->withErrors($validator)
                ->withInput();
        }

        $token = Str::random(64);

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        Mail::send('mail.forgot-password', ['token' => $token] , function($message) use ($request){
            $message->to($request->email);
            $message->subject('استعادة كلمة المرور');
        });

        return redirect()->back()->withSuccess('تم إرسال الرسالة إلى بريدكم الإلكتروني.');
    }

    public function showResetPasswordForm($token){
        $reset = DB::table('password_resets')->where('token', $token)->first();

        if (!$reset)
            abort(404);

        return view('auth.password', ['token' => $token, 'email' => $reset->email]);
    }

    public function submitResetPasswordForm(Request $request){
        $request->validate([
            'password' => 'required|confirmed|min:8'
        ]);

        $check = DB::table('password_resets')->where([
            'email' => $request->email,
            'token' => $request->token,
        ])->first();

        if (is_null($check))
            return redirect()->back()->withInput()->with('error', 'الرابط غير صالح برجاء تجربة استرجاع كلمة المرور مرة أخرى.');

        $user = User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email'=> $request->email])->delete();

        return redirect('login')->with('message', 'تم تغيير كلمة المرور بنجاح.');
    }
}
