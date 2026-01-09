<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\v1\ContractsResource;
use App\Models\Contract;
use App\Models\contractService;
use App\Models\History;
use App\Models\Setting;
use App\Models\Unit;
use App\Models\User;
use App\Notifications\ContractNotifications;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\generateAPI;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ContractsController extends Controller
{
    use generateAPI;

    public function index(){

        $activeCount = Contract::where('user_id', auth()->id())->active()->count();
        $signedCount = Contract::where('user_id', auth()->id())->signed()->count();
        $allCount = Contract::where('user_id', auth()->id())->count();

        $contracts = Contract::where('user_id', auth()->id());

        if (request()->type === 'active'){
            $contracts = $contracts->active();
        }else if(request()->type === 'signed') {
            $contracts = $contracts->signed();
        }

        if (request()->search != ''){
            $contracts = $contracts->where('code', 'like' , '%'.request()->search.'%');
        }

        $contracts = $contracts->with('unit', 'beach', 'sector')->orderBy('id', 'DESC')->paginate(15);

        return $this->success([
            'statics' => [
                'no_of_contracts' => $allCount,
                'active_contracts' => $activeCount,
                'signed_contracts' => $signedCount
            ],
            'data' => ContractsResource::collection($contracts),
            "current_page" => $contracts->currentPage(),
            "total" => $contracts->lastPage(),
            "next_page_url" => $contracts->nextPageUrl(),
            "previous_page_url" => $contracts->previousPageUrl()
        ]);
    }

    public function store(Request $request){

        $data = [
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
            'tenant_nationality' => 'required|max:191',
            'with_tenant_nationality' => 'required|max:191',
            'insurance_value' => 'required|numeric|max:1000000',
        ];

        $validator = Validator::make($request->all(), $data);

        // Check Validation
        if ($validator->fails()){
            return $this->error($validator->errors());
        }

        return DB::transaction(function () use ($request) {

            $settings = Setting::first();

            $price_before = $settings->price_before_vat;
            $price_after = $settings->price_after_vat;
            $vat = vat_without_percent($price_before, $price_after);

            $unit = Unit::findOrFail($request->unit_id);

            $data = [
                'unit_id' => $unit->id,
                'sector_id' => $unit->sector_id,
                'beach_id' => $unit->beach_id,

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

            if (!checkAvailability($data['from'], $data['to'], $request->unit_id))
                return $this->error(['error' => 'هناك عقد في نفس الفترة.']);

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
            $is_saved = $contract->save();

            $users = User::where('role' ,'admin')->get();

            foreach ($users as $user)
                @$user->notify(new ContractNotifications($contract));

            // Add the event to history
            History::create([
                'hismodel_id' => $contract->id,
                'hismodel_type' => 'App\Models\Contract',
                'type' => 'create',
                'user_id' => auth()->id(),
                'created_at' => Carbon::now(),
                'updated_at' => ''
            ]);

            if ($is_saved) {
                return $this->success([
                    'verify_phone' => url('contract/'.contractMix($contract).'/verifyPhone'),
                    'message' => 'تم انشاء العقد بنجاح.'
                ]);
            }

            return $this->error(['error' => 'حدثت مشكلة اثناء انشاء العقد ، برجاء التحقق من المشاكل وحلها.']);
        });
    }

    public function update() {

    }

    public function destroy(Request $request, $contract_id) {
        $contract = Contract::findOrFail($contract_id);

        return $this->success([
            'message' => 'تم الغاء العقد بنجاح.'
        ]);
    }
}
