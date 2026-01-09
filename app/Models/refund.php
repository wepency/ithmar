<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class refund extends Model
{
    protected $table = 'clients_refund';

    protected $fillable = [
        'reservation_id',
        'verification_image',
        'is_verified'
    ];

    use HasFactory;
}
