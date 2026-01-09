<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BeachesResource;
use App\Models\Beach;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class beachesController extends Controller
{
    public function getBeaches($id) {
        return BeachesResource::collection(Beach::where('sector_id', $id)->orderBy('id', 'ASC')->get());
    }

    public function getUnits($id) {
        return BeachesResource::collection(Unit::where('beach_id', $id)->where('status', 1)->orderBy('unit_number', 'DESC')->get());
    }

    public function getBeachesForInvestor($id) {
        $beaches = Beach::whereHas('unit', function (Builder $q){
            $q->where('user_id', Auth::id())->where('status', 1);
        })->where('sector_id', $id)->orderBy('id', 'ASC')->get();

        return BeachesResource::collection($beaches);
    }
}
