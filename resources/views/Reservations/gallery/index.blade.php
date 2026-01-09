@extends('layouts.front-page')

@section('styles')
    <link rel="stylesheet" href="{{asset('css/selectize.bootstrap4.css')}}">

    <style>
        .show-bond table.info-table *{
            text-align: right !important;
        }

        @media print {
            .table-responsive{
                min-height: initial !important;
                overflow: initial !important;
            }

            .table>tbody>tr>td, .table>tbody>tr>th,
            .table>tfoot>tr>td, .table>tfoot>tr>th,
            .table>thead>tr>td, .table>thead>tr>th{
                padding: 3px 5px !important;
            }
        }
    </style>

    <style media="print">
        @page
        {
            size:  auto;   /* auto is the initial value */
            margin: 0;  /* this affects the margin in the printer settings */
        }

        html
        {
            background-color: #FFFFFF;
            margin: 0;  /* this affects the margin on the html before sending to printer */
        }

        body
        {
            margin: 10mm 15mm 10mm 15mm;
        }

        .show-bond table.info-table *{
            text-align: right !important;
        }
    </style>
@endsection

@section('content')
    <div class="container main-container">

        @include('Reservations.Gallery.statics')

        @include('admin.layouts.messages')

        <div class="row">
            <div class="col-12">

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
                                <a>تعديل الصور</a>
                            </th>
                        </tr>

                        <tr class="spacer"><td colspan="100"></td></tr>

                        </thead>

                        <tbody>

                        @if($units->count())
                            @foreach($units as $unit)
                                <tr class="{{table_color_request($unit)}}">
                                    <td>{{$unit->id}}</td>
                                    <td>
                                        <p class="m-0"><b>{{$unit->unit->unit_number}}</b></p>
                                        <p class="m-0"><span class="text-success">{{@$unit->unit->beach->beach ?? ''}}</span></p>
                                        <p class="m-0"><span class="text-danger">{{@$unit->unit->sector->sector_name ?? ''}}</span></p>
                                    </td>

                                    <td>
                                        @if($unit->status === 1)
                                            <span class='badge-status badge-success'>فعال</span>
                                        @elseif(is_null($unit->status) && $unit->need_approval)
                                            <span class='badge-status badge-warning'>بإنتظار مراجعة اضافة الوحدة</span>
                                        @elseif($unit->status === 2 && !$unit->need_approval)
                                            <span class='badge-status badge-danger'>تم رفض الطلب</span>
                                            <p class="text-danger">{{$unit->note}}</p>
                                        @endif

                                        <div class="mt-2">
                                            @if($unit->status != '' && $unit->need_approval)
                                                <span class='badge-status badge-warning'>بإنتظار مراجعة التعديلات</span>
                                            @elseif($unit->edit_status)
                                                <span class='badge-status badge-warning'>تم رفض التعديل</span>
                                                <p class="text-danger">{{$unit->edit_note}}</p>
                                            @endif
                                        </div>

                                    </td>

                                    <td>
                                        @if(!is_null($unit->status) && !$unit->need_approval)
                                            <a class="btn btn-primary icon-button tooltip-container" title="تعديل الصور" href="{{route('gallery.edit', $unit->id)}}"><i class="fa-regular fa-edit"></i></a>
                                            <a class="btn btn-success icon-button tooltip-container" target="_blank" title="عرض الوحدة" href="{{env('RESERVATION_APP_URL').'/unit/'.base64_encode($unit->id)}}"><i class="fa-regular fa-eye"></i></a>
                                        @endif
                                    </td>

                                </tr>
                                <tr class="spacer"><td colspan="100"></td></tr>
                            @endforeach
                        @else


                            <tr>
                                <td></td>
                                <td>لا يوجد وحدات فعالة</td>
                                <td></td>
                                <td></td>
                            </tr>

                            <tr class="spacer"><td colspan="100"></td></tr>

                        @endif


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
