@extends('layouts.front-page')

@section('content')
    <div class="container home-container">
        <div class="card">
            <div class="card-header">
                <h3 class="page-title">{{$page_title}}</h3>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/')}}">الرئيسية</a></li>
                        <li class="breadcrumb-item"><a href="{{investor_url('all-units')}}">الوحدات</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{$page_title}}</li>
                    </ol>
                </nav>
            </div>

            <div class="card-body">
                <form style="background-color: #fff;padding: 10px;margin-bottom: 25px" method="post" enctype="multipart/form-data" action="{{investor_url('unit/update/'.$unit->id)}}">
                    @csrf

                    @method('PUT')

                    @if($errors->any())
                        <ul class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                <li>{{$error}}</li>
                            @endforeach
                        </ul>
                    @endif

                    <div class="form-group">
                        <label for="attachment_1">مستند طلب تأهيل وحدة</label>
                        <input type="file" class="form-control" id="attachment_1" name="attachment_1" />
                    </div>

                    <div class="form-group">
                        <button type="submit" class="gb gb-bordered hover-slide gb9"><i class="arrow_right"></i> <span class="text"> تحديث المرفقات </span> <span class="loader"></span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
