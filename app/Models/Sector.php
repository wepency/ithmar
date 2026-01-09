<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    use HasFactory;

    protected $fillable = [
        'sector_name',
        'user_id',
        'percentage'
    ];

    public function beach(){
        return $this->hasMany(Beach::class, 'sector_id', 'id');
    }

    public function unit(){
        return $this->hasMany(Unit::class, 'sector_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
