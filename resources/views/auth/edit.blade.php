@extends('layouts.front-page')

@section('content')
    <div class="container main-container">
        <div class="card">
            <div class="preloader-wrapper">
                <img src="{{asset('images/preloader.svg')}}" alt="loading ..." />
            </div>

{{--            <div class="card-header">--}}
{{--                <h3 class="page-title">{{$page_title}}</h3>--}}

{{--                <nav aria-label="breadcrumb">--}}
{{--                    <ol class="breadcrumb">--}}
{{--                        <li class="breadcrumb-item"><a href="{{url('/')}}">الرئيسية</a></li>--}}
{{--                        <li class="breadcrumb-item active" aria-current="page">{{$page_title}}</li>--}}
{{--                    </ol>--}}
{{--                </nav>--}}
{{--            </div>--}}

            <div class="card-body">
                @include('admin.layouts.messages')

                <h3 class="form-title"><span class="form-ribbon">تعديل بيانات الحساب</span></h3>

                <form id="contract-form" style="background-color: #fff;padding: 10px;margin-bottom: 25px" method="post" action="{{investor_url('user/update')}}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="email">البريد الإلكتروني</label>

                                <input class="form-control grey @error('email') is-invalid @enderror" value="{{old('email') ?? $user->email}}" id="email" type="email" name="email" />

                                @error('email')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="password">كلمة المرور</label>

                                <input class="form-control grey @error('password') is-invalid @enderror" id="password" type="password" name="password" />

                                @error('password')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="two_factor_auth">تفعيل تسجيل الدخول بخطوتين</label>

                                <div>
                                    <label class="switch mt-3">
                                        <input type="checkbox" id="two_factor_auth" name="two_factor_auth" {{auth()->user()->two_factor ? 'checked' : ''}}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>

                                @error('password')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="gb gb-bordered hover-slide next gb9"><i class="arrow_right"></i> <span class="text">حفظ بيانات الحساب</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function (){
            $('.preloader-wrapper').remove()
        })
    </script>
@endsection
