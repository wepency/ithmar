<?php
    $format = 'Y-m-d';

    $today = \Carbon\Carbon::today()->format($format);
    $tomorrow = \Carbon\Carbon::tomorrow()->format($format);
?>

@extends('layouts.front-page')

@section('styles')
    <link rel="stylesheet" href="{{asset('css/cropper.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{asset('css/contract.css')}}" />
@endsection

@section('content')
    <div class="container">

        <div class="form-container">
            <ul id="section-tabs">
                <li class="current active"><span>1.</span> بيانات العقد </li>
                <li><span>2.</span> تأكيد رقم الجوال</li>
                <li><span>3.</span> الدفع و التفعيل</li>
            </ul>

            <div class="card">
                <div class="preloader-wrapper">
                    <img src="{{asset('images/preloader.svg')}}" alt="loading ..." />
                </div>

                <div class="card-body">

                    <div class="progress-outer">
                        <div class="progress">
                            <div class="progress-bar progress-bar-info progress-bar-striped active" style="width:80%; box-shadow:0 3px 10px rgba(91, 192, 222, 0.7);"></div>
                            {{--                        <div class="progress-value">80%</div>--}}
                        </div>
                    </div>

                    @include('admin.layouts.messages')

                    @if(count($refused) > 0)
                        <div class="alert alert-danger">
                            الوحدة/الوحدات مغلقة:
                            <ul>
                                @foreach($refused as $ref)
                                    <li>{{$ref->unit_number}} - {{$ref->note}}</li>
                                @endforeach
                            </ul>

                        </div>
                    @endif

                    @if(count($expired) > 0)
                        <div class="alert alert-warning">
                            الوحدة/الوحدات تحتاج إلى تحديث:
                            <ul>
                                @foreach($expired as $exp)
                                    <li>{{$exp->unit_number}} <a href="{{investor_url('unit/update/'.base64_encode($exp->id))}}">تحديث المرفقات</a></li>
                                @endforeach
                            </ul>

                            <p><strong>يمكنك انشاء العقود بعد تحديث الوحدات والموافقه عليها من الاداره</strong></p>
                        </div>
                    @endif

                    @if(!is_blocked() && count($sectors) > 0)
                        <form id="contract-form" style="background-color: #fff;padding: 0;margin-bottom: 0" method="post" action="{{url('/contracts/save')}}" enctype="multipart/form-data">
                            @csrf

                            <div id="main-form" class="form-box">
                                <div class="row">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="sector">رقم القطاع</label>

                                            <select class="form-control @error('sector_id') is-invalid @enderror" id="sector" name="sector_id">
                                                @foreach($sectors as $sector)
                                                    <option {{old('sector_id') == $sector->id ? 'selected' : ''}} value="{{$sector->id}}">{{$sector->sector_name}}</option>
                                                @endforeach
                                            </select>

                                            @error('sector_id')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="beach">الشاطئ</label>

                                            <select class="form-control @error('beach_id') is-invalid @enderror" id="beach" name="beach_id"></select>

                                            @error('beach_id')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="unit">رقم الفيلا</label>
                                            <select class="form-control @error('unit_id') is-invalid @enderror" id="unit" name="unit_id"></select>

                                            @error('unit_id')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

{{--                                <div class="row">--}}
{{--                                    <div class="col-md-6 col-xs-12">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <div class="form-inline">--}}
{{--                                                <label for="tenant_name">اسم المستأجر</label>: &nbsp;<input id="mr" type="radio" name="tenant_title" value="mr" checked /> &nbsp;--}}
{{--                                                <label for="mr">السيد</label> &nbsp;&nbsp;--}}
{{--                                                <input id="mrs" type="radio" name="tenant_title" value="mrs" /> &nbsp;--}}
{{--                                                <label for="mrs">السيدة</label>--}}
{{--                                            </div>--}}
{{--                                            <input type="text" class="form-control @error('tenant_name') is-invalid @enderror" id="tenant_name" value="{{old('tenant_name')}}" name="tenant_name" required />--}}

