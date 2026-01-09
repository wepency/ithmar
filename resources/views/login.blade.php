@extends('layouts.front-page')

@section('content')
    <div class="container d-flex justify-content-center">
        <div class="row">
            <form class="form" method="post" action="{{url('login')}}">
                @csrf

                @if(session()->has('error'))
                    <div class="alert alert-danger">
                        {{session()->get('error')}}
                    </div>
                @endif

                <div class="form-group">
                    <label for="phonenumber">رقم الهاتف</label>
                    <input id="phonenumber" value="{{old('phonenumber')}}" name="phonenumber" type="text" class="form-control grey" />
                </div>

                <div class="form-group">
                    <label for="password">كلمة المرور</label>
                    <input id="password" type="password" name="password" class="form-control grey" />
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-login">تسجيل دخول</button>
                </div>

                <div class="text-center">
                    <a href="{{url('password/reset')}}">استعادة كلمة المرور</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')

@endsection
