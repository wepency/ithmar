@extends('layouts.front-page')

@section('content')
    <div class="container d-flex justify-content-center">
        <div class="row">
            <form class="form" method="post" action="{{route('reset.password.post')}}" style="background-color: #fff;padding: 10px;margin-bottom: 25px">
                @csrf

                @include('admin.layouts.messages')

                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="form-group">
                    <label for="password">كلمة المرور</label>
                    <input id="password" value="{{old('password')}}" name="password" type="password" class="form-control" />
                </div>

                <div class="form-group">
                    <label for="password-confirmation">تأكيد كلمة المرور</label>
                    <input id="password-confirmation" value="{{old('password_confirmation')}}" name="password_confirmation" type="password" class="form-control" />
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-login">تغيير كلمة المرور</button>
                </div>
            </form>
        </div>
    </div>
@endsection
