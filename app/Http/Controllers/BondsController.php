<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\InvestorBonds;
use Illuminate\Http\Request;

class BondsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('show');
    }

    public function get(){
        $page_title = 'سندات المستثمر';

        $contracts = Contract::where('user_id', auth()->id())
            ->where('status', 1)
            ->whereDoesntHave('bond')
            ->orderBy('id', 'DESC')
            ->get();

        $bonds = InvestorBonds::with('contract:code,id,unit_id,rent_value,tenant_name', 'contract.unit')->where('user_id', auth()->id())->paginate(20);
        return view('bonds.index', compact('page_title', 'bonds', 'contracts'));
    }

    public function addBond(Request $request){
        $code = $request->code;

        $request->validate([
            'code' => 'required|max:191',
            'bond_note' => 'nullable'
        ]);

        $contract = Contract::where('code', $code)->firstOrFail();
        $bonds = InvestorBonds::where('contract_id', $contract->id)->first();
        $for = "قيمة إيجار فيلا {$contract->unit->unit_number} بشاطئ {$contract->beach->beach}";

        if (auth()->id() == $contract->user_id && is_null($bonds)){
            $create = InvestorBonds::create([
                'notes' => $request->bond_note,
                'user_id' => auth()->id(),
                'contract_id' => $contract->id,
                'value' => $request->bond_value,
                'for' => $for
            ]);

            if ($create){
                return redirect()->back()->with('success', 'تم إصدار السند بنجاح.');
            }
        }

        return redirect()->back()->with('error', 'لا يمكن إضافة السند ، برجاء التواصل مع الدعم الفني.')->withInput($request->input());
    }

    public function destroy($id){
        $bond = InvestorBonds::findOrFail($id);

        if (auth()->id() == $bond->user_id){
            if($bond->delete())
                return redirect()->back()->with('success', 'تم حذف السند بنجاح.');
        }

        return redirect()->back()->with('error', 'هناك مشكلة في حذف السند ، برجاء المحاولة لاحقاََ.');
    }

    public function show($code){
        $code = base64_decode(base64_decode($code)) / 15965585478;
        $bond = InvestorBonds::with('user:id,name')->findOrFail($code);
        return view('bonds.show', compact('bond'));
    }
}
