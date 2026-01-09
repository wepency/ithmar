<?php
$format = 'Y-m-d';

$carbon = new \Carbon\Carbon;

$today = $carbon->today()->format($format);
$tomorrow = $carbon->tomorrow()->format($format);

$from = $carbon->parse($contract->from)->format($format);
$from_plus = $carbon->parse($contract->from)->addDay()->format($format);
$to = $carbon->parse($contract->to)->format($format);
?>

@extends('layouts.front-page')

@section('styles')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
@endsection

@section('content')
    <div class="container">
        <form style="background-color: #fff;margin-bottom: 25px" method="post"
              action="{{investor_url('contract/'.$contract->code.'/edit')}}">
            @csrf

            <div class="card">
                <div class="card-header">
                    <h2>تعديل بيانات العقد</h2>
                </div>

                <div class="card-body">


                    @method('PUT')

                    @if($errors->any())
                        <ul class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                <li>{{$error}}</li>
                            @endforeach
                        </ul>
                    @endif

                    <h3 class="form-title"><span class="form-ribbon">1. بيانات العقد</span></h3>

                    <div class="form-group">
                        <label for="sector">رقم القطاع</label>

                        <select class="form-control" id="sector" name="sector_id" disabled>
                            <option>{{$contract->sector->sector_name}}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="beach">الشاطئ</label>

                        <select class="form-control" id="beach" name="beach_id" disabled>
                            <option>{{$contract->beach->beach}}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="unit">رقم الفيلا</label>

                        <select class="form-control" id="unit" name="unit_id" disabled>
                            <option>{{$contract->unit->unit_number}}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="tenant_name">اسم المستأجر</label>
                        <input type="text" class="form-control" id="tenant_name" value="{{$contract->tenant_name}}"
                               name="tenant_name" disabled/>
                    </div>

                    <div class="form-group">
                        <label for="with_tenant_name">بيانات المرافق</label>
                        <input type="text" class="form-control" id="with_tenant_name"
                               value="{{$contract->with_tenant_name}}" name="with_tenant_name" disabled/>
                    </div>

                    <div class="form-group">
                        <label for="attachment_1">باركود هوية المستأجر</label>
                        <input type="file" class="form-control" id="attachment_1" name="attachment_1" disabled/>
                    </div>

                    <div class="form-group">
                        <label for="attachment_2">باركود هوية المرافق</label>
                        <input type="file" class="form-control" id="attachment_2" name="attachment_2" disabled/>
                    </div>

                    <div class="form-group">
                        <label for="from">تاريخ الدخول</label>
                        <input type="date" value="{{$from}}" min="{{$today}}" class="form-control" id="from"
                               disabled/>
                    </div>

                    <div class="form-group">
                        <label for="to">تاريخ المغادرة</label>
                        <input type="date" value="{{$to}}" min="{{$from_plus}}" max="{{$to}}" class="form-control"
                               id="to" disabled/>
                    </div>

                    <div class="form-group">
                        <label for="rent_value">قيمة الإيجار</label>
                        <input type="text" class="form-control" id="rent_value" name="rent_value"
                               value="{{$contract->rent_value}}" disabled/>
                    </div>

                </div>

                <div class="card-footer">
                    <div class="form-group">

                        <button
                            type="submit" class="gb gb-bordered hover-slide gb9">
                            <span class="text">{{$contract->exists ? 'تعديل العقد' : 'إضافة العقد'}}</span>
                        </button>

                    </div>
                </div>
            </div>

        </form>


        <div class="card">

            <div class="card-body">

                <h3 class="form-title"><span class="form-ribbon">1. السيارات</span></h3>

                <div class="alert alert-warning">
                    <h4 class="m-0">عند تعديل وحفظ بيانات أي لوحة لا يمكن التعديل عليها مرة أخرى الا من قبل
                        الادارة.</h4>
                </div>

                @for($i=1;$i<=$contract->beach->allowed_cars;$i++)
                    <h3>سيارة {{$i}}</h3>

                    @php
                        $isCarSet = isset($contract->cars[$i-1]);
                        $car = $isCarSet ? $contract->cars[$i-1] : null;
                    @endphp

                    <div class="car-wrap" data-id="{{$i}}">
                        <div class="form-group">
                            <label for="car_type1">نوع السيارة</label>
                            <input type="text" class="form-control car-type" id="car_type{{$i}}" name="car_type1"
                                   value="{{$car?->car_type}}" {{$isCarSet ? 'disabled' : ''}}/>
                        </div>

                        <div class="form-group">
                            <label for="car_serial1">بيانات اللوحة</label>
                            <input type="text" class="form-control car-serial" id="car_serial{{$i}}" name="car_serial1"
                                   value="{{$car?->car_serial}}" {{$isCarSet ? 'disabled' : ''}}/>
                        </div>

                        <button
                            {{$isCarSet ? 'disabled' : ''}}
                            type="submit" class="gb gb-bordered btn btn-success save-car hover-slide gb9 mb-5">
                            <span class="text">حفظ</span>
                        </button>
                    </div>

                @endfor

            </div>
        </div>
    </div>


@endsection

@section('scripts')
    <script>
        $('.save-car').on('click', function(e){
            e.preventDefault();

            const form = $(this).parents('.car-wrap')
            const car = form.data('id')
            const $this = $(this)

            $this.attr('disabled', true)

            $.post('/contracts/{{$contract->id}}/update-cars', {
                car_type: form.find('.car-type').val(),
                car_serial: form.find('.car-serial').val(),
            }).done(function (data){
                form.find('.car-type').attr('disabled', true)
                form.find('.car-serial').attr('disabled', true)

                Swal.fire({
                    title: "تم تعديل بيانات السيارة بنجاح.",
                    icon: "success"
                });

            }).fail(function (jqXHR, textStatus, errorThrown){
                $this.attr('disabled', false)
                let errorTitle = 'هناك خطأ في البيانات برجاء المراجعة.'

                // Check if error code is 401 or 403
                if (jqXHR.status === 401) {
                    errorTitle = 'برجاء التأكد من ان لديك صلاحيات الوصول لهذا العقد.'
                } else if (jqXHR.status === 403) {
                    errorTitle = 'بيانات تلك السيارة تم تحديثها بالفعل.';
                }

                Swal.fire({
                    title: errorTitle,
                    icon: "error"
                });
            });
        });
    </script>
@endsection
