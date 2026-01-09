<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SectorsResource;
use App\Http\Resources\UnitsResource;
use App\Models\Beach;
use App\Models\Sector;
use App\Models\Unit;
use App\Traits\generateAPI;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\BeachesResource;

class AvailableController extends Controller
{
    use generateAPI;

    public function sectors() {
        $sectors = Sector::orderBy('sector_name', 'ASC')->get();

        return $this->success([
            'data' => SectorsResource::collection($sectors)
        ]);
    }

    public function beaches($id) {
        $beaches = Beach::whereHas('unit', function (Builder $q){
            $q->where('user_id', auth()->id())->where('status', 1);
        })->where('sector_id', $id)->orderBy('id', 'ASC')->get();

        return $this->success([
            'data' => BeachesResource::collection($beaches)
        ]);
    }

    public function units($id) {
        return $this->success([
            'data' => UnitsResource::collection(Unit::where('beach_id', $id)
                ->where('user_id', auth()->id())
                ->where('status', 1)
                ->valid()
                ->orderBy('unit_number', 'DESC')->get()
            )
        ]);
    }

    public function unitsType(){
        return $this->success([
            'data' => collect([
                [
                    'type' => 'in'
                ],
                [
                    'type' => 'apart'
                ],
                [
                    'type' => 'villa'
                ],
                [
                    'type' => 'palace'
                ],
            ])
        ]);
    }
}
