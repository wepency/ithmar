<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bond extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function sector(){
        return $this->belongsTo(Sector::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function recalculate(){
        $from = Carbon::parse($this->from)->format('Y-m-d 00:00:00');
        $to = Carbon::parse($this->to)->format('Y-m-d 23:59:59');

        $contracts = Contract::forBonds($this->sector_id, $from, $to);

        return $this->update([
            'amount' => round(($contracts->sum('price') * $this->sector->percentage) / 100, 2),
            'count' => $contracts->count()
        ]);
    }

    public static function coveringContract(Contract $contract){
        $created_at = Carbon::parse($contract->created_at);

        return static::where('sector_id', $contract->sector_id)->get()
            ->filter(function ($bond) use ($created_at){
                return $created_at->between(
                    Carbon::parse($bond->from)->startOfDay(),
                    Carbon::parse($bond->to)->endOfDay()
                );
            });
    }
}
