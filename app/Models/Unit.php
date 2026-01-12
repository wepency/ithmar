<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_number',
        'sector_id',
        'beach_id',
        'user_id',
        'attachment_1',
        'status',
        'type',
        'valid_to',
        'renewed',
        'count',
        'note',
        'price',
        'vat',
        'total'
    ];

    public function beach(){
        return $this->belongsTo(Beach::class, 'beach_id', 'id');
    }

    public function sector(){
        return $this->belongsTo(Sector::class, 'sector_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function attachments(){
        return $this->morphMany(Attachment::class, 'attachmentable', 'type', 'type_id')->orderBy('id', 'DESC');
    }

    public function scopeExpired($q){
        return $q->where('valid_to', '<', Carbon::now())->where('type', 'investor')->whereNull('is_terminated');
    }

    public function scopeTerminated($q){
        return $q->where('is_terminated', 1);
    }

    public function scopeBlocked($q){
        $q->where(function ($query){
            $query->where('is_terminated', 1)
                ->orWhere('status', 2);
        });
    }

    public function scopeValid($q){
        $q->where(function ($query){
            $query->where('valid_to', '>', Carbon::now())
                ->orWhere('type', 'owner');
       })->where('status', 1);
    }
}
