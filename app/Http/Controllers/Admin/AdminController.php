<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('sectorAdmin');
    }

    public function edit(){
        $user = auth()->user();
        $page_title = 'تعديل الملف الشخصي';

        return view('admin.users.edit', compact('user', 'page_title'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'email' => [
                Rule::unique('users')->ignore($user->id)
            ]
        ]);

        $user->email = $request->email;
        if ($request->password != '')
            $user->password = Hash::make($request->password);

        if ($user->save())
            return redirect()->back()->withSuccess('تم حفظ البيانات بنجاح.');
    }
}
