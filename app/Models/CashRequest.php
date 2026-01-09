<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashRequest extends Model
{
    use HasFactory;
    protected $table = 'cash_requests';

    protected $fillable = [
        'user_id',
        'amount',
        'holder_name',
        'bank_name',
        'bank_account',
        'iban',
        'extra'
    ];
}
