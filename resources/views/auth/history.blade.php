@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">

            <div class="col-md-12 col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">{{$page_title}}</h3>
                    </div>

                    <div class="box-body">

                        @include('admin.layouts.messages')

                        {{--                        'create','update','accepted','delete','reject','blocked','unblocked'--}}

                        <ul class="nav nav-pills justify-content-center align-items-center" style="margin-bottom: 25px">
                            <li class="{{request()->type == '' ? 'active' : ''}}"><a href="{{admin_url('users/'.$user_id.'/history')}}">الكل</a></li>
                            <li class="{{request()->type == 'update' ? 'active' : ''}}"><a href="{{admin_url('users/'.$user_id.'/history?type=update')}}">تعديل عقد</a></li>
                            <li class="{{request()->type == 'accepted' ? 'active' : ''}}"><a href="{{admin_url('users/'.$user_id.'/history?type=accepted')}}">موافقه عقد</a></li>
                            <li class="{{request()->type == 'create' ? 'active' : ''}}"><a href="{{admin_url('users/'.$user_id.'/history?type=create')}}">انشاء عقد</a></li>
                        </ul>

                        {!! $history !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
