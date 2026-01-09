<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class userController extends Controller
{
    public function getUser(){
        $page_title = 'تعديل البيانات';
        $user = auth()->user();

        return view('auth.edit', compact('user', 'page_title'));
    }

    public function updateUser(Request $request){
        $user = auth()->user();

        $request->validate([
            'email' => [
                'nullable',
                Rule::unique('users')->ignore($user->id)
                ]
        ]);

        if ($request->email != '')
            $user->email = $request->email;

        if ($request->password != '')
            $user->password = Hash::make($request->password);

        $user->two_factor = $request->two_factor_auth == 'on';

        if ($user->save())
            return redirect()->back()->withSuccess('تم تعديل البيانات بنجاح.');

        return redirect()->back()->withError('حدث خطأ اثناء محاولة تعديل البيانات.');
    }
}
