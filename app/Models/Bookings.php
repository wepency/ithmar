<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookings extends Model
{
    use HasFactory;

    protected $table = 'booking_bookings';

    protected $fillable = [
        'status',
        'refund_id',
        'contract_id',
        'contract_ready'
    ];

    public function unit(){
        return $this->belongsTo(BookingUnit::class);
    }

    public function user(){
        return $this->belongsTo(BookingUser::class, 'booking_user_id');
    }

    public function contract(){
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    public function refund(){
        return $this->hasOne(refund::class, 'id', 'refund_id');
    }
}
