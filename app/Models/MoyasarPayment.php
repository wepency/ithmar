<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoyasarPayment extends Model
{
    use HasFactory;

    protected $primaryKey = 'moyasar_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'moyasar_id',
        'user_id',
        'model_type',
        'model_id',
        'amount',
        'status',
        'metadata'
    ];

        protected $casts = [

            'metadata' => 'array'

        ];

    

        public function user()

        {

            return $this->belongsTo(User::class);

        }

    }

    