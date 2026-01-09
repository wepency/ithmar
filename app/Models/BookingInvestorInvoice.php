<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingInvestorInvoice extends Model
{
    use HasFactory;

    protected $table = 'booking_investor_invoices';

    public function unit(){
        return $this->belongsTo(BookingUnit::class, 'booking_unit_id');
    }

    public function violation_rows(){
        return $this->hasMany(Violation::class, 'investor_invoices_id');
    }
}
