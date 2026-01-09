@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">إعدادات الموقع</h3>
                    </div>

                    <div class="box-body">
                        @include('admin.layouts.messages')

                        <form action="{{admin_url('settings')}}" method="post">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="price_before_vat">السعر قبل الضريبة</label>
                                <input class="form-control" name="price_before_vat" type="text" id="price_before_vat" value="{{old('price_before_vat') ?? $settings->price_before_vat}}" required />
                            </div>


                            <div class="form-group">
                                <label for="price_after_vat">السعر بعد الضريبة</label>
                                <input class="form-control" name="price_after_vat" type="text" id="price_after_vat" value="{{old('price_after_vat') ?? $settings->price_after_vat}}" required />
                            </div>

                            <div class="form-group">
                                <label for="name">اسم الشركة</label>
                                <input class="form-control" name="name" type="text" id="name" value="{{old('name') ?? $settings->name}}" required />
                            </div>

                            <div class="form-group">
                                <label for="phonenumber">رقم الجوال</label>
                                <input class="form-control" name="phonenumber" type="text" id="phonenumber" value="{{old('phonenumber') ?? $settings->phonenumber}}" required />
                            </div>

                            <div class="form-group">
                                <label for="website">رابط الموقع</label>
                                <input class="form-control" name="website" type="url" id="website" value="{{old('website') ?? $settings->website}}" required />
                            </div>

                            <div class="form-group">
                                <label for="email">البريد الإلكتروني</label>
                                <input class="form-control" name="email" type="email" id="email" value="{{old('email') ?? $settings->email}}" required />
                            </div>

                            <div class="form-group">
                                <label for="vat">VAT</label>
                                <input class="form-control" name="vat" type="text" id="vat" value="{{old('vat') ?? $settings->vat}}" required />
                            </div>

                            <div class="form-group">
                                <label for="confirmation">نص رسالة تأكيد الهاتف</label>
                                <input class="form-control" name="confirmation" type="text" id="confirmation" value="{{old('confirmation') ?? $settings->confirmation}}" required />
                            </div>

                            <div class="form-group">
                                <label for="whatsapp">رقم الواتساب</label>
                                <input class="form-control" name="whatsapp" type="text" id="whatsapp" value="{{old('whatsapp') ?? $settings->whatsapp}}" required />
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-success">حفظ</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
