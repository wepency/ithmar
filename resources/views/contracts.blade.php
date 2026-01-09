@extends('layouts.front-page')

@section('styles')
    <style>
        .badge{
            font-size: 12px;
            font-weight: 400;
            width: 125px;
            white-space: initial;
            line-height: 1.5;
        }
        .table thead th{
            font-size: 13px;
            font-weight: 300;
        }
        .badge-warning{
            font-size: 10px;
        }
        .reserved td{
            background-color: rgba(241, 196, 15,.95) !important
        }
    </style>
@endsection

@section('content')
    <div class="container main-container">
        <div class="row mb-2">
            <div class="col-xl-4 col-md-4 col-sm-6 col-xs-6 col-6 stats-container">
                <a href="{{url('contracts')}}" class="card card-stats {{request()->type == '' ? 'active' : ''}}">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="media primary d-flex">
                                <div class="align-self-center">
                                    <i class="icon_documents_alt"></i>
                                </div>
                                <div class="media-body text-left">
                                    <h3>{{$allCount}}</h3>
                                    <span>جميع العقود</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-4 col-md-4 col-sm-6 col-xs-6 col-6 stats-container">
                <a href="{{url('contracts?type=active')}}" class="card card-stats {{request()->type == 'active' ? 'active' : ''}}">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="media success d-flex">
                                <div class="align-self-center">
                                    <i class="icon_check_alt"></i>
                                </div>
                                <div class="media-body text-left">
                                    <h3>{{$activeCount}}</h3>
                                    <span>العقود الفعالة</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-4 col-md-4 col-sm-12 col-xs-6 col-12 stats-container">
                <a href="{{url('contracts?type=signed')}}" class="card card-stats {{request()->type == 'signed' ? 'active' : ''}}">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="media warning d-flex">
                                <div class="align-self-center">
                                    <i class="icon_pencil-edit"></i>
                                </div>
                                <div class="media-body text-left">
                                    <h3>{{$signedCount}}</h3>
                                    <span>العقود المصدقة</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

{{--        <div class="card filter-wrapper">--}}
{{--            <div class="card-header">--}}
{{--                <h6 class="page-title">فلترة النتائج</h6>--}}

{{--                <a href="#" class="open-filter" data-filter="closed">عرض الفلاتر</a>--}}
{{--            </div>--}}

{{--            <div class="card-body text-center">--}}

{{--                @include('admin.layouts.messages')--}}

{{--                <ul class="nav nav-pills justify-content-center mb-4">--}}
{{--                    <li class="nav-item">--}}
{{--                        <a class="nav-link {{request()->type == '' ? 'active' : ''}}" href="{{investor_url('contracts')}}">جميع العقود</a>--}}
{{--                    </li>--}}

{{--                    <li class="nav-item">--}}
{{--                        <a class="nav-link {{request()->type == 'active' ? 'active' : ''}}" href="{{investor_url('contracts?type=active')}}">العقود الفعالة</a>--}}
{{--                    </li>--}}

{{--                    <li class="nav-item">--}}
{{--                        <a class="nav-link {{request()->type == 'signed' ? 'active' : ''}}" href="{{investor_url('contracts?type=signed')}}">العقود المصدقة</a>--}}
{{--                    </li>--}}
{{--                </ul>--}}

{{--                    <form action="{{investor_url('contracts?type='.request()->type)}}">--}}
{{--                        <div class="row">--}}
{{--                            <div class="col-md-4 col-xs-12">--}}
{{--                                <div class="form-group text-left">--}}
{{--                                    <label for="search" class="form-label">كلمة البحث</label>--}}
{{--                                    <input id="search" value="{{old('search')}}" name="search" type="search" class="form-control grey" />--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-md-4 col-xs-12">--}}
{{--                                <div class="form-group text-left">--}}
{{--                                    <label for="sorting" class="form-label">القطاع</label>--}}

{{--                                    <select style="width: 100%" id="sorting" class="nice-select w-100" name="sorting">--}}
{{--                                        <option value="">الكل</option>--}}
{{--                                        <option value="pay_later">الآجلة</option>--}}
{{--                                        <option value="exempt">المعفية</option>--}}
{{--                                        <option value="paid">المدفوعه</option>--}}
{{--                                        <option value="unpaid">غير المدفوعه</option>--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-md-4 col-xs-12">--}}
{{--                                <div class="form-group text-left">--}}
{{--                                    <label for="sorting" class="form-label">الشاطئ</label>--}}

{{--                                    <select style="width: 100%" id="sorting" class="nice-select w-100" name="sorting">--}}
{{--                                        <option value="">الكل</option>--}}
{{--                                        <option value="pay_later">الآجلة</option>--}}
{{--                                        <option value="exempt">المعفية</option>--}}
{{--                                        <option value="paid">المدفوعه</option>--}}
{{--                                        <option value="unpaid">غير المدفوعه</option>--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-md-4 col-xs-12">--}}
{{--                                <div class="form-group text-left">--}}
{{--                                    <label for="sorting" class="form-label">الفيلا</label>--}}

{{--                                    <select style="width: 100%" id="sorting" class="nice-select w-100" name="sorting">--}}
{{--                                        <option value="">الكل</option>--}}
{{--                                        <option value="pay_later">الآجلة</option>--}}
{{--                                        <option value="exempt">المعفية</option>--}}
{{--                                        <option value="paid">المدفوعه</option>--}}
{{--                                        <option value="unpaid">غير المدفوعه</option>--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="col-md-4 col-xs-12">--}}
{{--                                <div class="form-group text-left">--}}
{{--                                    <label for="sorting" class="form-label">عرض العقود</label>--}}

{{--                                    <select style="width: 100%" id="sorting" class="nice-select w-100" name="sorting">--}}
{{--                                        <option value="">الكل</option>--}}
{{--                                        <option value="pay_later">الآجلة</option>--}}
{{--                                        <option value="exempt">المعفية</option>--}}
{{--                                        <option value="paid">المدفوعه</option>--}}
{{--                                        <option value="unpaid">غير المدفوعه</option>--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-md-4 col-xs-12">--}}
{{--                                <div class="form-group text-left">--}}
{{--                                    <label for="sorting" class="form-label">عرض العقود</label>--}}

{{--                                    <select style="width: 100%" id="sorting" class="nice-select w-100" name="sorting">--}}
{{--                                        <option value="">الكل</option>--}}
{{--                                        <option value="pay_later">الآجلة</option>--}}
{{--                                        <option value="exempt">المعفية</option>--}}
{{--                                        <option value="paid">المدفوعه</option>--}}
{{--                                        <option value="unpaid">غير المدفوعه</option>--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                        </div>--}}

{{--                        <div class="text-right">--}}
{{--                            <a href="#" class="open-filter" data-filter="closed">عرض الفلاتر</a> &nbsp;--}}
{{--                            <button class="g-recaptcha gb gb-bordered hover-slide gb9"><i class="icon_search"></i> <span class="text">البحث</span> </button>--}}
{{--                        </div>--}}
{{--                    </form>--}}
{{--            </div>--}}
{{--        </div>--}}


{{--        {!! $contracts_builder->render() !!}--}}

        @include('admin.layouts.messages')

        @include('contracts.table', compact('contracts'))

        <div class="text-center d-flex justify-content-center mt-2">
            {!! $contracts->links() !!}
        </div>
    </div>
@endsection
