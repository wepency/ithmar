<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingUnit extends Model
{
    use HasFactory;

    protected $table = 'booking_units';
    protected $guarded = [];

    public function unit(){
        return $this->belongsTo(Unit::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function unitGallery(){
        return $this->hasMany(UnitGallery::class)->orderBy('term', 'DESC');
    }

//    public function scopeActive($q){
//        return $q->where('status' , 1);
//    }

    public function scopeActive($q){
        $q->whereHas('unit', function ($query){
            $query->where(function ($q){
                $q->where('valid_to', '>', Carbon::now())
                    ->orWhere('type', 'owner');
            })->where('status', 1)->whereNull('is_terminated');
        })->where('status', 1);
    }
}
