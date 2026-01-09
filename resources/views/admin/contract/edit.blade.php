<?php
$format = 'Y-m-d';

$carbon = new \Carbon\Carbon;

$today = $contract->exists ? $carbon->parse($contract->from)->format($format) : $carbon->today()->format($format);
$tomorrow = $contract->exists ? $carbon->parse($contract->to)->format($format) : $carbon->tomorrow()->format($format);


?>

@extends('layouts.admin')

@section('styles')
    <link rel="stylesheet" href="{{asset('css/cropper.min.css')}}">

    <style>
        .single-file-upload{
            display: none !important;
        }
        .uploaded-image{
            display: flex;
        }
        label.upload-button{
            cursor: pointer;
            width: 100px;
            height: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 4px dashed #ccc;
            border-radius: 20px;
        }
        label.upload-button:hover{
            opacity: .7;
        }
        .uploaded-image-wrapper{
            width: 100px;
            height: 100px;
            border: 2px solid#ccc;
            border-radius: 20px;
            margin-right: 10px;
        }

        img {
            display: block;
            max-width: 100%;
        }
        .preview {
            overflow: hidden;
            width: 160px;
            height: 160px;
            margin: 10px;
            border: 1px solid red;
        }
        #rental-barcode-image,
        #with-rental-barcode-image{
            padding: 3px;
            border-radius: 20px;
        }
    </style>
@endsection

