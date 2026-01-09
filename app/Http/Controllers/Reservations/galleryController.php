<?php

namespace App\Http\Controllers\Reservations;

use App\Http\Controllers\Controller;
use App\Models\BookingHistory;
use App\Models\BookingUnit;
use App\Models\Sector;
use App\Models\Unit;
use App\Models\UnitGallery;
use App\Models\UnitUpdates;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Stevebauman\Purify\Facades\Purify;

class galleryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $units = BookingUnit::where('user_id', auth()->id())->paginate(10);
        $active_units = BookingUnit::where('user_id', auth()->id())->active()->whereNotNull('status')->count();
        $pending_units = BookingUnit::where('user_id', auth()->id())->whereNull('status')->count();

        return view('Reservations.Gallery.index', [
            'page_title' => 'صور الوحدات',
            'units' => $units,
            'active_count' => $active_units,
            'pending_count' => $pending_units
        ]);
    }

    public function create(){

        $sectors = Sector::whereHas('unit', function (Builder $q){
            $q->where('user_id', auth()->id())->where('status', 1);
        })->orderBy('id', 'DESC')->get();

        $refused = Unit::where('status', 2)->where('user_id', auth()->id())->get();

        $expired = Unit::where(function ($q){
            $q->where('valid_to', '')->orWhere('valid_to', '<', Carbon::today());
        })->where('type', 'investor')->where('user_id', auth()->id())->get();

        $booking_units = BookingUnit::select('unit_id')->where('user_id', auth()->id())->get()->pluck('unit_id');

        $units = Unit::where('user_id', auth()->id())->whereNotIn('id', $booking_units)->where('status', 1)->valid()->count();
//        return $units;

        $gallery = new BookingUnit;

        return view('Reservations.Gallery.create', [
            'page_title' => '',
            'sectors' => $sectors,
            'refused' => $refused,
            'expired' => $expired,
            'gallery' => $gallery,
            'booking_units' => $units
        ]);
    }

    public function store(Request $request){
        $request->validate([
            'unit_id' => 'required|numeric',
            'unit_type' => 'required',
            'unit_name' => 'required|max:191',
            'unit_location' => 'required',
            'room_count' => 'required|numeric',
            'rooms.*' => 'required',
            'halls_count' => 'required|numeric',
            'halls.*' => 'nullable',
            'toilets_count' => 'required|numeric',
            'toilets.*' => 'required',
            'pools_count' => 'required|numeric',
            'pools.*' => 'nullable',
            'details' => 'nullable|max:16000',
//            'video_url' => 'nullable|mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4',
            'video_url' => 'nullable',
            'front_image' => 'required|mimes:jpg,png,jpeg,gif,pmb,svg,webp',
            'view_image' => 'required|mimes:jpg,png,jpeg,gif,pmb,svg,webp'
        ]);

        return DB::transaction(function () use($request) {

            $data['user_id'] = auth()->id();
            $data['unit_id'] = $request->unit_id;
            $data['unit_type'] = $request->unit_type;
            $data['room_count'] = $request->room_count;
            $data['halls_count'] = $request->halls_count;
            $data['toilets_count'] = $request->toilets_count;
            $data['pools_count'] = $request->pools_count;
            $data['kitchens_count'] = $request->kitchens_count;
            $data['details'] = Purify::clean($request->details);

            $data['unit_name'] = $request->unit_name;
            $data['slug']      = Str::slug($request->unit_name).'-'.rand(11111,99999);

            $data['unit_location'] = $request->unit_location;

            $data['need_approval'] = 1;

            // view_image
            $data['view_image'] = $this->generateAndSaveImage($request->file('view_image'));
            // front_image
            $data['front_image'] = $this->generateAndSaveImage($request->file('front_image'));


            if ($request->has('video_url') && $request->video_url != ''){
                $video = $request->file('video_url');
                $video_name = Str::slug($video->getClientOriginalName()).'-'.rand(1,9999).'.'.$video->getClientOriginalExtension();
                $video->move(UNIT_VIDEO_URL, $video_name);
                $data['video_url'] = $video_name;
            }

            $create_unit = BookingUnit::create($data);

            $rooms = $halls = $toilets = $pools = $kitchens = [];

            if (!empty($request->rooms)){
                foreach ($request->rooms as $key => $room){
                    if (key_exists($key, $request->rooms) && $request->rooms[$key]){
//                        $file = $request->rooms[$key];
//                        $file_name = $file->getClientOriginalName().'-'.rand(1111,9999).'.'.$file->getClientOriginalExtension();

//                        if ($file->move(UNIT_IMAGE_URL, $file_name)){
                            $rooms[$key]['image_path'] = $this->generateAndSaveGalleryImage($room);
                            $rooms[$key]['booking_unit_id'] = $create_unit->id;
                            $rooms[$key]['term'] = 'rooms';
                            $rooms[$key]['term_no'] = $request->rooms_no[$key];
//                        }
                    }
                }

                UnitGallery::insert($rooms);
            }

            if (!empty($request->halls)){
                foreach ($request->halls as $key => $hall){
                    if (key_exists($key, $request->halls) && $request->halls[$key]){

//                        return $request->halls_no[$key];
//                        $file = $request->halls[$key];
//                        $file_name = $file->getClientOriginalName().'-'.rand(1111,9999).'.'.$file->getClientOriginalExtension();

//                        if ($file->move(UNIT_IMAGE_URL, $file_name)){
                            $halls[$key]['image_path'] = $this->generateAndSaveGalleryImage($hall);
                            $halls[$key]['booking_unit_id'] = $create_unit->id;
                            $halls[$key]['term'] = 'hall';
                            $halls[$key]['term_no'] = $request->halls_no[$key];
//                        }
                    }
                }

                UnitGallery::insert($halls);
            }

            if (!empty($request->toilets)){
                foreach ($request->toilets as $key => $toilet){
                    if (key_exists($key, $request->toilets) && $request->toilets[$key]){
//                        $file = $request->toilets[$key];
//                        $file_name = $file->getClientOriginalName().'-'.rand(1111,9999).'.'.$file->getClientOriginalExtension();

//                        if ($file->move(UNIT_IMAGE_URL, $file_name)){
                            $toilets[$key]['image_path'] = $this->generateAndSaveGalleryImage($toilet);
                            $toilets[$key]['booking_unit_id'] = $create_unit->id;
                            $toilets[$key]['term'] = 'toilets';
                            $toilets[$key]['term_no'] = $request->toilets_no[$key];
//                        }
                    }
                }

                UnitGallery::insert($toilets);
            }

            if (!empty($request->pools)){
                foreach ($request->pools as $key => $pool){
                    if (key_exists($key, $request->pools) && $request->pools[$key]){
//                        $file = $request->pools[$key];
//                        $file_name = $file->getClientOriginalName().'-'.rand(1111,9999).'.'.$file->getClientOriginalExtension();

//                        if ($file->move(UNIT_IMAGE_URL, $file_name)){
                            $pools[$key]['image_path'] = $this->generateAndSaveGalleryImage($pool);;
                            $pools[$key]['booking_unit_id'] = $create_unit->id;
                            $pools[$key]['term'] = 'pools';
                            $pools[$key]['term_no'] = $request->pools_no[$key];
//                        }
                    }
                }

                $save_gallery = UnitGallery::insert($pools);
            }

            if (!empty($request->kitchens)){
                foreach ($request->kitchens as $key => $kitchen){
                    if (key_exists($key, $request->kitchens) && $request->kitchens[$key]){

                        $kitchens[$key]['image_path'] = $this->generateAndSaveGalleryImage($kitchen);;
                        $kitchens[$key]['booking_unit_id'] = $create_unit->id;
                        $kitchens[$key]['term'] = 'kitchens';
                        $kitchens[$key]['term_no'] = $request->kitchens_no[$key];

                    }
                }

                $save_gallery = UnitGallery::insert($kitchens);
            }

            BookingHistory::create([
                'hismodel_id' => $create_unit->id,
                'hismodel_type' => 'App\Models\BookingUnit',
                'type' => 'create',
                'user_id' => auth()->id(),
                'user_type' => 'App\Models\ResUser'
            ]);

            return redirect()->route('gallery.index')->with('success', 'تمت إضافة الوحدة بنجاح و بانتظار المراجعة.');
        });
    }

    public function edit(Request $request, $id){
        $unit = BookingUnit::with('unit')->findOrFail($id);

        if (!$unit->unit->user_id == auth()->id())
            abort(404);

//        $gallery = BookingUnit::findOrFail($id);

        $rooms_arr = $halls_arr = $pools_arr = $toilets_arr = $kitchens_arr = [];

        $rooms = $unit->unitGallery()->select('term_no', 'image_path')->where('term', 'rooms')->get();

        $rooms_no = array_unique($rooms->pluck('term_no')->toArray());

//        $rooms = $rooms->map(function ($item, $index){
//            return[
//                'id' => $item['image_path'],
//                'src' => asset(UNIT_THUMBNAIL_IMAGE.'/'.$item['image_path']),
//                'term_no' => $item['term_no']
//            ];
//        });

        foreach ($rooms as $room){
            $rooms_arr[$room->term_no][] = array(
                'id' => $room->image_path,
                'thumbnail' => asset(UNIT_THUMBNAIL_IMAGE.'/'.$room->image_path),
                'original' => asset(UNIT_ORIGINAL_IMAGE.'/'.$room->image_path)
            );
        }

//        return dd($rooms_arr);

        $halls = $unit->unitGallery()->select('term_no', 'image_path')->where('term', 'hall')->get();

        $halls_no = array_unique($halls->pluck('term_no')->toArray());

        foreach ($halls as $hall){
            $halls_arr[$hall->term_no][] = array(
                'id' => $hall->image_path,
                'thumbnail' => asset(UNIT_THUMBNAIL_IMAGE.'/'.$hall->image_path),
                'original' => asset(UNIT_ORIGINAL_IMAGE.'/'.$hall->image_path)
            );
        }

//        $halls = $halls->map(function ($item, $index){
//            return[
//                'id' => $item['image_path'],
//                'src' => asset(UNIT_THUMBNAIL_IMAGE.'/'.$item['image_path']),
//                'term_no' => $item['term_no']
//            ];
//        });

        $toilets = $unit->unitGallery()->select('term_no', 'image_path')->where('term', 'toilets')->get();

        $toilets_no = array_unique($toilets->pluck('term_no')->toArray());

        foreach ($toilets as $toilet){
            $toilets_arr[$toilet->term_no][] = array(
                'id' => $toilet->image_path,
                'thumbnail' => asset(UNIT_THUMBNAIL_IMAGE.'/'.$toilet->image_path),
                'original' => asset(UNIT_ORIGINAL_IMAGE.'/'.$toilet->image_path)
            );
        }

//        $toilets = $toilets->map(function ($item, $index){
//            return[
//                'id' => $item['image_path'],
//                'src' => asset(UNIT_THUMBNAIL_IMAGE.'/'.$item['image_path'])
//            ];
//        });

        $pools = $unit->unitGallery()->select('term_no', 'image_path')->where('term', 'pools')->get();

        $pools_no = array_unique($pools->pluck('term_no')->toArray());

        foreach ($pools as $pool){
            $pools_arr[$pool->term_no][] = array(
                'id' => $pool->image_path,
                'thumbnail' => asset(UNIT_THUMBNAIL_IMAGE.'/'.$pool->image_path),
                'original' => asset(UNIT_ORIGINAL_IMAGE.'/'.$pool->image_path)
            );
        }

        // Kitchens
        $kitchens = $unit->unitGallery()->select('term_no', 'image_path')->where('term', 'kitchens')->get();

        $kitchens_no = array_unique($kitchens->pluck('term_no')->toArray());

        foreach ($kitchens as $kitchen){
            $kitchens_arr[$kitchen->term_no][] = array(
                'id' => $kitchen->image_path,
                'thumbnail' => asset(UNIT_THUMBNAIL_IMAGE.'/'.$kitchen->image_path),
                'original' => asset(UNIT_ORIGINAL_IMAGE.'/'.$kitchen->image_path)
            );
        }
//        $pools = $pools->map(function ($item, $index){
//            return[
//                'id' => $item['image_path'],
//                'src' => asset(UNIT_THUMBNAIL_IMAGE.'/'.$item['image_path'])
//            ];
//        });

//        return $pools;

        return view('Reservations.Gallery.create', [
            'page_title' => 'تعديل الوحدة',
            'sectors' => [],
            'refused' => [],
            'expired' => [],
            'gallery' => $unit,
            'rooms' => $rooms_arr,
            'rooms_no' => $rooms_no,
            'halls' => $halls_arr,
            'halls_no' => $halls_no,
            'toilets' => $toilets_arr,
            'toilets_no' => $toilets_no,
            'pools' => $pools_arr,
            'pools_no' => $pools_no,
            'kitchens' => $kitchens_arr,
            'kitchens_no' => $kitchens_no
        ]);
    }

    public function update(Request $request, $unit_id){

//        return dd($request->all());

        $request->validate([
            'unit_id' => 'required|numeric',
            'unit_type' => 'required',
            'room_count' => 'required|numeric',
            'rooms.*' => 'nullable',
            'halls_count' => 'required|numeric',
            'halls.*' => 'nullable',
            'toilets_count' => 'required|numeric',
            'toilets.*' => 'nullable',
            'pools_count' => 'required|numeric',
            'pools.*' => 'nullable',
            'details' => 'nullable|max:16000',
            'video_url' => 'nullable',
            'front_image' => 'nullable|mimes:jpg,png,jpeg,gif,pmb,svg,webp',
            'view_image' => 'nullable|mimes:jpg,png,jpeg,gif,pmb,svg,webp'
        ]);

        return DB::transaction(function () use($request, $unit_id) {

            $unit = BookingUnit::findOrFail($unit_id);

            // Type
            // Value
            // Term
            // Extra

            $data[0] = [
                'unit_id' => $unit_id,
                'type' => 'room_count',
                'value' => $request->room_count,
                'term' => null,
                'extra' => null
            ];

            $data[1] = [
                'unit_id' => $unit_id,
                'type' => 'halls_count',
                'value' => $request->halls_count,
                'term' => null,
                'extra' => null
            ];

            $data[2] = [
                'unit_id' => $unit_id,
                'type' => 'toilets_count',
                'value' => $request->toilets_count,
                'term' => null,
                'extra' => null
            ];

            $data[3] = [
                'unit_id' => $unit_id,
                'type' => 'pools_count',
                'value' => $request->pools_count,
                'term' => null,
                'extra' => null
            ];

            $data[4] = [
                'unit_id' => $unit_id,
                'type' => 'kitchens_count',
                'value' => $request->kitchens_count,
                'term' => null,
                'extra' => null
            ];

            $data[5] = [
                'unit_id' => $unit_id,
                'type' => 'details',
                'value' => Purify::clean($request->details),
                'term' => null,
                'extra' => null
            ];

            $data[6] = [
                'unit_id' => $unit_id,
                'type' => 'unit_type',
                'value' => $request->unit_type,
                'term' => null,
                'extra' => null
            ];

//            $data['need_approval'] = 1;

            if ($request->has('view_image') && $request->view_image != ''){
                $image = $this->generateAndSaveImage($request->file('view_image'));

                $data[7] = [
                    'unit_id' => $unit_id,
                    'type' => 'view_image',
                    'value' => $image,
                    'term' => null,
                    'extra' => null
                ];
            }else {
                $data[7] = [
                    'unit_id' => $unit_id,
                    'type' => 'view_image',
                    'value' => $unit->view_image,
                    'term' => null,
                    'extra' => null
                ];
            }

            if ($request->has('front_image') && $request->front_image != ''){
                $data[8] = [
                    'unit_id' => $unit_id,
                    'type' => 'front_image',
                    'value' => $this->generateAndSaveImage($request->file('front_image')),
                    'term' => null,
                    'extra' => null
                ];
            }else {
                $data[8] = [
                    'unit_id' => $unit_id,
                    'type' => 'front_image',
                    'value' => $unit->front_image,
                    'term' => null,
                    'extra' => null
                ];
            }

            // view_image
//            $data['view_image'] = $this->generateAndSaveImage($request->file('view_image'));
            // front_image
//            $data['front_image'] = $this->generateAndSaveImage($request->file('front_image'));


            if ($request->has('video_url') && $request->video_url != ''){
                $video = $request->file('video_url');
                $video_name = Str::slug($video->getClientOriginalName()).'-'.rand(1,9999).'.'.$video->getClientOriginalExtension();
                $video->move(UNIT_VIDEO_URL, $video_name);

                $data[9] = [
                    'unit_id' => $unit_id,
                    'type' => 'video_url',
                    'value' => $video_name,
                    'term' => null,
                    'extra' => null
                ];

            }else {
                $data[9] = [
                    'unit_id' => $unit_id,
                    'type' => 'video_url',
                    'value' => $unit->video_url,
                    'term' => null,
                    'extra' => null
                ];
            }

            $data[10] = [
                'unit_id' => $unit_id,
                'type' => 'unit_name',
                'value' => $request->unit_name,
                'term' => null,
                'extra' => null
            ];

            $data[11] = [
                'unit_id' => $unit_id,
                'type' => 'unit_location',
                'value' => $request->unit_location,
                'term' => null,
                'extra' => null
            ];
//            $create_unit = BookingUnit::findOrFail($unit_id)->update($data);

            $rooms = $halls = $toilets = $pools = [];

            $i = 12;

            if (!empty($request->roomsPreloaded)){
                foreach ($request->roomsPreloaded as $key => $room){
                    $data[$i]['unit_id'] = $unit_id;
                    $data[$i]['type'] = 'gallery';
                    $data[$i]['value'] = $room;
                    $data[$i]['term'] = 'rooms';
                    $data[$i]['extra'] = $request->roomsPreloaded_no[$key];

                    $i++;
                }
            }

//            dd($request->rooms);
            if (!empty($request->rooms)){
                foreach ($request->rooms as $key => $room){
//                    dd(key_exists($key, $request->rooms));
                    if (key_exists($key, $request->rooms) && $request->rooms[$key]){
//                        $file = $request->file('rooms')[$key];

                        $data[$i]['unit_id'] = $unit_id;
                        $data[$i]['type'] = 'gallery';
                        $data[$i]['value'] = $this->generateAndSaveGalleryImage($room);
                        $data[$i]['term'] = 'rooms';
                        $data[$i]['extra'] = $request->rooms_no[$key];

                        $i++;
                    }
                }
            }

            if (!empty($request->hallsPreloaded)){
                foreach ($request->hallsPreloaded as $key => $hall){
                    $data[$i]['unit_id'] = $unit_id;
                    $data[$i]['type'] = 'gallery';
                    $data[$i]['value'] = $hall;
                    $data[$i]['term'] = 'hall';
                    $data[$i]['extra'] = $request->hallsPreloaded_no[$key];

                    $i++;
                }
            }

            if (!empty($request->halls)){
                foreach ($request->halls as $key => $hall){
                    if (key_exists($key, $request->halls) && $request->halls[$key]){
//                        $file = $request->file('halls')[$key];

                        $data[$i]['unit_id'] = $unit_id;
                        $data[$i]['type'] = 'gallery';
                        $data[$i]['value'] = $this->generateAndSaveGalleryImage($hall);
                        $data[$i]['term'] = 'hall';
                        $data[$i]['extra'] = $request->halls_no[$key];

                        $i++;
                    }
                }
            }

            if (!empty($request->toiletsPreloaded)){
                foreach ($request->toiletsPreloaded as $key => $toilet){
                    $data[$i]['unit_id'] = $unit_id;
                    $data[$i]['type'] = 'gallery';
                    $data[$i]['value'] = $toilet;
                    $data[$i]['term'] = 'toilets';
                    $data[$i]['extra'] = $request->toiletsPreloaded_no[$key];

                    $i++;
                }
            }

            if (!empty($request->toilets)){
                foreach ($request->toilets as $key => $toilet){
                    if (key_exists($key, $request->toilets) && $request->toilets[$key]){
//                        $file = $request->file('toilets')[$key];

                        $data[$i]['unit_id'] = $unit_id;
                        $data[$i]['type'] = 'gallery';
                        $data[$i]['value'] = $this->generateAndSaveGalleryImage($toilet);
                        $data[$i]['term'] = 'toilets';
                        $data[$i]['extra'] = $request->toilets_no[$key];

                        $i++;
                    }
                }
            }

            if (!empty($request->poolsPreloaded)){
                foreach ($request->poolsPreloaded as $key => $pool){
                    $data[$i]['unit_id'] = $unit_id;
                    $data[$i]['type'] = 'gallery';
                    $data[$i]['value'] = $pool;
                    $data[$i]['term'] = 'pools';
                    $data[$i]['extra'] = $request->poolsPreloaded_no[$key];

                    $i++;
                }
            }

            if (!empty($request->pools)){
                foreach ($request->pools as $key => $pool){
                    if (key_exists($key, $request->pools) && $request->pools[$key]){
//                        $file = $request->file('pools')[$key];

                        $data[$i]['unit_id'] = $unit_id;
                        $data[$i]['type'] = 'gallery';
                        $data[$i]['value'] = $this->generateAndSaveGalleryImage($pool);
                        $data[$i]['term'] = 'pools';
                        $data[$i]['extra'] = $request->pools_no[$key];

                        $i++;
                    }
                }
            }

            if (!empty($request->kitchensPreloaded)){
                foreach ($request->kitchensPreloaded as $key => $kitchen){
                    $data[$i]['unit_id'] = $unit_id;
                    $data[$i]['type'] = 'gallery';
                    $data[$i]['value'] = $kitchen;
                    $data[$i]['term'] = 'kitchens';
                    $data[$i]['extra'] = $request->kitchensPreloaded_no[$key];

                    $i++;
                }
            }

            if (!empty($request->kitchens)){
                foreach ($request->kitchens as $key => $kitchen){
                    if (key_exists($key, $request->kitchens) && $request->kitchens[$key]){
//                        $file = $request->file('pools')[$key];

                        $data[$i]['unit_id'] = $unit_id;
                        $data[$i]['type'] = 'gallery';
                        $data[$i]['value'] = $this->generateAndSaveGalleryImage($kitchen);
                        $data[$i]['term'] = 'kitchens';
                        $data[$i]['extra'] = $request->kitchens_no[$key];

                        $i++;
                    }
                }
            }

            $unit->need_approval = 1;
            $unit->save();

//            return dd($data);

            UnitUpdates::where('unit_id', $unit_id)->delete();

            BookingHistory::create([
                'hismodel_id' => $unit_id,
                'hismodel_type' => 'App\Models\BookingUnit',
                'type' => 'update',
                'user_id' => auth()->id(),
                'user_type' => 'App\Models\ResUser'
            ]);

            if (UnitUpdates::insert($data))
                return redirect()->route('gallery.index')->with('success', 'تم ارسال تعديل الوحدة بنجاح و بانتظار المراجعة.');

            return redirect()->route('gallery.index')->with('error', 'هناك مشكلة ما في تعديل الغرفة ، برجاء التواصل مع الدعم الفني.');
        });
    }

    private function generateAndSaveImage($image){

        $options = array(
            'thumbnail' => [
                'destination' => UNIT_THUMBNAIL_IMAGE,
                'width' => 210,
                'height' => 140
            ],
            'original' => [
                'destination' => UNIT_ORIGINAL_IMAGE,
                'width' => 1000,
                'height' => ''
            ]
        );

        $imageName = Str::slug($image->getClientOriginalName()).'-'.rand(1,999999).'.jpg';

        foreach ($options as $key => $option){
            $destination = public_path($option['destination']);

            $img = Image::make($image->path());
            $img->orientate();
//            $img->scale
            $img->resize($option['width'], $option['height'], function ($constraint) use($key) {
                if ($key == 'original')
                    $constraint->aspectRatio();
            })->save($destination.'/'.$imageName);
        }

        return $imageName;
    }

    private function generateAndSaveGalleryImage($image_path){
        $options = array(
            'thumbnail' => [
                'destination' => UNIT_THUMBNAIL_IMAGE,
                'width' => 210,
                'height' => 140
            ],
            'original' => [
                'destination' => UNIT_ORIGINAL_IMAGE,
                'width' => 1000,
                'height' => ''
            ]
        );

//        if (file_exists(public_path(UNIT_GALLERY_TEMP.'/'.$image_path)) && !is_null($image_path)){
            foreach ($options as $key => $option){
                $destination = public_path($option['destination']);

                $img = Image::make(public_path(UNIT_GALLERY_TEMP.'/'.$image_path));
                $img->orientate();
                $img->resize($option['width'], $option['height'], function ($constraint) use($key) {
                    if ($key == 'original')
                        $constraint->aspectRatio();
                })->save($destination.'/'.$image_path);


//                $img->save($destination.'/'.$image_path);
            }
//        }

        return $image_path;
    }
}
