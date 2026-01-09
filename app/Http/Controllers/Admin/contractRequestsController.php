<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\History;
use App\Notifications\ContractAcceptNotifications;
use App\Notifications\ContractNotifications;
use Carbon\Carbon;
use Illuminate\Http\Request;

class contractRequestsController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function requests(){
        $page_title = 'طلبات العقود';

        $rows = Contract::whereNull('is_accepted')
                        ->whereNull('is_cancelled')
                        ->where('payment_type', '!=', 'phone')->orderBy('id', 'DESC')->paginate();

        return view('admin.contract.requests', compact('page_title', 'rows'));
    }

    public function acceptContract($id, $status){
        // 'phone','accepted','paid','unpaid','pay_later','exempt','rejected'

        $contract = Contract::findOrFail($id);

        if ($status == 'accepted'){
            $contract->is_accepted = 1;
//            @$contract->user->notify(new ContractAcceptNotifications($contract));

            if ($contract->payment_type == 'pay_later' || $contract->payment_type == 'exempt')
                $contract->status = 1;
        }else{
            $contract->is_cancelled = 1;
        }

        History::create([
            'hismodel_id' => $contract->id,
            'hismodel_type' => 'App\Models\Contract',
            'type' => $status,
            'user_id' => auth()->id(),
            'created_at' => Carbon::now(),
            'updated_at' => ''
        ]);

        cache()->forget('contracts-count');

        if ($contract->save())
            return redirect()->back()->withSuccess('تم تعديل حالة العقد بنجاح.');

    }
}
