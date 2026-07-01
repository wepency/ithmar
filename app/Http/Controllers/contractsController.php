<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContractsResource;
use App\Models\Car;
use App\Models\ContractCompanion;
use App\Models\contractService;
use App\Models\History;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Sector;
use App\Models\Contract;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class contractsController extends Controller
{

    protected $apiURL;
    protected $apiKey;

    public function __construct()
    {
        $this->middleware('auth')->except('check', 'getByToken', 'getSingleContract', 'verify', 'payValidate');
        //        $this->middleware('FrontEnd');
    }

    public function getContracts()
    {
        $page_title = 'العقود';

        $activeCount = Contract::where('user_id', auth()->id())->active()->count();
        $signedCount = Contract::where('user_id', auth()->id())->signed()->count();
        $allCount = Contract::where('user_id', auth()->id())->count();

        $contracts = Contract::where('user_id', auth()->id());

        if (request()->type === 'active') {
            $contracts = $contracts->active();
        } else if (request()->type === 'signed') {
            $contracts = $contracts->signed();
        }

        if (request()->sorting != '') {
            $contracts = $contracts->where('payment_type', request()->sorting);
        }

        $contracts = $contracts->with('unit', 'beach', 'sector')->orderBy('id', 'DESC')->paginate(15);

        return view('contracts', compact('page_title', 'contracts', 'activeCount', 'signedCount', 'allCount'));
    }

    public function add()
    {
        $page_title = 'إضافة عقد جديد';

        $sectors = Sector::whereHas('unit', function (Builder $q) {
            $q->where('user_id', auth()->id())->where('status', 1);
        })->orderBy('id', 'DESC')->get();

        $refused = Unit::where('status', 2)->where('user_id', auth()->id())->get();

        $expired = Unit::where(function ($q) {
            $q->where('valid_to', '')->orWhere('valid_to', '<', Carbon::today());
        })->where('type', 'investor')->where('user_id', auth()->id())->get();

        $companionsMax = config('contracts.companions.max');
        $companionsMultiSectorId = config('contracts.companions.multi_sector_id');

        return view('add-contract', compact(
            'sectors', 'page_title', 'refused', 'expired',
            'companionsMax', 'companionsMultiSectorId'
        ));
    }

    private function filter($q)
    {
        if (request('beach')) {
            return $q->where('beach_id', request('beach'))->get();
        }
    }

    public function saveNew(Request $request)
    {
        $prices = Setting::first();

        $request->validate([
            'sector_id' => 'required',
            'beach_id' => 'required',
            'unit_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    $ok = \App\Models\Unit::whereKey($value)
                        ->whereNull('is_terminated')
                        ->valid()
                        ->exists();
                    if (! $ok) {
                        $fail('لا يمكن اختيار وحدة غير سارية أو متوقفة.');
                    }
                },
            ],
            'tenant_name' => 'required',
            'tenant_name_code' => 'required|numeric|digits:10',
            'with_tenant_name' => 'required',
            'with_tenant_name_code' => 'required|numeric|digits:10',
            'from' => 'required',
            'to' => 'required',
            'rent_value' => 'required|numeric',
            'rental_barcode_image' => 'nullable|mimes:jpg,jpeg,png,webp,svg,gif',
            'with_rental_barcode_image' => 'nullable|mimes:jpg,jpeg,png,webp,svg,gif',
            'tenant_nationality' => 'required',
            'with_tenant_nationality' => 'required',
            'insurance_value' => 'required|numeric|max:100000',
            'car' => 'required|array'
        ]);

        return DB::transaction(function () use ($request, $prices) {
            $contract = new Contract;
            $unit = Unit::findOrFail($request->unit_id);
            $code = time();

            $contract->unit_id = $request->unit_id;
            $contract->sector_id = $request->sector_id;
            $contract->beach_id = $request->beach_id;

            $contract->tenant_name = $request->tenant_name;
            $contract->tenant_name_code = $request->tenant_name_code;
            $contract->with_tenant_name = $request->with_tenant_name;
            $contract->with_tenant_name_code = $request->with_tenant_name_code;
            $contract->birth_date = Carbon::parse($request->birth_date)->format('Y-m-d');
            $contract->from = Carbon::parse($request->from . ' 15:00:00')->format('Y-m-d H:i:s');
            $contract->to = Carbon::parse($request->to . ' 15:00:00')->format('Y-m-d H:i:s');

            $contract->rent_value = $request->rent_value;
            $contract->car_type1 = $request->car_type1;
            $contract->car_serial1 = $request->car_serial1;

            $contract->user_id = auth()->id();

            $contract->car_type2 = $request->car_type2;
            $contract->car_serial2 = $request->car_serial2;

            // Default values from settings
            $price_before = $prices->price_before_vat;
            $price_after = $prices->price_after_vat;

            $calculateFinalPrices = getContractPriceAndTotal($unit, $price_before, $price_after);

            $final_price = $calculateFinalPrices['price'];
            $final_vat = $calculateFinalPrices['vat'];
            $final_total = $calculateFinalPrices['total'];

            $contract->price = $final_price;
            $contract->total = $final_total;
            $contract->vat = $final_vat;

            $contract->code = $code;
            $contract->token = time() + rand(1111, 9999);

            $contract->is_pending = true;
            $contract->pay_later = auth()->user()->user_free;

            $contract->tenant_nationality = $request->tenant_nationality;
            $contract->with_tenant_nationality = $request->with_tenant_nationality;
            $contract->insurance_value = $request->insurance_value;

            if (!$this->checkAvailability($contract->from, $contract->to, $request->unit_id)) {
                return  redirect()->back()
                    ->withInput($request->input())
                    ->with('error', 'هناك حجز أخر في نفس الفتره برجاء المحاولة مجددا.');
            }

            if ($request->rental_barcode_image != '') {
                $file = $request->rental_barcode_image;
                $file_array = explode('/', $file);
                $filename = end($file_array);

                if (file_exists(public_path('temp/' . $filename))) {
                    File::move(public_path('temp/' . $filename), public_path('uploads/' . $filename));
                    $contract->attachment_1 = $filename;
                }
            }

            if ($request->with_rental_barcode_image != '') {
                $file = $request->with_rental_barcode_image;
                $file_array = explode('/', $file);
                $filename = end($file_array);

                if (file_exists(public_path('temp/' . $filename))) {
                    File::move(public_path('temp/' . $filename), public_path('uploads/' . $filename));
                    $contract->attachment_2 = $filename;
                }
            }

            $contract->save();

            $carArray = [];
            $i = 0;

            foreach ($request->car as $c) {
                if ($c['type'] != '' && $c['serial'] != '') {
                    $carArray[$i]['car_type'] = $c['type'];
                    $carArray[$i]['car_serial'] = $c['serial'];
                    $carArray[$i]['contract_id'] = $contract->id;
                }

                $i++;
            }

            DB::table('cars')->insert($carArray);

            History::create([
                'hismodel_id' => $contract->id,
                'hismodel_type' => 'App\Models\Contract',
                'type' => 'create',
                'user_id' => auth()->id(),
                'created_at' => Carbon::now(),
                'updated_at' => ''
            ]);

            return redirect()->to(url('contract/' . $code . '/verifyPhone'))->with('message', 'تم حفظ العقد بنجاح و بإنتظار التفعيل.');
        });
    }

    public function save(Request $request)
    {

        $request->validate([
            'sector_id' => 'required',
            'beach_id' => 'required',
            'unit_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    $ok = \App\Models\Unit::whereKey($value)
                        ->whereNull('is_terminated')
                        ->valid()
                        ->exists();
                    if (! $ok) {
                        $fail('لا يمكن اختيار وحدة غير سارية أو متوقفة.');
                    }
                },
            ],
            'from' => 'required',
            'to' => 'required',
            'tenant_title' => 'required|max:191',
            'tenant_name' => 'required|max:191',
            'tenant_name_code' => 'required|size:10',
            'rent_value' => 'required|numeric|max:1000000',
            'attachment_1' => 'nullable|image',
            'tenant_nationality' => 'required|max:191',
            'insurance_value' => 'required|numeric|max:1000000',
            'car' => 'required|array',
            'companions' => 'required|array|min:1|max:' . config('contracts.companions.max'),
            'companions.*.title' => 'required|in:wife,mother,sister,daughter,others',
            'companions.*.name' => 'required|max:191',
            'companions.*.id_number' => 'required|size:10',
            'companions.*.nationality' => 'required|max:191',
            'companions.*.barcode' => 'nullable|image',
        ]);

        $multiSectorId = (int) config('contracts.companions.multi_sector_id');
        if ((int) $request->sector_id !== $multiSectorId && count($request->companions) !== 1) {
            return redirect()->back()
                ->withInput($request->input())
                ->withErrors(['companions' => 'يمكن إضافة أكثر من مرافق فقط للقطاع رقم ' . $multiSectorId . '.']);
        }

        return DB::transaction(function () use ($request) {

            $settings = Setting::first();
            $unit = Unit::findOrFail($request->unit_id);

            // Default values from settings
            $price_before = $settings->price_before_vat;
            $price_after = $settings->price_after_vat;

            $calculateFinalPrices = getContractPriceAndTotal($unit, $price_before, $price_after);

            $final_price = $calculateFinalPrices['price'];
            $final_vat = $calculateFinalPrices['vat'];
            $final_total = $calculateFinalPrices['total'];

            $firstCompanion = $request->companions[array_key_first($request->companions)];

            $data = [
                'unit_id' => $request->unit_id,
                'sector_id' => $request->sector_id,
                'beach_id' => $request->beach_id,

                'tenant_title' => $request->tenant_title,
                'tenant_name' => $request->tenant_name,
                'tenant_name_code' => $request->tenant_name_code,
                'tenant_nationality' => $request->tenant_nationality,

                'with_tenant_title' => $firstCompanion['title'],
                'with_tenant_name' => $firstCompanion['name'],
                'with_tenant_name_code' => $firstCompanion['id_number'],
                'with_tenant_nationality' => $firstCompanion['nationality'],

                'insurance_value' => $request->insurance_value,
                'rent_value' => $request->rent_value,

                'from' => Carbon::parse($request->from . ' 15:00:00')->format('Y-m-d H:i:s'),
                'to' => Carbon::parse($request->to . ' 15:00:00')->format('Y-m-d H:i:s'),

                // Contract MetaData
                'user_id' => auth()->id(),
                'status' => 0,
                'is_accepted' => 1,

                'price' => $final_price,
                'vat' => $final_vat,
                'total' => $final_total,

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

            // Companions (مرافق)
            $sort = 0;
            foreach ($request->companions as $key => $companion) {
                $barcodeFilename = null;
                if ($request->hasFile("companions.$key.barcode")) {
                    $barcodeFile = $request->file("companions.$key.barcode");
                    $barcodeFilename = Str::slug($barcodeFile->getClientOriginalName()) . '-' . rand(1111, 9999) . '.' . $barcodeFile->getClientOriginalExtension();
                    $barcodeFile->move('uploads', $barcodeFilename);
                }

                ContractCompanion::create([
                    'contract_id'   => $contract->id,
                    'title'         => $companion['title'],
                    'name'          => $companion['name'],
                    'id_number'     => $companion['id_number'],
                    'nationality'   => $companion['nationality'],
                    'barcode_image' => $barcodeFilename,
                    'sort_order'    => $sort,
                ]);

                if ($sort === 0 && $barcodeFilename) {
                    $contract->attachment_2 = $barcodeFilename;
                }

                $sort++;
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

            $users = User::where('role', 'admin')->get();

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

            return redirect()->to(url('contract/' . contractMix($contract) . '/verifyPhone'))->with('message', 'تم حفظ العقد بنجاح و بإنتظار التفعيل.');
        });
    }

    public function checkAvailability($from, $to, $unit)
    {

        $from_plus = Carbon::parse($from)->addDay()->format('Y-m-d H:i:s');
        $to_sub = Carbon::parse($to)->subDay()->format('Y-m-d H:i:s');

        //        return $from_plus;
        $contract = Contract::where(function ($q) use ($from, $to, $to_sub, $from_plus) {
            $q->whereBetween('from', [$from, $to_sub])
                ->orWhereBetween('to', [$from_plus, $to])
                ->orWhere(function ($query) use ($from, $to) {
                    $query->where('from', '<=', $from)
                        ->where('to', '>=', $to)->get();
                })
                ->get();
        })->where('unit_id', $unit)->first();

        //        return $contract;
        return is_null($contract);
    }

    //    public function checkAvailability($contract){
    //        $contract = $contract->whereDate('from', )->whereDate('to', )->get();
    //        return $contract;
    //    }

    public function redirect()
    {
        return view('MyFatoorah::standard-redirect');
    }

    public function getSingleContract($code)
    {
        $contract = Contract::with('unit', 'beach', 'sector', 'user', 'cars', 'companions', 'services')->where('code', $code)->first();
        return ContractsResource::make($contract);
    }

    public function verify($code)
    {
        $page_title = 'تأكيد رقم جوال المستأجر';
        $code_decode = contractMixDecode($code);

        //        return $code_decode;
        $contract = Contract::where('code', $code_decode)->whereNull('is_cancelled')->where('status', 0)->firstOrFail();

        if (!is_null($contract->phonenumber))
            return redirect()->to(url('/contract/' . $code . '/pay-validate'));

        return view('contracts.verify', compact('page_title', 'contract'));
    }

    public function verifyPost($code)
    {
        $contract = Contract::where('code', $code)->first();

        if (is_null($contract))
            abort(404);


        $contract->phonenumber = request()->phonenumber;

        return redirect()->to('');
    }

    public function payValidate($code)
    {
        $code_decode = contractMixDecode($code);

        //        return $code_decode;

        //        return dd($contract);

        $contract = Contract::where('code', $code_decode)
            //                        ->where('user_id', auth()->id())
            ->whereDate('to', '>=', Carbon::now())
            ->whereNull('is_cancelled')
            ->first();

        if (is_null($contract->phonenumber)) {
            return redirect()->to(url('contract/' . $code . '/verifyPhone'));
        }

        return view('contracts.pay-step', compact('contract'));
    }

    //    public function payment($id, $code){
    //        $contract = Contract::where('code', $code)->first();
    //
    //        if (is_null($contract))
    //            abort(404);
    //
    //        return Redirect::to(route('myfatoorah.redirect' , ['contract_id' => $contract->id]));
    //    }

    public function show($code)
    {
        $contract = Contract::with('unit', 'beach', 'sector', 'user', 'cars', 'companions', 'services')->where('code', $code)->first();

        if (is_null($contract))
            abort(404);

        if (!$contract->status)
            return redirect()->to('contract/' . contractMix($contract) . '/draft-show');

        return $this->renderContractView($contract);
    }

    public function check($code)
    {
        $vars = qr_code_decode($code);

        $code = isset($vars['code']) ? $vars['code'] : '';

        $contract = Contract::where('code', $code)->first();

        if (is_null($contract))
            abort(404);

        $page_title = 'صلاحية العقد';

        return view('contracts.check', compact('contract', 'page_title'));
    }

    public function edit($code)
    {
        $contract = Contract::where('code', $code)->where('is_accepted', 1)->first();

        if (is_null($contract) && !is_valid($contract))
            abort(404);


        return view('contracts.edit', compact('contract'));
    }

    public function update(Request $request, $code)
    {
        $contract = Contract::where('code', $code)->where('status', 1)->first();

        //        $contract->from = Carbon::parse($request->from.' 16:00:00')->format('Y-m-d H:i:s');
        //        $contract->to = Carbon::parse($request->to.' 16:00:00')->format('Y-m-d H:i:s');

        History::create([
            'hismodel_id' => $contract->id,
            'hismodel_type' => 'App\Models\Contract',
            'type' => 'update',
            'user_id' => auth()->id(),
            'created_at' => Carbon::now(),
            'updated_at' => ''
        ]);

        if ($contract->save()) {
            return redirect()->to(investor_url('contract/' . $contract->code));
        }

        return redirect()->back()->with('error', 'هناك خطا في تعديل بيانات العقد.');
    }

    public function getByToken($code, $token)
    {
        $contract = Contract::with('unit', 'beach', 'sector', 'user', 'cars', 'companions', 'services')->where('code', $code)->where('token', $token)->first();

        if (is_null($contract))
            abort(404);

        $check_reservation = $contract->reservation && $contract->reservation->status <= 2;

        if (!$contract->status && $check_reservation)
            return redirect()->to('contract/draft/' . $code . '/' . $token);

        if ($check_reservation)
            return redirect()->to('contract/' . contractMix($contract) . '/pay-validate');

        if (!$contract->status && !$contract->reservation_id) {
            return view('contracts.show-guest', compact('contract'));
        }

        return $this->renderContractView($contract);
    }

    private function renderContractView($contract)
    {
        $settings = Setting::first();
        $services = $contract->services ? unserialize($contract->services->service_data) : [];

        $tenant_barcode = $this->getContractImage($contract->attachment_1);
        $with_tenant_barcode = $this->getContractImage($contract->attachment_2);

        $is_cancelled = (bool) $contract->is_cancelled;
        $is_downpayment = false;
        $is_reservation = !is_null($contract->reservation);
        $remaining_payment = 0;

        if (!is_null($contract->reservation_id)) {
            $booking = \App\Models\Bookings::find($contract->reservation_id);
            if ($booking && $booking->status < 4) {
                $is_downpayment = true;
            }
            if ($booking) {
                $remaining_payment = $booking->total - $booking->down_payment;
            }
        }

        $upload_image_link = route('upload.invoice', deep_encode($contract->id, $contract->created_at));
        $share_link = url('contract/' . $contract->code . '/' . $contract->token);
        $qr_code_link = env('APP_URL') . '/contract/check/' . qr_code_encode($contract);
        $tenant_title = trans('admin.' . $contract->tenant_title);
        $with_tenant_title = trans('admin.' . $contract->with_tenant_title);

        $beach_terms = $contract->beach ? $contract->beach->terms : collect();

        return view('contracts.show-print', compact(
            'contract', 'settings', 'services',
            'tenant_barcode', 'with_tenant_barcode',
            'is_cancelled', 'is_downpayment', 'is_reservation',
            'remaining_payment', 'upload_image_link', 'share_link',
            'qr_code_link', 'tenant_title', 'with_tenant_title',
            'beach_terms'
        ));
    }

    private function getContractImage($file_name)
    {
        if ($file_name && file_exists(public_path('uploads/' . $file_name))) {
            return asset('uploads/' . $file_name);
        }
        return false;
    }

    public function cancelContract($code)
    {
        $contract = Contract::where('code', $code)->first();

        $contract->update([
            'is_cancelled' => 1
        ]);

        return redirect()->to(investor_url('contracts'))->withSuccess('تم الغاء العقد بنجاح.');
    }

    public function updateCars(Request $request, Contract $contract, int $carId = null)
    {
        if ($contract->user_id != auth()->id())
            abort(401);

        if (!is_null($carId)) {
            $car = Car::findOrFail($carId);

            if ($car->is_edited)
                abort(403);
        }

        Car::updateOrCreate([
            'id' => $carId
        ], [
            'car_type' => $request->car_type,
            'car_serial' => $request->car_serial,
            'is_edited' => 1,
            'contract_id' => $contract->id
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function verifyCode()
    {
        $page_title = '';
        return view('contracts.verify-code', compact('page_title'));
    }
}
