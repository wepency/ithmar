@extends('layouts.admin')

@section('styles')
    <style>
        .d-flex{
            display: flex;
        }

        .card{
            margin-top: 40px;
            background-color: #fff;
            padding: 25px;
            border-radius: 5px;
        }

        .ribbon{
            border: 0;
            border-radius: 10px;
            background: #f2709c;
            background: -webkit-linear-gradient(to right, #f2709c, #ff9472);
            background: linear-gradient(to right, #f2709c, #ff9472);

            position: relative;
            overflow: hidden;
            height: 100%;

            padding: 10px;
        }

        .ribbon.ribbon-success{
            color: #ffffff;
        }
        .ribbon .ribbon-icon{
            position: absolute;
            left: -10px;
            font-size: 100px;
            opacity: .4;
        }
        .rounded-card{
            border-radius: 10px;
            box-shadow: 0 4px 8px 0 #00000014
        }

        .btn-rounded{
            border: 2px solid #fff;
            border-radius: 50px;
            padding: 5px 30px;
            color: #fff;

            transition: all .25s ease-in;
            -webkit-transition: all .25s ease-in;
            -moz-transition: all .25s ease-in;
            -o-transition: all .25s ease-in;
        }
        .btn-rounded:hover{
            background-color: #fff;
            color: #333;
        }

        /* Modal Style */
        .durrah-modal .modal-content{
            border-radius: 20px;
            border: 0;
        }
        .durrah-modal .modal-header{
            border-bottom: 1px solid #f9f9f9;
        }

        .hint{
            position: absolute;
            bottom: 5px;
            left: 15px;
            font-size: 30px;
        }
    </style>
@endsection

@section('content')
    <div class="modal durrah-modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{route('admin.user.wallet.store', $user->id)}}" method="POST">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">اضافة رصيد</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="icon_close"></i></span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8 col-xs-12">
                                <div class="form-group text-right">
                                    <label for="credit">الرصيد</label>
                                    <input name="credit" id="credit" type="number" class="form-control grey" min="0" required />
                                </div>
                            </div>

                            <div class="col-md-4 col-xs-12">
                                <div class="form-group text-right">
                                    <label for="credit-type">نوع العملية</label>

                                    <select id="credit-type" name="credit_type" class="form-control">
                                        <option value="add">اضافة</option>
                                        <option value="sub">خصم</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success"><i class="fa fa-plus"></i> <span class="text"> اضافة رصيد </span> <span class="loader"></span></button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon_close"></i> <span class="text">الغاء</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container-fluid">

        <h3 class="box-title">{{$page_title ?? ''}}</h3>

        <div class="row mb-3">
            <div class="col-md-3 col-xs-12 mt-1">
                <div class="ribbon ribbon-success new2 p-4" role="alert">
                    <i class="ribbon-icon fas fa-wallet" aria-hidden="true"></i>

                    <div class="ribbon-body" style="width: 100%">
                        <h6>اجمالي المحفظة</h6>
                        <h3><strong>{{currency($credit_total)}}</strong></h3>

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

        <div class="row">
            <div class="col-md-12 col-xs-12">

                @if($credits->total() > 0)
                    @include('admin.layouts.messages')

                    <div class="card">
                        <div class="card-body">
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
                                    @foreach($credits as $credit)

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


                                        <tr>
                                            <td>
                                                <span class="text-{{$credit_plain < 0 ? 'danger' : 'success'}} {{$credit_plain > 0 ? $credit->type : null}}">
                                                    <span class="icon mr-2"><i class="fa fa-caret-{{$credit_plain < 0 ? 'down' : 'up'}}"></i></span>
                                                    <span class="amount">{{currency($credit_plain)}}</span>
                                                </span>
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
                        </div>
                    </div>
                @else
                    @include('admin.layouts.no-records', ['records_name' => 'إضافة رصيد'])
                @endif

                <div class="text-center d-flex justify-content-center mt-2">
                    {{$credits->links()}}
                </div>

            </div>
        </div>
    </div>
@endsection
