<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\v1\BookingsResource;
use App\Http\Resources\API\v1\InvoicesResources;
use App\Http\Resources\API\v1\ViolationsResource;
use App\Models\BookingInvestorInvoice;
use App\Models\BookingInvoice;
use App\Traits\generateAPI;
use Illuminate\Http\Request;

class InvoicesController extends Controller
{
    use generateAPI;

    public function count(){
        return $this->success([
            'count' => 2,
            'unread' => false
        ]);
    }

    public function index(){
        $invoices = BookingInvestorInvoice::where('investor_id', auth()->id())->where(function ($q){
            return $q->where('total', 0)->orWhere('violations', 0);
        })->where('profit', '>', 0)->orderBy('id', 'DESC')->get();

        return InvoicesResources::collection($invoices);
    }

    public function single(Request $request, $invoice_id){
//        try {
            $invoice = BookingInvestorInvoice::find($invoice_id);

            $all_invoices = BookingInvoice::whereHas('booking.unit', function ($q) use ($invoice){
                $q->where('id', $invoice->booking_unit_id);
            })->whereBetween('created_at', [$invoice->start_date, $invoice->end_date])->paginate(25);

            $invoice_number = str_pad($invoice_id,6,'0',STR_PAD_LEFT);

            return $this->success([
                'invoice_number' => $invoice_number,
                'bookings' => BookingsResource::collection($all_invoices),
                'violations' => ViolationsResource::collection($invoice->violation_rows)
            ]);

//        }catch (\Exception $e) {
//            return $this->error(null, $e->getMessage(), $e->getCode());
//        }
    }

    public function pay($id){
        $id = deep_decode($id);
        $invoice = BookingInvestorInvoice::findOrFail($id);

        return 'جاري التحويل';
    }

    public function show($id){
        return 'عرض الفاتورة';
    }
}
