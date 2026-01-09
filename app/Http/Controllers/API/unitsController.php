<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BeachesResource;
use App\Http\Resources\UnitsResource;
use App\Models\Beach;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class unitsController extends Controller
{
    public function getVillas($id) {
        return UnitsResource::collection(Unit::where('beach_id', $id)->orderBy('unit_number', 'DESC')->get());
    }

    public function getVillasForInvestor($id){
        return UnitsResource::collection(Unit::where('beach_id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 1)
            ->whereNull('is_terminated')
            ->valid()
            ->orderBy('unit_number', 'DESC')->get()
        );
    }

    public function getSingleBeach($id){
        $beach = Beach::findOrFail($id);
        return response()->json($beach);
    }
}
