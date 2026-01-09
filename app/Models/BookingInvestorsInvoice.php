<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingInvestorsInvoice extends Model
{
    use HasFactory;

    public function unit(){
        return $this->belongsTo(BookingUnit::class, 'booking_unit_id');
    }
}