@section('content')
    <!-- Modal -->
    <div class="modal fade" id="crop-image" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">قطع الصورة</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="img-container">
                        <div class="row" style="flex-direction: row-reverse;direction: ltr;">
                            <div class="col-md-8 p-0 m-0">
                                <img id="image" alt="" />
                            </div>
                            <div class="col-md-4">
                                <div class="preview"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button id="crop" type="button" class="btn btn-secondary" data-dismiss="modal">حفظ الصورة</button>
                    <button type="button" class="btn btn-primary">إلغاء</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="box box-body box-primary">
            <div class="">
                <h3 class="box-title">{{$page_title}}</h3>
                <hr />
            </div>

            @include('admin.layouts.messages')

            <form action="{{$contract->exists? route('admin.contract.update', $contract) : route('admin.contract.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                @if($contract->exists)
                    @method('PUT')
                @endif

                <div class="row" style="margin: 0;padding: 0">
                    <div class="form-group col-md-4 col-xs-9 ">
                        <label for="sector_id"> رقم القطاع </label>

                        <select id="sector_id" class="form-control select2" name="sector_id" style="width: 100%;">
                            @foreach($sectors as $sector)
                                <option
                                    {{$contract->sector_id == $sector->id ? 'selected' : ''}} value="{{$sector->id}}">{{$sector->sector_name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-4 col-xs-9 ">
                        <label for="beach_id"> الشاطئ </label>
                        <select id="beach_id" class="form-control select2" name="beach_id" style="width: 100%;">
                            @foreach($beaches as $beach)
                                <option {{$beach->id == $contract->beach_id ? 'selected' : ''}} value="{{$beach->id}}">{{$beach->beach}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-4 col-xs-9 ">
                        <label for="unit_id"> الوحدة </label>
                        <select id="unit_id" class="form-control select2" name="unit_id" style="width: 100%;">
                            @foreach($units as $unit)
                                <option {{$unit->id == $contract->unit_id ? 'selected' : ''}} value="{{$unit->id}}">{{$unit->unit_number}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-6 col-xs-12">
                        <label for="from">من</label>

                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>

                            <input type="text" name="from" required value="{{old('from') ?? $today}}" class="form-control pull-right" id="from" autocomplete="off" />
                        </div>
                    </div>

                    <div class="form-group col-md-6 col-xs-12">
                        <label for="">الى</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" name="to" required value="{{old('to') ?? $tomorrow}}" class="form-control pull-right" id="to" autocomplete="off" />
                        </div>
                    </div>
                </div>

                <div class="with-border">
                    <h3 class="box-title" style="margin-top: 25px">بيانات المستأجر / المرافق</h3>

                    <div class="row">
                        <div class="form-group col-md-4 col-xs-9">
                            <div class="form-inline">
                                <label for="tenant_name">اسم المستأجر</label>: &nbsp;<input id="mr" type="radio" name="tenant_title" value="mr" checked /> &nbsp;
                                <label for="mr">السيد</label> &nbsp;&nbsp;
                                <input id="mrs" type="radio" name="tenant_title" value="mrs" /> &nbsp;
                                <label for="mrs">السيدة</label>
                            </div>
                            <input id="tenant_name" type="text" class="form-control " required name="tenant_name" value="{{old('tenant_name') ?? $contract->tenant_name}}" placeholder="" style="width: 100%;" />
                        </div>

                        <div class="form-group col-md-4 col-xs-9 ">
                            <label for="tenant_name_code">  رقم الهوية </label>
                            <input id="tenant_name_code" value="{{old('tenant_name_code') ?? $contract->tenant_name_code}}" type="text" class="form-control" required name="tenant_name_code" style="width: 100%;" />
                        </div>

                        <div class="form-group col-md-4 col-xs-9">
                            <label for="phonenumber"> رقم جوال المستأجر </label>
                            <input id="phonenumber" type="text" class="form-control " required name="phonenumber" value="{{old('phonenumber') ?? $contract->phonenumber}}" placeholder="05xxxxxxxx" style="width: 100%;" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-4 col-xs-9 ">
                            <div class="form-inline">
                                <label for="with_tenant_name">بيانات المرافق</label>: &nbsp;<input id="wife" type="radio" name="with_tenant_title" value="wife" checked /> &nbsp;
                                <label for="wife">زوج/ة</label> &nbsp;&nbsp;
                                <input id="mother" type="radio" name="with_tenant_title" value="mother" {{old('with_tenant_title') == 'mother' ? 'checked' : ($contract->with_tenant_title == 'mother' ? 'checked' : '')}} /> &nbsp;
                                <label for="mother">والد/ة</label> &nbsp;&nbsp;
                                <input id="sister" type="radio" name="with_tenant_title" value="sister" {{old('with_tenant_title') == 'sister' ? 'checked' : ($contract->with_tenant_title == 'sister' ? 'checked' : '')}} /> &nbsp;
                                <label for="sister">أخ/أخت</label>
                                <input id="daughter" type="radio" name="with_tenant_title" value="daughter" {{old('with_tenant_title') == 'daughter' ? 'checked' : ($contract->with_tenant_title == 'daughter' ? 'checked' : '')}} /> &nbsp;
                                <label for="daughter">ابن/ة</label>
                                <input id="sister" type="radio" name="with_tenant_title" value="others" {{old('with_tenant_title') == 'others' ? 'checked' : ($contract->with_tenant_title == 'others' ? 'checked' : '')}} /> &nbsp;
                                <label for="sister">أخرى</label>
                            </div>
{{--                            <label for="with_tenant_name">  بيانات المرافق </label>--}}
                            <input id="with_tenant_name" type="text" class="form-control" required name="with_tenant_name" value="{{old('with_tenant_name') ?? $contract->with_tenant_name}}" style="width: 100%;" />
                        </div>

                        <div class="form-group col-md-4 col-xs-9 ">
                            <label for="with_tenant_name_code">  هوية المرافق </label>
                            <input id="with_tenant_name_code" value="{{old('with_tenant_name_code') ?? $contract->with_tenant_name_code}}" type="text" class="form-control" required name="with_tenant_name_code" style="width: 100%;" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-4 col-xs-9">
                            <label for="tenant_nationality">  جنسية المستأجر </label>
                            <input id="tenant_nationality" value="{{old('tenant_nationality') ?? $contract->tenant_nationality}}" type="text" class="form-control" required name="tenant_nationality" style="width: 100%;" />
                        </div>

                        <div class="form-group col-md-4 col-xs-9">
                            <label for="with_tenant_nationality">  جنسية المرافق </label>
                            <input id="with_tenant_nationality" value="{{old('with_tenant_nationality') ?? $contract->with_tenant_nationality}}" type="text" class="form-control" required name="with_tenant_nationality" style="width: 100%;" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-4 col-xs-9 ">
                            <label for="attachment_1">باركود هوية المستأجر (إختياري)</label>
                            <div class="uploaded-image">
                                <label class="upload-button" for="attachment_1"><img src="{{asset('images/icons/computing-cloud.svg')}}" alt="upload-image" style="width: 50px;height: auto" /></label>

                                <div class="uploaded-image-wrapper">
                                    <img src="" alt="" id="rental-barcode-image" />
                                    <input type="hidden" name="rental_barcode_image" id="rental-barcode-image-input" />
                                </div>
                            </div>

                            <input data-barcode-image="rental-barcode-image" data-barcode-input="rental-barcode-image-input" type="file" class="form-control single-file-upload @error('attachment_1') is-invalid @enderror" accept="image/*" value="{{old('attachment_1')}}" id="attachment_1" name="attachment_1" />
{{--                            <input id="attachment_1" type="file" class="form-control" required name="attachment_1" style="width: 100%;" />--}}
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-4 col-xs-9 ">
                            <label for="attachment_2">باركود هوية المرافق (إختياري)</label>
{{--                            <input id="attachment_2" type="file" class="form-control" required name="attachment_2" style="width: 100%;" />--}}

                            <input type="file" data-barcode-image="with-rental-barcode-image" data-barcode-input="with-rental-barcode-image-input" class="form-control single-file-upload @error('attachment_2') is-invalid @enderror" accept="image/*" value="{{old('attachment_2')}}" id="attachment_2" name="attachment_2" />

                            <div class="uploaded-image">
                                <label class="upload-button" for="attachment_2"><img src="{{asset('images/icons/computing-cloud.svg')}}" alt="upload-image" style="width: 50px;height: auto" /></label>

                                <div class="uploaded-image-wrapper">
                                    <img src="" alt="" id="with-rental-barcode-image" />
                                    <input type="hidden" name="with_rental_barcode_image" id="with-rental-barcode-image-input" />
                                </div>
                            </div>

                            @error('attachment_2')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-4 col-xs-9 ">
                            <label for="rent_value">قيمة الايجار</label>
                            <input id="rent_value" type="number" class="form-control" name="rent_value" value="{{old('rent_value') ?? $contract->rent_value}}" style="width: 100%;" />
                        </div>

                        <div class="form-group col-md-4 col-xs-9 ">
                            <label for="insurance_value">قيمة التأمين </label>
                            <input id="insurance_value" type="number" class="form-control" name="insurance_value" value="{{old('insurance_value') ?? $contract->insurance_value}}" style="width: 100%;" />
                        </div>
                    </div>

                    <h4 style="margin-top: 25px"><strong>السيارات</strong></h4>

                    <div id="cars">
                        @if($contract->exists)
                            @for($i=1;$i<=$contract->beach->allowed_cars;$i++)

                                @php
                                    $car = isset($contract->cars[$i-1]) ? $contract->cars[$i-1] : [];
                                @endphp

                                <h3>سيارة {{$i}}</h3>
                                <div class="row">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="car_type-{{$i}}">نوع السيارة {{$i > 0 ? '(إختياري)' : ''}}</label>
                                            <input type="text" class="form-control" id="car_type-{{$i}}" name="car[{{$i}}][type]" value="{{$car->car_type ?? ''}}" {{$i == 1 ? 'required' : ''}} />
                                            </div>
                                        </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="car_serial-{{$i}}">بيانات اللوحة {{$i > 0 ? '(إختياري)' : ''}}</label>
                                            <input type="text" class="form-control" id="car_serial-{{$i}}" value="{{$car->car_serial ?? ''}}" name="car[{{$i}}][serial]" {{$i == 1 ? 'required' : ''}} />
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        @endif
                    </div>

                </div>

                <div class="form-group col-md-1">
                    @if($contract->exists)
                        <button type="submit" class="btn btn-success"><i class="fa fa-plus"></i> تعديل العقد </button>
                    @else
                        <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> إضافة العقد </button>
                    @endif
                </div>

                <div style="clear:both"></div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('js/moment.min.js')}}"></script>
    <script src="{{asset('js/cropper.min.js')}}"></script>
    <script src="{{asset('js/bootstrap-datepicker.ar.js')}}" charset="UTF-8"></script>

    <script>

        $('#from').datepicker({
            autoclose: true,
            format:'yyyy/mm/dd',
            startDate: '0d',
            disableTouchKeyboard: true,
            orientation: "bottom",
            rtl: true
        });

        $('#to').datepicker({
            autoclose: true,
            format:'yyyy/mm/dd',
            startDate: '+1d',
            endDate: '+15d',
            disableTouchKeyboard: true,
            orientation: "bottom",
            rtl: true,
        });

        $('#from').on('change', function (){
            const startDate = $(this).val()
            const newVal = moment(startDate).add(1, 'days').format('YYYY/MM/DD');
            const newMaxVal = moment(startDate).add(15, 'days').format('YYYY/MM/DD');

            $('#to').val(newVal);

            $('#to').datepicker("destroy");

            $('#to').datepicker({
                autoclose: true,
                format:'yyyy/mm/dd',
                startDate: newVal,
                endDate: newMaxVal,
                disableTouchKeyboard: true,
                orientation: "bottom",
                rtl: true,
            });
        })
        //     const toDate = $('#to')
        //     const startDate = $(this).val()
        //     const newVal = moment(startDate).add(1, 'days').format('YYYY-MM-DD');
        //
        //     console.log($(toDate).attr('min'))
        //     $(toDate).attr('min', newVal)
        //     $(toDate).val(newVal)
        // })

        // $('#from').on('change', function (){
        //     const toDate = $('#to')
        //     const startDate = $(this).val()
        //     const newVal = moment(startDate).add(1, 'days').format('YYYY-MM-DD');
        //
        //     console.log($(toDate).attr('min'))
        //     $(toDate).attr('min', newVal)
        //     $(toDate).val(newVal)
        // })

        @if(count($sectors) > 0 && !$contract->exists)
            changeBeaches({{$sectors[0]->id}})
        @endif

        $('#sector_id').on('change', function (){
            const sector_id = $(this).val()

            changeBeaches(sector_id)
            // $('#unit').html('<option>اختر فيلا</option>')
        })

        $('body').on('change', '#beach_id', function () {
            const beach_id = $(this).val()
            changeVillas(beach_id)
        });

        function changeBeaches(sector_id){
            let output = '';

            $.post('/api/get-beaches/'+sector_id).done(function (data){
                for (let i=0;i<data.data.length;i++){
                    output += "<option value='"+data.data[i].id+"'>"+data.data[i].beach+"</option>"
                }

                $('#beach_id').html(output)
                $('.select2').select();
                changeVillas(data.data[0].id)
                @if(!$contract->exists)
                loadCars(data.data[0].allowed_cars)
                @endif
            })
        }
        function changeVillas(beach_id){
            let output = '';

            $.post('/get-single-beach/'+beach_id).done(function (data){
                @if(!$contract->exists)
                loadCars(data.allowed_cars)
                @endif
            });

            $.post('/api/get-villas/'+beach_id).done(function (data){
                for (let i=0;i<data.data.length;i++){
                    output += "<option value='"+data.data[i].id+"'>"+data.data[i].unit_number+"</option>"
                }
                $('.select2').select();
                $('#unit_id').html(output)
            })
        }

        const modal = $();

        function loadCars(cars){
            let output = '';
            var i;


            for (i=1; i<=cars; i++){
                output += '<h3>سيارة '+(i)+'</h3>';
                output += '<div class="row">';
                output += '<div class="col-md-6 col-xs-12">';
                output += '<div class="form-group">';
                output += '<label for="car_type'+i+'">نوع السيارة '+(i>1 ? "(اختياري)" : "")+'</label>';
                output += '<input type="text" class="form-control" id="car_type'+i+'" name="car['+i+'][type]" />';
                output += '</div>';
                output += '</div>';
                output += '<div class="col-md-6 col-xs-12">';
                output += '<div class="form-group">';
                output += '<label for="car_serial'+i+'">بيانات اللوحة '+(i>1 ? "(اختياري)" : "")+'</label>';
                output += '<input type="text" class="form-control" id="car_serial'+i+'" name="car['+i+'][serial]" />';
                output += '</div>';
                output += '</div>';
                if (i>1){
                    output += '<div class="col-md-6 col-xs-12">';
                    output += '<div class="form-group">';
                    output += '<label for="passenger_name'+i+'">اسم السائق (اختياري)</label>';
                    output += '<input type="text" class="form-control" id="passenger_name'+i+'" name="car['+i+'][passenger_name]" />';
                    output += '</div>';
                    output += '</div>';

                    output += '<div class="col-md-6 col-xs-12">';
                    output += '<div class="form-group">';
                    output += '<label for="identity'+i+'">رقم الهويه (اختياري)</label>';
                    output += '<input type="text" class="form-control" id="identity'+i+'" name="car['+i+'][identity]" />';
                    output += '</div>';
                    output += '</div>';
                }
                output += '</div>';
            }

            $('#cars').html(output)
        }

        // Crop Image
        //  bs_modal = $('#crop-image'),
        // const with_bs_modal = $('#with-crop-image'),
        //       image = document.getElementById('image'),
        //       with_image = document.getElementById('with_image')

        var bs_modal = $('#crop-image');
        var image = document.getElementById('image');

        var cropper,reader,file;


        $("body").on("change", ".single-file-upload", function(e) {
            var files = e.target.files;
            const crop = $("#crop");

            crop.data('barcode-image', $(this).data('barcode-image'));
            crop.data('barcode-input', $(this).data('barcode-input'));

            var done = function(url) {
                image.src = url;
                bs_modal.modal('show');
            };

            if (files && files.length > 0) {
                file = files[0];

                if (URL) {
                    done(URL.createObjectURL(file));
                } else if (FileReader) {
                    reader = new FileReader();
                    reader.onload = function(e) {
                        done(reader.result);
                    };
                    reader.readAsDataURL(file);
                }
            }
        });

        bs_modal.on('shown.bs.modal', function() {
            cropper = new Cropper(image, {
                aspectRatio: 1,
                viewMode: 3,
                preview: '.preview'
            });

        }).on('hidden.bs.modal', function() {
            cropper.destroy();
            cropper = null;
        });

        $("#crop").click(function() {
            canvas = cropper.getCroppedCanvas({
                width: 400,
                height: 366
            });

            const barcodeImage = $(this).data('barcode-image');
            const barcodeInput = $(this).data('barcode-input');

            canvas.toBlob(function(blob) {
                url = URL.createObjectURL(blob);
                var reader = new FileReader();
                reader.readAsDataURL(blob);
                reader.onloadend = function() {
                    var base64data = reader.result;

                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "{{investor_url('image-temp-upload')}}",
                        data: {image: base64data},
                        success: function(data) {
                            bs_modal.modal('hide');
                            $('#'+barcodeImage).attr('src', data.request)
                            $('#'+barcodeInput).val(data.path)
                        }
                    });
                };
            });
        });
    </script>
@endsection
