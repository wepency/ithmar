@extends('layouts.front-page')

@section('content')
    @foreach($units as $unit)
        <div class="modal fade" id="unit-modal-{{$unit->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">نسبة عمولة الوحدة</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                @if(is_null($unit->profit_at) || \Carbon\Carbon::parse($unit->profit_at)->diffInHours(\Carbon\Carbon::now()) > 24)
                    <form method="POST" action="{{route('profit.update', deep_encode($unit->id, $unit->created_at))}}">
                        @csrf
                        @method('PUT')

                        <div class="modal-body">
                            <div class="alert alert-warning alert-dismissible new2 p-4" role="alert">
                                <i class="alert-icon icon_error-triangle" aria-hidden="true"></i>

                                <div class="alert-body">
                                    <h3><b>ماهي نسبة العموله؟</b></h3>
                                    <p>زيادة نسبة العموله على مبيعات حجوزاتكم تعزز من عدد مرات ظهور وحدتكم في مقدمة الوحدات للزوار عبر موقع حجوزات الدرة للحجز .</p>
                                </div>

                                <button type="button" class="close-button" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="profit_percent-{{$unit->id}}">نسبة العمولة</label>
                                <input name="profit_percent" id="profit_percent-{{$unit->id}}" class="form-control" value="{{$unit->profit_percentage ?? 10}}" />
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">تعديل النسبة</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                        </div>
                    </form>

                @else
                    <div class="modal-body">
                        <div class="alert alert-danger new2 p-4" role="alert">
                            <i class="alert-icon icon_close_alt2" aria-hidden="true"></i>

                            <div class="alert-body">
                                <h3><b>لا يمكنك تعديل النسبة قبل مرور 24 ساعة.</b></h3>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach

    <div class="container main-container">

        @include('admin.layouts.messages')

        <div class="row">
            <div class="col-12">

                @if(count($units) > 0)
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

                                <th>عرض الإتاحة</th>
                            </tr>

                            <tr class="spacer"><td colspan="100"></td></tr>

                            </thead>

                            <tbody>

                            @foreach($units as $unit)
                                <tr class="{{table_color_request($unit)}}">
                                    <td>{{$unit->id}}</td>

                                    <td>
                                        <p class="m-0"><b>{{$unit->unit->unit_number}}</b></p>
                                        <p class="m-0"><span class="text-success">{{@$unit->unit->beach->beach ?? ''}}</span></p>
                                        <p class="m-0"><span class="text-danger">{{@$unit->unit->sector->sector_name ?? ''}}</span></p>
                                    </td>

                                    <td>
                                        @if($unit->need_approval)
                                            <span class='badge-status badge-warning'>بإنتظار مراجعة التعديلات</span>
                                        @elseif($unit->status)
                                            <span class='badge-status badge-success'>فعال</span>
                                        @else
                                            <span class='badge-status badge-warning'>بإنتظار مراجعة اضافة الوحدة</span>
                                        @endif
                                    </td>

                                    <td>
                                        <button type="button" class="btn btn-primary icon-button tooltip-container" title="تعديل نسبة الوحدة" data-toggle="modal" data-target="#unit-modal-{{$unit->id}}">
                                            <i class="fa fa-flash"></i>
                                        </button>

                                        <a href="{{route('availability.show', $unit->id)}}" class="btn btn-danger icon-button tooltip-container" title="" type="submit" data-original-title="عرض الاسعار و الإتاحة"><i class="fa-solid fa-eye"></i></a>
                                    </td>
                                </tr>

                                <tr class="spacer"><td colspan="100"></td></tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="card">
                        <div class="card-body">
                            <h4 class="text-center" style="color: #cdcdcd;">لا يوجد أي وحدات فعالة بعد</h4>

                            <div class="text-center">
                                <a href="{{route('gallery.create')}}" class="gb gb-bordered hover-slide gb9"><i class="icon_plus"></i> <span class="text"> اضف وحدة جديدة </span> <span class="loader"></span></a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="text-center d-flex justify-content-center mt-2">
            {{$units->appends(request()->all())->links()}}
        </div>


    </div>

@endsection
