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

        td.tab-primary{
            background-color: #34495e;
            color: #fff;
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
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">سند إغلاق لـ {{$bond->sector->sector_name}} من {{$bond->from}} إلى {{$bond->to}}</h3>
            </div>

            <div class="box-body">
                <button id="btn-download-pdf" class="btn btn-primary"><i class="fa fa-print"></i> طباعة </button>
                <a href="{{admin_url('bonds/'.$bond->id.'/export')}}" class="btn btn-danger">تصدير اكسل</a>

                <div id="bond-info-table">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mt-0">
                            <thead>
                            <tr>
                                <th>اسم المستثمر</th>
                                <th>رقم الوحدة</th>
                                <th>الشاطئ</th>
                                <th>عدد العقود</th>
                                <th>إجمالي قيمة العقود</th>
                                <th>نسبة إثمار من العقود</th>
                                <th>نسبة القطاع من العقود</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($contracts as $contract)
                                <tr>
                                    <td>{{$contract['investor']}}</td>
                                    <td>{{$contract['unit']}}</td>
                                    <td>{{$contract['beach']}}</td>
                                    <td>{{$contract['count']}}</td>
                                    <td>{{currency_format($contract['total_contracts'])}}</td>
                                    <td>{{currency_format($contract['ithmar_total'])}}</td>
                                    <td>{{currency_format($contract['sector_total'])}}</td>
                                    {{--                                <td>{{$contract['total_contracts']}}</td>--}}
                                    {{--                                <td>{{$contract['ithmar_total']}}</td>--}}
                                    {{--                                <td>{{$contract['sector_total']}}</td>--}}
                                </tr>
                            @endforeach

                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="tab-primary">{{$contracts_count}} عقد</td>
                                <td class="tab-primary">{{currency_format($contracts_total)}}</td>
                                <td class="tab-primary">{{currency_format($ithmar_total)}}</td>
                                <td class="tab-primary">{{currency_format($sector_total)}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('#btn-download-pdf').on('click', function (e){
            e.preventDefault();

            var divElements = document.getElementById('bond-info-table').innerHTML;
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
@endsection
