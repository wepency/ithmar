<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ImagesController extends Controller
{
    public function uploadMultiple(Request $request){
        $images = [];

        for ($i=0;$i<$request->TotalFiles;$i++){
            $file = $request->file('file-'.$i);
            $filename = Str::slug($file->getClientOriginalName()).'-'.rand(1111,9999).'.'.$file->getClientOriginalExtension();
            if ($file->move(UNIT_GALLERY_TEMP, $filename))
                $images[] = $filename;
        }

        return response()->json($images);
    }
}
