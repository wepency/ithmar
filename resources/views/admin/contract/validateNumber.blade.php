@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">تفعيل رقم الجوال</h3>
                    </div>

                    <div class="box-body">

                        @include('admin.layouts.messages')

                        <div class="alert alert-warning text-center">تم إرسال كود التفعيل إلى رقم {{$contract->phonenumber}}</div>

                        <form method="POST" action="{{admin_url('contract/'.$contract->id.'/checkCode')}}">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="verify-code">قم بإدخال كود التفعيل</label>
                                <input type="text" class="form-control" name="code" />
                            </div>

                            <button type="submit" style="margin-top: 0" class="btn btn-success">تفعيل العقد</button>
                            <a href="#" style="height: 40px;padding: 6px 12px;line-height: 2" onclick="document.getElementById('resend').submit()" class="btn btn-warning">إعادة ارسال كود التفعيل</a>
                        </form>

                        <form method="POST" id="resend" action="{{admin_url('contract/'.$contract->id.'/resendCode')}}">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>
        </div>
    </div>
    </section>

@endsection

@section('scripts')
    <script>

    </script>
@endsection
