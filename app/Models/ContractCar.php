<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractCar extends Model
{
    use HasFactory;

    protected $table = 'contract_cars';

    protected $fillable = [
        'contract_id',
        'car_type',
        'car_serial',
        'passenger_name',
        'identity',
        'sort_order',
        'is_edited',
    ];

    protected $casts = [
        'is_edited' => 'boolean',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
}
