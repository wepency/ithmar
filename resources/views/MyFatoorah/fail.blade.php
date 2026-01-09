@extends('layouts.front-page')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3 class="page-title">خطأ في الدفع</h3>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/')}}">الرئيسية</a></li>
                        <li class="breadcrumb-item active" aria-current="page">خطأ في الدفع</li>
                    </ol>
                </nav>

            </div>

            <div class="card-body text-center">
                <div class="right-icon">
                    <img style="max-width: 50px" src="{{asset('images/icons/close.svg')}}" alt="">
                </div>

                <h1>هناك مشكله في تلقي المدفوعات ، برجاء التأكد من البطاقه.</h1>

                <p><a href="{{investor_url('contracts?type=signed')}}">العودة الى العقود المصدقة</a></p>
            </div>
        </div>
    </div>
@endsection
