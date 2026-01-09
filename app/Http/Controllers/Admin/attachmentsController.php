<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use Illuminate\Http\Request;

class attachmentsController extends Controller
{
    public function show($id)
    {
        $attachment = Attachment::findOrFail($id);

        $page_title = 'سجل المرفق '.$attachment->name;
        $history = (new \App\Classes\History)->getAllHistory('App\Models\Attachment', $id, 'attachments');
        return view('admin.history', compact('history', 'page_title'));
    }
}
