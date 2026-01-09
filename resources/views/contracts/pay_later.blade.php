@extends('layouts.front-page')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3 class="page-title">{{$page_title}}</h3>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/')}}">الرئيسية</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{$page_title}}</li>
                    </ol>
                </nav>
            </div>

            <div class="card-body text-center">

                @if(session()->has('message'))
                    <div class="alert alert-warning">{{session()->get('message')}}</div>
                @endif

                @if(session()->has('success'))
                    <div class="alert alert-success">{{session()->get('success')}}</div>
                @endif

                <div class="row justify-content-center">
                    <div class="col-md-6 col-xs-12">
                        <div class="alert alert-success text-center">
                            <h3>إجمالي المطلوب</h3>
                            <h2><strong>{{currency_format($sum)}}</strong></h2>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th scope="col">العقد</th>
                            <th scope="col">السعر</th>
                            <th scope="col">VAT</th>
                            <th scope="col">الإجمالي</th>
                            <th scope="col">إجمالي الخدمات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($contracts as $contract)
                            <tr>
                                <td>
                                    {{$contract->code}}
                                </td>

                                <td>
                                    {{currency_format($contract->price)}}
                                </td>

                                <td>
                                    {{currency_format($contract->total - $contract->price)}}
                                </td>

                                <td>
                                    {{currency_format($contract->total)}}
                                </td>

                                <td>
                                    {{currency_format($contract->services_total)}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <form action="{{investor_url('pay_later/'.$later->id)}}" method="POST">
                        @csrf
                        @method('PUT')

                        @php
                            $value = base64_encode(implode(',', $pay_contracts));
                        @endphp

                        <input type="hidden" name="c1o2n3t4" id="c1o2n3t4" value="{{$value}}">

                        <button type="submit" class="btn btn-success pay-unpaid" data-required="{{currency_format($sum)}}">الدفع الأن</button>
                    </form>
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
                    $.post('{{investor_url('payByCreditBulk')}}', {contract: $('#c1o2n3t4').val()})
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
