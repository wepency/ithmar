<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingDate extends Model
{
    use HasFactory;

    protected $table = 'booking_dates';
    protected $guarded = [];

    public function unit(){
        return $this->belongsTo(BookingUnit::class, 'unit_id');
    }

    public function contract(){
        return $this->belongsTo(Contract::class);
    }
}
