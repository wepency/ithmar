<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Support\Str;

class unitsController extends Controller
{
    public function all(){
        $page_title = 'عرض الوحدات';

        $units = Unit::where('status', 1)
            ->whereNull('is_terminated')
            ->where('user_id', auth()->id())
            ->with('sector', 'beach', 'user')
            ->paginate();

        return view('units.all', compact('units', 'page_title'));
    }

    public function edit ($code){
        $id = base64_decode($code);
        $unit = Unit::findOrFail($id);

        $page_title = 'تحديث الوحدة '.$unit->unit_number;
        return view('units.edit', compact('unit', 'page_title'));
    }

    public function update($code){
        $unit = Unit::findOrFail($code);

        $request = request();

        $request->validate([
            'attachment_1' => 'required'
        ]);

        if ($request->has('attachment_1')){
            $file = $request->file('attachment_1');
            $filename = Str::slug($file->getClientOriginalName()).'-'.rand(1111,9999).'.'.$file->getClientOriginalExtension();
            $file->move('uploads/'.$filename);
            $data['attachment_1'] = $filename;
        }

        $count = is_null($unit->count) ? 0 : $unit->count;

        $data['renewed'] = 1;
        $data['count'] = $count + 1;
        $data['status'] = 0;

        if ($unit->update($data))
            return redirect()->to(investor_url('all-units'))->withSuccess('تم ارسال المرفقات الجديدة الى الإدارة.');
    }
}
