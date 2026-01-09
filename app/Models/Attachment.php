<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'image_name',
        'type',
        'type_id',
        'path'
    ];

    public function attachmentable()
    {
        return $this->morphTo(__function__, 'type', 'type_id');
    }
}
