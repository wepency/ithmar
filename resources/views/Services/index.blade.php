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

                @if(session()->has('error'))
                    <div class="alert alert-danger">{{session()->get('error')}}</div>
                @endif

                @if(session()->has('success'))
                    <div class="alert alert-success">{{session()->get('success')}}</div>
                @endif

                <form action="{{investor_url('services')}}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-9 col-xs-12">
                            <div class="form-group text-left">
                                <label for="services">الخدمة</label>

                                <select name="service_id" id="services" class="form-control">
                                    <option></option>
                                    @foreach($services as $service)
                                        <option value="{{$service->id}}">{{$service->service_name.' - '.$service->price.' ريال'}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 col-xs-12">
                            <button type="submit" class="btn btn-primary btn-normal"><i class="fa fa-plus"></i> اضف الخدمة</button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th scope="col">الخدمة</th>
                                <th scope="col">السعر</th>
                                <th scope="col">حذف</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($user_services as $service)
                                <tr>
                                    <td>{{$service->service_name}}</td>
                                    <td>{{currency($service->price)}}</td>

                                    <td>
                                        <form onsubmit="return confirm('هل انت متأكد؟')" style="margin: 0;display: inline-block" action="{{investor_url('services/'.$service->pivot->id)}}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-normal">حذف الخدمة</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
            </div>
        </div>
    </div>
@endsection
