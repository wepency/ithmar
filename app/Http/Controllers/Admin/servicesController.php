<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class servicesController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $page_title = '';
        $rows = Service::orderBy('id', 'DESC')->paginate(15);

        return view('admin.services.index', compact('page_title', 'rows'));
    }

    public function store(Request $request)
    {
        $validate = [
            'service_name' => 'required|unique:services|max:191',
            'price' => 'required|numeric|max:10000'
        ];

        if( $this->addOrCreate($request, $validate, new Service) )
            return redirect()->back()->withSuccess('تمت إضافة الخدمة بنجاح.');

        return redirect()->back()->with('error', 'هناك مشكلة في إضافة الخدمة.');
    }

    public function update(Request $request, $id)
    {
        $validate = [
            'service_name' => [
                'required',
                Rule::unique('services')->ignore($id),
                'max:191'
            ],
            'price' => 'required|numeric|max:10000'
        ];

        $service = Service::findOrFail($id);

        if( $this->addOrCreate($request, $validate, $service) )
            return redirect()->back()->withSuccess('تمت تعديل الخدمة بنجاح.');

        return redirect()->back()->with('error', 'هناك مشكلة في تعديل الخدمة.');
    }

    private function addOrCreate($request, $validate, $service){
        $data = $request->validate($validate);

        if (!isset($service->service_name))
            return $service->create($data);

        return $service->update($data);
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id)->delete();

        if ($service)
            return redirect()->back()->withSuccess('تم حذف الخدمة بنجاح.');

        return redirect()->back()->with('error', 'هناك مشكلة في حذف الخدمة.');
    }
}
