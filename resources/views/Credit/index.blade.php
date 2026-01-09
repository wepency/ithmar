@extends('layouts.front-page')

@section('styles')
    <style>
        #table-credit{
            text-align: right;
        }

        #table-credit th{
            font-weight: 100;
            border: 0
        }

        #table-credit td{
            vertical-align: middle;
        }

        .booking_downpayment {
            color: #9b59b6 !important
        }

        .booking_downpayment_total{
            color: #2ecc71 !important;
        }

        .booking_downpayment{
            color: #3498db !important;
        }

        .hint{
            position: absolute;
            bottom: 5px;
            left: 15px;
            font-size: 30px;
        }

        .ribbon {
            padding-bottom: 55px !important
        }
    </style>
@endsection

@section('content')
    <div class="modal durrah-modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{investor_url('add-credit')}}" method="POST">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">اضافة رصيد</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="icon_close"></i></span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group text-left">
                            <label for="credit">الرصيد</label>
                            <input name="credit" id="credit" value="500" type="number" class="form-control grey" min="10" required />
                        </div>
                    </div>

                    <div class="modal-footer">
{{--                        <button type="button" class="btn btn-primary">Save changes</button>--}}
                        <button type="submit" class="gb gb-bordered hover-slide next gb9"><i class="icon_plus"></i> <span class="text"> اضافة رصيد </span> <span class="loader"></span></button>
                        <button type="button" class="gb gb-bordered hover-slide ml-2 hover-fill prev gb10" data-dismiss="modal"><i class="icon_close"></i> <span class="text">الغاء</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal durrah-modal fade" id="withdraw-model" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{route('credit.do.withdraw')}}" method="POST">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">طلب تسييل مبلغ</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="icon_close"></i></span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group text-left">
                            <label for="credit"><b>هل انت متأكد من تقديم طلب سحب {{currency($withdrawable_credit)}}؟</b></label>
{{--                            <input name="credit" id="credit" value="{{$credit_cash}}" type="number" class="form-control grey" min="10" required disabled />--}}
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="gb gb-bordered hover-slide next gb9"><i class="icon_plus"></i> <span class="text"> تقديم الطلب </span> <span class="loader"></span></button>
                        <button type="button" class="gb gb-bordered hover-slide ml-2 hover-fill prev gb10" data-dismiss="modal"><i class="icon_close"></i> <span class="text">الغاء</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container home-container">

        <div class="row mb-3">
            <div class="col-md-3 col-xs-12 mt-1">
                <div class="ribbon ribbon-success new2 p-4" role="alert">
                    <i class="ribbon-icon fas fa-wallet" aria-hidden="true"></i>

                    <div class="ribbon-body" style="width: 100%">
                        <h6>اجمالي المحفظة</h6>
                        <h3><strong>{{currency($added_credit + $locked_credit + $withdrawable_credit)}}</strong></h3>

                        <span class="hint tooltip-container" data-toggle="tooltip" title="هو مجموع الرصيد المضاف + الرصيد المحجوز + الرصيد القابل للتسييل"><i class="fa fa-question-circle"></i></span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-xs-12 mt-1">
                <div class="ribbon ribbon-success new2 p-4" role="alert">
                    <i class="ribbon-icon fas fa-wallet" aria-hidden="true"></i>

                    <div class="ribbon-body" style="width: 100%">
                        <h6>الرصيد المضاف والقابل للدفع</h6>
                        <h3><strong>{{currency($added_credit)}}</strong></h3>

                        <button class="btn btn-rounded w-100 mt-1" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus mr-2"></i> اضافة رصيد</button>

                        <span class="hint tooltip-container" data-toggle="tooltip" title="هو الرصيد الذي يمكن دفع المدفوعات (مثل : رسوم اصدار العقد)"><i class="fa fa-question-circle"></i></span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-xs-12 mt-1">
                <div class="ribbon ribbon-success new2 p-4" role="alert">
                    <i class="ribbon-icon fas fa-wallet" aria-hidden="true"></i>

                    <div class="ribbon-body" style="width: 100%">
                        <h6>الرصيد المحجوز</h6>
                        <h3><strong>{{currency($locked_credit)}}</strong></h3>

                        <span class="hint tooltip-container" data-toggle="tooltip" title="هو الرصيد الذي يتم حجزه لسداد فواتير الحجوزات - و يتم حجزه من مبلغ العربون المتلقى اذا كان العميل دفع الكترونياً ( قيمة الحجز / 100 * 10 % ) = قيمة العموله / 100 ❌ 15% ضريبة القيمة المضافة."><i class="fa fa-question-circle"></i></span>

                    </div>
                </div>
            </div>

            <div class="col-md-3 col-xs-12 mt-1">
                <div class="ribbon ribbon-success new2 p-4" role="alert">
                    <i class="ribbon-icon fas fa-wallet" aria-hidden="true"></i>

                    <div class="ribbon-body" style="width: 100%">
                        <h6>رصيد التسييل</h6>
                        <h3><strong>{{currency($withdrawable_credit)}}</strong></h3>

                        @if($withdrawable_credit > 0)
                            <button class="btn btn-rounded w-100" data-toggle="modal" data-target="#withdraw-model"><i class="fa fa-bank mr-2"></i> سحب المبالغ </button>
                        @endif

                        <a class="btn btn-rounded w-100 mt-1" href="{{route('credit.history')}}"><i class="fa fa-file mr-2"></i> عرض السجل </a>

                        <span class="hint tooltip-container" data-toggle="tooltip" title="( هو المبلغ المتلقى من المدفوعات الالكترونيه على الحجوزات ناقص الرصيد المحجوز ) ،،
