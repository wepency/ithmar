<?php

namespace App\Traits;

use App\Models\Contract;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

trait DivDays
{
    private function divDays($available, $dates, $api = false){
        $date_arr = [];

        foreach ($available as $dateObj){
            $date = Carbon::parse($dateObj->date)->format('Y-m-d');

            $date_arr[$date] = array(
                'day' => $date,
                'type' => 'available',
                'price' => $dateObj->price ?? 1,
                'min_stay' => $dateObj->min_stay ?? 1,
                'id' => $dateObj->id,
                'color' => '#58bae4',
//                'href' => route('availability.edit', [$unit_id, base64_encode(base64_encode($date->id))])
            );
        }

        $sub_types = ['waiting', 'pending', 'approved', 'contract'];

        $sub_colors = [
            'waiting' => '#f1c40f',
            'pending' => '#2ecc71',
            'approved' => '#e74c3c',
            'contract' => '#ff91a2',
            'closed' => '#34495e'
        ];

        foreach ($dates as $date){
            if (in_array($date->type, $sub_types)){
                $to = Carbon::parse($date->to)->subDay()->format('Y-m-d');
            }else{
                $to = $date->to;
            }

            $periods = CarbonPeriod::create($date->from, $to);

            foreach ($periods as $period) {
                $period = $period->format('Y-m-d');
                $url = '';

                if ($date->type == 'waiting'){
                    $url = route('availability.waiting', $date->id);
                }elseif($date->type == 'contract'){
                    $url = url('contract/'.$date->contract->code);
                }

                $date_arr[$period] = array(
                    'day' => $period,
                    'type' => $date->type,
                    'color' => $sub_colors[$date->type],
                    'id' => '',
                    'href' =>  $url
                );
            }
        }

        return $date_arr;
    }

    private function divContractDays($unit_id, $from, $to, $api = false){
        $date_arr = [];

        $from_plus = Carbon::parse($from)->addDay()->format('Y-m-d H:i:s');
        $to_sub = Carbon::parse($to)->subDay()->format('Y-m-d H:i:s');

        $contracts = Contract::where(function ($q) use ($from, $to, $to_sub, $from_plus){
            $q->whereBetween('from', [$from, $to_sub])
                ->orWhereBetween('to', [$from_plus, $to])
                ->orWhere(function ($query) use ($from, $to){
                    $query->where('from', '<=', $from)
                        ->where('to', '>=', $to)->get();
                })
                ->get();
        })->whereNull('is_cancelled')->where('unit_id', $unit_id)->get();

        foreach ($contracts as $contract){
            $contract_dates = CarbonPeriod::dates($contract->from, $contract->to);
            $i = 0;

            foreach ($contract_dates as $key => $dateObj){
                if ($i == count($contract_dates) - 1)
                    break;

                $date = Carbon::parse($dateObj)->format('Y-m-d');

                $date_arr[$date] = array(
                    'day' => $date,
                    'type' => 'contract',
                    'color' => '#ff91a2',
                    'id' => $key,
                    'href' => ''
//                'href' => route('availability.edit', [$unit_id, base64_encode(base64_encode($date->id))])
                );

                $i++;
            }
        }

        return $date_arr;
    }

}
