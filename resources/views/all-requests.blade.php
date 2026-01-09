@extends('layouts.front-page')

@section('styles')
    <style>

    </style>
@endsection

@section('content')
    <div class="container main-container">

        <div class="row mb-4">
            <div class="col-xl-3 col-sm-6 stats-container">
                <a href="#" class="card card-stats">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="media success d-flex">
                                <div class="align-self-center">
                                    <i class="icon_building"></i>
                                </div>
                                <div class="media-body text-left">
                                    <h3>{{$units->total()}}</h3>
                                    <span>عدد الوحدات</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-3 col-sm-6 stats-container">
                <a href="#" class="card card-stats">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="media primary d-flex">
                                <div class="align-self-center">
                                    <i class="icon_documents_alt"></i>
                                </div>
                                <div class="media-body text-left">
                                    <h3>{{$valid}}</h3>
                                    <span>الوحدات الفعالة</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-3 col-sm-6 col-xs-6 col-12 stats-container">
                <a href="#" class="card card-stats">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="media warning d-flex">
                                <div class="align-self-center">
                                    <i class="icon_error-triangle_alt"></i>
                                </div>
                                <div class="media-body text-left">
                                    <h3>{{$expired}}</h3>
                                    <span class="long-text">وحدات منتهية الصلاحية</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-3 col-sm-6 stats-container">
                <a href="#" class="card card-stats">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="media danger d-flex">
                                <div class="align-self-center">
                                    <i class="icon_close_alt2"></i>
                                </div>
                                <div class="media-body text-left">
                                    <h3>{{$blocked}}</h3>
                                    <span>وحدات موقوفة</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
{{--                <div class="card">--}}
{{--                    <div class="card-body text-center">--}}
{{--                        <form action="" method="POST">--}}
{{--                            @csrf--}}

{{--                            <div class="row">--}}
{{--                                <div class="col-lg-3 col-md-3 ">--}}
{{--                                    <div class="form-group text-left">--}}
{{--                                        <label>الوحدة</label>--}}
{{--                                        <input class="form-control grey" type="text" />--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="col-lg-3 col-md-3">--}}
{{--                                    <div class="form-group text-left">--}}
{{--                                        <label>الشاطئ</label>--}}

{{--                                        <select name="type" id="type" class="nice-select w-100">--}}
{{--                                            <option value="investor" {{old('type') == 'investor' ? 'selected' : ''}}>مستثمر</option>--}}
{{--                                            <option value="owner" {{old('type') == 'investor' ? 'selected' : ''}}>مالك</option>--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="col-lg-3 col-md-3">--}}
{{--                                    <div class="form-group text-left">--}}
{{--                                        <label>الشاطئ</label>--}}
{{--                                        <input class="form-control grey" type="text" />--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="col-lg-3 col-md-3">--}}

{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="text-right">--}}
{{--                                <button class="g-recaptcha gb gb-bordered hover-slide gb9"><i class="icon_search"></i> <span class="text">البحث</span> </button>--}}
{{--                            </div>--}}
{{--                        </form>--}}
{{--                    </div>--}}
{{--                </div>--}}

                <div class="table-responsive mt-2">
                    <table class="table table-striped table-hover ithmar-table">
                        <thead>
                        <tr>
                            <th scope="col">
                                <a href="#">#</a>
                            </th>

                            <th scope="col">
                                <a href="#">الوحدة</a>
                            </th>

                            <th scope="col">
                                <a href="#">الحالة</a>
                            </th>

                            <th scope="col">
                                <a href="#">ساري حتى</a>
                            </th>

                            <th scope="col">
                                <a>اجراءات</a>
                            </th>
                        </tr>

                        <tr class="spacer"><td colspan="100"></td></tr>
                        </thead>

                        <tbody>

                        @foreach($units as $unit)
                            <tr class="{{table_color_request($unit)}}">
                                <td>{{$unit->id}}</td>
                                <td>
                                    <p class="m-0"><b>{{$unit->unit_number}}</b></p>
                                    <p class="m-0"><span class="text-success">{{@$unit->beach->beach ?? ''}}</span></p>
                                    <p class="m-0"><span class="text-danger">{{@$unit->sector->sector_name ?? ''}}</span></p>
                                </td>

                                <td>
                                    {!! unit_status($unit) !!}

                                    @if($unit->status == 2)
                                        <p class="text-danger" style="max-width: 190px;margin-top: 10px;">{{$unit->note}}</p>
                                    @endif
                                </td>

                                <td><span class="text-success"> {!! is_null($unit->valid_to) ? '--' : $unit->valid_to !!} </span></td>

                                <td>

                                    @if(!unit_is_not_valid($unit))
                                        <a href="{{asset('uploads/'.$unit->attachment_1)}}">المرفقات</a>
                                    @else
                                        <a href="{{investor_url('unit/update/'.base64_encode($unit->id))}}">تحديث المرفقات</a>
                                    @endif

                                </td>
                            </tr>

                            <tr class="spacer"><td colspan="100"></td></tr>
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
