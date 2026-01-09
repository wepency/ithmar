<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BookingDate;
use App\Models\BookingUnit;
use App\Models\Contract;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckAvailabilityController extends Controller
{
    public function check(Request $request)
    {
        $unit = $request->unit;

        $booking_units = BookingUnit::active()->where('unit_id', $unit)->first();

        $check_closed = $check_dates = null;

        if (!is_null($booking_units)){
            $from = Carbon::parse($request->from);
            $to = clone $minus_to = Carbon::parse($request->to);
            $minus_to = $minus_to->subDay();

            $dates = CarbonPeriod::create($from, $minus_to)->toArray();

//            $check_closed = DB::table('booking_dates')->select('id')->where('unit_id', $booking_units->id)->where('type', 'closed')->whereIn('from', $dates)->first();

            $check_dates = DB::table('booking_dates')->select('id')->where('unit_id', $booking_units->id)->where(function ($builder) use ($from, $to, $minus_to){
                $builder->where(function ($q) use ($from, $to){
                    $q->where('from', '>=', $from)
                        ->where('from', '<', $to);
                })->orWhere(function ($q) use ($from, $to){
                    $q->where('to', '>', $from)
                        ->where('to', '<', $to);
                });
            })->where(function ($builder){
                $builder->whereNotIn('type', ['refused', 'waiting'])->orWhere(function ($q){
                    $q->where('type', 'waiting')->where('created_at', '>', Carbon::now()->subMinutes(get_vertime())->toDateTimeString());
                });
            })->where('type', '!=','closed')->first();
        }

        $from_date = Carbon::parse($request->from. '15:00:00')->format('Y-m-d H:i:s');
        $to_date = Carbon::parse($request->to. '15:00:00')->format('Y-m-d H:i:s');

//        return $check_closed;
        if (!checkAvailability($from_date, $to_date, $unit) || !is_null($check_dates))
            return response()->json('هناك حجز أخر في نفس الفترة.', 500);
    }
}
