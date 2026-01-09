<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use App\Models\History;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class attachmentsController extends Controller
{
    public function index($id){
//        return $id;
        $attachments = Attachment::where('type_id', $id)->where('type', 'App\Models\Unit')->orderBy('id', 'DESC')->get();
        return view('admin.units.attachments', compact('attachments'))->render();

    }

    public function upload(Request $request){
        if ($request->ajax()){
            $path = 'attachments';

            if ($request->hasFile('files')){
                $image = $request->file('files');
                $unit_number = $request->unit_number;
                $unit   = Unit::with('beach', 'sector')->findOrFail($unit_number);
                $beach  = $unit->beach->beach ?? '';
                $sector = $unit->sector->sector_name ?? '';

                $image_original_name = Str::slug($image->getClientOriginalName());

                $image_name = Str::slug($unit_number.'-'.$beach.'-'.$sector.'-'.date('d m Y H-i-s'));
                $image_path = $image_name.'.'.$image->getClientOriginalExtension();

                if ($image->move($path, $image_path)){
                    Attachment::create([
                        'image_name' => $image_original_name,
                        'type' => 'App\Models\Unit',
                        'type_id' => $unit_number,
                        'path' => $path.'/'.$image_path
                    ]);

                    return response()->json('تم حفظ الصوره بنجاح.');
                }
            }

        }

        abort(404);
    }

    public function delete(Request $request, $code){
        if ($request->ajax()){
            $attach = Attachment::findOrFail(base64_decode($code));

            if (!is_null($attach)){
                if (file_exists(public_path($attach->path))){
                    File::delete(public_path($attach->path));
                }

                $attach->delete();
            }
            return response()->json();
        }
        abort(404);
    }

    public function download($id){
        $id = base64_decode($id);

        $attach = Attachment::findOrFail($id);

        History::create([
            'hismodel_id' => $attach->id,
            'hismodel_type' => 'App\Models\Attachment',
            'type' => 'view',
            'user_id' => auth()->id(),
            'created_at' => Carbon::now(),
            'updated_at' => null
        ]);

        return response()->json([
            'link' => asset($attach->path),
            'name' => $attach->image_name
        ]);
    }


}
