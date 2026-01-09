@extends('layouts.front-page')

@section('styles')
    <style>
        .table-danger, .table-danger>td, .table-danger>th {
            background-color: #f5c6cb !important;
        }

        .line-through{
            text-decoration: line-through;
            color: red;
        }
    </style>
@endsection

@section('content')
    <div class="container main-container">
        @if(count($invoices))
        <div class="table-responsive mt-2">
            <table class="table table-striped table-hover ithmar-table">
                <thead>
                <tr>
                    <th scope="col">
                        <a href="#">رقم الحجز</a>
                    </th>

                    <th scope="col">
                        <a href="#">قيمة الحجز</a>
                    </th>

                    <th scope="col">
                        <a href="#">نسبة اثمار</a>
                    </th>

                    <th scope="col">
                        <a href="#">قيمة الخصم</a>
                    </th>

                    <th scope="col">
                        <a href="#">الإجمالي</a>
                    </th>

                    <th scope="col">
                        <a href="#">ضريبة العموله</a>
                    </th>

                    <th scope="col">
                        <a href="#">المطلوب سدادة</a>
                    </th>

                    <th>
                        حالة الحجز
                    </th>

                </tr>

                <tr class="spacer"><td colspan="100"></td></tr>

                </thead>

                <tbody>

                @foreach($invoices as $invoice)
                    <?php
//                        $tax = ($invoice->booking_profit*15) / 100;
//                        $total_to_pay = $invoice->booking_profit + $tax;

                        $booking_profit = (($invoice->subtotal*$invoice->profit_percentage) / 100) - ($invoice->subtotal - $invoice->total);
                        $tax = ($booking_profit*15) / 100;
                        $total_to_pay = $booking_profit + $tax;
                    ?>

                    <tr class="{{$invoice->is_cancelled ? 'table-danger' : ''}}">
                        <td>{{str_pad($invoice->booking_id,6 ,'0', STR_PAD_LEFT)}}</td>

                        <td>
                            <span class="{{($invoice->subtotal - $invoice->total) > 0 ? 'line-through' : ''}}">{{currency($invoice->subtotal)}}</span>

                            @if(($invoice->subtotal - $invoice->total) > 0)
                                <p><span><strong class="text-success">{{currency($invoice->total)}}</strong></span></p>
                            @endif

{{--                            {{currency($invoice->total)}}--}}
                        </td>

                        <td>
{{--                            @if($invoice->subtotal - $invoice->total > 0)--}}
{{--                                <span class="{{($invoice->subtotal - $invoice->total) > 0 ? 'line-through' : ''}}">{{currency($invoice->booking_profit + ($invoice->subtotal - $invoice->total))}}</span>--}}
{{--                            @endif--}}

                            <p><span>{{currency(number_format(($invoice->subtotal*$invoice->profit_percentage) / 100))}}</span></p>
{{--                            <p><span>{{currency(number_format($invoice->booking_profit))}}</span></p>--}}
                        </td>

                        <td class="text-danger">
                            {{currency(number_format($invoice->subtotal - $invoice->total))}}
                        </td>

                        <td>
                            {{currency(number_format($booking_profit))}}
                        </td>

                        <td>{{currency(number_format($tax, 2))}}</td>
                        <td>{{currency(number_format($total_to_pay, 2))}}</td>

                        <td>
                            @if($invoice->is_cancelled)
                                <span class="badge badge-danger">ملغي</span>

                                @if($invoice->type == 'with-commission')
                                    <p class="text-success m-0">مع احتساب العمولة</p>
                                @else
                                    <p class="text-success m-0">بدون احتساب عمولة</p>
                                @endif
                            @else
                                <span class="badge badge-success">مؤكد</span>
                            @endif
                        </td>
                    </tr>
                    <tr class="spacer"><td colspan="100"></td></tr>
                @endforeach

                </tbody>
            </table>
        </div>

        <div class="text-center d-flex justify-content-center mt-2">
            {{$invoices->appends(request()->all())->links()}}
        </div>
        @endif

        @if($invoice_onj->violation_rows)
            <div class="table-responsive mt-2">
                <table class="table table-striped table-hover ithmar-table">
                    <thead>
                    <tr>
                        <th scope="col">
                            <a href="#">سبب المخالفة</a>
                        </th>

                        <th scope="col">
                            <a href="#">قيمة المخالفة</a>
                        </th>
                    </tr>

                    <tr class="spacer"><td colspan="100"></td></tr>

                    </thead>

                    <tbody>

                    @foreach($invoice_onj->violation_rows as $violation)

                        <tr>
                            <td>{{$violation->reason}}</td>
                            <td>{{currency($violation->price)}}</td>
                        </tr>

                        <tr class="spacer"><td colspan="100"></td></tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
