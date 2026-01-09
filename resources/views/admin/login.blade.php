@extends('layouts.front-page')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4 col-xs-12">
                <h2 class="text-center">
                    دخول المسؤولين
                </h2>
                <form class="form" method="post" action="{{route('admin.login')}}" style="background-color: #fff;padding: 10px;margin-bottom: 25px">
                    @csrf
                    <div class="form-group">
                        <label for="phonenumber">رقم الهاتف</label>
                        <input id="phonenumber" name="phonenumber" type="text" class="form-control" />
                    </div>

                    <div class="form-group">
                        <label for="password">كلمة المرور</label>
                        <input id="password" type="password" name="password" class="form-control" />
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-login">تسجيل دخول</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

@endsection
