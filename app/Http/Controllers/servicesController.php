<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\userService;

class servicesController extends Controller
{

    public function index()
    {
        $page_title = 'عرض الخدمات';

        $services = Service::orderBy('service_name', 'ASC')->get();

        $user_services = User::with('services')->findOrFail(auth()->id());

        return view('Services.index', [
            'services' => $services,
            'user_services' => $user_services->services,
            'page_title' => $page_title
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|numeric'
        ]);

        $check = userService::where('user_id', auth()->id())->where('service_id', $request->service_id)->first();

        if (is_null($check)) {
            $userService = userService::create([
                'user_id' => auth()->id(),
                'service_id' => $request->service_id
            ]);

            cache()->forget('services-' . Auth::id());

            if ($userService)
                return redirect()->back()->withSuccess('تمت إضافة الخدمة بنجاح و ستضاف الان إلى جميع العقود.');

            return redirect()->back()->with('error', 'هناك مشكلة في اضافة الخدمة ، برجاء التواصل مع الإدارة.');
        }

        return redirect()->back()->with('error', 'هذه الخدمة مضافة بالفعل.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        $delete = userService::findOrFail($id)->delete();

        cache()->forget('services-' . Auth::id());

        if ($delete) {
            return redirect()->back()->withSuccess('تم الحذف بنجاح.');
        }

        return redirect()->back()->with('error', 'هناك مشكلة في الحجز.');
    }
}
