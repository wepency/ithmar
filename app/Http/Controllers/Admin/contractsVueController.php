<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use Illuminate\Http\Request;

class contractsVueController extends Controller
{
    public function show($code) {
        return $code;
        $contract = Contract::where('code', $code)->where('status', 1)->first();

        if (is_null($contract))
            abort(404);

        return view('admin.contracts.show', compact('contract'));
    }
}
