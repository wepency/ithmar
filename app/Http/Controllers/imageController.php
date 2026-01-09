<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class imageController extends Controller
{
    public function upload(){
        $folderPath = public_path('temp');

        $image_parts = explode(";base64,", $_POST['image']);

        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $temp_name = uniqid() . '.' .$image_type;
        $file = $folderPath .'/'. $temp_name;

        file_put_contents($file, $image_base64);

        return response()->json([
            'path' => public_path('temp/'.$temp_name),
            'request' => asset('temp/'.$temp_name)
        ]);
    }
}
