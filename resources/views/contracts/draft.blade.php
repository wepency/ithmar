@extends('layouts.front-page')

@section('styles')
    <link rel="stylesheet" href="{{asset('css/contract.css')}}" />
@endsection

@section('content')
    <div class="container main-container">

        <div class="form-container">
            <ul id="section-tabs">
                <li class="current active"><span>1.</span> بيانات العقد </li>
                <li><span>2.</span> تأكيد رقم الجوال</li>
                <li><span>3.</span> الدفع و التفعيل</li>
            </ul>

            <div class="card">

                <div class="card-body">

                    <div class="progress-outer mt-4 mb-4">
                        <div class="progress-bar--ribbon active"></div>
                        <div class="progress-bar--ribbon active"></div>
                        <div class="progress-bar--ribbon active"></div>
                        <div class="progress-bar--ribbon active"></div>
                        <div class="progress-bar--ribbon active"></div>
                        <div class="progress-bar--ribbon active"></div>
                    </div>


                    <div class="alert alert-success alert-dismissible new2 p-4" role="alert">
                        <i class="alert-icon icon_close_alt2" aria-hidden="true"></i>

                        <div class="alert-body">هل تريد عرض مسودة العقد قبل الاستمرار؟</div>

                    </div>

                    <div class="form-group">
                        <a href="{{getDraftLink($contract)}}" class="gb gb-bordered hover-slide ml-2 hover-fill gb9"><i class="icon_document_alt"></i> <span class="text">عرض المسودة</span></a>
                        <a href="{{url('contract/'.contractMix($contract).'/verifyPhone')}}" class="gb gb-bordered hover-slide gb10"><i class="arrow_right"></i> <span class="text">استكمال العقد</span></a>
                    </div>
{{--                    <a href="" class="btn btn-login">مسودة العقد</a>--}}
{{--                    <a href="" class="btn btn-transparent">تأكيد رقم الجوال</a>--}}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

@endsection
