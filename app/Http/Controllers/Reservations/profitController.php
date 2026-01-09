<?php

namespace App\Http\Controllers\Reservations;

use App\Http\Controllers\Controller;
use App\Models\BookingUnit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class profitController extends Controller
{
    public function update(Request $request, $id){
        $request->validate([
            'profit_percent' => 'numeric|min:10'
        ]);

        $id = deep_decode($id);
        $unit = BookingUnit::findOrFail($id);

        $unit_update = $unit->update([
            'profit_percentage' => $request->profit_percent,
            'profit_at' => Carbon::now()
        ]);

        return $this->messages($unit_update);
    }
}
