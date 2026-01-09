<?php
$format = 'Y-m-d';

$from = \Carbon\Carbon::parse($reservation->from)->format($format);
$to = \Carbon\Carbon::parse($reservation->to)->format($format);
?>

@extends('layouts.front-page')

@section('styles')
    <link rel="stylesheet" href="{{asset('css/cropper.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{asset('css/contract.css')}}" />
    {{--    <script src="https://www.google.com/recaptcha/api.js"></script>--}}
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

            <ul id="section-tabs">
                <li class="current active"><span>1.</span> بيانات العقد </li>
                <li><span>2.</span> الدفع و التفعيل </li>
            </ul>

            <div class="card">
                <div class="preloader-wrapper">
                    <img src="{{asset('images/preloader.svg')}}" alt="loading ..." />
                </div>

                <div class="card-body">

                    <div class="progress-outer mt-4 mb-4">
                        <div class="progress-bar--ribbon">
                            <span class="step-header">بيانات الوحدة</span>
                        </div>
                        <div class="progress-bar--ribbon">
                            <span class="step-header">تاريخ الحجز</span>
                        </div>
                        <div class="progress-bar--ribbon">
                            <span class="step-header">بيانات المستأجر</span>
                        </div>
                        <div class="progress-bar--ribbon">
                            <span class="step-header">بيانات المرافق</span>
                        </div>
                        <div class="progress-bar--ribbon">
                            <span class="step-header">بيانات الحجز</span>
                        </div>
                        <div class="progress-bar--ribbon">
                            <span class="step-header">السيارات</span>
                        </div>
                    </div>

                    @include('admin.layouts.messages')

                    @if(!is_blocked())
                        <form id="contract-form" style="background-color: #fff;padding: 0;margin-bottom: 0" method="post" action="{{route('reservation.contract.store', deep_encode($reservation->id, $reservation->created_at))}}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div id="main-form" class="form-box">
                                <div class="step active" data-step="1">
                                    <h3 class="form-title"><span class="form-ribbon">1. اختيار الوحدة</span></h3>

                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group">
                                                <label for="sector" class="form-label">رقم القطاع</label>
                                                <input type="text" disabled class="form-control mt-2 w-100 @error('sector_id') has-error @enderror" id="sector" value="{{$reservation->unit->unit->sector->sector_name}}"  />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group">
                                                <label for="beach" class="form-label">الشاطئ</label>
                                                <input type="text" disabled class="form-control mt-2 w-100" id="beach" value="{{$reservation->unit->unit->beach->beach}}"  />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group">
                                                <label for="unit" class="form-label">رقم الفيلا</label>
                                                <input type="text" disabled class="form-control mt-2 w-100" id="unit" value="{{$reservation->unit->unit->unit_number}}"  />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="step" data-step="2">
                                    <h3 class="form-title"><span class="form-ribbon">2. تاريخ الحجز</span></h3>

                                    <div id="available-error" style="display: none">
                                        <div class="alert alert-danger alert-dismissible new2 p-4" role="alert">
                                            <i class="alert-icon icon_close_alt2" aria-hidden="true"></i>

                                            <div class="alert-body"></div>

                                            <button type="button" class="close-button" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group">
                                                <label for="from">تاريخ الدخول</label>

                                                <div class="form-control-container">
                                                    <input type="text" value="{{$from}}" class="form-control grey" id="from" disabled />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group">
                                                <label for="to">تاريخ المغادرة</label>

                                                <div class="form-control-container">
                                                    <input type="text" value="{{$to}}" class="form-control grey" id="to" disabled />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="step" data-step="3">
                                    <h3 class="form-title"><span class="form-ribbon">3. بيانات المستأجر</span></h3>

                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group">
                                                <div class="form-inline">
                                                    <label for="tenant_name" class="form-label">اسم المستأجر</label>
                                                </div>

                                                <div class="radio-buttons small-wrapper">
                                                    <input id="mr" type="radio" name="tenant_title" value="mr" checked />
                                                    <label for="mr">السيد</label>
                                                    <input id="mrs" type="radio" name="tenant_title" value="mrs" />
                                                    <label for="mrs">السيدة</label>
                                                </div>

                                                <div class="form-control-container">
                                                    <input type="text" data-max-size="191" maxlength="191" class="form-control grey @error('tenant_name') has-error @enderror" id="tenant_name" value="{{old('tenant_name')}}" name="tenant_name" required />
                                                    <i></i>


                                                    <div class="text-danger @error('tenant_name') active @enderror">
                                                        @error('tenant_name')
                                                        {{ $message }}
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-end col-md-6 col-xs-12">
                                            <div class="form-group">
                                                <label for="tenant_name_code">رقم الهوية</label>

                                                <div class="form-control-container">
                                                    <input type="text" maxlength="10" data-size="10" class="form-control grey @error('tenant_name_code') has-error @enderror" id="tenant_name_code" value="{{old('tenant_name_code')}}" name="tenant_name_code" required />
                                                    <i></i>

                                                    <div class="text-danger @error('tenant_name_code') active @enderror">
                                                        @error('tenant_name_code')
                                                        {{ $message }}
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group">
                                                <label for="tenant_nationality">جنسية المستأجر</label>
                                                <div class="form-control-container">
                                                    <input type="text" data-max-size="191" maxlength="191" class="form-control grey @error('tenant_nationality') has-error @enderror" value="{{old('tenant_nationality')}}" id="tenant_nationality" name="tenant_nationality" required />
                                                    <i></i>
                                                </div>

                                                <div class="text-danger @error('tenant_nationality') active @enderror">
                                                    @error('tenant_nationality')
                                                    {{ $message }}
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group">
                                                <label for="attachment_1">باركود هوية المستأجر (إختياري)</label>

                                                <div class="uploaded-image">
                                                    <label class="upload-button" for="attachment_1"><img src="{{asset('images/icons/computing-cloud.svg')}}" alt="upload-image" style="width: 50px;height: auto" /></label>

                                                    <div class="uploaded-image-wrapper">
                                                        <img src="" alt="" id="rental-barcode-image" />
                                                        <input type="hidden" name="rental_barcode_image" id="rental-barcode-image-input" />
                                                    </div>
                                                </div>

                                                <input data-barcode-image="rental-barcode-image" data-barcode-input="rental-barcode-image-input" type="file" class="form-control single-file-upload @error('attachment_1') has-error @enderror" accept="image/*" value="{{old('attachment_1')}}" id="attachment_1" name="attachment_1" />

                                                <div class="text-danger @error('attachment_1') active @enderror">
                                                    @error('attachment_1')
                                                    {{ $message }}
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="step" data-step="4">
                                    <h3 class="form-title"><span class="form-ribbon">4. بيانات المرافق</span></h3>

                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group">
                                                <div class="form-inline">
                                                    <label for="with_tenant_title" class="form-label">بيانات المرافق</label>
                                                </div>

                                                <div class="radio-buttons large-wrapper">
                                                    <input id="wife" type="radio" name="with_tenant_title" value="wife" checked />
                                                    <label for="wife">زوج/ة</label>
                                                    <input id="mother" type="radio" name="with_tenant_title" value="mother" />
                                                    <label for="mother">والد/ة</label>
                                                    <input id="sister" type="radio" name="with_tenant_title" value="sister" />
                                                    <label for="sister">أخ/أخت</label>
                                                    <input id="daughter" type="radio" name="with_tenant_title" value="daughter" />
                                                    <label for="daughter">ابن/ة</label>
                                                    <input id="others" type="radio" name="with_tenant_title" value="others" />
                                                    <label for="others">أخرى</label>
                                                </div>
                                                {{--                                        <label for="with_tenant_name">اسم المرافق</label>--}}

                                                <div class="form-control-container">
                                                    <input type="text" data-max-size="191" maxlength="191" class="form-control grey @error('with_tenant_name') has-error @enderror" value="{{old('with_tenant_name')}}" id="with_tenant_name" name="with_tenant_name" required />
                                                    <i></i>
                                                </div>

                                                <div class="text-danger @error('with_tenant_name') active @enderror">
                                                    @error('with_tenant_name')
                                                    {{ $message }}
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 flex-end col-xs-12">
                                            <div class="form-group">
                                                <label for="with_tenant_name_code">رقم الهوية</label>

                                                <div class="form-control-container">
                                                    <input type="text" maxlength="10" class="form-control grey @error('with_tenant_name_code') has-error @enderror" value="{{old('with_tenant_name_code')}}" id="with_tenant_name_code" name="with_tenant_name_code" required />
                                                    <i></i>
                                                </div>

                                                <div class="text-danger @error('with_tenant_name_code') active @enderror">
                                                    @error('with_tenant_name_code')
                                                    {{ $message }}
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group">
                                                <label for="with_tenant_nationality">جنسية المرافق</label>

                                                <div class="form-control-container">
                                                    <input type="text" data-max-size="191" maxlength="191" class="form-control grey @error('with_tenant_nationality') has-error @enderror" value="{{old('with_tenant_nationality')}}" id="with_tenant_nationality" name="with_tenant_nationality" required />
                                                    <i></i>
                                                </div>

                                                <div class="text-danger @error('with_tenant_nationality') active @enderror">
                                                    @error('with_tenant_nationality')
                                                    {{ $message }}
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group">
                                                <label for="attachment_2">باركود هوية المرافق (إختياري)</label>
                                                <input type="file" data-barcode-image="with-rental-barcode-image" data-barcode-input="with-rental-barcode-image-input" class="form-control single-file-upload @error('attachment_2') has-error @enderror" accept="image/*" value="{{old('attachment_2')}}" id="attachment_2" name="attachment_2" />

                                                <div class="uploaded-image">
                                                    <label class="upload-button" for="attachment_2"><img src="{{asset('images/icons/computing-cloud.svg')}}" alt="upload-image" style="width: 50px;height: auto" /></label>

                                                    <div class="uploaded-image-wrapper">
                                                        <img src="" alt="" id="with-rental-barcode-image" />
                                                        <input type="hidden" name="with_rental_barcode_image" id="with-rental-barcode-image-input" />
                                                    </div>
                                                </div>

                                                <div class="text-danger @error('attachment_2') active @enderror">
                                                    @error('attachment_2')
                                                    {{ $message }}
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="step" data-step="5">
                                    <h3 class="form-title"><span class="form-ribbon">5. بيانات الحجز</span></h3>

                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group">
                                                <label for="rent_value">قيمة الإيجار</label>

                                                <div class="form-control-container">
                                                    <input type="number" class="form-control grey" value="{{$reservation->total}}" id="rent_value" disabled />
                                                    <i></i>
                                                </div>

                                                <div class="text-danger @error('rent_value') active @enderror">
                                                    @error('rent_value')
                                                    {{ $message }}
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group">
                                                <label for="insurance_value">قيمة التأمين</label>
                                                <div class="form-control-container">
                                                    <input type="number" data-max-size="6" maxlength="6" class="form-control grey @error('insurance_value') has-error @enderror" value="{{old('insurance_value')}}" id="insurance_value" name="insurance_value" required />
                                                    <i></i>
                                                </div>

                                                <div class="text-danger @error('insurance_value') active @enderror">
                                                    @error('insurance_value')
                                                    {{ $message }}
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="step" data-step="6">
                                    <h3 class="form-title"><span class="form-ribbon">6. السيارات</span></h3>

                                    <div id="cars"></div>
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
                                    ><i class="icon_check"></i> <span class="text">تسجيل العقد</span></button>
                                    <button class="gb gb-bordered hover-slide ml-2 hover-fill prev gb10"><i class="icon_close"></i> <span class="text">السابق</span></button>
                                </div>
                            </div>

                        </form>
                    @else
                        <div class="alert alert-danger text-center">
                            <div><i class="icon_close_alt2" style="font-size:50px;color:#dc3545!important"></i></div>

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

        function submitNewContract(token) {
            document.getElementById('contract-form').submit();
        }

        // Handle Next & Prev
        $('.next').on('click', function (e){
            e.preventDefault();
            $(this).attr('disabled', true).addClass('button-loading');

            let activeStep = $('.step.active'),
                currentStep = activeStep.data('step');

            if (currentStep === 1)
                $('.prev').show()

            if (currentStep === 5) {
                $(this).hide()
                $('#submit').show()
            }

            if(currentStep === 2){
                $.post('/api/checkAvailability', {
                    from: $('#from').val(),
                    to: $('#to').val(),
                    unit: $('#unit').val()
                }).done(function (data){
                    $('#available-error').hide().find('.alert-body').html('');
                    $('#from, #to').removeClass('has-error').addClass('valid')
                    goNext(currentStep)
                }).fail(function(xhr, status, error) {
                    $('#available-error').show().find('.alert-body').html(xhr.responseJSON);
                    $('#from, #to').addClass('has-error')
                });

            }else {
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

                if (errorsCount === 0)
                    goNext(currentStep)
            }

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

        function loadCars(cars){
            let output = '';
            var i;

            for (i=1; i<=cars; i++){
                output += '<h3>سيارة '+(i)+'</h3>';
                output += '<div class="row">';
                output += '<div class="col-md-6 col-xs-12">';
                output += '<div class="form-group">';
                output += '<label class="form-label" for="car_type'+i+'">نوع السيارة '+("(اختياري)")+'</label>';
                output += '<input type="text" class="form-control grey" id="car_type'+i+'" name="car['+i+'][type]" />';
                output += '</div>';
                output += '</div>';
                output += '<div class="col-md-6 col-xs-12">';
                output += '<div class="form-group">';
                output += '<label class="form-label" for="car_serial'+i+'">بيانات اللوحة '+("(اختياري)")+'</label>';
                output += '<input type="text" class="form-control grey" id="car_serial'+i+'" name="car['+i+'][serial]" />';
                output += '</div>';
                output += '</div>';
                // if (i>1){
                output += '<div class="col-md-6 col-xs-12">';
                output += '<div class="form-group">';
                output += '<label class="form-label" for="passenger_name'+i+'">اسم السائق (اختياري)</label>';
                output += '<input type="text" class="form-control grey" id="passenger_name'+i+'" name="car['+i+'][passenger_name]" />';
                output += '</div>';
                output += '</div>';

                output += '<div class="col-md-6 col-xs-12">';
                output += '<div class="form-group">';
                output += '<label class="form-label" for="identity'+i+'">رقم الهويه (اختياري)</label>';
                output += '<input type="text" class="form-control grey" id="identity'+i+'" name="car['+i+'][identity]" />';
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

        const bs_modal = $('#crop-image');
        const image = document.getElementById('image');

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

        function goNext(currentStep){
            $('.progress-bar--ribbon').eq(currentStep-1).addClass('active')
            $('body').find('.step.active').hide().removeClass('active').next().fadeIn().addClass('active')
        }

        $('form').on('submit', function (e){
            $('#submit-form').attr('disabled', true)
        })

        loadCars('{{$allowed_cars}}')
    </script>
@endsection
