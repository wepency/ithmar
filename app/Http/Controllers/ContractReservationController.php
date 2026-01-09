<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\contractService;
use App\Models\History;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ContractReservationController extends Controller
{
    public function save(Request $request){
        $request->validate([
            'sector_id' => 'required',
            'beach_id' => 'required',
            'unit_id' => 'required',
            'from' => 'required',
            'to' => 'required',
            'tenant_title' => 'required|max:191',
            'tenant_name' => 'required|max:191',
            'tenant_name_code' => 'required|size:10',
            'with_tenant_title' => 'required|max:191',
            'with_tenant_name' => 'required|max:191',
            'with_tenant_name_code' => 'required|size:10',
            'rent_value' => 'required|numeric|max:1000000',
            'attachment_1' => 'nullable|image',
            'attachment_2' => 'nullable|image',
            'tenant_nationality' => 'required|max:191',
            'with_tenant_nationality' => 'required|max:191',
            'insurance_value' => 'required|numeric|max:1000000',
            'car' => 'required|array'
        ]);

        return DB::transaction(function () use ($request) {

            $settings = Setting::first();

            $price_before = $settings->price_before_vat;
            $price_after = $settings->price_after_vat;
            $vat = vat_without_percent($price_before, $price_after);

            $data = [
                'unit_id' => $request->unit_id,
                'sector_id' => $request->sector_id,
                'beach_id' => $request->beach_id,

                'tenant_title' => $request->tenant_title,
                'tenant_name' => $request->tenant_name,
                'tenant_name_code' => $request->tenant_name_code,
                'tenant_nationality' => $request->tenant_nationality,

                'with_tenant_title' => $request->with_tenant_title,
                'with_tenant_name' => $request->with_tenant_name,
                'with_tenant_name_code' => $request->with_tenant_name_code,
                'with_tenant_nationality' => $request->with_tenant_nationality,

                'insurance_value' => $request->insurance_value,
                'rent_value' => $request->rent_value,

                'from' => Carbon::parse($request->from . ' 15:00:00')->format('Y-m-d H:i:s'),
                'to' => Carbon::parse($request->to . ' 15:00:00')->format('Y-m-d H:i:s'),

                // Contract MetaData
                'user_id' => auth()->id(),
                'status' => 0,

                'price' => $price_before,
                'vat' => $vat,
                'total' => $price_after,

                'payment_type' => 'phone',
                'phonenumber' => $request->phonenumber,

                'code' => get_code(),
                'token' => time() + rand(1111, 9999),
            ];

            if ($request->has('attachment_1')) {
                $file_1 = $request->file('attachment_1');
                $filename_1 = Str::slug($file_1->getClientOriginalName()) . '-' . rand(1111, 9999) . '.' . $file_1->getClientOriginalExtension();
                $file_1->move('uploads', $filename_1);
                $data['attachment_1'] = $filename_1;
            }

            if ($request->has('attachment_2')) {
                $file_2 = $request->file('attachment_2');
                $filename_2 = Str::slug($file_2->getClientOriginalName()) . '-' . rand(1111, 9999) . '.' . $file_2->getClientOriginalExtension();
                $file_2->move('uploads', $filename_2);
                $data['attachment_2'] = $filename_2;
            }

            if (!checkAvailability($data['from'], $data['to'], $request->unit_id)) {
                return redirect()->back()
                    ->withInput($request->input())
                    ->with('error', 'هناك حجز أخر في نفس الفتره برجاء المحاولة مجددا.');
            }

            $contract = Contract::create($data);

            // Cars
            $carArray = [];
            $i = 0;

            if (!empty($request->car)) {
                foreach ($request->car as $c) {
                    if ($c['type'] != '' || $c['serial'] != '' || $c['passenger_name'] != '' || $c['identity'] != '') {
                        $carArray[$i]['car_type'] = $c['type'];
                        $carArray[$i]['car_serial'] = isset($c['serial']) ? $c['serial'] : '';
                        $carArray[$i]['passenger_name'] = isset($c['passenger_name']) ? $c['passenger_name'] : '';
                        $carArray[$i]['identity'] = isset($c['identity']) ? $c['identity'] : '';
                        $carArray[$i]['contract_id'] = $contract->id;
                    }

                    $i++;
                }

                DB::table('cars')->insert($carArray);
            }

            // Services
            $services = @count(auth()->user()->services) > 0 ? auth()->user()->services->toArray() : '';
            $services_total = @count(auth()->user()->services) > 0 ? auth()->user()->services()->sum('price') : 0;

            if (!is_null($services)) {
                contractService::create([
                    'contract_id' => $contract->id,
                    'service_data' => serialize($services),
                    'service_total' => $services_total
                ]);
            }

            $contract->services_total = $services_total;
            $contract->save();

            $users = User::where('role' ,'admin')->get();

//            foreach ($users as $user)
//                @$user->notify(new ContractNotifications($contract));

            // Add the event to history
            History::create([
                'hismodel_id' => $contract->id,
                'hismodel_type' => 'App\Models\Contract',
                'type' => 'create',
                'user_id' => auth()->id(),
                'created_at' => Carbon::now(),
                'updated_at' => ''
            ]);

            return redirect()->to(url('contract/'.contractMix($contract).'/verifyPhone'))->with('message', 'تم حفظ العقد بنجاح و بإنتظار التفعيل.');
        });
    }
}
