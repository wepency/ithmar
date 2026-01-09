<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\settingsResource;
use App\Models\Setting;

class settingsController extends Controller
{
    public function get(){
        return settingsResource::make(Setting::first());
    }
}
