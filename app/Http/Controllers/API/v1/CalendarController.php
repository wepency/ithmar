<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\BookingAvailability;
use App\Models\BookingDate;
use App\Models\BookingUnit;
use App\Traits\DivDays;
use App\Traits\generateAPI;
use App\Traits\Token;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    use generateAPI;
    use Token;
    use DivDays;

    private $date_format = 'm/d/Y';
    private $date_time_format = 'Y-m-d 00:00:00';

    public function getDates(Request $request, $id){
        $unit = BookingUnit::active()->findOrFail($id);

        if ($unit->unit->user->id !== auth()->id())
            abort(404);

        $month = clone $date = $request->has('date') && $request->date != '' ? Carbon::parse(urldecode($request->date))->startOfMonth() : Carbon::now()->startOfMonth();

        $current_month = $month->format('Y-m-d');
        $next_month = $month->addMonth()->format('Y-m-d');
        $previous_month = $month->subMonths(2)->format('Y-m-d');

        $start_date = $date->startOfMonth()->format('Y-m-d H:i:s');
        $end_date = $date->endOfMonth()->format('Y-m-d H:i:s');

        $dates = BookingDate::where('unit_id', $id)->where(function (Builder $builder) use ($start_date, $end_date){
            $builder->whereBetween('from', [$start_date, $end_date])
                ->orWhereBetween('to', [$start_date, $end_date]);
        })->where(function (Builder $builder){
            $builder->whereNotIn('type', ['refused', 'waiting'])->orWhere(function ($q){
                $q->where('type', 'waiting')->where('created_at', '>', Carbon::now()->subMinutes(cache()->get('vertime'))->toDateTimeString());
            });
        })->get();

        $available = BookingAvailability::where('unit_id', $id)->whereBetween('date', [$start_date, $end_date])->get();

        $dates = $this->divDays($available, $dates, true);
        $get_contracts = $this->divContractDays($unit->unit_id, $start_date, $end_date, true);
        $dates = array_merge($dates, $get_contracts);

        ksort($dates);

        auth()->user()->update([
            'calendar_logged' => Carbon::now()
        ]);

        return $this->success([
            'month' => $month,
            'next_month' => $next_month,
            'current_month' => $current_month,
            'previous_month' => $previous_month,
            'unit_id' => $id,
            'dates' => array_values($dates),
            'unit' => $unit
        ]);
    }

    public function close(Request $request, $id){
        $request->validate([
            'dates' => 'required'
        ]);

//        $dates = json_decode($request->dates);
        $dates = explode(',', $request->dates);

        BookingAvailability::whereIn('date', $dates)->where('unit_id', $id)->delete();
        BookingDate::whereIn('from', $dates)->where('unit_id', $id)->delete();

        $date_arr = [];

        foreach ($dates as $date){

            $date_arr[] = array(
                'unit_id' => $id,
                'from' => $date,
                'to' => $date,
                'type' => 'closed',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            );
        }

        $booking = BookingDate::insert($date_arr);

        if ($booking)
            return $this->success([], 'تم اغلاق الفتره بنجاح.');

        return $this->error([], 'لا يمكن إغلاق تلك الفترة ، برجاء التأكد من التواريخ المختارة.');
    }

    public function open(Request $request, $unit_id){

        $request->validate([
            'price' => 'required|numeric',
            'dates' => 'required',
            'min_stay' => 'required|numeric'
        ]);

//        $dates = json_decode($request->dates);
        $dates = explode(',', $request->dates);

        BookingDate::whereIn('from', $dates)->where('unit_id', $unit_id)->where('type', 'closed')->delete();
        BookingAvailability::whereIn('date', $dates)->where('unit_id', $unit_id)->delete();

        $avail_arr = [];

        foreach ($dates as $date){
            $avail_arr[] = array(
                'unit_id' => $unit_id,
                'date' => $date,
                'price' => $request->price,
                'min_stay' => $request->min_stay
            );
        }

        $availability = BookingAvailability::insert($avail_arr);

        if ($availability)
            return $this->success([], 'تم إضافة السعر و التوافر بنجاح.');

        return $this->error([], 'لا يمكن إلإضافة بسبب تداخل التواريخ برجاء التأكد من عدم وجود تواريخ سابقة', 405);
    }

    public function update(Request $request){

        $request->validate([
            'availability' => 'required|numeric',
            'price' => 'required|numeric',
            'min_stay' => 'required|numeric'
        ]);

        $availability = BookingAvailability::findOrFail($request->availability);

        $availability_update = $availability->update([
            'min_stay' => $request->min_stay,
            'price' => $request->price
        ]);

        if ($availability_update)
            return $this->success('تم تعديل السعر بنجاح.');

        return $this->error([], 'لا يمكن إلإضافة بسبب تداخل التواريخ برجاء التأكد من عدم وجود تواريخ سابقة');
    }
}
