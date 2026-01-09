<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class pagesController extends Controller
{
    public function terms(){
        $page_title = 'اشتراطات التأهيل - مكتب إثمار';
        return view('pages.terms', compact('page_title'));
    }
}
