<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\NotificationsResource;
use App\Http\Resources\API\v1\UnitsResource;
use App\Models\BookingUnit;
use Illuminate\Http\Request;

class UnitsController extends Controller
{
    public function getUnits(){

        $units = BookingUnit::whereHas('unit.user', function ($q){
           $q->where('user_id', auth()->id());
        })->active()->with('unit', 'unit.beach', 'unit.sector')->get();

        return UnitsResource::collection($units);
    }
}
