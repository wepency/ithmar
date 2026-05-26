<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractCompanion extends Model
{
    use HasFactory;

    protected $table = 'contract_companions';

    protected $fillable = [
        'contract_id',
        'title',
        'name',
        'id_number',
        'nationality',
        'barcode_image',
        'sort_order',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
}
