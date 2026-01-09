<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;

class ContractDraftController extends Controller
{
    public function __construct(){
        $this->middleware(['auth', 'FrontEnd'])->except('showDraft');
    }

    public function draft($code){
        $page_title = 'مسودة العقد';
        $code = contractMixDecode($code);

        $contract = Contract::where('code', $code)->first();

        if (is_null($contract))
            abort(404);

        if (!checkUserContractCode($contract) && !ContractExists($contract))
            abort(404);

        return view('contracts.draft', compact('page_title', 'contract'));
    }

    public function showDraft($code, $token){
        $page_title = 'مسودة العقد';
        $contract = Contract::where('code', $code)->where('token', $token)->first();

//        if (!ContractExists($contract))
//            abort(404);

        return view('contracts.show-draft', compact('page_title', 'contract'));
    }
}
