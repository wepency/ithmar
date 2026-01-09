@extends('layouts.front-page')

@section('content')

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3 class="page-title">رسالة</h3>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/')}}">الرئيسية</a></li>
                        <li class="breadcrumb-item"><a href="{{url('request')}}">طلب تأهيل وحدة</a></li>
                        <li class="breadcrumb-item active" aria-current="page">رسالة</li>
                    </ol>
                </nav>
            </div>

            <div class="card-body text-center">
                <div class="right-icon">
                    <img src="{{asset('images/icons/check.png')}}" alt="">
                </div>

                <h5 class="mt-5">تم الحصول على طلب التأهيل بنجاح ، برجاء انتظار تواصلنا معكم.</h5>

                <a href="{{investor_url('all-requests')}}">مراجعة طلبات التأهيل</a>
            </div>
        </div>
    </div>
@endsection
