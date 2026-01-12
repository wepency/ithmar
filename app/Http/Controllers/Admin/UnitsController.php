<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Beach;
use App\Models\Setting;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UnitsController extends Controller
{
    public function __construct()
    {
        $this->middleware('sectorAdmin');
    }

    public function index(Request $request)
    {
        $rows = new Unit;

        if (is_sector_admin()){
            $rows = $rows->where('sector_id', auth()->user()->role_id);
        }

        if (\request()->user != ''){
            $rows = $rows->where('user_id', base64_decode(\request()->user));
        }

        if (\request()->unit_number != ''){
            $rows = $rows->where('unit_number', 'like', '%'.\request()->unit_number.'%');
        }

        if (\request()->beach != ''){
            $rows = $rows->where('beach_id', \request()->beach);
        }

        if ($request->type != ''){
            if ($request->type == 'expired'){
                $rows = $rows->expired();
            }elseif ($request->type == 'terminated'){
                $rows = $rows->terminated();
            }
        }

        $beaches = Beach::select('id', 'beach', 'sector_id')->with('sector:id,sector_name')->orderBy('sector_id', 'ASC')->get();

        $rows = $rows->with('attachments')->where('status', '>', 0)->paginate(10);
        
        $settings = Setting::first();

        return view('admin.units.all', compact('rows', 'beaches', 'settings'));
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        $page_title = 'سجل الوحدة';
        $history = (new \App\Classes\History)->getAllHistory('App\Models\Unit', $id, 'units');
        return view('admin.history', compact('history', 'page_title'));
    }

    public function update(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);

        $unit->valid_to = Carbon::parse(request()->valid_to)->format('Y-m-d');
        $unit->price = $request->price ?? 0;
        $unit->vat = $request->vat ?? 0;
        $unit->total = $request->total ?? 0;

        if ($unit->save()){
            return redirect()->back()->withSuccess('تمت العملية بنجاح.');
        }

        return redirect()->back()->withError('هناك مشكلة في اضافة الوحدة.');
    }

    public function destroy($id)
    {

    }
}
