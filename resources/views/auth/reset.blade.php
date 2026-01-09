@extends('layouts.front-page')

@section('content')
    <div class="container d-flex justify-content-center">
        <div class="row">
            <form class="form" method="post" action="{{url('password/reset')}}" style="background-color: #fff;padding: 10px;margin-bottom: 25px">
                @csrf

                @include('admin.layouts.messages')

                <div class="form-group">
                    <label for="email">البريد الإلكتروني</label>
                    <input id="email" value="{{old('email')}}" name="email" type="email" class="form-control" />
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-login">استعادة كلمة المرور</button>
                </div>
            </form>
        </div>
    </div>
@endsection
