<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UnitsResource;
use App\Models\BookingUnit;
use App\Models\Unit;

class BookingController extends Controller
{
    public function getVillas($beach_id){
        $booking_units = BookingUnit::select('unit_id')->where('user_id', auth()->id())->get()->pluck('unit_id');

        return UnitsResource::collection(Unit::where('beach_id', $beach_id)
            ->where('user_id', auth()->id())
            ->whereNotIn('id', $booking_units)
            ->where('status', 1)
            ->valid()
            ->orderBy('unit_number', 'DESC')->get()
        );
    }
}
