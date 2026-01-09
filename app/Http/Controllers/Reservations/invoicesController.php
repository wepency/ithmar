<?php

namespace App\Http\Controllers\Reservations;

use App\Http\Controllers\Controller;
use App\Models\BookingInvestorInvoice;
use App\Models\BookingInvoice;

class invoicesController extends Controller
{
    public function index(){
        $invoices = BookingInvestorInvoice::where('investor_id', auth()->id())->where('profit', '>', 0)->orderBy('id', 'DESC')->get();

        return view('Reservations.Invoices.index', [
            'page_title' => 'الفواتير الشهرية',
            'invoices' => $invoices
        ]);
    }

    public function show($invoice_id){
        $id = base64_decode($invoice_id);

        $invoice = BookingInvestorInvoice::findOrFail($id);

        $all_invoices = BookingInvoice::whereHas('booking.unit', function ($q) use ($invoice){
            $q->where('id', $invoice->booking_unit_id);
        })->whereBetween('created_at', [$invoice->start_date, $invoice->end_date])->paginate(25);

        $invoice_number = str_pad($id,6,'0',STR_PAD_LEFT);

        return view('Reservations.Invoices.show', [
            'page_title' => 'تفاصيل الفاتورة : '.$invoice_number,
            'invoices' => $all_invoices,
            'invoice_onj' => $invoice
        ]);
    }

    public function render($id){
        $id = deep_decode($id);
        $invoice = BookingInvestorInvoice::findOrFail($id);

        return view('Reservations.Invoices.html', [
            'page_title' => 'فاتورة '.$invoice->id,
            'invoice' => $invoice
        ]);
    }
}
