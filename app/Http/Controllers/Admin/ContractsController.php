<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Contract;
use App\Models\contractService;
use App\Models\History;
use App\Models\Setting;
use App\Models\Token;
use App\Models\Unit;
use App\Notifications\contractNotification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\Beach;
use App\Models\Sector;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class ContractsController extends Controller
{

    public function __construct()
    {
        $this->middleware('sectorAdmin');
    }

    private function filter($q)
    {
      if(request('from') AND request('to'))
      {
        $from = str_replace('-','/',request('from'));
        $to = str_replace('-','/',request('to'));

        return $q->whereDate('from', '>=', $from)->whereDate('to', '<=', $to)->get();
      }
    }

    private function filterValid($q){

    }

    public function index(Request $request)
    {
        $today = Carbon::now()->toDateString();
        $today = str_replace('-','/',$today);

      if(is_admin())
      {
        $rows = Contract::with('unit', 'sector', 'beach', 'user')->where(function ($q){
          return $this->filter($q);
        });

        if ($request->sector != ''){
            $rows = $rows->where('sector_id', $request->sector);

        }

          if ($request->beach != ''){
              $rows = $rows->where('beach_id', $request->beach);
          }

          if ($request->unit != ''){
              $rows = $rows->where('unit_id', $request->unit);
          }

        if ($request->code != ''){
            $rows = $rows->where('code', 'like', '%'.$request->code.'%');
        }

        if ($request->phonenumber != ''){
            $rows = $rows->whereHas('user', function (Builder $builder) use ($request){
                $builder->where('phonenumber', 'like', '%'.$request->phonenumber.'%');
            });
        }

        if ($request->payment_type != ''){
            $rows = $rows->where('payment_type', $request->payment_type);
        }

        $sectors = Sector::orderby('sector_name', 'ASC')->get();
        $beachs = Beach::all();
      }else{
          $sectors = [];
        $beachs = Beach::where('sector_id', auth()->user()->role_id)->get();

        $rows = Contract::with('unit', 'sector', 'beach', 'user')->where(function ($q){
          return $this->filter($q);
        })->where('status', 1)->where('payment_type', '!=', 'exempt')->where('sector_id', auth()->user()->role_id);

//        $today = date("Y-m-d");
      }

      // Contract Day Count

        if ($request->type == 'last') {
            $rows = $rows->whereBetween('created_at', [Carbon::today() , Carbon::tomorrow()->subMinute()])
                ->valid();
        }

      // Enter Today Count
        if ($request->type == 'enter-today'){
            $rows = $rows->where(function ($q){
                $this->filter($q);
            })->valid()->whereDate('from', $today);
        }


        // Leave Today Count
        if ($request->type == 'leave-today'){
            $rows = $rows->where(function ($q){
                $this->filter($q);
            })->valid()->whereDate('to', $today);
        }

        $rows = $rows->where('is_accepted', 1)->orderby('id', 'DESC')->paginate();

        if ($request->ajax()) {
            return response()->json([
                'data' => view('admin.contract.table', [
                    'rows' => $rows,
                    'beachs' => $beachs,
                    'sectors' => $sectors
                ])->render()
            ]);
        }

        return view('admin.contract.index',[
          'rows' => $rows,
          'beachs' => $beachs,
          'sectors' => $sectors
        ]);
    }

    public function create()
    {
        $page_title = 'إضافة حجز';
        $contract = new Contract;
        $sectors  = Sector::get();
        $beaches  = Beach::where('sector_id', $contract->sector_id)->get();
        $units    = Unit::where('beach_id', $contract->beach_id)->get();

        return view('admin.contract.edit', compact('contract', 'sectors', 'beaches', 'units', 'page_title'));
    }

    public function store(Request $request)
    {
        $validate = [
            'sector_id' => 'required',
            'beach_id' => 'required',
            'unit_id' => 'required',
            'from' => 'required',
            'to' => 'required',
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
            'insurance_value' => 'required|max:1000000',
            'phonenumber' => 'required|min:8|max:15',
            'car' => 'required|array'
        ];

        $request->validate($validate);

        return DB::transaction(function () use ($request){
            $contract = new Contract;
            return $this->addOrCreate($contract, $request);
        });

//        $data['unit_id'] = $request->unit_id;
//        $data['sector_id'] = $request->sector_id;
//        $data['beach_id'] = $request->beach_id;

//        $data['tenant_name_code'] = $request->tenant_name_code;
//        $data['tenant_nationality'] = $request->tenant_nationality;
//        $data['with_tenant_name_code'] = $request->with_tenant_name_code;
//        $data['with_tenant_nationality'] = $request->with_tenant_nationality;
//        $data['insurance_value'] = $request->insurance_value;

//        $data['from'] = Carbon::parse($request->from.' 15:00:00')->format('Y-m-d H:i:s');
//        $data['to'] = Carbon::parse($request->to.' 15:00:00')->format('Y-m-d H:i:s');

//        $data['price'] = $price_before;
//        $data['vat']   = (($price_after - $price_before) / $price_before) * 100;
//        $data['total'] = $price_after;
    }

    // Store & Update in one function
    protected function AddOrCreate($contract, $request){

        $unit = Unit::with('user', 'user.services', 'sector')->findOrFail($request->unit_id);

        $settings = Setting::first();

        if ($request->attachment_1){
            $file_1 = $request->file('attachment_1');
            $filename_1 = Str::slug($file_1->getClientOriginalName()).'-'.rand(1111,9999).'.'.$file_1->getClientOriginalExtension();
            $file_1->move('uploads', $filename_1);
            $data['attachment_1'] = $filename_1;
        }

        if ($request->attachment_2){
            $file_2 = $request->file('attachment_2');
            $filename_2 = Str::slug($file_2->getClientOriginalName()).'-'.rand(1111,9999).'.'.$file_2->getClientOriginalExtension();
            $file_2->move('uploads', $filename_2);
            $data['attachment_2'] = $filename_2;
        }

        // Default values from settings
        $price_before = $settings->price_before_vat;
        $price_after = $settings->price_after_vat;

        $calculateFinalPrices = getContractPriceAndTotal($unit, $price_before, $price_after);

        $final_price = $calculateFinalPrices['price'];
        $final_vat = $calculateFinalPrices['vat'];
        $final_total = $calculateFinalPrices['total'];

        // Contracts
        $data['unit_id'] = $request->unit_id;
        $data['sector_id'] = $request->sector_id;
        $data['beach_id'] = $request->beach_id;
        $data['user_id'] = $unit->user_id;

        $data['tenant_title'] = $request->tenant_title;
        $data['tenant_name'] = $request->tenant_name;
        $data['tenant_name_code'] = $request->tenant_name_code;
        $data['tenant_nationality'] = $request->tenant_nationality;

        $data['with_tenant_title'] = $request->with_tenant_title;
        $data['with_tenant_name'] = $request->with_tenant_name;
        $data['with_tenant_name_code'] = $request->with_tenant_name_code;
        $data['with_tenant_nationality'] = $request->with_tenant_nationality;

        $data['insurance_value'] = $request->insurance_value;
        $data['rent_value'] = $request->rent_value;

        $data['from'] = Carbon::parse($request->from.' 15:00:00')->format('Y-m-d H:i:s');
        $data['to'] = Carbon::parse($request->to.' 15:00:00')->format('Y-m-d H:i:s');

        $data['phonenumber'] = $request->phonenumber;

        if (!$contract->exists){
            $data['code']  = get_code();
            $data['token'] = time() + rand(1111,9999);

            $data['price'] = $final_price;
            $data['vat']   = $final_vat;
            $data['total'] = $final_total;

            $data['status'] = 0;
            $data['is_accepted'] = 1;

            $data['payment_type'] = 'phone';
        }

        if (!checkAvailability($data['from'], $data['to'], $request->unit_id, $contract)){
            return  redirect()->back()
                ->withInput($request->input())
                ->with('error', 'هناك حجز أخر في نفس الفتره برجاء المحاولة مجددا.');
        }

        $contract_exists = $contract->exists;

        if ($contract_exists)
            $contract_obj = $contract->update($data);
        else
            $contract = Contract::create($data);

        $contract_id = $contract->id;

        // Cars
        $carArray = [];
        $i = 0;

        // Delete all records if we are updating
        if ($contract_exists){
            Car::where('contract_id', $contract->id)->delete();
        }

        if (!empty($request->car)){
            foreach ($request->car as $c){
                if ($c['type'] != '' || $c['serial'] != '' || $c['passenger_name'] != '' || $c['identity'] != ''){
                    $carArray[$i]['car_type'] = $c['type'];
                    $carArray[$i]['car_serial'] = isset($c['serial']) ? $c['serial'] : '';
                    $carArray[$i]['passenger_name'] = isset($c['passenger_name']) ? $c['passenger_name'] : '';
                    $carArray[$i]['identity'] = isset($c['identity']) ? $c['identity'] : '';
                    $carArray[$i]['contract_id'] = $contract_exists ? $contract->id : $contract_id;
                }

                $i++;
            }

            DB::table('cars')->insert($carArray);
        }

        // Services
        $services = @count($unit->user->services) > 0 ? $unit->user->services->toArray() : '';
        $services_total = @count($unit->user->services) > 0 ? $unit->user->services()->sum('price') : 0;

        if (!is_null($services)) {
            contractService::create([
                'contract_id' => $contract_id,
                'service_data' => serialize($services),
                'service_total' => $services_total
            ]);
        }

        $contract->services_total = $services_total;
        $contract->save();

        $redirect = admin_url('contract');

        // if Not updating the contract Send SMS Token
        if (!$contract_exists || $contract->phonenumber !== $request->phonenumber){
            addPhoneValidateToken($request->phonenumber);
            $redirect = admin_url('contract/'.$contract->id.'/validateNumber');
        }

        // Add the event to history
        History::create([
            'hismodel_id' => $contract->id,
            'hismodel_type' => 'App\Models\Contract',
            'type' => $contract_exists ? 'update' : 'create',
            'user_id' => auth()->id(),
            'created_at' => Carbon::now(),
            'updated_at' => ''
        ]);

        return redirect()->to($redirect)->withSuccess('عملية ناجحة');
    }

    public function validateNumber($id){
        $contract = Contract::findOrFail($id);
        return view('admin.contract.validateNumber', compact('contract'));
    }

    public function checkCode(Request $request, $id){
        $contract = Contract::with('unit', 'unit.user')->findOrFail($id);
        $token = Token::where('phonenumber', $contract->phonenumber)->where('token', $request->code)->orderBy('id', 'DESC')->limit('1')->first();

        $type = 'unpaid';
        $status = 0;

        if ($contract->unit->user->user_free){
            $type = 'pay_later';
            $status = 1;
        }

        if ($contract->unit->user->user_exempt){
            $type = 'exempt';
            $status = 1;
        }

        $contract->update([
            'payment_type' => $type,
            'status' => $status
        ]);

        if (is_null($token))
            return redirect()->back()->with('error', 'هذا الكود خاطئ برجاء التأكد من الكودالمدخل.');

        return redirect()->to(admin_url('contract'))->with('success', 'تم حفظ العقد بنجاح.');
    }

    public function resendCode($id){
        $contract = Contract::findOrFail($id);
        addPhoneValidateToken($contract->phonenumber);
        return redirect()->back()->withSuccess('تم ارسال الكود بنجاح.');
    }

    public function show(Contract $contract, $code)
    {
//        return $code;
        $contract = Contract::where('code', $code)->first();

        if (request()->not != ''){
            auth()->user()->unreadNotifications->where('id', request()->not)->markAsRead();
        }

        if (is_null($contract))
            abort(404);

        return view('admin.contract.show', compact('contract'));
    }

    public function edit($id)
    {
        $contract = Contract::findOrFail($id);
        $page_title = 'تعديل العقد '.$contract->code;
        $sectors  = Sector::get();
        $beaches  = Beach::where('sector_id', $contract->sector_id)->get();
        $units    = Unit::where('beach_id', $contract->beach_id)->get();

//        if ()
        return view('admin.contract.edit', compact('contract', 'page_title', 'sectors', 'beaches', 'units'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'sector_id' => 'required',
            'beach_id' => 'required',
            'unit_id' => 'required',
            'from' => 'required',
            'to' => 'required',
            'tenant_name' => 'required|max:191',
            'tenant_name_code' => 'required|size:10',
            'with_tenant_title' => 'required|max:191',
            'with_tenant_name' => 'required|max:191',
            'with_tenant_name_code' => 'required|size:10',
            'rent_value' => 'required|numeric|max:100000',
            'rental_barcode_image' => 'nullable|image',
            'tenant_nationality' => 'required|max:191',
            'with_tenant_nationality' => 'required|max:191',
            'insurance_value' => 'required|max:100000',
            'phonenumber' => 'required|min:8|max:15',
            'car' => 'required|array'
        ]);

        return DB::transaction(function () use ($request, $id){
            $contract = Contract::findOrFail($id);
            return $this->addOrCreate($contract, $request);
        });
    }

    public function getHistory($id)
    {
        $history = (new \App\Classes\History)->getAllHistory('App\Models\Contract', $id, 'contracts');
        return view('admin.contract.history', compact('history'));
    }

    public function cancel($id){
        $contract = Contract::findOrFail($id);

        $updated = $contract->update([
            'is_cancelled' => 1,
            'payment_type' => 'unpaid'
        ]);

        if ($updated){
            return redirect()->back()->with('message', 'تم إلغاء العقد بنجاح.');
        }

        return redirect()->back()->with('error', 'هناك مشكلة في إنشاء العقد ، برجاء المحاولة لاحقاََ.');
    }

    public function changeStatus(Request $request, $contract_id) {
        $contract = Contract::findOrFail($contract_id);

        $status = $request->contract_status == 'paid' ? 1 : null;

        if ($contract->update(['payment_type' => $request->contract_status, 'status' => $status]))
            return redirect()->back()->with('message', 'تم تعديل حالة العقد بنجاح.');

        return redirect()->back()->with('error', 'هناك مشكلة في تعديل العقد ، برجاء المحاولة لاحقاََ.');
    }
}
