<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class twoFactorToken extends Model
{
    protected $table = 'two_factor_tokens';

    protected $fillable = [
        'user_id',
        'code'
    ];

    use HasFactory;
}
