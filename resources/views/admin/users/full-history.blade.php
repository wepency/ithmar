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
                            <li class="{{request()->type == 'view' ? 'active' : ''}}"><a href="{{admin_url('users/'.$user_id.'/history?type=view')}}">عرض المرفق</a></li>
                            <li class="{{request()->type == 'update' ? 'active' : ''}}"><a href="{{admin_url('users/'.$user_id.'/history?type=update')}}">تعديل عقد</a></li>
                            <li class="{{request()->type == 'accepted' ? 'active' : ''}}"><a href="{{admin_url('users/'.$user_id.'/history?type=accepted')}}">موافقه عقد</a></li>
                            <li class="{{request()->type == 'create' ? 'active' : ''}}"><a href="{{admin_url('users/'.$user_id.'/history?type=create')}}">انشاء عقد</a></li>
                            <li class="{{request()->type == 'reset' ? 'active' : ''}}"><a href="{{admin_url('users/'.$user_id.'/history?type=reset')}}">فتح وحدة</a></li>
                            <li class="{{request()->type == 'closed' ? 'active' : ''}}"><a href="{{admin_url('users/'.$user_id.'/history?type=closed')}}">اغلاق وحدة</a></li>
                        </ul>

                        <form action="{{admin_url('users/'.$user_id.'/history')}}" method="get">
                            <div class="row baseline-row">
                                <div class="col-md-3 col-xs-6">
                                    <div class="form-group">
                                        <label for="phonenumber">رقم الجوال</label>
                                        <input type="text" id="phonenumber" value="{{request()->phonenumber}}" class="form-control" name="phonenumber" />
                                    </div>
                                </div>

                                <div class="col-md-3 col-xs-6">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">بحث من</label>
                                        <input type="date" value="{{request()->from}}" class="form-control" name="from" />
                                    </div>
                                </div>

                                <div class="col-md-3 col-xs-6">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">الي</label>
                                        <input type="date" value="{{request()->to}}" class="form-control" name="to">
                                    </div>
                                </div>

                                <div class="col-md-3 col-xs-6">
                                    <button type="submit" class="btn btn-primary width-100">بحث</button>
                                </div>

                                @if(!empty(request()->all()))
                                    <div class="col-md-3 col-xs-6">
                                        <a href="{{admin_url('users/'.$user_id.'/history')}}" class="btn btn-danger width-100">إلغاء البحث</a>
                                    </div>
                                @endif
                            </div>
                        </form>

                        {!! $history !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