{{--                                            @error('tenant_name')--}}
{{--                                            <div class="text-danger">{{ $message }}</div>--}}
{{--                                            @enderror--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                    <div class="col-md-6 col-xs-12">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label for="tenant_name_code">رقم الهوية</label>--}}
{{--                                            <input type="text" class="form-control @error('tenant_name_code') is-invalid @enderror" id="tenant_name_code" value="{{old('tenant_name_code')}}" name="tenant_name_code" required />--}}

{{--                                            @error('tenant_name_code')--}}
{{--                                            <div class="text-danger">{{ $message }}</div>--}}
{{--                                            @enderror--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="row">--}}
{{--                                    <div class="col-md-6 col-xs-12">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <div class="form-inline">--}}
{{--                                                <label for="with_tenant_title">بيانات المرافق</label>: &nbsp;<input id="wife" type="radio" name="with_tenant_title" value="wife" checked /> &nbsp;--}}
{{--                                                <label for="wife">زوج/ة</label> &nbsp;&nbsp;--}}
{{--                                                <input id="mother" type="radio" name="with_tenant_title" value="mother" /> &nbsp;--}}
{{--                                                <label for="mother">والد/ة</label> &nbsp;&nbsp;--}}
{{--                                                <input id="sister" type="radio" name="with_tenant_title" value="sister" /> &nbsp;--}}
{{--                                                <label for="sister">أخ/أخت</label> &nbsp;--}}
{{--                                                <input id="daughter" type="radio" name="with_tenant_title" value="daughter" /> &nbsp;--}}
{{--                                                <label for="daughter">ابن/ة</label> &nbsp;--}}
{{--                                                <input id="others" type="radio" name="with_tenant_title" value="others" /> &nbsp;--}}
{{--                                                <label for="others">أخرى</label>--}}
{{--                                            </div>--}}
{{--                                            --}}{{--                                        <label for="with_tenant_name">اسم المرافق</label>--}}
{{--                                            <input type="text" class="form-control @error('with_tenant_name') is-invalid @enderror" value="{{old('with_tenant_name')}}" id="with_tenant_name" name="with_tenant_name" required />--}}

{{--                                            @error('with_tenant_name')--}}
{{--                                            <div class="text-danger">{{ $message }}</div>--}}
{{--                                            @enderror--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                    <div class="col-md-6 col-xs-12">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label for="with_tenant_name_code">رقم الهوية</label>--}}
{{--                                            <input type="text" class="form-control @error('with_tenant_name_code') is-invalid @enderror" value="{{old('with_tenant_name_code')}}" id="with_tenant_name_code" name="with_tenant_name_code" required />--}}

{{--                                            @error('with_tenant_name_code')--}}
{{--                                            <div class="text-danger">{{ $message }}</div>--}}
{{--                                            @enderror--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                    <div class="col-md-6 col-xs-12">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label for="tenant_nationality">جنسية المستأجر</label>--}}
{{--                                            <input type="text" class="form-control @error('tenant_nationality') is-invalid @enderror" value="{{old('tenant_nationality')}}" id="tenant_nationality" name="tenant_nationality" required />--}}

{{--                                            @error('tenant_nationality')--}}
{{--                                            <div class="text-danger">{{ $message }}</div>--}}
{{--                                            @enderror--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                    <div class="col-md-6 col-xs-12">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label for="with_tenant_nationality">جنسية المرافق</label>--}}
{{--                                            <input type="text" class="form-control @error('with_tenant_nationality') is-invalid @enderror" value="{{old('with_tenant_nationality')}}" id="with_tenant_nationality" name="with_tenant_nationality" required />--}}

{{--                                            @error('with_tenant_nationality')--}}
{{--                                            <div class="text-danger">{{ $message }}</div>--}}
{{--                                            @enderror--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                    <div class="col-md-6 col-xs-12">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label for="insurance_value">قيمة التأمين</label>--}}
{{--                                            <input type="text" class="form-control @error('insurance_value') is-invalid @enderror" value="{{old('insurance_value')}}" id="insurance_value" name="insurance_value" required />--}}

