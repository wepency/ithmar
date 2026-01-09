<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BookingAvailability;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DatesController extends Controller
{
    public function getAvailableData(Request $request){
        $id = $request->avail;
        $avail = BookingAvailability::whereHas('unit.user', function (Builder $builder){
            $builder->where('id', auth()->id());
        })->where('id', $id)->first();

        return response()->json([
            'min_stay' => $avail->min_stay,
            'price' => $avail->price,
            'date' => Carbon::parse($avail->date)->format('Y-m-d'),
            'href' => route('availability.update', ['unit_id' => base64_encode($avail->unit_id), 'availability' => base64_encode($avail->id)])
        ]);
    }
}
