<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Contract extends Model
{
    use Notifiable;

    use HasFactory;

    protected $fillable = [
        "sector_id",
        "user_id",
        "beach_id",
        "unit_id",
        "from",
        "to",
        "tenant_title",
        "tenant_name",
        "with_tenant_name",
        "rent_value",
        "attachment_1",
        "attachment_2",
        "tenant_name_code",
        "with_tenant_name_code",
        "tenant_nationality",
        "with_tenant_nationality",
        "with_tenant_title",
        "insurance_value",
        'price',
        'vat',
        'total',
        'code',
        'token',
        'status',
        'is_accepted',
        'is_cancelled',
        'excluded_from_bonds',
        'phonenumber',
        'payment_type',
        'reservation_id',
        'birth_date'
    ];

    protected $nullable = [
        "car_type2",
        "car_serial2",
        "car_type3",
        "car_serial3"
    ];

    public function unit(){
        return $this->belongsTo(Unit::class);
    }

    public function reservation(){
        return $this->belongsTo(Bookings::class, 'reservation_id');
    }

    public function sector(){
        return $this->belongsTo(Sector::class);
    }

    public function beach(){
        return $this->belongsTo(Beach::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function services(){
        return $this->hasOne(contractService::class);
    }

    public function cars(){
        return $this->hasMany(Car::class);
    }

    public function companions(){
        return $this->hasMany(ContractCompanion::class)->orderBy('sort_order');
    }

    public function bond(){
        return $this->hasMany(InvestorBonds::class);
    }

    public function scopeForBonds($query, $sector_id, $from, $to){
        $query->where('sector_id', $sector_id)
            ->whereBetween('created_at', [$from, $to])
            ->where('status', 1)
            ->where('is_accepted', 1)
            ->whereNull('is_cancelled')
            ->whereNull('excluded_from_bonds')
            ->where(function ($q) {
                $q->where('payment_type', 'paid')
                    ->orWhere('payment_type', 'pay_later');
            });
    }

    public function scopeListed($query, $code = null){
        // Contracts excluded from the bonds drop out of every listing; the only
        // way back to one is searching for its code.
        $query->where(function ($q) use ($code){
            $q->whereNull('excluded_from_bonds');

            if (filled($code)){
                $q->orWhere('code', 'like', '%'.$code.'%');
            }
        });
    }

    public function scopeValid($query){
        $query
            ->where('status', 1)
            ->where('is_accepted', 1)
            ->whereNull('is_cancelled')
            ->orderBy('created_at', 'DESC');
    }

    public function scopeValidForReport($query){
        $query
            ->where('status', 1)
            ->where('is_accepted', 1)
            ->orderBy('created_at', 'DESC');
    }

    public function scopeActive($query){
        // 'phone','accepted','paid','unpaid','pay_later','exempt','rejected'

        $query->whereDate('to', '>', Carbon::now())
            ->where('status', 1)
            ->where('is_accepted', 1)
            ->whereNull('is_cancelled')
            ->where(function ($q){
                $q->where('payment_type', 'paid')
                    ->orWhere('payment_type', 'pay_later')
                    ->orWhere('payment_type', 'exempt');
            });
    }

    public function scopeSigned($query){
        $query->whereDate('to', '>=', Carbon::now())
//            ->where('status', 0)
            ->where(function ($q){
                $q->whereIn('payment_type', ['phone','unpaid'])
                    ->orWhereNull('is_accepted');
            })
//            ->whereNull('is_accepted')
            ->whereNull('is_cancelled');
//            ->where(function ($q){
//                $q->where('payment_type', 'phone')
//                    ->orWhere('payment_type', 'accepted')
//                    ->orWhere('payment_type', 'unpaid');
//            });
    }

    public function scopeRequests($query){
        $query->whereNull('is_accepted')
            ->whereNull('is_cancelled')
            ->where('payment_type', '!=', 'phone')->orderBy('id', 'DESC')->count();
    }

    public function history(){
        return $this->morphOne(History::class, 'hismodel');
    }
}
