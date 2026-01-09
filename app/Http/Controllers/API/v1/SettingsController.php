<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Traits\generateAPI;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    use generateAPI;

    public function whatsapp(){
        return $this->success([
            'whatsapp' => '+966548408369'
        ]);
    }
}
