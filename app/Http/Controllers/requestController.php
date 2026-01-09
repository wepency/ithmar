<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\Sector;
use App\Models\Unit;
use App\Models\User;
use App\Notifications\requestNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class requestController extends Controller
{
    public function __construct()
    {
//        $this->middleware('auth');
    }

    public function getRequest(){
        $sectors = Sector::orderBy('sector_name')->get();
        return view('request', compact('sectors'));
    }

    public function postRequest(Request $request){
        $sector = new Unit;

        $url = url()->previous();
        $redirect_link = strpos($url, '?') > -1 ? explode('?', $url)[0] : $url;
        $redirect_link .= '?error-form=request';

        $validator = Validator::make($request->all(), [
            'unit_number' => 'required',
            'sector_id' => 'required',
            'beach_id' => 'required',
            'attachment_1' => 'required|max:20560|mimes:jpeg,jpg,png,gif,doc,docx,ppt,pdf'
        ], [
            'unit_number' => 'رقم الوحدة',
            'sector_id' => 'قطاع',
            'beach_id' => 'شاطئ',
            'attachment_1' => 'مستند طلب التأهيل'
        ]);

        if ($validator->fails()){
            return redirect()->to($redirect_link)
                ->withErrors($validator)
                ->withInput();
        }

        $units = Unit::where('beach_id', $request->beach_id)->where('unit_number', $request->unit_number)->where(function ($q){
            $q->where('status', 0)->orWhere('status', 1);
        })->first();

        if (!is_null($units)){
            return redirect()->back()
                ->withInput($request->input())
                ->withErrors('هذه الوحدة مسجلة بالفعل في نفس الشاطئ ، برجاء مراجعة رقم الوحدة.');
        }

        $sector->sector_id = $request->sector_id;
        $sector->beach_id = $request->beach_id;
        $sector->unit_number = $request->unit_number;

        $check_unit = Unit::where('unit_number', $request->unit_number)
                                ->where('sector_id', $request->sector_id)
                                ->where('beach_id', $request->beach_id)
                                ->first();

        if (!is_null($check_unit)) {
            if (!$check_unit->is_terminated){
                return  redirect()->back()
                    ->withInput($request->input())
                    ->with('error', 'هذة الوحدة مسجلة بالفعل ، برجاء التأكد من رقم الوحدة.');
            }
        }

        $sector->type = $request->type;

        if ($request->attachment_1 != ''){
            $file = $request->file('attachment_1');
            $filename = Str::slug($file->getClientOriginalName()).'-'.rand(11111, 99999).'.'.$file->extension();
            $file->move('uploads', $filename);
            $sector->attachment_1 = $filename;
        }

        $users = User::where('role', 'admin')->get();

        foreach ($users as $user){
            $user->notify(new requestNotification($sector));
        }

        if (!auth()->check()) {
            $user = new User;

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'phonenumber' => 'required|unique:users',
                'email' => 'required|email|unique:users',
                'password' => 'required'
            ]);

            if ($validator->fails()){
                return redirect()->to($redirect_link)
                    ->withErrors($validator)
                    ->withInput();
            }

            $user->name = $request->name;
            $user->phonenumber = $request->phonenumber;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);

            $user->role = 'investor';
            $user->blocked = 1;
            $user->blocked_note = 'لم يتم قبول الحساب بعد';

            $user->save();

            auth()->attempt(['phonenumber' => $request->phonenumber, 'password' => $request->password]);
        }

        $sector->user_id = auth()->check() ? auth()->id() : $user->id;

        if ($sector->save()){

            History::create([
                'hismodel_id' => $sector->id,
                'hismodel_type' => 'App\Models\Unit',
                'type' => 'create',
                'user_id' => auth()->id(),
                'created_at' => Carbon::now(),
                'updated_at' => ''
            ]);

            return redirect()->to(investor_url('request/success'));
        }

        return redirect()->back()->with('error', 'هناك مشكله في تلقي الطلب ، برجاء المحاولة لاحقاََ.');
    }

    public function success(){
        $page_title = 'تمت العملية بنجاح.';

        return view('request-success', compact('page_title'));
    }

    public function all() {
        $page_title = 'حالة الوحدات';

        $user_id = auth()->id();

        $valid = Unit::where('user_id', $user_id)->valid()->count();
        $expired = Unit::where('user_id', $user_id)->expired()->count();
        $blocked = Unit::where('user_id', $user_id)->blocked()->count();

        $units = Unit::where('user_id', $user_id)
            ->with('sector', 'beach')
            ->orderBy('created_at', 'DESC')
            ->paginate();

//        $expired = Unit::where('user_id', auth()->id())->expired()->count();
//        $terminated = Unit::where('user_id', auth()->id())->terminated()->count();

        return view('all-requests', compact('units', 'page_title', 'expired', 'blocked', 'valid'));
    }
}
