<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Bond;
use App\Models\Contract;
use App\Models\Sector;
use Carbon\Carbon;
use Illuminate\Http\Request;

class fundsController extends Controller
{
    public function get(Request $request){
        $sector_id = $request->sector;
        $sector = Sector::findOrFail($sector_id);
        $to = Carbon::parse($request->to)->format('Y-m-d 23:59:59');
        $from = Carbon::parse($request->from)->format('Y-m-d 00:00:00');

        $price_sum = Contract::where('sector_id', $sector_id)
            ->ValidForReport()
            ->where(function ($q) {
                $q->where('payment_type', 'paid')
                    ->orWhere('payment_type', 'pay_later');
            })
            ->where('is_accepted', 1)
            ->whereNull('is_cancelled')
            ->whereBetween('created_at', [$from, $to]);

        return [
            'total' => number_format(($price_sum->sum('price') * $sector->percentage) / 100,2, '.', ''),
            'count' => $price_sum->count()
        ];
    }

    public function getBond(Request $request){
        $bond = Bond::findOrFail($request->bond);
        return response()->json($bond);
    }
}
