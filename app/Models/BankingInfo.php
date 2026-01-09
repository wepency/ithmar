<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankingInfo extends Model
{
    use HasFactory;

    protected $table = 'banking_info';
    protected $guarded = [];
}
