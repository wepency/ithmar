<?php
$carbon = new \Carbon\Carbon;
?>

<div class="table-responsive mt-2">
    <table class="table table-striped table-hover ithmar-table has-badges">
        <thead>
        <tr>
            <th></th>
            <th>
                <a class="has-carrot" href="#">
                    <span class="text">رقم العقد</span>
                    <i class="arrow_carrot-down"></i>
                </a>

            </th>

            <th>
                <a class="has-carrot" href="#">
                    <span class="text">الوحدة</span>
                    <i class="arrow_carrot-down"></i>
                </a>
            </th>

            <th>
                <a class="has-carrot" href="#">
                    <span class="text">القطاع</span>
                    <i class="arrow_carrot-down"></i>
                </a>
            </th>

            <th>
                <a class="has-carrot" href="#">
                    <span class="text">الشاطئ</span>
                    <i class="arrow_carrot-down"></i>
                </a>
            </th>

            <th style="width: 160px;">
                <a>التواريخ</a>
            </th>

{{--            <th>--}}
{{--                <a href="#">حالة العقد</a>--}}
{{--            </th>--}}

            <th>
                <a class="has-carrot" href="#">
                    <span class="text">حالة الدفع</span>
                    <i class="arrow_carrot-down"></i>
                </a>
            </th>

            <th>
                <a>العمليات</a>
            </th>
        </tr>
        <tr class="spacer"><td colspan="100"></td></tr>
        </thead>
        <tbody>
        @foreach($contracts as $contract)
            @php
                $accepted  = $contract->is_accepted;
                $cancelled = $contract->is_cancelled;
            @endphp

            <tr class="{{$contract->reservation_id != '' ? 'reserved' : ''}}">
                <td>
                    @if(acceptedNotCancelled($accepted, $cancelled))
                        @if($contract->payment_type == 'pay_later')
                            <input class="pay_later_field" type="checkbox" value="{{$contract->id}}" name="pay_later_contract[]" />
                        @endif
                    @endif
                </td>

                <td>
                    {!! get_contract_status_badge($contract) !!}
                    {{$contract->code}}
                    @if($contract->reservation_id != '')
                    <p class="mb-0 text-success">بواسطه : حجوزات الدره</p>
                    @endif
                </td>

                <td>{{@$contract->unit->unit_number ?? ''}}</td>
                <td>{{@$contract->sector->sector_name ?? ''}}</td>
                <td>{{@$contract->beach->beach ?? ''}}</td>

                <td>
                    <p class="mb-0 text-success"><i class="far fa-arrow-alt-circle-left"></i> {{$carbon->parse($contract->from)->format(get_format())}}</p>
                    <p class="mb-0 text-danger"><i class="far fa-arrow-alt-circle-right"></i> {{$carbon->parse($contract->to)->format(get_format())}}</p>
                </td>

{{--                <td>{!! get_contract_badge($contract) !!}</td>--}}

                <td>{!! get_contract_status($contract) !!}</td>

                <td>
                    @include('contracts.buttons')
                </td>
            </tr>

            <tr class="spacer"><td colspan="100"></td></tr>
        @endforeach
        </tbody>
    </table>
    <form action="{{investor_url('pay_later')}}" id="pay_later" method="POST">
        @csrf
        <input type="hidden" name="pay_later_contract" id="pay_later_contract">
        <button style="display: none" id="pay-later-button" class="btn btn-primary" type="submit">دفع الاوآجل</button>
    </form>
</div>

@if($contracts->total() == 0)
    @include('no-records')
@endif

<script>
    @if(session()->has('payment_success'))
    Swal.fire(
        'تمت العملية بنجاح',
        'تم الدفع بنجاح و العقد الأن مفعل.',
        'success'
    )

        @php
        session()->forget('payment_success')
        @endphp

    @endif

    $('.pay_later_field').on('click', function (e){
        let fields = [];

        if ($('.pay_later_field:checked').length > 0)
            $('#pay-later-button').show()
        else
            $('#pay-later-button').hide()

        $('.pay_later_field:checked').each(function (data){
            fields.push($(this).val())
        })

        $('#pay_later_contract').val(fields)
    })

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
