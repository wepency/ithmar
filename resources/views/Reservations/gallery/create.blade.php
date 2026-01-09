@extends('layouts.front-page')

@section('styles')
    <link rel="stylesheet" href="{{asset('css/contract.css')}}" />
    {{--    <script src="https://www.google.com/recaptcha/api.js"></script>--}}
    <link rel="stylesheet" href="{{asset('css/gallery.css')}}" />

    <link href="{{asset('css/image-uploader.min.css')}}" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />

    <style>
        @if(empty($errors->any()) && !$gallery->exists && request()->show != 'form')
        #add-unit-form{
            display: none;
        }
        #terms-and-conditions ul{

        }
        #terms-and-conditions ul li{
            list-style: none;
            line-height: 30px;
        }
        @endif

        .iui-cloud-upload::before {
            font-family: 'Font Awesome 6 Free' !important;
            content: "\2b" !important;
        }

        .image-exists img{
            display: block !important;
        }

        .image-exists i{
            display: none;
        }

        .progress{
            display: none;
        }

        .resume-upload,
        .pause-upload,
        .stop-upload{
            display: none;
        }
    </style>
@endsection

@section('content')
    <div class="container main-container">

        <div class="form-container">

            @if(count($refused) > 0)
                <div class="alert alert-danger alert-dismissible new2 p-4" role="alert">
                    <i class="alert-icon icon_close_alt2" aria-hidden="true"></i>

                    <div class="alert-body">
                        الوحدة/الوحدات مغلقة:
                        <ul>
                            @foreach($refused as $ref)
                                <li>{{$ref->unit_number}} - {{$ref->note}}</li>
                            @endforeach
                        </ul>
                    </div>

                    <button type="button" class="close-button" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(count($expired) > 0)
                <div class="alert alert-warning alert-dismissible new2 p-4" role="alert">
                    <i class="alert-icon icon_info" aria-hidden="true"></i>

                    <div class="alert-body">
                        الوحدة/الوحدات تحتاج إلى تحديث:
                        <ul>
                            @foreach($expired as $exp)
                                <li>{{$exp->unit_number}} <a href="{{investor_url('unit/update/'.base64_encode($exp->id))}}">تحديث المرفقات</a></li>
                            @endforeach
                        </ul>

                        <p><strong>يمكنك انشاء العقود بعد تحديث الوحدات والموافقه عليها من الاداره</strong></p>
                    </div>

                    <button type="button" class="close-button" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card">
                <div class="preloader-wrapper">
                    <img src="{{asset('images/preloader.svg')}}" alt="loading ..." />
                </div>

                @if(auth()->user()->banks()->count() > 0)
                    @if(empty($errors->any()) && !$gallery->exists && request()->show != 'form')
                        @include('Reservations.Gallery.unit-terms')
                    @endif

                        @if(empty(old()) && request()->show == 'form')
                        <div class="modal fade" id="warning-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">

                                    <div class="modal-body p-0">
                                        <div class="alert alert-success new2 p-4" id="success-message" role="alert">
                                            <i class="alert-icon icon_check_alt" aria-hidden="true"></i>

                                            <div class="alert-body">
                                                <strong>تنبيه ⚠️</strong>
                                                <p>                                                يمنع رفع صور تحتوي على أي ارقام تواصل او مؤثرات او حسابات خارجيه ويمنع ذكر أي وسيله تواصل في الوصف</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer justify-content-center text-center">
                                        <button  data-dismiss="modal" type="button" class="btn btn-primary modal-button">موافق</button>
                                        <a href="{{url('gallery')}}" class="btn btn-danger modal-button">الغاء</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                            $('#warning-modal').modal("show")
                        </script>
                        @endif

                        <div id="add-unit-form" class="card-body">

                            <div class="progress-outer mt-4 mb-4">
                                <div class="progress-bar--ribbon">
                                    <span class="step-header">اختيار الوحدة</span>
                                </div>

                                <div class="progress-bar--ribbon">
                                    <span class="step-header">الغرف</span>
                                </div>
                                <div class="progress-bar--ribbon">
                                    <span class="step-header">الصالات</span>
                                </div>
                                <div class="progress-bar--ribbon">
                                    <span class="step-header">دورات المياة</span>
                                </div>
                                <div class="progress-bar--ribbon">
                                    <span class="step-header">المسابح</span>
                                </div>
                                <div class="progress-bar--ribbon">
                                    <span class="step-header">المطابخ</span>
                                </div>
                                <div class="progress-bar--ribbon">
                                    <span class="step-header">تفاصيل اضافية</span>
                                </div>
                            </div>

                            @include('admin.layouts.messages')

                            @if((!is_blocked() && count($sectors) > 0 && $booking_units > 0) || $gallery->exists)
                                <form id="contract-form" style="background-color: #fff;padding: 0;margin-bottom: 0" method="post" action="{{$gallery->exists ? route('gallery.update', $gallery->id) : route('gallery.store')}}" enctype="multipart/form-data">
                                    @csrf

                                    @if($gallery->exists)
                                        @method('PUT')
                                    @endif

                                    <div id="available-error" style="display: none">
                                        <div class="alert alert-danger alert-dismissible new2 p-4" role="alert">
                                            <i class="alert-icon icon_close_alt2" aria-hidden="true"></i>

                                            <div class="alert-body"></div>

                                            <button type="button" class="close-button" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    </div>

                                    <div id="main-form" class="form-box">

                                        <div class="step required" data-step="1">
                                            <h3 class="form-title"><span class="form-ribbon">1. اختيار الوحدة</span></h3>

                                            @if(!$gallery->exists)
                                                <div class="row">
                                                    <div class="col-md-6 col-xs-12">
                                                        <div class="form-group">
                                                            <label for="sector" class="form-label">رقم القطاع</label>

                                                            <select class="nice-select mt-2 w-100 @error('sector_id') has-error @enderror" id="sector" name="sector_id">
                                                                @foreach($sectors as $sector)
                                                                    <option {{old('sector_id') == $sector->id ? 'selected' : ''}} value="{{$sector->id}}">{{$sector->sector_name}}</option>
                                                                @endforeach
                                                            </select>


                                                            <div class="text-danger @error('sector_id') active @enderror">
                                                                @error('sector_id')
                                                                {{ $message }}
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if(!$gallery->exists)
                                                <div class="row">
                                                    <div class="col-md-6 col-xs-12">
                                                        <div class="form-group">
                                                            <label for="beach" class="form-label">الشاطئ</label>

                                                            <select class="nice-select mt-2 w-100 @error('beach_id') has-error @enderror" id="beach" name="beach_id"></select>

                                                            <div class="text-danger @error('beach_id') active @enderror">
                                                                @error('beach_id')
                                                                {{ $message }}
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="row">
                                                <div class="col-md-6 col-xs-12">
                                                    <div class="form-group">
                                                        <label for="unit" class="form-label">رقم الفيلا</label>
                                                        <select class="nice-select mt-2 w-100 @error('unit_id') has-error @enderror" id="unit" name="unit_id">
                                                            @if($gallery->exists)
                                                                <option value="{{$gallery->unit_id ?? ''}}">{{$gallery->unit->unit_number ?? ''}}</option>
                                                            @endif
                                                        </select>

                                                        <div class="text-danger @error('unit_id') active @enderror">
                                                            @error('unit_id')
                                                            {{ $message }}
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 col-xs-12">
                                                    <div class="form-group">
                                                        <label for="unit_type" class="form-label">نوع الوحدة</label>

                                                        <select class="nice-select mt-2 w-100 @error('unit_type') has-error @enderror" id="unit_type" name="unit_type">
{{--                                                            @if($gallery->exists)--}}
{{--                                                                <option value="{{$gallery->unit_type ?? ''}}">{{$gallery->unit->unit_number ?? ''}}</option>--}}
{{--                                                            @endif--}}
                                                            <option value="in" {{$gallery->unit_type == 'in' ? 'selected' : ''}}>شقة بداخل مجمع</option>
                                                            <option value="apart" {{$gallery->unit_type == 'apart' ? 'selected' : ''}}>شقة</option>
                                                            <option value="villa" {{$gallery->unit_type == 'villa' ? 'selected' : ''}}>فيلا</option>
                                                            <option value="palace" {{$gallery->unit_type == 'palace' ? 'selected' : ''}}>قصر</option>
                                                        </select>

                                                        <div class="text-danger @error('unit_type') active @enderror">
                                                            @error('unit_type')
                                                            {{ $message }}
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 col-xs-12">
                                                    <div class="form-group">
                                                        <label for="unit_name" class="form-label">اسم الوحدة <span style="color: red"> (مطلوب)</span></label>
                                                        <h6><small>برجاء اعطاء اسم للوحدة</small></h6>

                                                        <input type="text" class="form-control" name="unit_name" id="unit_name" placeholder="استديو بتصميم مودرن وسرير ماستر" value="{{old('unit_name') ?? $gallery->unit_name}}" required />

                                                        <div class="text-danger @error('sector_id') active @enderror">
                                                            @error('sector_id')
                                                            {{ $message }}
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="step image-with-select required" data-name="rooms" data-step="2">
                                            <h3 class="form-title"><span class="form-ribbon">2. الغرف</span></h3>

                                            <div class="row">
                                                <div class="col-md-6 col-xs-12">
                                                    <div class="form-group">
                                                        <label for="room_count" class="form-label">عدد الغرف</label>

                                                        <select class="nice-select gallery-items-count mt-2 w-100 @error('room_count') has-error @enderror" data-name="rooms" data-trans="الغرفة" id="room_count" name="room_count">
                                                            @for($i=1;$i<=10;$i++)
                                                                <option value="{{$i}}" {{$gallery->room_count == $i ? 'selected' : ''}}>{{$i}}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="rooms" class="form-label">صور الغرف</label>

