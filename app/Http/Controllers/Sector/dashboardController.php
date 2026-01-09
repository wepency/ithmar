<?php

namespace App\Http\Controllers\Sector;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class dashboardController extends Controller
{
    public function __invoke() {
        return view('Sectors.dashboard');
    }
}
