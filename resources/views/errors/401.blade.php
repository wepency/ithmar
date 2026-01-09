@extends('layouts.front-page')

@section('content')
    <div class="container main-container">
        <div class="card">
            <div class="card-body">
                <div class="alert alert-danger alert-dismissible new2 p-4" role="alert">
                    <i class="alert-icon icon_close_alt2" aria-hidden="true"></i>

                    <div class="alert-body">ليس لديك الصلاحيات الكافية للدخول لتلك الصفحة.</div>
                </div>
            </div>
        </div>
    </div>
@endsection