{{--                                                        <div class="image-upload-box" id="rooms-images"></div>--}}

                                                        <div class="images-container mt-2">
                                                            @if($gallery->exists && isset($rooms_no) && !empty($rooms_no))
                                                                @foreach($rooms_no as $no)
                                                                    @include('Reservations.Gallery.image_box', ['data' => 'rooms', 'name' => 'roomsPreloaded', 'objs' => $rooms, 'trans' => 'الغرفة'])
                                                                @endforeach
                                                            @else
                                                                @include('Reservations.Gallery.image_box', ['data' => 'rooms', 'name' => 'rooms', 'trans' => 'الغرفة'])
                                                            @endif
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="step image-with-select" data-name="halls" data-step="3">
                                            <h3 class="form-title"><span class="form-ribbon">3. الصالات</span></h3>

                                            <div class="row">
                                                <div class="col-md-6 col-xs-12">
                                                    <div class="form-group">
                                                        <label for="halls_count" class="form-label">عدد الصالات</label>

                                                        <select {{$gallery->halls_count == $i ? 'selected' : ''}} class="nice-select gallery-items-count mt-2 w-100 @error('halls_count') has-error @enderror" data-name="halls" data-trans="الصالة" id="halls_count" name="halls_count">
                                                            @for($i=0;$i<=7;$i++)
                                                                <option value="{{$i}}" {{$gallery->halls_count == $i ? 'selected' : ''}}>{{$i}}</option>
                                                            @endfor
                                                        </select>

                                                        <label for="halls_count" class="form-label"><small>يمكنك التخطي في حالة عدم توفر صالة في وحدتكم.</small></label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="halls" class="form-label">صور الصالات</label>