{{--                                            @error('insurance_value')--}}
{{--                                            <div class="text-danger">{{ $message }}</div>--}}
{{--                                            @enderror--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="row">--}}
{{--                                    <div class="col-md-6 col-xs-12">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label for="attachment_1">باركود هوية المستأجر (إختياري)</label>--}}

{{--                                            <div class="uploaded-image">--}}
{{--                                                <label class="upload-button" for="attachment_1"><img src="{{asset('images/icons/computing-cloud.svg')}}" alt="upload-image" style="width: 50px;height: auto" /></label>--}}

{{--                                                <div class="uploaded-image-wrapper">--}}
{{--                                                    <img src="" alt="" id="rental-barcode-image" />--}}
{{--                                                    <input type="hidden" name="rental_barcode_image" id="rental-barcode-image-input" />--}}
{{--                                                </div>--}}
{{--                                            </div>--}}

{{--                                            <input data-barcode-image="rental-barcode-image" data-barcode-input="rental-barcode-image-input" type="file" class="form-control single-file-upload @error('attachment_1') is-invalid @enderror" accept="image/*" value="{{old('attachment_1')}}" id="attachment_1" name="attachment_1" />--}}

{{--                                            @error('attachment_1')--}}
{{--                                            <div class="text-danger">{{ $message }}</div>--}}
{{--                                            @enderror--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="row">--}}
{{--                                    <div class="col-md-6 col-xs-12">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label for="attachment_2">باركود هوية المرافق (إختياري)</label>--}}
{{--                                            <input type="file" data-barcode-image="with-rental-barcode-image" data-barcode-input="with-rental-barcode-image-input" class="form-control single-file-upload @error('attachment_2') is-invalid @enderror" accept="image/*" value="{{old('attachment_2')}}" id="attachment_2" name="attachment_2" />--}}

{{--                                            <div class="uploaded-image">--}}
{{--                                                <label class="upload-button" for="attachment_2"><img src="{{asset('images/icons/computing-cloud.svg')}}" alt="upload-image" style="width: 50px;height: auto" /></label>--}}

{{--                                                <div class="uploaded-image-wrapper">--}}
{{--                                                    <img src="" alt="" id="with-rental-barcode-image" />--}}
{{--                                                    <input type="hidden" name="with_rental_barcode_image" id="with-rental-barcode-image-input" />--}}
{{--                                                </div>--}}
{{--                                            </div>--}}

{{--                                            @error('attachment_2')--}}
{{--                                            <div class="text-danger">{{ $message }}</div>--}}
{{--                                            @enderror--}}

{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="row">--}}
{{--                                    <div class="col-md-6 col-xs-12">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label for="from">تاريخ الدخول</label>--}}
{{--                                            <input type="text" value="{{old('from') ?? $today}}" min="{{$today}}" class="form-control @error('from') is-invalid @enderror" id="from" name="from" required />--}}

{{--                                            @error('from')--}}
{{--                                            <div class="text-danger">{{ $message }}</div>--}}
{{--                                            @enderror--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="row">--}}
{{--                                    <div class="col-md-6 col-xs-12">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label for="to">تاريخ المغادرة</label>--}}
{{--                                            <input type="text" value="{{old('to') ?? $tomorrow}}" min="{{$tomorrow}}" class="form-control @error('to') is-invalid @enderror" id="to" name="to" required />--}}

{{--                                            @error('to')--}}
{{--                                            <div class="text-danger">{{ $message }}</div>--}}
{{--                                            @enderror--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="row">--}}
{{--                                    <div class="col-md-6 col-xs-12">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label for="rent_value">قيمة الإيجار</label>--}}
{{--                                            <input type="text" class="form-control @error('rent_value') is-invalid @enderror" value="{{old('rent_value')}}" id="rent_value" name="rent_value" required />--}}

