@extends('layouts.front-page')

@section('styles')
    <link rel="stylesheet" href="{{asset('css/jquery-ui.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/jquery-ui.multidatespicker.css')}}" />

    <style>
        .swal-notification{
            font-size: 16px;
            font-weight: normal;
            line-height: initial;
            display: block;
            color: #ea5455;
            margin-top: 10px
        }
    </style>
@endsection

@section('content')
    <div class="container main-container">

        @include('admin.layouts.messages')

        @if(request()->has('status') && request()->status == 'paySuccessful')
            <div class="alert alert-success alert-dismissible new2 p-4" role="alert">
                <i class="alert-icon icon_check_alt" aria-hidden="true"></i>

                <div class="alert-body">تم دفع الفاتورة بنجاح.</div>

                <button type="button" class="close-button" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row">
            @if($invoices->count() == 0)
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center"><i class="icon_documents_alt" style="font-size: 50px; color: #ccc"></i></div>
                            <h4 class="text-center" style="color: #cdcdcd;">لا يوجد أي فواتير حالية</h4>
                        </div>
                    </div>
                </div>
            @else
                @foreach($invoices as $invoice)
                <div class="col-md-4 col-sm-12 col-xs-12 mt-2">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{route('investor.pay.invoice')}}" method="POST" class="payment-form">
                                @csrf

                                <input type="hidden" name="code" value="{{base64_encode($invoice->id)}}" />

                                <div class="">
                                    <label class="form-label">المدة</label>
                                    <h6 style="direction: ltr">{{\Carbon\Carbon::parse($invoice->start_date)->format('Y-m-d')}} - {{\Carbon\Carbon::parse($invoice->end_date)->format('Y-m-d')}}</h6>
                                </div>

                                <div class="">
                                    <label class="form-label">الوحدة</label>
                                    <h5>{{$invoice->unit->unit->unit_number ?? ''}}</h5>
                                    <h6>{{$invoice->unit->unit->sector->sector_name ?? ''}}</h6>
                                    <h6>{{$invoice->unit->unit->beach->beach ?? ''}}</h6>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 col-xs-12">
                                        <label class="form-label">الإجمالي</label>
                                        <h6>{{currency($invoice->total) ?? ''}}</h6>
                                    </div>

                                    <div class="col-md-6 col-xs-12">
                                        <label class="form-label text-danger">المخالفات</label>
                                        <h6 class="text-danger">{{currency($invoice->violations) ?? ''}}</h6>
                                    </div>

                                    <div class="col-md-6 col-xs-12">
                                        <label class="form-label">المطلوب سداده</label>
                                        <h6>{{currency(investor_to_pay($invoice))}}</h6>
                                    </div>

                                    <div class="col-md-6 col-xs-12"></div>

                                    @if($invoice->locked_paid != '')
                                        <div class="col-md-6 col-xs-12">
                                            <label class="form-label">مبلغ تم سداده بالرصيد المحجوز</label>
                                            <h6>{{currency($invoice->locked_paid)}}</h6>
                                        </div>

                                        <div class="col-md-6 col-xs-12">
                                            <label class="form-label">المتبقي</label>
                                            <h6>{{currency(investor_to_pay($invoice) - $invoice->locked_paid)}}</h6>
                                        </div>
                                    @endif
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12">

                                        @if($invoice->status)
                                            <div class="icon-wrapper text-success"><i class="icon fa fa-check-circle"></i> مدفوع </div>
                                        @elseif($invoice->total || $invoice->violations)
                                            <div class="icon-wrapper text-warning"><i class="icon fa fa-exclamation-circle"></i> بانتظار السداد </div>
                                        @else
                                            <div class="icon-wrapper text-success"><i class="icon fa fa-check-circle"></i> لا يوجد أي مبلغ لسداده </div>
                                        @endif

                                        <div class="buttons">
                                            <a href="{{route('invoices.show', base64_encode($invoice->id))}}" class="button button-blue">عرض التفاصيل</a>

                                            @if(!$invoice->status && ($invoice->total || $invoice->violations))
                                                <a href="#" class="button button-border pay-invoice" data-code="{{base64_encode($invoice->id)}}" data-required="{{currency(investor_to_pay($invoice) - $invoice->locked_paid)}}">الدفع</a>
                                            @else
                                                <a href="{{route('invoices.html', deep_encode($invoice->id, $invoice->created_at))}}" class="button button-border">عرض الفاتورة</a>
                                            @endif
                                        </div>

                                        <label class="form-label">تم الاصدار في</label>
                                        <h6 style="direction: ltr">{{\Carbon\Carbon::parse($invoice->created_at)->format('Y-m-d - H:i:s')}}</h6>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>

    </div>
@endsection

@section('scripts')
<script>
    $('.pay-invoice').on('click', function (e){
        e.preventDefault();

        const $this = $(this);
        const moneyRequired = $this.data('required');

        let title = 'المبلغ المطلوب';
        title += '<br/>';
        title += '<span class="text text-danger">'+ moneyRequired +'</span>';

        @if(locked_credit() > 0)
            title += '<br/>';
            title += 'باي طريقة تفضل الدفع؟';
            title += '<br/>';

            title += '<span class="swal-notification">';
            title += 'هناك رصيد محجوز بقيمة {{currency(locked_credit())}} اذا اردت استخدامه برجاء الضغط على دفع بالرصيد المحجوز.';
            title += '</span>';
        @endif


        Swal.fire({
            title: title,

            showCancelButton: true,

            @if(locked_credit() > 0)
                showDenyButton: true,
                denyButtonText: 'دفع بالرصيد المحجوز',
            @endif

            confirmButtonText: `دفع عبر مدى`,
            cancelButtonText: `إلغاء`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            @if(locked_credit() > 0)
                if (result.isDenied) {
                    const invoice = $this.data('code');

                    $.post('{{investor_url('payInvoiceByCredit')}}', {invoice: invoice})
                        .done(function (data){
                            console.log(data);
                            window.location.href = '{{investor_url('invoices?type=active&status=paySuccessful')}}'
                        })
                        .fail(function (data){
                            Swal.fire(
                                data.responseJSON,
                                '',
                                'error'
                            )
                        })
                } else if (result.isConfirmed) {
                    $this.parents('form').submit();
                }
            @endif
        })
    })
</script>
@endsection
