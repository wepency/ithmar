<?php

namespace App\Classes;

use App\Models\Contract;
use Carbon\Carbon;

class Contracts
{
    protected $contracts;


    public function __construct()
    {

    }

    public function render(){
        $contracts = Contract::where('user_id', auth()->id());

        if (request()->type === 'active'){
            $contracts = $contracts->active();
        }else if(request()->type === 'signed') {
            $contracts = $contracts->signed();
        }

        if (request()->sorting != ''){
            $contracts = $contracts->where('payment_type', request()->sorting);
        }

        $this->contracts = $contracts->with('unit', 'beach', 'sector')->orderBy('id', 'DESC')->paginate(20);

        return view('contracts.table', [
            'contracts' => $this->contracts
        ])->render();
    }

    public function getLinks(){
        return $this->contracts;
    }

    function get_contract_status($contract){

        $output = '';

        // 'phone','accepted','paid','unpaid','pay_later','exempt','rejected'
        switch ($contract->payment_type){
            case 'phone':
            case 'accepted':
            case 'unpaid':
                $output = "<span class='badge badge-warning'>غير مدفوع</span>";
                break;
            case 'pay_later':
                $output = "<span class='badge badge-success'>آجل</span>";
                break;
            case 'exempt':
                $output = "<span class='badge badge-success'>معفي</span>";
                break;
            case 'paid':
                $output = "<span class='badge badge-success'>مدفوع</span>";
                break;
        }

        if($contract->status == 1 && $contract->payment_type == 'rejected') {
            $output = "<span class='badge badge-success'>مدفوع</span>";
        }

        return $output;
    }

    function get_contract_badge($contract){

        if($contract->is_accepted == 1 && $contract->payment_type == 'phone') {
            return "<span class='badge badge-warning'>تمت الموافقة و بإنتظار تفعيل رقم الهاتف</span>";
        }

        if (!is_valid($contract)){
            return "<span class='badge badge-danger'>انتهى</span>";
        }elseif($contract->payment_type == 'rejected'){
            return "<span class='badge badge-danger'>ملغي</span>";
        }elseif($contract->payment_type == 'unpaid'){
            return "<span class='badge badge-warning'>بانتظار الدفع</span>";
        }

        return "<span class='badge badge-success'>فعال</span>";
    }

    public function get_contract_table($contract){

        // 'phone','accepted','paid','unpaid','pay_later','exempt','rejected'
        $danger = ['rejected'];
        $warning = ['phone', 'unpaid', 'rejected'];

        if (!is_valid($contract) || in_array($contract->payment_type, $danger)){
            return "table-danger";
        }

        if(in_array($contract->payment_type, $warning)){
            return "table-warning";
        }

        return '';
    }
}
