<?php

namespace App\Traits;

use App\Models\BookingUnit;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait Reservations
{
    public function searchReservation($request){

        $request->validate([
            'from' => 'required',
            'to' => 'required',
            'unit' => 'required'
        ]);

        $unit = base64_decode($request->unit);
        $unit_obj = BookingUnit::findOrFail($unit);
        $from = clone $from_minus = $this->from($request->from);
        $to = clone $to_plus = $this->to($request->to);
        $diff = $from->diffInDays($to);

        if(availability($unit, $from, $to)) {

//				$available = BookingAvailability::where('unit_id', $unit)->where('from', '<', $from_minus->subDay())->where('to', '>=', $from_minus->subDay())->get();
            $available = DB::table('booking_availabilities')->where('unit_id', $unit)->where(function ($q) use ($from, $to, $from_minus, $to_plus) {
                $q->where(function ($q) use ($from, $to) {
                    $q->where('from', '<=', $from)->where('to', '>=', $from);
                })->orWhere(function ($q) use ($from, $to) {
                    $q->where('from', '<=', $to)->where('to', '>=', $to);
                })->orWhere(function ($q) use ($from, $to) {
                    $q->where('from', '>=', $from)->where('to', '<=', $to);
                })->orWhere(function ($q) use ($from_minus) {
                    $q->where('from', '<', $from_minus->subDay())->where('to', '>=', $from_minus->subDay());
                })->orWhere(function ($q) use ($to_plus) {
                    $q->where('from', '<', $to_plus->subDay())->where('to', '>=', $to_plus->subDay());
                });

//				})->orderBy('from', 'ASC')->get();
            })->where('min_stay', '<=', $diff)->orderBy('from', 'ASC')->get();

            $i = 0;
            $duration = $total = [];

//			return 'Yes';

//				return $available;

            foreach ($available as $avail){
                $avail_from = Carbon::parse($avail->from)->addHours(3);
                $avail_to = Carbon::parse($avail->to)->addHours(3);

                if ($i > 0){
                    $last_avail = $available[$i - 1];
                    $last_to = Carbon::parse($last_avail->to)->addHours(3);
                    $diff_period = $last_to->diffInHours($avail_from);

                    // Diff must be less than one day
                    if ($diff_period < 24){
                        if ($avail_to > $to){
                            $sub_period_days = $last_to->diffInDays($to);
                            $duration += $sub_period_days;
                            $total += $sub_period_days * $avail->price;
                            break;
                        }else {
                            $sub_period_days = $avail_to->diffInDays($last_to);
                            $duration += $avail_to->diffInDays($last_to);
                            $total += $sub_period_days * $avail->price;
                        }

                    }else{
                        break;
                    }
                }else {
                    if ($avail_to > $to){
                        $duration = $to->diffInDays($from);
                    }else{
                        $duration = $avail_to->diffInDays($from);
                    }

                    $total = $duration * $avail->price;

                    if ($duration == $diff)
                        break;
                }

                $i++;
            }

            if ($duration == $diff){
                $down_payment = $unit_obj->unit->user->down_payment ?? 100;
                $down_payment_plain = number_format((($down_payment * $total) / 100), 2, '.', ',');
                $price = $total / $diff;

                return [
                    'unit_id' => $request->unit,
                    'duration' => $diff,
                    'from' => $from->format('Y-m-d'),
                    'to' => $to->subHours(3)->format('Y-m-d'),
                    'price' => currency($price),
                    'price_plain' => $price,
                    'total' => currency($total),
                    'total_plain' => $total,
                    'down_payment' => currency($down_payment_plain),
                    'down_payment_plain' => $down_payment_plain,
                    'down_payment_percentage' => $unit_obj->unit->user->down_payment
                ];
            }
        }

        return false;
    }

    private function from($from){
        return Carbon::createFromFormat('Y-m-d H:i:s', $from.' 00:00:00')->addHours(3);;

    }
    private function to($to){
        return Carbon::createFromFormat('Y-m-d H:i:s', $to.' 23:59:59')->addHours(3);;
    }
}