و يمكن طلب تحويله الى حسابكم البنكي المعرف من قبلكم في الحسابات البنكيه"><i class="fa fa-question-circle"></i></span>

                    </div>
                </div>
            </div>
        </div>

        <div class="card rounded-card">

            <div class="card-body text-center">

                @if($credits->total() > 0)
                @include('admin.layouts.messages')

                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="table-credit">
                            <thead>
                            <tr>
                                <th scope="col">القيمة</th>
                                <th scope="col">العملية</th>
                                <th scope="col">الوقت</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($credits_obj as $credit)
                                <tr>
                                    <?php
                                        $credit_plain = $credit->credit;

                                        $downpayment = [
                                            'booking_downpayment_locked',
                                            'booking_downpayment_total',
                                            'booking_downpayment',

                                            'booking_cancelled_locked_with-commission',
                                            'booking_cancelled_locked_without-commission',
                                            'booking_cancelled_withdrawable_with-commission',
                                            'booking_cancelled_withdrawable_without-commission'
                                        ]
                                    ?>

                                    <td>
{{--                                        <span class="text-{{$credit_plain < 0 ? 'danger' : 'success'}} {{in_array($credit->type, $downpayment) && $credit_plain > 0 ? 'locked' : ''}}">--}}
                                        <span class="text-{{$credit_plain < 0 ? 'danger' : 'success'}} {{$credit_plain > 0 ? $credit->type : null}}">
                                            <span class="icon mr-2"><i class="fa-solid fa-caret-{{$credit_plain < 0 ? 'down' : 'up'}}"></i></span>
                                            <span class="amount">{{currency($credit_plain)}}</span>
                                        </span>

{{--                                        <br />--}}
{{--                                        --}}
{{--                                        <span class="text-muted">--}}
{{--                                            <span class="amount">اجمالي العربون 478 ر.س</span>--}}
{{--                                        </span>--}}
                                    </td>

                                    <td>
                                        {{!is_null($credit->type) ? trans('validation.wallet_types.'.$credit->type) : 'غير معروف'}}

                                        @if($credit->type == 'investor_contract' || $credit->type == 'contract')
                                            @if($credit->contract)
                                                <a href="{{route('contract.by.token', ['code' => $credit->contract->code, 'token' => $credit->contract->token])}}">{{$credit->contract->code}}</a>
                                            @endif
                                        @elseif(in_array($credit->type, $downpayment))
                                            @if($credit->type == 'booking_downpayment_locked' && $credit->credit < 0)
                                                <p>تم استخدام الرصيد في دفع فاتورة رقم {{pad_code($credit->model_id)}}</p>
                                            @else
                                                <p>حجز رقم {{pad_code($credit->model_id)}}</p>
                                            @endif
                                        @endif
                                    </td>

                                    <td>
                                        <h6 class="text-muted">{{$credit->created_at->diffForHumans(\Carbon\Carbon::now())}}</h6>
                                        <h6 class="text-muted" style="direction: ltr">{{$credit->created_at->format('d/m/Y H:i:s')}}</h6>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>

                @else
                    @include('layouts.no-records', ['records_name' => 'إضافة رصيد'])
                @endif
            </div>
        </div>

        <div class="text-center d-flex justify-content-center mt-2">
            {{$credits->links()}}
        </div>
    </div>
@endsection

@section('scripts')
{{--    <script>--}}
{{--        $('#credit').on('keyup', function (){--}}
{{--            if ($(this).val() < 500){--}}
{{--                $(this).val(500)--}}
{{--            }--}}
{{--        })--}}
{{--    </script>--}}
@endsection
