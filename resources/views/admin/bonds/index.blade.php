@php
    $carbon = new \Carbon\Carbon;
@endphp

@extends('layouts.admin')

@section('styles')
    <link href="{{asset("css/daterangepicker.css")}}" rel="stylesheet" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
    <script src="https://unpkg.com/html2canvas@1.0.0-rc.5/dist/html2canvas.js"></script>

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
    <div class="container-fluid">
        @can('can view bonds')
            <div class="row">
                @if(is_admin())
                    <!--@can('can add bond')-->
                        <div class="col-md-12 col-xs-12">
                            <button data-toggle="modal" data-target="#ClientM" class="btn btn-primary" style="margin-bottom: 20px">إضافه إغلاق <i class="fa fa-plus-circle"></i></button>
                        </div>
                    <!--@endcan-->
                @endif

                <div class="col-md-12 col-xs-12">
                    @can('can filter bonds')
                        <form method="get" style="margin:auto;background:#fff;padding:20px;margin-bottom:10px">

                            <div class="row baseline-row">

                                @if(is_admin())
                                    <div class="col-md-3 col-xs-6">
                                        <div class="form-group">
                                            <label for="sector_id">القطاع</label>

                                            <select id="sector_id" name="sector" class="form-control">
                                                <option value=""></option>
                                                @foreach($sectors as $sector)
                                                    <option value="{{$sector->id}}" {{request()->sector == $sector->id ? 'selected' : ''}}>{{$sector->sector_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-md-3 col-xs-6">
                                    <div class="form-group">
                                        <label for="from">بحث من</label>
                                        <input type="text" value="{{request()->from}}" class="form-control from" name="from" />
                                    </div>
                                </div>

                                <div class="col-md-3 col-xs-6">
                                    <div class="form-group">
                                        <label for="to">الي</label>
                                        <input type="text" value="{{request()->to}}" class="form-control to" name="to">
                                    </div>
                                </div>
                            </div>

                            <div class="row baseline-row">

                                <div class="col-md-3 col-xs-6">
                                    <button type="submit" class="btn btn-primary width-100">بحث</button>
                                </div>

                                @if(!empty(request()->all()))
                                    <div class="col-md-3 col-xs-6">
                                        <a href="{{admin_url('contract')}}" class="btn btn-danger width-100">إلغاء البحث</a>
                                    </div>
                                @endif
                            </div>
                        </form>

                    @endcan

                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">السندات</h3>
                        </div>

                        <div class="box-body">

                            @include('admin.layouts.messages')

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped mt-0">
                                    <thead>
                                        <tr>
                                            <th>رقم السند</th>

                                            @if(is_admin())
                                                <th>القطاع</th>
                                            @endif

                                            <th>من - إلى</th>
                                            <th>تاريخ الإغلاق</th>
                                            <th>مبلغ الإغلاق</th>
                                            <th>ملاحظة</th>
                                            <th>الحاله</th>
                                            <th>إجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bonds as $bond)
                                            <tr>
                                                <td>{{$bond->id}}</td>
                                                @if(is_admin())
                                                <td>{{$bond->sector->sector_name ?? ''}}</td>
                                                @endif
                                                <td>
                                                    {{$bond->from}}<br />
                                                    {{$bond->to}}
                                                </td>
                                                <td>{{$bond->created_at->format('Y/m/d')}}</td>
                                                <td>{{$bond->amount.' ر.س'}}</td>
                                                <td>{{$bond->note}}</td>
                                                <td>
                                                    @if(is_admin())
                                                        @if($bond->is_cancelled)
                                                            <span class="text-danger">تم الرفض</span>
                                                        @elseif($bond->is_accepted)
                                                            <span class="text-success">مقبول</span>
                                                        @else
                                                            <span class="text-warning">بانتظار الموافقة</span>
                                                        @endif
                                                    @endif

                                                    @if(is_sector_admin())
                                                        @if($bond->is_accepted)
                                                            <span class="text-success">مقبول</span>
                                                        @else
                                                            <span class="text-warning">بانتظار الموافقة</span>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td style="width: 300px">
                                                    @can('can view history bonds')
                                                    <a class="btn btn-primary" data-toggle="tooltip" title="السجل" href="{{admin_url('bonds/'.$bond->id)}}"><i class="fa fa-history"></i></a>
                                                    @endcan

                                                    <a class="btn btn-primary" data-toggle="modal" title="عرض السند" data-target="#show-bond-{{$bond->id}}"><i style="color: #fff !important;" class="fa fa-eye"></i></a>

                                                    <div id="show-bond-{{$bond->id}}" class="modal fade show-bond text-center" style="text-align: right" role="dialog">
                                                        <div class="modal-dialog modal-md">

                                                            <!-- Modal content-->
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close pull-left" data-dismiss="modal">&times;</button>
                                                                    <h4 class="modal-title">عرض سند الصرف</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="box box-body box-primary">

                                                                        <button id="btn-download-{{$bond->id}}" class="btn btn-primary" style="width: 100%"><i class="fa fa-print"></i> طباعة </button>

                                                                        <div id="bond-info-table-{{$bond->id}}">
                                                                            <div class="table-responsive">
                                                                                <table class="table-hover info-table table">
                                                                                    <thead class="thead-dark">
                                                                                    <tr>
                                                                                        <th scope="col">السند</th>
                                                                                        <th scope="col">{{$bond->id}}</th>
                                                                                    </tr>
                                                                                    </thead>

                                                                                    <tbody>
                                                                                    <tr>
                                                                                        <th scope="row">تم صرف مبلغ:</th>
                                                                                        <td>{{$bond->amount}} ر.س  </td>
                                                                                    </tr>

                                                                                    <tr>
                                                                                        <th scope="row">عدد العقود:</th>
                                                                                        <td>{{$bond->count}} <a href="{{admin_url('bonds/'.$bond->id.'/contracts')}}">عرض التفاصيل</a></td>
                                                                                    </tr>

                                                                                    <tr>
                                                                                        <th scope="row">لأمر السيد:</th>
                                                                                        <td>{{$bond->sector->user->name ?? ''}}</td>
                                                                                    </tr>

                                                                                    <tr>
                                                                                        <th scope="row">قطاع:</th>
                                                                                        <td>{{$bond->sector->sector_name ?? ''}}</td>
                                                                                    </tr>

                                                                                    <tr>
                                                                                        <th scope="row">طريقة الدفع:</th>
                                                                                        <td>تحويل بنكي</td>
                                                                                    </tr>

                                                                                    <tr>
                                                                                        <th scope="row">وذلك لأجل:</th>
                                                                                        <td>إغلاق شهري</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th scope="row">من:</th>
                                                                                        <td>{{$bond->from}}</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th scope="row">إلى:</th>
                                                                                        <td>{{$bond->to}}</td>
                                                                                    </tr>

                                                                                    @if(!is_null($bond->note))
                                                                                    <tr>
                                                                                        <th scope="row">ملاحظات:</th>
                                                                                        <td>{{$bond->note}}</td>
                                                                                    </tr>
                                                                                    @endif
                                                                                    </tbody>
                                                                                </table>
                                                                                <table style="width: 100%">
                                                                                    <thead>
                                                                                    <tr>
                                                                                        <th style="width: 50%">إسم المستلم</th>
                                                                                        <th style="width: 50%">المدير</th>
                                                                                    </tr>
                                                                                    </thead>

                                                                                    <tbody>
                                                                                    <tr>
                                                                                        <td>{{$bond->sector->user->name ?? ''}}</td>
                                                                                        <td>{{$bond->user->name ?? ''}}</td>
                                                                                    </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>

                                                                        <script>
                                                                            $('#btn-download-{{$bond->id}}').on('click', function (e){
                                                                                e.preventDefault();

                                                                                var divElements = document.getElementById('bond-info-table-{{$bond->id}}').innerHTML;
                                                                                // Get the HTML of whole page
                                                                                var oldPage = document.body.innerHTML;
                                                                                //Reset the page's HTML with div's HTML only
                                                                                document.body.innerHTML =
                                                                                    "<html><head><title></title></head><body>" +
                                                                                    divElements + "</body></html>";
                                                                                //Print Page
                                                                                window.print();
                                                                                //Restore orignal HTML
                                                                                document.body.innerHTML = oldPage;
                                                                                window.location.reload()
                                                                            })
                                                                        </script>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @if(is_admin() && !$bond->is_accepted)
                                                        <a href="" data-toggle="modal" class="edit-button btn btn-primary" data-bond="{{$bond->id}}" data-form-link="{{admin_url('bonds/'.$bond->id)}}" data-target="#edit-bond"><i style="color: #fff !important;" class="fa fa-edit"></i></a>

                                                        <form action="{{admin_url('bonds/'.$bond->id)}}" method="POST" style="margin:0;display: inline-block" onsubmit="return confirm('هل انت متأكد من حذف السند؟')">
                                                            @csrf
                                                            @method('DELETE')

                                                            <button class="btn btn-danger" data-toggle="tooltip" title="حذف السند"><i class="fa fa-trash" style="color: #fff !important;"></i></button>
                                                        </form>
                                                    @endif

                                                    @if(is_sector_admin() && !$bond->is_accepted && !$bond->is_cancelled)
                                                        <form method="POST" action="{{admin_url('bonds/'.$bond->id.'/change/accept')}}" style="margin: 0;display: inline-block">
                                                            @csrf
                                                            @method('PUT')
                                                            <button class="btn btn-success" data-toggle="tooltip" title="قبول" onclick="return confirm('هل متأكد من قبول السند؟')"><i class="fa fa-check"></i></button>
                                                        </form>

                                                        <button class="btn btn-danger refuse" data-bond="{{$bond->id}}" data-toggle="modal" data-target="#refuse-bond" data-form-link="{{admin_url('bonds/'.$bond->id.'/change/cancel')}}" title="رفض"><i class="fa fa-times"></i></button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="text-center d-flex justify-content-center mt-2">
                                {{$bonds->appends(request()->all())->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
    </div>

    @if(is_admin())
        <div id="ClientM" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close pull-left" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">سند جديد</h4>
                    </div>
                    <div class="modal-body">
                        <div class="box box-body box-primary">
                            <div class="">
                                <h3 class="box-title">بيانات الاغلاق</h3>
                            </div>
                            <hr>

                            <form action="{{route('admin.bonds.store')}}" method="post" id="create-bond">
                                @csrf
                                <div class="row" style="margin: 0;padding: 0">
                                    <div class="form-group col-12 ">
                                        <label for="create-sector">القطاع</label>

                                        <select id="create-sector" type="text" class="form-control" name="sector" required>
                                            <option value=""></option>

                                            @foreach($sectors as $sector)
                                                <option value="{{$sector->id}}">{{$sector->sector_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-12">
                                        <label for="create-from">الاغلاق من:</label>
                                        <input id="create-from" type="text" value="{{old('from')}}" class="form-control from" name="from" required />
                                    </div>

                                    <div class="form-group col-12">
                                        <label for="create-to">الي:</label>
                                        <input id="create-to" type="text" value="{{old('to')}}" class="form-control to" name="to" required />
                                    </div>

                                    <div class="form-group col-12 ">
                                        <label for="create-amount">المبلغ:</label>
                                        <input type="text" id="create-amount" name="amount" value="{{old('amount')}}" class="form-control" required />
                                    </div>

                                    <div class="form-group col-12 ">
                                        <label for="create-contracts-count">عدد العقود:</label>
                                        <input type="number" name="count_contracts" id="create-contracts-count" class="form-control" readonly />
                                    </div>

                                    <div class="form-group col-12 ">
                                        <label for="create-note">ملاحظه:</label>
                                        <textarea type="text" id="create-note" name="note" class="form-control">{{old('note')}}</textarea>
                                    </div>
                                </div>

                                <input type="submit" class="btn btn-success" value="اضافة" />
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


    @if(is_admin())
        <div id="edit-bond" class="modal fade text-center" style="text-align: right" role="dialog">
            <div class="modal-dialog modal-lg">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close pull-left" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">تعديل الإغلاق</h4>
                    </div>
                    <div class="modal-body">
                        <div class="box box-body box-primary">
                            <div class="">
                                <h3 class="box-title">بيانات الاغلاق</h3>
                            </div>
                            <hr>

                            <form action="" method="post" id="edit-bond-form">
                                @csrf
                                @method('PUT')

                                <div class="row" style="margin: 0;padding: 0">
                                    <div class="form-group col-12 ">
                                        <label for="edit-sector">القطاع</label>

                                        <select id="edit-sector" type="text" class="form-control" name="sector" required>
                                            <option value=""></option>

                                            @foreach($sectors as $sector)
                                                <option value="{{$sector->id}}">{{$sector->sector_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-12">
                                        <label for="edit-from">الاغلاق من:</label>
                                        <input id="edit-from" type="text" value="{{old('from')}}" class="form-control from" name="from" required />
                                    </div>

                                    <div class="form-group col-12">
                                        <label for="edit-to">الي:</label>
                                        <input id="edit-to" type="text" value="{{old('to')}}" class="form-control to" name="to" required />
                                    </div>

                                    <div class="form-group col-12 ">
                                        <label for="edit-amount">المبلغ:</label>
                                        <input type="text" id="edit-amount" name="amount" value="{{old('amount')}}" class="form-control" required />
                                    </div>

                                    <div class="form-group col-12 ">
                                        <label for="edit-contracts-count">عدد العقود:</label>
                                        <input type="number" name="count_contracts" id="edit-contracts-count" class="form-control" readonly />
                                    </div>

                                    <div class="form-group col-12 ">
                                        <label for="edit-note">ملاحظه:</label>
                                        <textarea type="text" id="edit-note" name="note" class="form-control">{{old('note')}}</textarea>
                                    </div>

                                    <div class="form-group col-12" id="edit-reason-field" style="display: none">
                                        <label for="edit-reason">سبب الرفض:</label>
                                        <input type="text" id="edit-reason" name="reason" class="form-control" readonly disabled />
                                    </div>
                                </div>

                                <input type="submit" class="btn btn-success" value="تعديل" />
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(is_sector_admin())
    <div id="refuse-bond" class="modal fade text-center" style="text-align: right" role="dialog">
        <div class="modal-dialog modal-md">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close pull-left" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">سبب الرفض</h4>
                </div>
                <div class="modal-body">
                    <div class="box box-body box-primary">
                        <div class="">
                            <h3 class="box-title">سبب رفض الإغلاق</h3>
                        </div>
                        <hr>

                        <form action="" method="post" id="refuse-bond-form">
                            @csrf
                            @method('PUT')

                            <div class="row" style="margin: 0;padding: 0">
                                <div class="form-group col-12 ">
                                    <label for="reason">سبب الرفض</label>
                                    <input type="text" name="reason" id="reason" class="form-control" />
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-danger"><i class="fa fa-times"></i> رفض</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection

@section('scripts')
    <script src="{{asset('js/moment.min.js')}}"></script>
    <script src="{{asset('js/daterangepicker.js')}}"></script>

    <script>
        const opt = {
            singleDatePicker: true,
            timePicker: true,
            timePickerSeconds: true,
            timePicker24Hour: true,
            locale: {
                format: 'YYYY-MM-DD HH:mm:ss'
            }
        }

        $('.from').daterangepicker(opt);

        $('.to').daterangepicker({
            singleDatePicker: true,
            timePicker: true,
            timePickerSeconds: true,
            timePicker24Hour: true,
            locale: {
                format: 'YYYY-MM-DD HH:mm:ss'
            },
            startDate: moment(new Date()).add(1,'days')
        });

        // $("#example").tableHTMLExport({
        //
        //     // csv, txt, json, pdf
        //     type:'json',
        //
        //     // file name
        //     filename:'sample.json',
        //
        //     ignoreColumns: '.ignore',
        //     ignoreRows: '.ignore'
        //
        // });

        $('#create-from, #create-to, #create-sector').on('change', function (){
            getFunds('#create-bond', '#create-amount','#create-contracts-count');
        })

        $('#edit-from, #edit-to, #edit-sector').on('change', function (){
            getFunds('#edit-bond-form', '#edit-amount', '#edit-contracts-count');
        })

        function getFunds(form, element, countEle){
            let formData = $(form).serializeArray().filter(function (r){
                return r.name.indexOf('_method') === -1
            });

            $.post('/api/getFunds', formData).done(function (data){
                $(element).val(data.total)
                $(countEle).val(data.count)
            })
        }

        $('.edit-button').on('click', function (e){
            e.preventDefault();

            $('#edit-bond').find('form').attr('action', $(this).data('form-link'));

            $.post('/api/getBond', {bond: $(this).data('bond')}).done(function (data){
                $('#edit-sector').find('[value="'+data.sector_id+'"]').prop('selected', true)
                $('#edit-from').val(data.from)
                $('#edit-to').val(data.to)
                $('#edit-amount').val(data.amount)
                $('#edit-note').val(data.note)
                $('#edit-contracts-count').val(data.count)

                if (data.reason != ''){
                    $('#edit-reason-field').show().find('input').val(data.reason)
                }else{
                    $('#edit-reason-field').hide().find('input').val()
                }
            })
        })

        $('.refuse').on('click', function (e){
            e.preventDefault();

            $('#refuse-bond').find('form').attr('action', $(this).data('form-link'))
        })
    </script>
@endsection
