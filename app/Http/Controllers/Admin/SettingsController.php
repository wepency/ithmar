<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function form(){
        $settings = Setting::first();
        return view('admin.settings', compact('settings'));
    }

    public function save(Request $request){
        $request->validate([
            'price_before_vat' => 'required',
            'price_after_vat' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'phonenumber' => 'required',
            'website' => 'required',
            'vat' => 'required',
            'confirmation' => 'required|max:49',
        ]);


        $settings = Setting::first();

        $settings->price_before_vat = $request->price_before_vat;
        $settings->price_after_vat = $request->price_after_vat;
        $settings->name = $request->name;
        $settings->phonenumber = $request->phonenumber;
        $settings->website = $request->website;
        $settings->vat = $request->vat;
        $settings->email = $request->email;
        $settings->confirmation = $request->confirmation;
        $settings->whatsapp = $request->whatsapp;

        cache()->forget('settings');

        if ($settings->save())
            return redirect()->back()->with('message', 'تم تعديل بيانات الموقع بنجاح.');

        return redirect()->back()->with('error', 'هناك خطأ ، برجاء المحاولة لاحفاََ.');
    }
}
