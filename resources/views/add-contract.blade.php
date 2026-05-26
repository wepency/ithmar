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
    <style>
        .companion-card {
            position: relative;
            background: #ffffff;
            border: 1px solid #eef1f5;
            border-radius: 18px;
            padding: 28px 28px 18px;
            margin-bottom: 22px;
            box-shadow: 0 4px 18px rgba(23, 43, 77, 0.06);
            transition: box-shadow .2s ease, transform .2s ease;
        }
        .companion-card:hover { box-shadow: 0 8px 28px rgba(23, 43, 77, 0.09); }
        .companion-card__header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
            padding-bottom: 14px;
            border-bottom: 1px dashed #e6ecf3;
        }
        .companion-card__title {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 16px;
            font-weight: 700;
            color: #1f2d3d;
            margin: 0;
        }
        .companion-card__badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ffd36a, #f6a623);
            color: #fff;
            font-weight: 700;
            box-shadow: 0 3px 8px rgba(246, 166, 35, 0.35);
        }
        .companion-remove {
            background: transparent;
            border: 1px solid #f3d2d2;
            color: #c0392b;
            border-radius: 10px;
            padding: 6px 14px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background .15s ease, color .15s ease;
        }
        .companion-remove:hover { background: #c0392b; color: #fff; border-color: #c0392b; }
        .companion-barcode {
            border: 2px dashed #d5dce6;
            border-radius: 14px;
            padding: 18px;
            background: #fbfcfe;
            text-align: center;
            transition: border-color .15s ease, background .15s ease;
        }
        .companion-barcode:hover { border-color: #f6a623; background: #fff8ea; }
        .companion-barcode .upload-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 68px;
            height: 68px;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 3px 10px rgba(23, 43, 77, 0.08);
            cursor: pointer;
            margin-bottom: 8px;
            transition: transform .15s ease;
        }
        .companion-barcode .upload-button:hover { transform: translateY(-2px); }
        .companion-barcode .upload-button img { width: 34px; height: auto; }
        .companion-barcode .upload-hint { color: #7b8794; font-size: 13px; margin: 0; }
        .companion-barcode .preview-img {
            display: none;
            max-width: 180px;
            max-height: 120px;
            margin-top: 10px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(23, 43, 77, 0.1);
        }
        .companion-barcode .preview-img.has-image { display: inline-block; }
        .companion-barcode input[type="file"] { display: none; }
        .btn-add-companion {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 22px;
            border-radius: 14px;
            border: 2px dashed #f6a623;
            background: #fff8ea;
            color: #b3781a;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            transition: background .15s ease, transform .1s ease;
        }
        .btn-add-companion:hover { background: #ffe8bf; transform: translateY(-1px); }
        .btn-add-companion .plus-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px; height: 24px;
            border-radius: 50%;
            background: #f6a623;
            color: #fff;
            font-size: 16px;
            line-height: 1;
        }
        .companions-hint { color: #7b8794; font-size: 12px; margin: 8px 0 0; }
    </style>
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
                <li><span>2.</span> تأكيد رقم الجوال</li>
                <li><span>3.</span> الدفع و التفعيل</li>
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

                    @if(!is_blocked() && count($sectors) > 0)
                        <form id="contract-form" style="background-color: #fff;padding: 0;margin-bottom: 0" method="post" action="{{url('/contracts/save')}}" enctype="multipart/form-data">
                            @csrf

                            <div id="main-form" class="form-box">
                                <div class="step active" data-step="1">
                                    <h3 class="form-title"><span class="form-ribbon">1. اختيار الوحدة</span></h3>

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

                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group">
                                                <label for="unit" class="form-label">رقم الفيلا</label>
                                                <select class="nice-select mt-2 w-100 @error('unit_id') has-error @enderror" id="unit" name="unit_id"></select>

                                                <div class="text-danger @error('unit_id') active @enderror">
                                                    @error('unit_id')
                                                    {{ $message }}
                                                    @enderror
                                                </div>
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
                                                    <input type="text" value="{{old('from') ?? $today}}" min="{{$today}}" class="form-control grey @error('from') has-error @enderror" id="from" name="from" required />
                                                    <i></i>

                                                    <div class="text-danger @error('from') active @enderror">
                                                        @error('from')
                                                        {{ $message }}
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group">
                                                <label for="to">تاريخ المغادرة</label>

                                                <div class="form-control-container">
                                                    <input type="text" value="{{old('to') ?? $tomorrow}}" min="{{$tomorrow}}" class="form-control grey @error('to') has-error @enderror" id="to" name="to" required />
                                                    <i></i>
                                                </div>

                                                <div class="text-danger @error('to') active @enderror">
                                                    @error('to')
                                                    {{ $message }}
                                                    @enderror
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

                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group">
                                                <label for="birth_date">تاريخ الميلاد</label>
                                                <div class="form-control-container">
                                                    <input type="date" class="form-control grey @error('birth_date') has-error @enderror" value="{{old('birth_date')}}" id="birth_date" name="birth_date" required />
                                                    <i></i>
                                                </div>

                                                <div class="text-danger @error('birth_date') active @enderror">
                                                    @error('birth_date')
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

                                    <div id="companions-wrapper"></div>

                                    <div class="row companions-actions">
                                        <div class="col-md-12 col-xs-12">
                                            <button type="button" id="add-companion" class="btn-add-companion" style="display:none;">
                                                <span class="plus-icon">+</span>
                                                <span>إضافة مرافق</span>
                                            </button>
                                            <p class="companions-hint" id="companions-hint" style="display:none;">يمكنك إضافة حتى {{ $companionsMax }} مرافقين لهذا القطاع.</p>
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
                                                    <input type="number" data-max-size="6" maxlength="6" class="form-control grey @error('rent_value') has-error @enderror" value="{{old('rent_value')}}" id="rent_value" name="rent_value" required />
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
                    @elseif(count($sectors) == 0)
                        <div class="alert alert-danger text-center">
                            <div><i class="icon_close_alt2" style="font-size:50px;color:#dc3545!important"></i></div>
                            <h3>ليس لديك وحدات مفعلة.</h3>
                        </div>
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
                $('#beach').niceSelect('update')
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
                $('#unit').niceSelect('update')
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

        // Companions (مرافق) — dynamic rows, limits driven by config/contracts.php
        const MAX_COMPANIONS = {{ (int) $companionsMax }};
        const COMPANIONS_SECTOR = {{ (int) $companionsMultiSectorId }};

        const COMPANION_ORDINALS = ['الأول', 'الثاني', 'الثالث', 'الرابع', 'الخامس', 'السادس', 'السابع', 'الثامن', 'التاسع', 'العاشر'];

        function companionHeading(index){
            return 'المرافق ' + (COMPANION_ORDINALS[index] || ('رقم ' + (index + 1)));
        }

        function renderCompanion(index, values){
            values = values || {};
            const title = (values.title && values.title !== '') ? values.title : 'wife';
            const name = values.name ? String(values.name).replace(/"/g, '&quot;') : '';
            const idNumber = values.id_number ? String(values.id_number).replace(/"/g, '&quot;') : '';
            const nationality = values.nationality ? String(values.nationality).replace(/"/g, '&quot;') : '';
            const prefix = 'companions['+index+']';
            const removable = index > 0;
            const heading = companionHeading(index);
            const badge = index + 1;
            const fileId = 'companion-barcode-file-'+index;
            const imgId = 'companion-barcode-preview-'+index;
            const checked = function(v){ return title === v ? ' checked' : ''; };

            return '' +
                '<div class="companion-card" data-companion-index="'+index+'">' +
                    '<div class="companion-card__header">' +
                        '<h4 class="companion-card__title"><span class="companion-card__badge">'+badge+'</span><span>'+heading+'</span></h4>' +
                        (removable ? '<button type="button" class="companion-remove">× إزالة</button>' : '') +
                    '</div>' +
                    '<div class="row">' +
                        '<div class="col-md-12 col-xs-12">' +
                            '<div class="form-group">' +
                                '<label class="form-label">صلة القرابة</label>' +
                                '<div class="radio-buttons large-wrapper">' +
                                    '<input id="title_wife_'+index+'" type="radio" name="'+prefix+'[title]" value="wife"'+checked('wife')+'><label for="title_wife_'+index+'">زوج/ة</label>' +
                                    '<input id="title_mother_'+index+'" type="radio" name="'+prefix+'[title]" value="mother"'+checked('mother')+'><label for="title_mother_'+index+'">والد/ة</label>' +
                                    '<input id="title_sister_'+index+'" type="radio" name="'+prefix+'[title]" value="sister"'+checked('sister')+'><label for="title_sister_'+index+'">أخ/أخت</label>' +
                                    '<input id="title_daughter_'+index+'" type="radio" name="'+prefix+'[title]" value="daughter"'+checked('daughter')+'><label for="title_daughter_'+index+'">ابن/ة</label>' +
                                    '<input id="title_others_'+index+'" type="radio" name="'+prefix+'[title]" value="others"'+checked('others')+'><label for="title_others_'+index+'">أخرى</label>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="row">' +
                        '<div class="col-md-6 col-xs-12">' +
                            '<div class="form-group">' +
                                '<label class="form-label">اسم المرافق</label>' +
                                '<div class="form-control-container"><input type="text" maxlength="191" class="form-control grey" placeholder="اسم المرافق" value="'+name+'" name="'+prefix+'[name]" required /><i></i></div>' +
                            '</div>' +
                        '</div>' +
                        '<div class="col-md-6 col-xs-12">' +
                            '<div class="form-group">' +
                                '<label class="form-label">رقم الهوية</label>' +
                                '<div class="form-control-container"><input type="text" maxlength="10" class="form-control grey" placeholder="10 أرقام" value="'+idNumber+'" name="'+prefix+'[id_number]" required /><i></i></div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="row">' +
                        '<div class="col-md-6 col-xs-12">' +
                            '<div class="form-group">' +
                                '<label class="form-label">جنسية المرافق</label>' +
                                '<div class="form-control-container"><input type="text" maxlength="191" class="form-control grey" placeholder="الجنسية" value="'+nationality+'" name="'+prefix+'[nationality]" required /><i></i></div>' +
                            '</div>' +
                        '</div>' +
                        '<div class="col-md-6 col-xs-12">' +
                            '<div class="form-group">' +
                                '<label class="form-label">باركود هوية المرافق (إختياري)</label>' +
                                '<div class="companion-barcode">' +
                                    '<label class="upload-button" for="'+fileId+'"><img src="{{asset('images/icons/computing-cloud.svg')}}" alt="upload" /></label>' +
                                    '<p class="upload-hint">اضغط لرفع صورة الباركود</p>' +
                                    '<img id="'+imgId+'" class="preview-img" alt="preview" />' +
                                    '<input type="file" id="'+fileId+'" class="companion-file-upload" data-preview="'+imgId+'" accept="image/*" name="'+prefix+'[barcode]" />' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>';
        }

        function reindexCompanions(){
            $('#companions-wrapper .companion-card').each(function(newIndex){
                $(this).attr('data-companion-index', newIndex);
                $(this).find('.companion-card__badge').text(newIndex + 1);
                $(this).find('.companion-card__title span:last-child').text(companionHeading(newIndex));
                $(this).find('[name^="companions["]').each(function(){
                    const name = $(this).attr('name');
                    $(this).attr('name', name.replace(/companions\[\d+\]/, 'companions['+newIndex+']'));
                });
            });
        }

        function refreshCompanionControls(){
            const sectorId = parseInt($('#sector').val(), 10);
            const allowsMultiple = sectorId === COMPANIONS_SECTOR;
            const count = $('#companions-wrapper .companion-card').length;

            if (allowsMultiple && count < MAX_COMPANIONS) {
                $('#add-companion').show();
                $('#companions-hint').show();
            } else {
                $('#add-companion').hide();
                $('#companions-hint').hide();
            }

            if (!allowsMultiple && count > 1) {
                $('#companions-wrapper .companion-card').slice(1).remove();
                reindexCompanions();
            }
        }

        (function initCompanions(){
            const oldCompanions = @json(old('companions', []));
            const wrapper = $('#companions-wrapper');

            if (Array.isArray(oldCompanions) && oldCompanions.length > 0) {
                oldCompanions.forEach(function(values, i){
                    wrapper.append(renderCompanion(i, values));
                });
            } else {
                wrapper.append(renderCompanion(0, {
                    title: @json(old('with_tenant_title', 'wife')),
                    name: @json(old('with_tenant_name', '')),
                    id_number: @json(old('with_tenant_name_code', '')),
                    nationality: @json(old('with_tenant_nationality', ''))
                }));
            }

            $('#companions-wrapper .companion-card').each(function(){
                const card = $(this);
                if (!card.find('input[type="radio"]:checked').length) {
                    card.find('input[type="radio"][value="wife"]').prop('checked', true);
                }
            });

            refreshCompanionControls();
        })();

        $('#sector').on('change', refreshCompanionControls);

        $('#add-companion').on('click', function(){
            const count = $('#companions-wrapper .companion-card').length;
            if (count >= MAX_COMPANIONS) return;
            $('#companions-wrapper').append(renderCompanion(count, {}));
            $('#companions-wrapper .companion-card').last().find('input[type="radio"][value="wife"]').prop('checked', true);
            refreshCompanionControls();
        });

        $('body').on('click', '.companion-remove', function(){
            $(this).closest('.companion-card').remove();
            reindexCompanions();
            refreshCompanionControls();
        });

        $('body').on('change', '.companion-file-upload', function(){
            const previewId = $(this).data('preview');
            const preview = $('#'+previewId);
            const file = this.files && this.files[0];
            if (!file) { preview.removeClass('has-image').attr('src', ''); return; }
            const reader = new FileReader();
            reader.onload = function(e){ preview.attr('src', e.target.result).addClass('has-image'); };
            reader.readAsDataURL(file);
        });
    </script>
@endsection
