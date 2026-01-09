<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class authController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function loginForm(){
        return view('admin.login');
    }

    public function login(Request $request){
//        $user = User::where('phonenumber', $request->phonenumber)->first();

        if (Auth::attempt(['phonenumber' => $request->phonenumber, 'password' => $request->password]))
            return redirect(admin_url());

        return redirect()->back()->with('error', '');
    }

    public function registerForm(){
        return redirect();
    }
}
