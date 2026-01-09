<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $fillable = [
        'hismodel_id',
        'hismodel_type',
        'user_id',
        'type',
        'extra'
    ];

    public function hismodel(){
        return $this->morphTo(__function__, 'hismodel_type', 'hismodel_id');
    }

    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
