<?php

namespace App\Http\Controllers;

use App\Models\Sector;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('FrontEnd');
    }

    public function index(){

        if (auth()->check() && is_factor_auth()){
            return view('Investors.home');
        }

        $sectors = Sector::orderBy('sector_name')->get();
        return view('home', compact('sectors'));
    }
}
