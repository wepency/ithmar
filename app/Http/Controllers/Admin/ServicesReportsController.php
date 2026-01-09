<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Beach;
use App\Models\Contract;
use App\Models\Sector;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ServicesReportsController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin');
    }

    private function filter($q)
    {
        if(request('from') AND request('to'))
        {
            $from = str_replace('-','/',request('from'));
            $to = str_replace('-','/',request('to'));

            return $q->whereDate('from', '>=', $from)->whereDate('to', '<=', $to)->get();
        }
    }

    public function get(Request $request, Contract $contract){

        $rows = Contract::with('unit', 'sector', 'beach', 'user', 'services', 'services.service');

        $sum = $contract->select('services_total')
            ->valid()
            ->sum('services_total');

        if ($request->sector != ''){
            $rows = $rows->where('sector_id', $request->sector);
        }

        if ($request->beach != ''){
            $rows = $rows->where('beach_id', $request->beach);
        }

        if ($request->code != ''){
            $rows = $rows->where('code', 'like', '%'.$request->code.'%');
        }

        if ($request->phonenumber != ''){
            $rows = $rows->whereHas('user', function (Builder $builder) use ($request){
                $builder->where('phonenumber', 'like', '%'.$request->phonenumber.'%');
            });
        }

        if ($request->from != '' && $request->to != ''){
            $contracts = $rows->where(function ($q){
                return $this->filter($q);
            });;
        }

        $sectors = Sector::orderby('sector_name', 'ASC')->get();

        $rows = $rows->where('services_total', '>', '0')->whereHas('services')->valid()->orderby('id', 'DESC')->paginate();

        return view('admin.reports.services.index',[
            'rows' => $rows,
            'sum' => $sum,
            'sectors' => $sectors
        ]);
    }
}