{{--                                                        <div id="halls-note"></div>--}}

                                                        <div class="images-container mt-2">
                                                            @if($gallery->exists && isset($halls_no) && !empty($halls_no))
                                                                @foreach($halls_no as $no)
                                                                    @include('Reservations.Gallery.image_box', ['data' => 'halls', 'name' => 'hallsPreloaded', 'objs' => $halls, 'trans' => 'الصالة'])
                                                                @endforeach
                                                            @endif
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="step image-with-select required" data-name="toilets" data-step="4">
                                            <h3 class="form-title"><span class="form-ribbon">4. دورات المياة</span></h3>

                                            <div class="row">
                                                <div class="col-md-6 col-xs-12">
                                                    <div class="form-group">
                                                        <label for="toilets_count" class="form-label">عدد دورات المياة</label>

                                                        <select {{$gallery->toilets_count == $i ? 'selected' : ''}} class="nice-select gallery-items-count mt-2 w-100 @error('toilets_count') has-error @enderror" data-name="toilets" data-trans="دورة مياة" id="toilets_count" name="toilets_count">
                                                            @for($i=1;$i<=10;$i++)
                                                                <option value="{{$i}}" {{$gallery->toilets_count == $i ? 'selected' : ''}}>{{$i}}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="toilets" class="form-label">صور دورات المياة</label>

{{--                                                        <div class="image-upload-box" id="toilets-images"></div>--}}

                                                        <div class="images-container mt-2">
{{--                                                            <h6><small>صور دورة مياة 1</small></h6>--}}

{{--                                                            <div class="images-wrapper">--}}
{{--                                                                <div class="single-image-upload">--}}
{{--                                                                    <label class="" for="toilets-image-field">--}}
{{--                                                                        <i class="fa fa-plus"></i>--}}
{{--                                                                    </label>--}}

