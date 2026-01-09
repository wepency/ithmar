<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wallet extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'wallet';

    protected $reservation_array = [
        'booking_downpayment',
        'booking_downpayment_locked',
        'booking_downpayment_total',

        'booking_cancelled_locked_with-commission',
        'booking_cancelled_locked_without-commission',
        'booking_cancelled_withdrawable_with-commission',
        'booking_cancelled_withdrawable_without-commission'
    ];

    protected $guarded = [];

    public function contract(){
        return $this->belongsTo(Contract::class, 'model_id', 'id');
    }

    public function scopeAddedCredit($query){
        return $query->where(function ($q){
            $q->whereNotIn('type', $this->reservation_array)->orWhereNull('type');
        });
    }

    public function scopeLockedCredit($query){
        return $query->where('type', 'booking_downpayment_locked')->whereNull('status');
    }

    public function scopeWithdrawableCredit($query){
        return $query->where('type', 'booking_downpayment')->whereNull('request_id')->whereNull('status');
    }
}
