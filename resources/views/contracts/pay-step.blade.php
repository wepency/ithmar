@extends('layouts.front-page')

@section('styles')
    <link rel="stylesheet" href="{{asset('css/contract.css')}}" />
@endsection

@section('content')
    <div class="container main-container">
        <div class="form-container">
            <ul id="section-tabs">
                <li><span>1.</span> بيانات العقد </li>
                <li><span>2.</span> تأكيد رقم الجوال</li>
                <li class="current active"><span>3.</span> الدفع و التفعيل</li>
            </ul>

            <div class="card">
                <div class="card-body">
                    @guest
                        <div class="alert alert-success alert-dismissible new2 p-4" role="alert">
                            <i class="alert-icon icon_check_alt" aria-hidden="true"></i>

                            <div class="alert-body">تم إنشاء العقد بنجاح ، برجاء التواصل مع صاحب العقار للدفع و التفعيل.</div>
                        </div>
                    @endguest

                    @auth
                        @if($contract->is_accepted)
                            <div class="alert alert-success alert-dismissible new2 p-4" role="alert">
                                <i class="alert-icon icon_check_alt" aria-hidden="true"></i>

                                <div class="alert-body">تم الموافقة على العقد من قبل الإدارة ، برجاء الدفع و التفعيل.</div>
                            </div>

                            <form method="post" action="{{investor_url('contract/'.$contract->code.'/payment')}}" style="display: inline-block;padding: 0;margin: 0">
                                @csrf
                                <button class="pay-unpaid gb gb-bordered hover-slide gb9" data-required="{{currency_format($contract->total + $contract->services_total)}}" data-contract-code="{{$contract->code}}" type="submit"><i class="check-mark"></i> <span class="text">الدفع و التفعيل</span></button>
                            </form>
{{--                            <div class="form-group">--}}
{{--                                <a href="#" class="gb gb-bordered hover-slide gb10"><i class="check-mark"></i> <span class="text"></span></a>--}}
{{--                            </div>--}}
                        @else
                            <div class="alert alert-warning new2 p-4" role="alert">
                                <i class="alert-icon icon_info_alt" aria-hidden="true"></i>

                                <div class="alert-body">تم إنشاء العقد و بانتظار موافقة الإدارة.</div>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('.pay-unpaid').on('click', function (e){
            e.preventDefault();

            const $this = $(this);
            const moneyRequired = $this.data('required');

            let title = 'المبلغ الإجمالي المطلوب';
            title += '<br/>';
            title += '<span class="text text-danger">'+ moneyRequired +'</span>';
            title += '<br/>';
            title += 'باي طريقة تفضل الدفع؟';

            Swal.fire({
                title: title,
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'دفع بالمحفظة',
                denyButtonText: `دفع عبر مدى`,
                cancelButtonText: `إلغاء`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    const contract = $this.data('contract-code');

                    $.post('{{investor_url('payByCredit')}}', {contract: contract})
                        .done(function (data){
                            window.location.href = '{{investor_url('contracts?type=active&paySuccessful')}}'
                        })
                        .fail(function (data){
                            Swal.fire(
                                data.responseJSON,
                                '',
                                'error'
                            )
                        })
                } else if (result.isDenied) {
                    $this.parents('form').submit();
                }
            })
        })
    </script>
@endsection
