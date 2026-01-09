<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class notificationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('sectorAdmin');
    }

    public function index() {
        $notifications = auth()->user()->notifications()->paginate();
        return view('admin.notifications.all', compact('notifications'));
    }
}
