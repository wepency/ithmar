@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="box box-body box-primary">
            <div class="">
                <h3 class="box-title">{{$page_title}}</h3>
            </div>

            @include('admin.layouts.messages')

            <form action="{{admin_url('user/update')}}" method="post">
                @csrf
                @method('PUT')

                <div class="row" style="margin: 0;padding: 0">
                    <div class="form-group col-md-4 col-xs-9 ">
                        <label for="email">البريد الإلكتروني</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" value="{{old('email') ?? $user->email}}" id="email" name="email" />

                        @error('email')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row" style="margin: 0;padding: 0">
                    <div class="form-group col-md-4 col-xs-9 ">
                        <label for="password">كلمة المرور</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" />

                        @error('password')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group col-md-1">
                    <button type="submit" class="btn btn-success"><i class="fa fa-plus"></i> حفظ البيانات </button>
                </div>
            </form>
        </div>
    </div>
@endsection
