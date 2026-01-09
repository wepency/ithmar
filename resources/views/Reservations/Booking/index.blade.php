<?php
$vertime = cache()->get('vertime');
?>

@extends('layouts.front-page')

@section('styles')
    <link rel="stylesheet" href="{{asset('css/selectize.bootstrap4.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" />

    <style>
        .show-bond table.info-table *{
            text-align: right !important;
        }

        .badge-td{
            min-width: 170px;
        }

        .gb{
            display: block;
            margin-top: 8px;
        }

        .badge:not(.badge-status){
            width: 100%;
            border-radius: 50px;
        }

        .buttons{
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .line-through{
            text-decoration: line-through;
            color: red;
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

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>

    <script>
        $("a.ver-image").fancybox();
    </script>
@endsection

@section('content')
    <div class="container main-container">

        @include('Reservations.Booking.statics')

        @if(session()->has('success'))
            <div class="alert alert-success alert-dismissible new2 p-4" role="alert">
                <i class="alert-icon icon_check_alt2" aria-hidden="true"></i>

                <div class="alert-body">
                    {{session()->get('success')}}
                </div>

                <button type="button" class="close-button" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">

                <div class="table-responsive mt-2">
                    <table class="table table-striped table-hover ithmar-table has-badges">
                        <thead>
                        <tr>
                            <th scope="col">
                                <a href="#">#</a>
                            </th>

                            <th scope="col">
                                <a href="#">اسم العميل</a>
                            </th>

                            <th scope="col">
                                <a href="#">الوحدة</a>
                            </th>

                            <th scope="col">
                                <a>الدخول / الخروج</a>
                            </th>

                            <th scope="col">
                                <a>اجمالي المبلغ</a>
                            </th>

                            <th scope="col">
                                <a>العربون</a>
                            </th>

                            <th scope="col">
                                <a>الصور</a>
                            </th>

                            <th>اجراءات</th>
                        </tr>

                        <tr class="spacer"><td colspan="100"></td></tr>

                        </thead>

                        <tbody>

                        @foreach($rows as $row)
                            <tr>
                                <td class="badge-td">
                                    @php
                                        $status = check_refund_status($row);
                                    @endphp

                                    @if($status)
                                        @switch($status)
                                            @case('refund-request')
                                                <span class='badge badge-status badge-warning badge-floating'>طلب استرداد</span>
                                                @break

                                            @case('waiting-approval')
                                                <span class='badge badge-status badge-floating badge-warning'>بانتظار مراجعة طلب الاسترداد</span>
                                                @break

                                            @case('converted-reservation')
                                                <span class='badge badge-status badge-floating badge-danger'>حجز محول</span>
                                            @break

                                            @case('new-reservation')
                                                {!! get_badge($row) !!}
                                            @break

                                        @endswitch
                                    @else
                                        {!! get_badge($row) !!}
                                    @endif

                                    {{number_with_zeros($row->id)}}

                                    @if($row->discount)
                                        <p class="text-danger mb-0" style="max-width: 200px;font-size: 90%">قام المستخدم باستخدام كوبون {{$row->coupon}} وحصل على خصم {{currency($row->sub_total - $row->total)}}</p>
                                        <p class="text-dark m-0"><small style="font-size: 80r%">ستخصم من الفاتورة الشهرية</small></p>
                                    @endif
                                </td>

                                <td>{{$row->user->name ?? ''}}</td>

                                <td>
                                    <h6>{{$row->unit->unit->unit_number ?? ''}}</h6>
                                    <h5 class="text-danger m-0"><small>{{$row->unit->unit->beach->beach ?? ''}}</small></h5>
                                    <h5 class="text-success mb-0"><small>{{$row->unit->unit->sector->sector_name ?? ''}}</small></h5>
                                </td>

                                <td>
                                    <h6 class="text-success">{{\Carbon\Carbon::parse($row->from)->format('Y-m-d')}}</h6>
                                    <h6 class="text-danger">{{\Carbon\Carbon::parse($row->to)->format('Y-m-d')}}</h6>
                                </td>

                                <td>
                                    <span class="{{$row->discount ? 'line-through' : ''}}">{{currency($row->sub_total)}}</span>

                                    @if($row->discount)
                                        <p><span><strong class="text-success">{{currency($row->total)}}</strong></span></p>
                                    @endif
                                </td>

                                <td>
                                    <span class="{{$row->discount ? 'line-through' : ''}}">{{currency(($row->sub_total * $row->down_payment_percent) / 100)}}</span>

                                    @if($row->discount)
                                        <p><strong class="text-warning">{{currency($row->down_payment)}}</strong></p>
                                    @endif
                                </td>

                                <td>
                                    @if(($row->status >= 1) && !$status)
                                        <p class="mt-0"><a class="ver-image" href="{{asset(env('RESERVATION_APP_URL').'/uploads/verifications/'.$row->verification_image)}}">عرض حوالة العربون</a></p>
                                    @endif

{{--                                    @if(($row->status >= 3) && !$status)--}}
{{--                                        <p class="mt-0"><a class="ver-image" href="{{asset(env('RESERVATION_APP_URL').'/uploads/verifications/'.$row->final_verification_image)}}">عرض حوالة المبلغ بالكامل</a></p>--}}
{{--                                    @endif--}}
                                </td>

                                <td>
                                    <?php
                                        $contract = $row?->contract;
                                    ?>

                                    @if($row->status > 1 && $row->status < 5 && get_status($row) != 'expired' && !$status)

                                        <div class="text-center">
                                            <a href="https://wa.me/+966{{$row->phonenumber}}?text={{client_message()}}" target="_blank" class="btn btn-success d-block m-0"><i class="fa fa-whatsapp"></i> مراسلة العميل </a>

                                            @if(!$contract)
                                                @if(get_status($row) != 'cancelled')
                                                    <a href="{{route('reservation.contract.generate', deep_encode($row->id, $row->created_at))}}" class="btn btn-danger d-block mb-0 mt-1"><i class="fa fa-plus"></i> انشاء عقد </a>
                                                @endif
                                            @else
                                                @if($contract->is_accepted != 1)
                                                <span class="badge badge-warning d-block mt-2">بانتظار الموافقة على العقد</span>
                                                @elseif($contract->status != 1 && $contract->payment_type != 'pay_later' && $contract->payment_type != 'exempt' && !$contract->is_cancelled)
                                                    <form method="post" action="{{investor_url('contract/'.$row->contract->code.'/payment')}}" style="display: inline-block;padding: 0;margin: 0">
                                                        @csrf
                                                        <button class="pay-unpaid btn btn-success d-block mb-0 mt-1 icon-button tooltip-container" title="الدفع و تفعيل العقد" data-required="{{currency_format($contract->total + $contract->services_total)}}" data-contract-code="{{$contract->code}}" type="submit">الدفع</button>
                                                    </form>
                                                @else
                                                    <a class="btn btn-primary d-block mb-0 mt-1 icon-button tooltip-container" title="عرض العقد" href="{{investor_url('contract/'.$contract->code)}}"><i class="fa-regular fa-eye"></i></a>
                                                @endif
                                            @endif
                                        </div>
                                    @endif

                                    <div class="buttons">
                                        @if($status)
                                            @switch($status)
                                                @case('refund-request')
                                                    <p class="badge badge-danger mb-0">الوحدة موقوفة</p>
                                                    <a href="{{route('upload.investor.refund.form', deep_encode($row->id, $row->created_at))}}" class="gb gb-bordered hover-slide next gb9"><i class="fa fa-cloud-upload"></i> <span class="text"> رفع صورة التحويل </span> </a>
                                                @break

                                                @case('waiting-approval')
                                                    <span class="badge badge-warning">جاري مراجعة الطلب</span>
                                                @break

                                                @case('converted-reservation')
                                                    <p class="badge badge-danger mb-0">محول بغرامة</p>
                                                    <p class="text-danger text-center m-0"><strong>{{currency($row->fine_amount)}}</strong></p>
                                                @break
                                            @endswitch
                                        @else
                                            @if($row->status === 1)
{{--                                                <form action="{{route('online-reservation.acceptDownPayment', $row->id)}}" onclick="return confirm('هل انت متأكد من تأكيد العربون؟')" method="post" style="display:inline-block;margin:0">--}}
{{--                                                    @csrf--}}
{{--                                                    @method('PUT')--}}

{{--                                                    <button class="button button-blue tooltip-container" data-toggle="tooltip" title="تأكيد العربون"><i class="fa fa-calendar-check-o"></i></button>--}}
{{--                                                </form>--}}

                                                <form action="{{route('online-reservation.acceptReservation', $row->id)}}" method="post" onclick="return confirm('هل انت متاكد من تأكيد الحجز؟')" style="display:inline-block;margin:0">
                                                    @csrf
                                                    @method('PUT')

                                                    <button class="button button-blue tooltip-container" data-toggle="tooltip" title="تأكيد الحجز"><i class="fa fa-check"></i></button>
                                                </form>
                                            @elseif($row->status === 3)
{{--                                                <form action="{{route('online-reservation.acceptReservation', $row->id)}}" method="post" onclick="return confirm('هل انت متاكد بأستلام كامل مبلغ التأجير؟')" style="display:inline-block;margin:0">--}}
{{--                                                    @csrf--}}
{{--                                                    @method('PUT')--}}

{{--                                                    <button class="button button-blue tooltip-container" data-toggle="tooltip" title="تأكيد الحجز نهائياََ"><i class="fa fa-check"></i></button>--}}
{{--                                                </form>--}}
                                            @endif
                                        @endif
                                    </div>

{{--                                    {{$row->status}}--}}
                                    @if(is_null($row->status) && $row->created_at->diffInMinutes(\Carbon\Carbon::now()) < $vertime)
                                        <strong class="text-success"><span class="counter" id="counter-{{$row->id}}"></span></strong>

                                        <script>
                                            // let countDownDate = ;

                                            let Date{{$row->id}} = new Date('{{$row->created_at->addMinutes($vertime)->format('M d, Y H:i:s')}}').getTime();

                                            setInterval(function() {
                                                // Get today's date and time
                                                var now = new Date().getTime();

                                                // Find the distance between now and the count down date
                                                var distance = Date{{$row->id}} - now;

                                                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                                                // Display the result in the element with id="demo"
                                                $('#counter-{{$row->id}}').html(hours + "س " + minutes + "د " + seconds + "ث ");

                                            }, 1000);
                                        </script>
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
            {{$rows->withQueryString()}}
        </div>

    </div>
@endsection
