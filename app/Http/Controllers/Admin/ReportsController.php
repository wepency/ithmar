<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Beach;
use App\Models\Contract;
use App\Models\Sector;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('sectorAdmin');
    }

    private function filter($q)
    {
        if(request('from') AND request('to'))
        {
            $from = str_replace('-','/',request('from'));
            $to = str_replace('-','/',request('to'));

            return $q->whereBetween('created_at', [$from, $to])->get();
        }
    }

    public function index(Request $request)
    {
        $contracts = new Contract;

        $sectors = Sector::orderBy('sector_name', 'DESC')->get();
        $beaches = Beach::where('sector_id', $sectors[0]->id ?? 0)->get();
        $percentage = 0;

        if (auth()->user()->role == 'admin'){
            if ($request->sector){
                $contracts = $contracts->where('sector_id', $request->sector);
                $percentage = @Sector::find($request->sector)->percentage ?? 0;
                $beaches = Beach::where('sector_id', $request->sector)->get();
            }
        }else{
            $contracts = $contracts->where('status', 1)->where('payment_type', '!=', 'exempt')->where('sector_id', auth()->user()->role_id);
            $percentage = Sector::where('user_id', auth()->id())->first();

            $percentage = is_null($percentage) ? 1 : $percentage->percentage;

            $beaches = Beach::where('sector_id', auth()->user()->role_id)->get();
        }

        if ($request->beach != ''){
            $contracts = $contracts->where('beach_id', $request->beach);
        }

        if ($request->payment_type != ''){
            $contracts = $contracts->where('payment_type', $request->payment_type);
        }

        if ($request->code != ''){
            $contracts = $contracts->where('code', 'like' , '%'.$request->code.'%');
        }

        if ($request->from != '' && $request->to != ''){
            $contracts = $contracts->whereBetween('created_at', [$request->from, $request->to]);
        }

        if ($request->phonenumber != ''){
            $contracts = $contracts->whereHas('user', function (Builder $builder) use ($request){
                $builder->where('phonenumber', 'like', '%'.$request->phonenumber.'%');
            });
        }

        $sum = $contracts
                ->ValidForReport()
                ->where(function ($q) use ($request) {
                    if ($request->payment_type == '')
                    $q->where('payment_type', 'paid')
                        ->orWhere('payment_type', 'pay_later');
                })
                ->sum('price');

        $sum = $this->format($sum);
        $percentage = $this->format($percentage);
        $total = $this->format((($sum * $percentage) / 100));

        $rows = $contracts
                ->ValidForReport()
                ->where(function ($q) {
                    $q->where('payment_type', 'paid')
                        ->orWhere('payment_type', 'pay_later');
                })
                ->paginate();

        return view('admin.reports.index', compact('rows', 'sectors', 'beaches', 'sum', 'percentage', 'total'));
    }

    public function format($number){
        return number_format($number, 2, '.', '');
    }

}
