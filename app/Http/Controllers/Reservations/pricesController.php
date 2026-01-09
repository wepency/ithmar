<?php

namespace App\Http\Controllers\Reservations;

use App\Http\Controllers\Controller;
use App\Models\BookingAvailability;
use App\Models\BookingDate;
use App\Models\BookingUnit;
use App\Models\Contract;
use App\Traits\DivDays;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class pricesController extends Controller
{
    use DivDays;

    private $date_format = 'm/d/Y';
    private $date_time_format = 'Y-m-d 00:00:00';

    public function index(Request $request){
        $units = BookingUnit::where('user_id', auth()->id())->active()->paginate(10);

        auth()->user()->update([
            'calendar_logged' => Carbon::now()
        ]);

        return view('Reservations.Prices.index', [
            'page_title' => 'الوحدات',
            'units' => $units
        ]);
    }

    public function create(Request $request, $unit_id){
        $from = $request->from != '' ? Carbon::parse($request->from)->format($this->date_format) : Carbon::now()->format($this->date_format);
        $to = $request->to != '' ? Carbon::parse($request->to)->subDay()->format($this->date_format) : Carbon::now()->format($this->date_format);

        return view('Reservations.Prices.create', [
            'page_title' => 'إنشاء تاريخ إتاحة جديد',
            'price' => new BookingAvailability,
            'unit_id' => $unit_id,
            'dates' => $from.' - '.$to
        ]);
    }

    public function store(Request $request, $unit_id){
        $request->validate([
            'price' => 'required|numeric',
            'dates' => 'required',
//            'to' => 'required'
        ]);


//        $dates = explode(' - ', $request->dates);
//        $from = Carbon::parse($dates[0])->format('Y-m-d 00:00:00');
//        $to = Carbon::parse($dates[1])->format('Y-m-d 23:59:59');

        $dates = json_decode($request->dates);

//        return $dates;
        BookingDate::whereIn('from', $dates)->where('unit_id', $unit_id)->where('type', 'closed')->delete();
        BookingAvailability::whereIn('date', $dates)->where('unit_id', $unit_id)->delete();

//        $from = Carbon::parse($request->from)->format('Y-m-d 00:00:00');
//        $to = Carbon::parse($request->to)->format('Y-m-d 23:59:59');

        $avail_arr = [];

        foreach ($dates as $date){
            $avail_arr[] = array(
                'unit_id' => $unit_id,
                'date' => $date,
                'price' => $request->price,
                'min_stay' => $request->min_stay
            );
        }
//        if (check_available($unit_id, $from, $to)) {
            $availability = BookingAvailability::insert($avail_arr);



            if ($availability){
                @create_booking_history_record($unit_id, 'BookingUnit', 'open_price', serialize($dates));
                return redirect()->route('availability.show', $unit_id)->with('success', 'تم إضافة السعر و التوافر بنجاح.');

            }
//        }

        return redirect()->back()->with('error', 'لا يمكن إلإضافة بسبب تداخل التواريخ برجاء التأكد من عدم وجود تواريخ سابقة');
    }

    public function edit($unit_id, $availability){
        $availability = base64_decode(base64_decode($availability));
        $booking = BookingAvailability::findOrFail($availability);
        $dates = Carbon::parse($booking->from)->format($this->date_format).' - '.Carbon::parse($booking->to)->format($this->date_format);

        return view('Reservations.Prices.create', [
            'page_title' => 'تعديل تاريخ التوافر',
            'price' => $booking,
            'unit_id' => $unit_id,
            'dates' => $dates
        ]);
    }

    public function update(Request $request, $unit_id, $availability){
        $availability = base64_decode($availability);

        $request->validate([
            'price' => 'required|numeric',
            'min_stay' => 'required|numeric'
        ]);

        $availability = BookingAvailability::findOrFail($availability);

        $availability_update = $availability->update([
            'min_stay' => $request->min_stay,
            'price' => $request->price
        ]);

        if ($availability_update){
            @create_booking_history_record($availability->unit->id, 'BookingUnit', 'update_price', serialize([Carbon::parse($availability->date)->format('Y-m-d')]));
            return redirect()->back()->with('success', 'تم تعديل السعر بنجاح.');
        }

        return redirect()->back()->with('error', 'لا يمكن إلإضافة بسبب تداخل التواريخ برجاء التأكد من عدم وجود تواريخ سابقة');
    }

    public function show(Request $request, $id){
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

        $dates = $this->divDays($available, $dates);
        $get_contracts = $this->divContractDays($unit->unit_id, $start_date, $end_date);
        $dates = array_merge($dates, $get_contracts);

        ksort($dates);

        return view('Reservations.Prices.show', [
            'page_title' => '',
            'month' => $month,
            'next_month' => $next_month,
            'current_month' => $current_month,
            'previous_month' => $previous_month,
            'unit_id' => $id,
            'dates' => $dates,
            'unit' => $unit
//            'available' => $available
        ]);
    }

//    private function divDays($available, $dates){
//        $date_arr = [];
//
//        foreach ($available as $dateObj){
//            $date = Carbon::parse($dateObj->date)->format('Y-m-d');
//
//            $date_arr[$date] = array(
//                'day' => $date,
//                'type' => 'available',
//                'id' => $dateObj->id,
//                'color' => '',
////                'href' => route('availability.edit', [$unit_id, base64_encode(base64_encode($date->id))])
//            );
//        }
//
//        $sub_types = ['waiting', 'pending', 'approved', 'contract'];
//
//        foreach ($dates as $date){
//            if (in_array($date->type, $sub_types)){
//                $to = Carbon::parse($date->to)->subDay()->format('Y-m-d');
//            }else{
//                $to = $date->to;
//            }
//
//            $periods = CarbonPeriod::create($date->from, $to);
//
//            foreach ($periods as $period) {
//                $period = $period->format('Y-m-d');
//                $url = '';
//
//                if ($date->type == 'waiting'){
//                    $url = route('availability.waiting', $date->id);
//                }elseif($date->type == 'contract'){
//                    $url = url('contract/'.$date->contract->code);
//                }
//
//                $date_arr[$period] = array(
//                    'day' => $period,
//                    'type' => $date->type,
//                    'color' => '',
//                    'id' => '',
//                    'href' =>  $url
//                );
//            }
//        }
//
//        return $date_arr;
//    }
//
//    private function divContractDays($unit_id, $from, $to){
//        $date_arr = [];
//
//        $from_plus = Carbon::parse($from)->addDay()->format('Y-m-d H:i:s');
//        $to_sub = Carbon::parse($to)->subDay()->format('Y-m-d H:i:s');
//
//        $contracts = Contract::where(function ($q) use ($from, $to, $to_sub, $from_plus){
//            $q->whereBetween('from', [$from, $to_sub])
//                ->orWhereBetween('to', [$from_plus, $to])
//                ->orWhere(function ($query) use ($from, $to){
//                    $query->where('from', '<=', $from)
//                        ->where('to', '>=', $to)->get();
//                })
//                ->get();
//        })->whereNull('is_cancelled')->where('unit_id', $unit_id)->get();
//
//        foreach ($contracts as $contract){
//            $contract_dates = CarbonPeriod::dates($contract->from, $contract->to);
//            $i = 0;
//
//            foreach ($contract_dates as $key => $dateObj){
//                if ($i == count($contract_dates) - 1)
//                    break;
//
//                $date = Carbon::parse($dateObj)->format('Y-m-d');
//
//                $date_arr[$date] = array(
//                    'day' => $date,
//                    'type' => 'contract',
//                    'id' => $key,
//                    'color' => '',
////                'href' => route('availability.edit', [$unit_id, base64_encode(base64_encode($date->id))])
//                );
//
//                $i++;
//            }
//        }
//
//        return $date_arr;
//    }

    public function close(Request $request, $id){
        $request->validate([
           'dates' => 'required'
        ]);

        $dates = json_decode($request->dates);

//        return dd($dates);
        BookingAvailability::whereIn('date', $dates)->where('unit_id', $id)->delete();

//        $start_date = Carbon::parse($request->from)->format('Y-m-d 23:59:59');
//        $end_date = clone $plus_date = Carbon::parse($request->to);

//        $end_date = $end_date->format('Y-m-d 23:59:59');

//        $plus_date = $plus_date->addDay()->format($this->date_time_format);

//        $check_dates = BookingDate::where('unit_id', $id)->where(function (Builder $builder) use ($start_date, $end_date, $plus_date){
//            $builder->where('from', [$start_date, $end_date])
//                ->orWhereBetween('to', [$plus_date, $end_date]);
//        })->where(function (Builder $builder){
//            $builder->whereNotIn('type', ['refused', 'waiting'])->orWhere(function ($q){
//                $q->where('type', 'waiting')->where('created_at', '>', Carbon::now()->subHours(5)->toDateTimeString());
//            });
//        })->first();
//
//        $check_availability = $this->checkAvailabilityCloseOpen($id, $start_date, $end_date);

//        return $check_availability;

//        if (is_null($check_dates) && !$check_availability) {
//            $dates = CarbonPeriod::create($start_date, $end_date);

            $date_arr = [];

            foreach ($dates as $date){

//                $date = Carbon::parse($date)->format($this->date_time_format);

                $date_arr[] = array(
                    'unit_id' => $id,
                    'from' => $date,
                    'to' => $date,
                    'type' => 'closed'
                );
            }

            $booking = BookingDate::insert($date_arr);
            @create_booking_history_record($id, 'BookingUnit', 'close_price', serialize($dates));

            return $this->messages($booking);
//        }

//        return redirect()->back()->with('error', 'لا يمكن إغلاق تلك الفترة ، برجاء التأكد من التواريخ المختارة.');
    }

    public function waiting($date_id){
        $date = BookingDate::findOrFail($date_id);
        if ($date->unit->unit->user->id != auth()->id())
            abort(404);

        return view('Reservations.Prices.counter', [
            'page_title' => '',
            'date' => $date
        ]);
    }

    public function open(Request $request, $unit_id){

        $dates = CarbonPeriod::create($request->from, $request->to);
        $dates_arr = [];

        foreach ($dates as $date){
            $dates_arr[] = $date->format('Y-m-d 00:00:00');
            $dates_arr[] = $date->format('Y-m-d 23:59:59');
        }

        $dates_delete = BookingDate::whereIn('from', $dates_arr)->where('type', 'closed')->where('unit_id', $unit_id)->delete();

        return $this->messages($dates_delete);
    }

    private function checkAvailabilityCloseOpen($unit_id, $start_date, $end_date){
        return is_null(BookingAvailability::select('id')
            ->where('unit_id', $unit_id)
            ->where(function ($q) use ($start_date, $end_date){
                $q->where(function ($q) use ($start_date, $end_date){
                    $q->where('from', '<=', $start_date)
                        ->where('to', '>=', $end_date);
                })
                ->orWhere(function ($q) use ($start_date, $end_date){
                    $q->where('from' , '<=', $end_date)
                        ->where('to', '>= ', $start_date);
                });
            })
            ->first());
    }

    private function dateFormat($date, $sub = null){
        $date = Carbon::parse($date);
        if ($sub){
            $date = $date->subDay();
        }
        return $date->format($this->date_time_format);
    }

    private function updatePeriods($unit_id, $from, $to, $start_date, $end_date){
        $newClosePeriods[] = array(
            'unit_id' => $unit_id,
            'type' => 'closed',
            'from' => $from,
            'to' => $start_date
        );

        $newClosePeriods[] = array(
            'unit_id' => $unit_id,
            'type' => 'closed',
            'from' => $end_date,
            'to' => $to
        );


        return $newClosePeriods;
    }
}
