<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phonenumber',
        'email',
        'role',
        'role_id',
        'blocked',
        'blocked_note',
        'user_free',
        'user_exempt',
        'down_payment',
        'calendar_logged',
        'ip',
        'fcm_token',
        'two_factor',
        'factor_validated'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function unit(){
        return $this->hasMany(Unit::class);
    }

    public function contracts(){
        return $this->hasMany(Contract::class);
    }

    public function unitOrder(){
        return $this->hasMany(Unit::class)->orderBy('id', 'DESC');
    }

    public function sector(){
        return $this->belongsTo(Sector::class, 'role_id', 'id');
    }

    public function services(){
        return $this->belongsToMany(Service::class, 'user_services')->withPivot('id');
    }

    public function credit(){
        return $this->hasMany(Wallet::class);
    }

    public function banks(){
        return $this->hasMany(BankingInfo::class);
    }

//    public function banks(){
//        return $this->hasMany(BankingInfo::class);
//    }
}
