<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use Illuminate\Http\Request;

class BondsController extends Controller
{
    public function __construct()
    {
//        $this->middleware('auth');
    }

    public function getData(Request $request){
        $code = $request->code;
        $contract = Contract::where('code', $code)->first();

        $name = $contract->tenant_name;
        $value = $contract->rent_value;
        $for = "قيمة إيجار فيلا {$contract->unit->unit_number} بشاطئ {$contract->beach->beach}";

        return response()->json([
            'name' => $name,
            'value' => $value,
            'for' => $for
        ]);
    }
}
