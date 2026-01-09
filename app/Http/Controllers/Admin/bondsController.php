<?php

namespace App\Http\Controllers\Admin;

use App\Exports\BondContractsExport;
use App\Http\Controllers\Controller;
use App\Models\Bond;
use App\Models\Contract;
use App\Models\History;
use App\Models\Sector;
use App\Notifications\BondCreateNotifications;
use App\Notifications\ContractNotifications;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class bondsController extends Controller
{
    public function __construct(){
        $this->middleware('sectorAdmin');
    }

    public function index(Request $request){
        $bonds = Bond::with('sector');
        $sectors = [];

        if (is_sector_admin()){
            $bonds = $bonds->where('sector_id', auth()->user()->role_id);
        }else{
            $sectors = Sector::orderBy('id', 'DESC')->get();
        }

        $bonds = $bonds->orderBy('id', 'DESC')->paginate(15);

        return view('admin.bonds.index', compact('bonds', 'sectors'));
    }

    public function store(Request $request){
        $bond = new Bond;

        $request->validate([
            'sector' => 'required|numeric',
            'from' => 'required',
            'to' => 'required',
            'amount' => 'required|numeric',
            'note' => 'nullable'
        ]);

        $saved = $bond->create([
            'sector_id' => $request->sector,
            'from' => $request->from,
            'to' => $request->to,
            'amount' => $request->amount,
            'note' => $request->note,
            'user_id' => auth()->id(),
            'count' => $request->count_contracts
        ]);

        @Sector::find($request->sector)->user->notify(new BondCreateNotifications($saved));

        History::create([
            'hismodel_id' => $saved->id,
            'hismodel_type' => 'App\Models\Bond',
            'type' => 'create',
            'user_id' => auth()->id(),
            'created_at' => Carbon::now(),
            'updated_at' => ''
        ]);

        if ($saved)
            return redirect()->to(admin_url('bonds'))->with('success', 'تم حفظ الإغلاق بنجاح.');

        return redirect()->to(admin_url('bonds'))->with('error', 'هناك خطأ في حفظ السجل.');
    }

    public function update(Request $request, $id){
        $bond = Bond::findOrFail($id);

        $request->validate([
            'sector' => 'required|numeric',
            'from' => 'required',
            'to' => 'required',
            'amount' => 'required|numeric',
            'note' => 'nullable'
        ]);

        $saved = $bond->update([
            'sector_id' => $request->sector,
            'from' => $request->from,
            'to' => $request->to,
            'amount' => $request->amount,
            'note' => $request->note,
            'count' => $request->count_contracts,
            'is_cancelled' => null
        ]);

        History::create([
            'hismodel_id' => $bond->id,
            'hismodel_type' => 'App\Models\Bond',
            'type' => 'update',
            'user_id' => auth()->id(),
            'created_at' => Carbon::now(),
            'updated_at' => ''
        ]);

        if ($saved)
            return redirect()->to(admin_url('bonds'))->with('success', 'تم تعديل الإغلاق بنجاح.');

        return redirect()->to(admin_url('bonds'))->with('error', 'هناك خطأ في تعديل السجل.');
    }

    public function show($id){
        $page_title = 'سجل السندات';
        $history = (new \App\Classes\History)->getAllHistory('App\Models\Bond', $id, 'bonds');
        return view('admin.history', compact('history', 'page_title'));
    }

    public function changeStatus(Request $request, $id, $status){
        $bond = Bond::findOrFail($id);

        if (auth()->user()->role_id == $bond->sector_id){
            switch ($status){
                case 'accept':
                    $bond->is_accepted = 1;

                    History::create([
                        'hismodel_id' => $bond->id,
                        'hismodel_type' => 'App\Models\Bond',
                        'type' => 'accepted',
                        'user_id' => auth()->id(),
                        'created_at' => Carbon::now(),
                        'updated_at' => ''
                    ]);

                    break;
                case 'cancel':
                    $bond->reason = $request->reason;
                    $bond->is_cancelled = 1;

                    History::create([
                        'hismodel_id' => $bond->id,
                        'hismodel_type' => 'App\Models\Bond',
                        'type' => 'reject',
                        'user_id' => auth()->id(),
                        'created_at' => Carbon::now(),
                        'updated_at' => ''
                    ]);

                    break;
            }

            if($bond->save())
                return redirect()->to(admin_url('bonds'))->with('success', 'تمت العمليه بنجاح.');
        }

        return redirect()->to(admin_url('bonds'))->with('error', 'هناك مشكلة في تغيير حالة السند.');
    }

    public function destroy(Request $request, $id){
        $bonds = Bond::findOrFail($id);

        if ($bonds->delete())
                return redirect()->to(admin_url('bonds'))->with('success', 'تم حذف السند بنجاح.');

        return redirect()->to(admin_url('bonds'))->with('error', 'هناك مشكلة في حذف السند ، برجاء المحاولة في وقت لاحق.');
    }

    public function contracts($id){
        $bond = Bond::findOrFail($id);
        $contracts = Contract::
            whereBetween('created_at', [$bond->from, $bond->to])
            ->where('sector_id', $bond->sector_id)
            ->where('status', 1)
            ->whereNull('is_cancelled')
            ->where(function ($q) {
                $q->where('payment_type', 'paid')
                    ->orWhere('payment_type', 'pay_later');
            })
            // ->whereNotIn('payment_type', ['exempt', 'pay_later'])
            ->where('is_accepted', 1);

        $contracts_total = $contracts->sum('price');

        $contracts_count = $contracts->count();

        $sector_total = ($contracts_total * $bond->sector->percentage) / 100;
        $ithmar_total = $contracts_total - $sector_total;

        $contracts = $this->countUnits($contracts->orderByDESC('unit_id')->get());

        return view('admin.bonds.contracts', compact('contracts','bond', 'contracts_total', 'sector_total','contracts_count', 'ithmar_total'));
    }

    public function countUnits($contracts){
        $contractArr = [];
        $units = [];
        $units_count = [];
        $i = 0;

        foreach($contracts as $contract){
            $unit_number = $contract->unit->unit_number;
            $unit_id = $contract->unit_id;

            if (array_key_exists($contract->unit_id, $units)){
                $units[$unit_id] += $contract->price;
                $units_count[$unit_id]++;
            }else{
                $units[$unit_id] = $contract->price;
                $units_count[$unit_id] = 1;

                $i++;

                $contractArr[$i]['unit'] = $unit_number;
                $contractArr[$i]['beach'] = $contract->beach->beach;
                $contractArr[$i]['investor'] = $contract->user->name;
            }

            $sector_total = ($units[$unit_id] * $contract->sector->percentage) / 100;

            $contractArr[$i]['count'] = $units_count[$unit_id];
            $contractArr[$i]['total_contracts'] = $units[$unit_id];
            $contractArr[$i]['ithmar_total'] = $units[$unit_id] - $sector_total;
            $contractArr[$i]['sector_total'] = $sector_total;
        }

        return $contractArr;
    }

    public function export($id)
    {
        $bond = Bond::findOrFail($id);

        $contracts = Contract::
        whereBetween('created_at', [$bond->from, $bond->to])
            ->where('sector_id', $bond->sector_id)
            ->where('status', 1)
            ->where('is_accepted', 1)
            ->where(function ($q) {
                $q->where('payment_type', 'paid')
                    ->orWhere('payment_type', 'pay_later');
            });

        $contracts_total = $contracts->sum('price');
        $contracts_count = $contracts->count();
        $sector_total = ($contracts_total * $bond->sector->percentage) / 100;
        $ithmar_total = $contracts_total - $sector_total;

        $contracts = $this->countUnits($contracts->orderByDESC('unit_id')->get());

        return Excel::download(new BondContractsExport($contracts, $contracts_total, $contracts_count, $sector_total, $ithmar_total), 'bond.xlsx');
    }
}
