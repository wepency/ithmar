@extends('layouts.front-page')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <form style="background-color: #fff;" id="request-form" method="post" action="{{investor_url('credit-request')}}" enctype="multipart/form-data">
                            @csrf

                            @include('admin.layouts.messages')

                            <h3 class="form-title"><span class="form-ribbon">1. بيانات التسييل</span></h3>
                            <h6>برجاء اكمال البيانات في الأسفل لاتمام الطلب والتأكد من انها صحيحة بالكامل.</h6>

                            <div class="form-group w-100 mt-3">
                                <label for="holder_name" class="form-label">اسم المستفيد*</label>
                                <input type="text" class="form-control grey" value="{{old('holder_name') ?? $bank_info->holder_name ?? ''}}" id="holder_name" name="holder_name" required />
                            </div>

                            <div class="form-group">
                                <label for="bank_name" class="form-label">اسم البنك*</label>
                                <input type="text" class="form-control grey" value="{{old('bank_name') ?? $bank_info->bank_name ?? '' }}" id="bank_name" name="bank_name" required />
                            </div>

                            <div class="form-group">
                                <label for="bank_account" class="form-label">رقم الحساب*</label>
                                <input type="text" class="form-control grey" value="{{old('bank_account') ?? $bank_info->bank_account ?? ''}}" id="bank_account" name="bank_account" required />
                            </div>

                            <div class="form-group">
                                <label for="iban" class="form-label">IBAN*</label>
                                {{--                        <input type="text" class="form-control grey" value="{{old('iban')}}" id="iban" name="iban" required />--}}

                                <div class="password-wrapper mt-2">
                                    <input style="direction: ltr" id="iban" type="text" class="input" name="iban" value="{{old('iban') ?? $bank_info->iban ?? ''}}" required />
                                    <div class="icon-wrapper">SA</div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button
                                    data-sitekey="{{env('reCaptch_site_key')}}"
                                    data-callback='onResetForm'
                                    data-action='submit'
                                    type="submit" class="gb gb-bordered hover-slide gb9 w-100">
                                    <span class="text">تقديم الطلب</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