{{--                                            @error('rent_value')--}}
{{--                                            <div class="text-danger">{{ $message }}</div>--}}
{{--                                            @enderror--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div id="cars"></div>--}}

                                <div class="form-group m-0">
                                    <button type="submit" data-sitekey="6LeAwgweAAAAAHGc2Kkn-lZovouBXCWwif2hDAOC" data-callback="onSubmit" data-action="submit" class="g-recaptcha gb gb-bordered hover-slide gb9"><i class="arrow_right"></i> <span class="text">التالي</span></button>
                                    <button class="gb gb-bordered hover-slide hover-fill gb10"><i class="icon_close"></i> <span class="text">إلغاء</span></button>
                                </div>
                            </div>

                        </form>
                    @elseif(count($sectors) == 0)
                        <div class="alert alert-danger text-center">
                            <div><img style="max-width: 50px; margin: 10px auto" src="{{asset('images/icons/close.svg')}}" alt="Forbidden" /></div>
                            <h3>ليس لديك وحدات مفعلة.</h3>
                        </div>
                    @else
                        <div class="alert alert-danger text-center">
                            <div><img style="max-width: 50px" src="{{asset('images/icons/close.svg')}}" alt="Forbidden" /></div>
                            @if(!is_null(block_note()))
                                <h3>عفوا قد تم إيقاف خاصية اضافة عقود بسبب:</h3>
                                <h4>{{auth()->user()->blocked_note}}</h4>
                            @else
                                <h3>عفوا قد تم إيقاف خاصية اضافة عقود</h3>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

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
@endsection

@section('scripts')
    <script src="{{asset('js/moment.min.js')}}"></script>
    <script src="{{asset('js/cropper.min.js')}}"></script>
    <script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-datepicker.ar.js') }}"></script>

    <script>
        const modal = $();

        $(document).ready(function (){
            $('.preloader-wrapper').remove()

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
        })

        @if(count($sectors) > 0)
            changeBeaches({{$sectors[0]->id}})
        @endif

        $('#sector').on('change', function (){
            const sector_id = $(this).val()

            changeBeaches(sector_id)
            // $('#unit').html('<option>اختر فيلا</option>')
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
                changeVillas(data.data[0].id)
                loadCars(data.data[0].allowed_cars)
            })
        }

        function changeVillas(beach_id){
            let output = '';

            $.post('/get-single-beach/'+beach_id).done(function (data){
                loadCars(data.allowed_cars)
            });

            $.post('/get-villas-investor/'+beach_id).done(function (data){
                for (let i=0;i<data.data.length;i++){
                    output += "<option value='"+data.data[i].id+"'>"+data.data[i].unit_number+"</option>"
                }

                $('#unit').html(output)
            })
        }

        function loadCars(cars){
            let output = '';
            var i;

            for (i=1; i<=cars; i++){
                output += '<h3>سيارة '+(i)+'</h3>';
                output += '<div class="row">';
                output += '<div class="col-md-6 col-xs-12">';
                output += '<div class="form-group">';
                output += '<label for="car_type'+i+'">نوع السيارة '+("(اختياري)")+'</label>';
                output += '<input type="text" class="form-control" id="car_type'+i+'" name="car['+i+'][type]" />';
                output += '</div>';
                output += '</div>';
                output += '<div class="col-md-6 col-xs-12">';
                output += '<div class="form-group">';
                output += '<label for="car_serial'+i+'">بيانات اللوحة '+("(اختياري)")+'</label>';
                output += '<input type="text" class="form-control" id="car_serial'+i+'" name="car['+i+'][serial]" />';
                output += '</div>';
                output += '</div>';
                // if (i>1){
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
                // }
                output += '</div>';
            }

            $('#cars').html(output)
        }

        $('#from').on('change', function (){
            const toDate = $('#to')
            const startDate = $(this).val()
            const newVal = moment(startDate).add(1, 'days').format('YYYY-MM-DD');

            console.log($(toDate).attr('min'))
            $(toDate).attr('min', newVal)
            $(toDate).val(newVal)
        })

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

        $('form').on('submit', function (e){
            $('#submit-form').attr('disabled', true)
        })
    </script>
@endsection
