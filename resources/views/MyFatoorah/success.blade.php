@extends('layouts.front-page')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3 class="page-title">{{$page_title}}</h3>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/')}}">الرئيسية</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{$page_title}}</li>
                    </ol>
                </nav>

            </div>

            <div class="card-body text-center">
                <div class="right-icon">
                    <img src="{{asset('images/icons/check.png')}}" alt="">
                </div>

                <h1>تم الدفع بنجاح و العقد الأن فعال.</h1>

                <p><a href="{{url('contract/'.$contracts->code.'/'.$contracts->token)}}">صورة العقد</a></p>
            </div>
        </div>
    </div>

{{--    <div id="app">--}}
{{--        <router-view />--}}
{{--    </div>--}}
@endsection

{{--@section('scripts')--}}
{{--    <script>--}}
{{--        window.contract_id = {{$contracts->code}};--}}
{{--    </script>--}}

{{--    <script src="{{asset('js/app.js')}}"></script>--}}
{{--@endsection--}}
