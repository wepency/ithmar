<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beach extends Model
{
    use HasFactory;

    protected $fillable = [
        'beach',
        'sector_id',
        'allowed_cars'
    ];

    public function unit(){
        return $this->hasMany(Unit::class, 'beach_id', 'id');
    }

    public function sector(){
        return $this->belongsTo(Sector::class, 'sector_id', 'id');
    }

    public function terms(){
        return $this->hasMany(BeachTerm::class, 'beach_id', 'id');
    }
}