{{--                                                                    <input class="gallery-image-field" id="toilets-image-field" type="file" accept="image/*" name="toilets_field[]" data-name="toilets" data-no="1" multiple />--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}

                                                            @if($gallery->exists && isset($toilets_no) && !empty($toilets_no))
                                                                @foreach($toilets_no as $no)
                                                                    @include('Reservations.Gallery.image_box', ['data' => 'toilets', 'name' => 'toiletsPreloaded', 'objs' => $toilets, 'trans' => 'دورة مياة'])
                                                                @endforeach
                                                            @else
                                                                @include('Reservations.Gallery.image_box', ['data' => 'toilets', 'name' => 'toilets', 'trans' => 'دورة مياة'])
                                                            @endif
                                                        </div>

                                                        {{--                                                <div class="images-wrapper">--}}
                                                        {{--                                                    <div class="single-image-upload">--}}
                                                        {{--                                                        <label class="" for="toilet-image-0">--}}
                                                        {{--                                                            <i class="fa fa-plus"></i>--}}
                                                        {{--                                                            <img src="" alt="" />--}}
                                                        {{--                                                        </label>--}}

                                                        {{--                                                        <input class="gallery-image-field" id="toilet-image-0" type="file" accept="image/*" name="toilets[]" />--}}
                                                        {{--                                                    </div>--}}
                                                        {{--                                                </div>--}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="step image-with-select" data-name="pools" data-step="5">
                                            <h3 class="form-title"><span class="form-ribbon">5. عدد المسابح</span></h3>

                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="pools_count" class="form-label">عدد المسابح</label>

                                                        <select {{$gallery->pools_count == $i ? 'selected' : ''}} class="nice-select gallery-items-count mt-2 w-100 @error('pools_count') has-error @enderror" data-name="pools" data-trans="المسبح" id="pools_count" name="pools_count">
                                                            @for($i=0;$i<=10;$i++)
                                                                <option value="{{$i}}" {{$gallery->pools_count == $i ? 'selected' : ''}}>{{$i}}</option>
                                                            @endfor
                                                        </select>

                                                        <label for="pools_count" class="form-label"><small>يمكنك التخطي في حالة عدم توفر مسبح في وحدتكم.</small></label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="pools" class="form-label">صور المسابح</label>

                                                        <div id="pools-note"></div>

                                                        <div class="images-container mt-2">
                                                            @if($gallery->exists && isset($pools_no) && !empty($pools_no))
                                                                @foreach($pools_no as $no)
                                                                    @include('Reservations.Gallery.image_box', ['data' => 'pools', 'name' => 'poolsPreloaded', 'objs' => $pools, 'trans' => 'مسبح'])
                                                                @endforeach
                                                            @endif
                                                        </div>

{{--                                                        <div class="image-upload-box" id="pools-images"></div>--}}
                                                        {{--                                                <div class="images-wrapper">--}}
                                                        {{--                                                    <div class="single-image-upload">--}}
                                                        {{--                                                        <label class="" for="pool-image-0">--}}
                                                        {{--                                                            <i class="fa fa-plus"></i>--}}
                                                        {{--                                                            <img src="" alt="" />--}}
                                                        {{--                                                        </label>--}}

                                                        {{--                                                        <input class="gallery-image-field" id="pool-image-0" type="file" accept="image/*" name="pools[]" />--}}
                                                        {{--                                                    </div>--}}
                                                        {{--                                                </div>--}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="step image-with-select required" data-name="toilets" data-step="6">
                                            <h3 class="form-title"><span class="form-ribbon">6. المطابخ</span></h3>

                                            <div class="row">
                                                <div class="col-md-6 col-xs-12">
                                                    <div class="form-group">
                                                        <label for="kitchens_count" class="form-label">عدد المطابخ</label>

                                                        <select {{$gallery->kitchens_count == $i ? 'selected' : ''}} class="nice-select gallery-items-count mt-2 w-100 @error('kitchens_count') has-error @enderror" data-name="kitchens" data-trans="مطابخ" id="kitchens_count" name="kitchens_count">
                                                            @for($i=1;$i<=10;$i++)
                                                                <option value="{{$i}}" {{$gallery->kitchens_count == $i ? 'selected' : ''}}>{{$i}}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="toilets" class="form-label">صور المطابخ</label>

                                                        <div class="images-container mt-2">
                                                            @if($gallery->exists && isset($kitchens_no) && !empty($kitchens_no))
                                                                @foreach($kitchens_no as $no)
                                                                    @include('Reservations.Gallery.image_box', ['data' => 'kitchens', 'name' => 'kitchensPreloaded', 'objs' => $kitchens, 'trans' => 'مطبخ'])
                                                                @endforeach
                                                            @else
                                                                @include('Reservations.Gallery.image_box', ['data' => 'kitchens', 'name' => 'kitchens', 'trans' => 'مطبخ'])
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="step" data-step="7">
                                            <h3 class="form-title"><span class="form-ribbon">7. تفاصيل اضافية</span></h3>

                                            <div class="row">
                                                <div class="col-md-6 col-xs-12">
                                                    <div class="form-group">
                                                        <label for="unit_location" class="form-label">رابط موقع الوحدة</label>
                                                        <h6><small>برجاء ادخال رابط لموقع الوحدة على الخريطة ، يظهر للعميل عند تأكيد الحجز.</small></h6>

                                                        <input type="url" class="form-control" name="unit_location" id="unit_location" value="{{old('unit_location') ?? $gallery->unit_location}}" placeholder="مثال: https://goo.gl/maps/UqXieS7agnbtEjoy5" required />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 col-xs-12">
                                                    <div class="form-group">
                                                        <label for="front-image" class="form-label">واجهة الوحدة*</label>

                                                        <div class="images-wrapper {{$gallery->exists ? 'image-exists' : ''}}">
                                                            <div class="main-image-upload">
                                                                <label class="" for="front-image">
                                                                    <i class="fa fa-plus"></i>
                                                                    <img src="{{asset(UNIT_THUMBNAIL_IMAGE.'/'.$gallery->front_image)}}" alt="" />
                                                                </label>

                                                                <input class="single-image-field" id="front-image" type="file" accept="image/*" name="front_image" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 col-xs-12">
                                                    <div class="form-group">
                                                        <label for="view-image" class="form-label">صورة عرض الوحدة*</label>

                                                        <div class="images-wrapper {{$gallery->exists ? 'image-exists' : ''}}">
                                                            <div class="main-image-upload">
                                                                <label class="" for="view-image">
                                                                    <i class="fa fa-plus"></i>
                                                                    <img src="{{asset(UNIT_THUMBNAIL_IMAGE.'/'.$gallery->view_image)}}" alt="" />
                                                                </label>

                                                                <input class="single-image-field" id="view-image" type="file" accept="image/*" name="view_image" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 col-xs-12">
                                                    <div class="form-group">
                                                        <label for="details" class="form-label mb-2">تفاصيل</label>

                                                        <textarea id="details" name="details">{{old('details') ?? $gallery->details}}</textarea>

                                                        <p class="mt-2">بامكانك كتابه هنا تفاصيل اضافه عن وحدتك ، على سبيل المثال:</p>

                                                        <ul>
                                                            <li>يتوفر واي فاي - يتوفر سماعات - يتوفر انظمه احتفالات منزليه.</li>
                                                            <li>تفاصيل الغرف (عدد ٢ سرير مفرد - عدد السرر المزدوجه - الخ).</li>
                                                            <li>احرص على وضع تفاصيل وحدتك لرفع مبيعاتك.</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 col-xs-12">
                                                    <div class="form-group">
                                                        <label for="video_url" class="form-label mb-2">فيديو دعائي خاص بالوحدة</label>
                                                        <h5 id="uploaded-file2">بحد أقصى 500 ميجابايت</h5>

                                                        <!-- Progress bar -->
                                                        <div id="progress-bar-encrypted0" class="progress mt-3" style="height: 25px">
                                                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0; height: 100%">0%</div>
                                                        </div>

                                                        <div class="d-flex">
                                                            <input type="file" id="encrypted_video0" class="form-control d-none" accept="video/*" />

                                                            <label for="encrypted_video0" id="encrypted_video_button0" class="btn btn-success m-0"><i class="fa fa-upload"></i> رفع الفيديو </label>

                                                            <button type="button" class="btn btn-success resume-upload" id="resume-upload0"><i class="fa fa-play-circle"></i> استئناف الرفع</button>

                                                            <button type="button" class="btn btn-warning pause-upload" id="pause-upload0"><i class="fa fa-pause-circle"></i> ايقاف الرفع</button>

                                                            <button type="button" class="btn btn-danger stop-upload" id="stop-upload0"><i class="fa fa-times-circle"></i> الغاء الرفع </button>
                                                        </div>

                                                        {{--                                                        <input type="file" class="form-control" id="video_url" accept="video/*" name="video_url" />--}}

                                                        @if($gallery->exists && $gallery->video_url != '')
                                                            <a href="{{asset(UNIT_VIDEO_URL.'/'.$gallery->video_url)}}"></a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group d-flex buttons m-0">
                                            <button type="submit" class="gb gb-bordered hover-slide next gb9"><i class="arrow_right"></i> <span class="text"> التالي </span> <span class="loader"></span></button>
                                            <button
                                                class="gb gb-bordered hover-slide ml-2 hover-fill g-recaptcha"
                                                id="submit"
                                                type="submit"
                                                data-sitekey="{{env('reCaptch_site_key')}}"
                                                data-callback='submitNewContract'
                                                data-action='submit'
                                            ><i class="icon_check"></i> <span class="text">{{$gallery->exists ? 'تعديل الوحدة' : 'تسجيل الوحدة'}}</span></button>
                                            <button class="gb gb-bordered hover-slide ml-2 hover-fill prev gb10"><i class="icon_close"></i> <span class="text">السابق</span></button>
                                        </div>
                                    </div>

                                </form>
                            @elseif(count($sectors) == 0 || $booking_units == 0)
                                <div class="alert alert-danger text-center">
                                    <div><i class="icon_close_alt2" style="font-size:50px;color:#dc3545!important"></i></div>
                                    <h3>ليس لديك وحدات مفعلة.</h3>
                                </div>
                            @else
                                <div class="alert alert-danger text-center">
                                    <div><i class="icon_close_alt2" style="font-size:50px;color:#dc3545!important"></i></div>

                                    @if(!is_null(block_note()))
                                        <h3>عفوا قد تم إيقاف خاصية اضافة صور الوحدات بسبب:</h3>
                                        <h4>{{auth()->user()->blocked_note}}</h4>
                                    @else
                                        <h3>عفوا قد تم إيقاف خاصية اضافة صور الوحدات</h3>
                                    @endif
                                </div>
                            @endif
                        </div>
                @else
                    <div class="card-body">
                        <div class="alert alert-danger text-center">
                            <div><i class="icon_close_alt2" style="font-size:50px;color:#dc3545!important"></i></div>
                            <h3>لم تقم باضافة حسابات بنكية بعد. برجاء الاضافة قبل استكمال اضافة الوحدة</h3>
                        </div>

                        <div class="text-center">
                            <a href="{{url('/user/bank')}}" class="gb gb-bordered hover-slide gb9"><i class="arrow_right"></i> <span class="text"> اضف الحسابات البنكية </span></a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{asset('js/moment.min.js')}}"></script>
    <script src="{{asset('js/gallery.js')}}"></script>
    <script src="{{asset('js/image-uploader.min.js')}}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script src="{{asset('js/resumable.js')}}"></script>

    <script>

        $("a.single-image-link").fancybox();

        $('#details').summernote({
            tabsize: 2,
            height: 120,
            toolbar: [
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['view', ['fullscreen']]
            ]
        });
    </script>

    <script>

        {{--let preloadedRooms;--}}

        {{--@if(isset($rooms))--}}
        {{--    preloadedRooms = {!! json_encode($rooms) !!};--}}
        {{--@endif--}}

        {{--roomsImages.imageUploader({--}}
        {{--    imagesInputName:'rooms',--}}
        {{--    preloadedInputName:'roomsPreloaded',--}}
        {{--    label:GalleryText,--}}
        {{--    maxFiles: '{{isset($rooms) ? count($rooms) : 1}}',--}}
        {{--    preloaded: preloadedRooms,--}}
        {{--    extensions,--}}
        {{--    mimes--}}
        {{--});--}}

        {{--let preloadedHalls;--}}

        {{--@if(isset($halls))--}}
        {{--    preloadedHalls = {!! json_encode($halls) !!};--}}
        {{--@endif--}}

        // const halls_count = $('#halls_count').val();

        {{--if (halls_count > 0) {--}}
        {{--    hallsImages.imageUploader({--}}
        {{--        imagesInputName:'halls',--}}
        {{--        preloadedInputName:'hallsPreloaded',--}}
        {{--        label:GalleryText,--}}
        {{--        maxFiles: '{{isset($halls) ? count($halls) : 0}}',--}}
        {{--        preloaded: preloadedHalls,--}}
        {{--        extensions,--}}
        {{--        mimes--}}
        {{--    });--}}
        {{--}else{--}}
        {{--    $('#halls-note').html('برجاء اختيار رقم أكبر من 0 لاضافة الصور')--}}
        {{--}--}}

        {{--let preloadedToilets;--}}

        {{--@if(isset($toilets))--}}
        {{--    preloadedToilets = {!! json_encode($toilets) !!};--}}
        {{--@endif--}}

        {{--toiletsImages.imageUploader({--}}
        {{--    imagesInputName:'toilets',--}}
        {{--    preloadedInputName:'toiletsPreloaded',--}}
        {{--    label:GalleryText,--}}
        {{--    maxFiles: '{{isset($toilets) ? count($toilets) : 1}}',--}}
        {{--    preloaded: preloadedToilets,--}}
        {{--    extensions,--}}
        {{--    mimes--}}
        {{--});--}}

        {{--let preloadedPools;--}}

        {{--@if(isset($pools))--}}
        {{--    preloadedPools = {!! json_encode($pools) !!};--}}
        {{--@endif--}}

        {{--const pools_count = $('#pools_count').val();--}}

        {{--if (pools_count > 0) {--}}
        {{--    poolsImages.imageUploader({--}}
        {{--        imagesInputName:'pools',--}}
        {{--        preloadedInputName:'poolsPreloaded',--}}
        {{--        label:GalleryText,--}}
        {{--        maxFiles: '{{isset($pools) ? count($pools) : 1}}',--}}
        {{--        preloaded: preloadedPools,--}}
        {{--        extensions,--}}
        {{--        mimes--}}
        {{--    });--}}
        {{--}else{--}}
        {{--    $('#pools-note').html('برجاء اختيار رقم أكبر من 0 لاضافة الصور')--}}
        {{--}--}}

        @if(empty($errors->any()) && !$gallery->exists && request()->show != 'form')
        $('#agree').on('change', function (){
            const isChecked = $(this).is(':checked');
            $('#agree-button').attr('disabled', !isChecked)
        })

        $('#agree-button').on('click', function (e){
            e.preventDefault();
            // $(this).parents('.card-body').hide().next().show()
            window.location.href = '{{url('gallery/create?show=form')}}'
        })
        @endif

        function submitNewContract(token) {
            document.getElementById('contract-form').submit();
        }

        // Handle Next & Prev
        $('.next').on('click', function (e){
            e.preventDefault();
            $(this).attr('disabled', true).addClass('button-loading');

            let activeStep = $('.step.active'),
                currentStep = activeStep.data('step'),
                zeroImagesWrapper = 0;

            const checkFile = activeStep.find('.image-upload-box .uploaded .uploaded-image');
            // const checkRequiredInputs = checkFile.length === 0 && activeStep.hasClass('required');
            const checkRequiredInputs = activeStep.hasClass('image-with-select');
            // const lengthRequiredOfImages = parseInt(activeStep.find('select').val());
            // const lengthOfImages = checkFile.length


            const selectValue = activeStep.find('select').val()
            const imageWrappers = activeStep.find('.images-wrapper')

            imageWrappers.each(function (index){
                if($(this).find('.single-image-link').length == 0)
                    zeroImagesWrapper = 1
            })

            if (currentStep === 1)
                $('.prev').show()

            if (currentStep === 6) {
                $(this).hide()
                $('#submit').show()
            }

            // if(currentStep === 2){
            //     $('#available-error').hide().find('.alert-body').html('');
            //     $('#from, #to').removeClass('has-error').addClass('valid')
            //     goNext(currentStep)
            // if {
                let requiredInput = activeStep.find('input[required]'),
                    selectMenus = activeStep.find('select[required]');

                let errorsCount = 0;

                requiredInput.each(function (){
                    if ($(this).val() === ''){
                        // errors.push($(this).val());
                        $(this).addClass('has-error').parents('.form-group').find('.text-danger').addClass('active').html('هذا الحقل مطلوب ، برجاء إدخال القيمة المطلوبة.')
                        errorsCount++;
                    }else {
                        const fieldSize = $(this).data('size');
                        const fieldMaxSize = $(this).data('max-size');

                        if ($(this).val().length === fieldSize || typeof (fieldSize) === "undefined")
                            $(this).removeClass('has-error').addClass('valid').parents('.form-group').find('.text-danger').removeClass('active').html('')
                        else{
                            $(this).addClass('has-error').parents('.form-group').find('.text-danger').addClass('active').html('يجب ان يكون عدد الحروف أو الارقام في تلك الخانة '+ fieldSize)
                            errorsCount++;
                        }
                    }
                })

                // if (checkRequiredInputs || (checkFile.length && lengthRequiredOfImages !== lengthOfImages)){
                if (checkRequiredInputs && (imageWrappers.length != selectValue || (selectValue > 0 && zeroImagesWrapper))){
                    $('#available-error').show().find('.alert-body').html('برجاء اكمال الصور بما يتناسب مع العدد.')
                }else if(errorsCount === 0){
                    $('#available-error').hide().find('.alert-body').html()
                    goNext(currentStep)
                }

            // if (errorsCount == 0)
            //     goNext(currentStep)
            // }

            $('html, body').animate({ scrollTop: $('.form-container').offset().top }, 'slow');

            $(this).attr('disabled', false).removeClass('button-loading');
        })

        $('.prev').on('click', function (e){
            e.preventDefault();

            $(this).attr('disabled', true)

            $('#submit').hide()
            $('.next').show()

            let currentStep = $('.step.active').data('step');

            $('body').find('.step.active').hide().removeClass('active').prev().fadeIn().addClass('active')

            if (currentStep === 2)
                $(this).hide()

            $('html, body').animate({ scrollTop: $('.form-container').offset().top }, 'slow');
            $(this).attr('disabled', false)
        })
        const modal = $();

        $(document).ready(function (){
            $('.preloader-wrapper').remove()
        })

        @if(count($sectors) > 0)
        changeBeaches({{$sectors[0]->id}})
        @endif

        $('#sector').on('change', function (){
            const sector_id = $(this).val()
            changeBeaches(sector_id)
        })

        $('body').on('change', '#beach', function () {
            const beach_id = $(this).val()
            changeVillas(beach_id)
        });

        function changeBeaches(sector_id){
            let output = '';

            $.post('/get-beaches-investor/'+sector_id).done(function (data){
                for (let i=0;i<data.data.length;i++){
                    output += "<option value='"+data.data[i].id+"'>"+data.data[i].beach+"</option>"
                }

                $('#beach').html(output)
                $('#beach').niceSelect('update')
                changeVillas(data.data[0].id)
            })
        }

        function changeVillas(beach_id){
            let output = '';

            $.post('/get-villas-for-booking/'+beach_id).done(function (data){
                for (let i=0;i<data.data.length;i++){
                    output += "<option value='"+data.data[i].id+"'>"+data.data[i].unit_number+"</option>"
                }

                $('#unit').html(output)
                $('#unit').niceSelect('update')
            })
        }

        function goNext(currentStep){
            $('.progress-bar--ribbon').eq(currentStep-1).addClass('active')
            $('body').find('.step.active').hide().removeClass('active').next().fadeIn().addClass('active')
        }

        $('form').on('submit', function (e){
            $('#submit-form').attr('disabled', true)
        })


        let resumable = [];

        for (let i = 0; i<=2; i++) {
            let browseFile = $('#encrypted_video'+i);
            const progress = $('#progress-bar-encrypted'+i);

            resumable[i] = new Resumable({
                target: '{{url('upload-video')}}',
                query:{_token:'{{ csrf_token() }}'} ,// CSRF token
                // fileType: ['mp4'],
                headers: {
                    'Accept' : 'application/json'
                },
                testChunks: false,
                throttleProgressCallbacks: 2
            });

            resumable[i].assignBrowse(browseFile[0]);

            resumable[i].on('fileAdded', function (file) { // trigger when file picked
                showProgress(progress);
                resumable[i].upload() // to actually start uploading.

                $('#encrypted_video_button'+i).hide()
                $('#pause-upload'+i).show()
                $('#stop-upload'+i).show()

                $(this).parents('.upload-to-omega').find('.progress').show()

                // browseFile.parents('.form-group').find('.stop-upload').attr('id', file.uniqueIdentifier);
            });

            resumable[i].on('fileProgress', function (file) { // trigger when file progress update
                updateProgress(progress, Math.floor(file.progress() * 100));
            });

            resumable[i].on('fileSuccess', function (file, response) { // trigger when file upload complete
                response = JSON.parse(response)
                $('#encrypted_video_upload'+i).val(response.filename);
                $(this).parents('.upload-to-omega').find('.progress-bar').addClass('bg-success')

                toastr.success("{{trans('messages.upload-success')}}");
            });

            resumable[i].on('fileError', function (file, response) { // trigger when there is any error
                toastr.error("{{trans('messages.upload-error')}}", "error");
            });

            $('#pause-upload'+i).on('click', function (){
                resumable[i].pause();

                const progressBar = $(this).parents('.upload-to-omega').find('.progress-bar')
                progressBar.addClass('bg-warning')

                $('#encrypted_video_button'+i).show()
                $('#pause-upload'+i).hide()
                $('#stop-upload'+i).show();
                $('#resume-upload'+i).show();

                toastr.warning("{{trans('messages.upload-paused')}}");
            });

            $('#resume-upload'+i).on('click', function (){
                resumable[i].upload();

                const progressBar = $(this).parents('.upload-to-omega').find('.progress-bar')
                progressBar.removeClass('bg-warning')

                $('#encrypted_video_button'+i).hide()
                $('#pause-upload'+i).show()
                $('#stop-upload'+i).show();
                $('#resume-upload'+i).hide();

                toastr.success("{{trans('messages.upload-resumed')}}");
            });

            $('#stop-upload'+i).on('click', function (){
                resumable[i].cancel();

                $(this).parents('.upload-to-omega').find('.progress').hide()

                $('#encrypted_video_button'+i).show()
                $('#pause-upload'+i).hide()
                $('#stop-upload'+i).hide();
                $('#resume-upload'+i).hide();

                toastr.error("{{trans('messages.upload-stopped')}}", "error");
            });

        }


        function showProgress(progress) {
            progress.find('.progress-bar').css('width', '0%');
            progress.find('.progress-bar').html('0%');
            progress.find('.progress-bar').removeClass('bg-success');
            progress.show();
        }

        function updateProgress(progress, value) {
            progress.find('.progress-bar').css('width', `${value}%`)
            progress.find('.progress-bar').html(`${value}%`)
        }

        function hideProgress(progress) {
            progress.hide();
        }
    </script>
@endsection
