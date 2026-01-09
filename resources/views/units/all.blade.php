@extends('layouts.front-page')

@section('styles')
    <style>
        .bg-white{
            text-align: center;
            padding: 40px;
            border-radius: 10px;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3 class="page-title">{{$page_title}}</h3>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/')}}">الرئيسية</a></li>
                        <li class="breadcrumb-item"><a href="{{url('units')}}">الوحدات</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{$page_title}}</li>
                    </ol>
                </nav>
            </div>

            <div class="card-body">
                @include('admin.layouts.messages')

                <div class="table-responsive text-center">
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th scope="col">الوحدة</th>
                            <th scope="col">القطاع</th>
                            <th scope="col">الشاطئ</th>
                            <th scope="col">ساري حتى</th>
                            <th scope="col">المرفقات</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($units as $unit)
                            <tr class="{{table_color_request($unit)}}">
                                <th scope="row">{{$unit->unit_number}}</th>
                                <td>{{$unit->sector->sector_name ?? 'غير معروف'}}</td>
                                <td>{{$unit->beach->beach ?? 'غير معروف'}}</td>
                                <td>{{$unit->valid_to}}</td>
                                <td><a href="{{asset('uploads/'.$unit->attachment_1)}}">المرفقات</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="text-center d-flex justify-content-center mt-2">
            {{$units->appends(request()->all())->links()}}
        </div>
    </div>
@endsection
