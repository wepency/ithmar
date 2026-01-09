{{-- 'phone','accepted','paid','unpaid','pay_later','exempt','rejected' --}}

@if($contract->payment_type == 'phone')
    <a class="btn btn-warning icon-button tooltip-container" title="تفعيل رقم الجوال" href="{{url('contract/'.contractMix($contract).'/verifyPhone')}}"><i class="fa-solid fa-mobile-screen-button"></i></a>
@elseif($contract->is_accepted && !$contract->status && !$contract->pay_later && !$contract->is_cancelled)
    <form method="post" action="{{investor_url('contract/'.$contract->code.'/payment')}}" style="display: inline-block;padding: 0;margin: 0">
        @csrf
        <button class="pay-unpaid btn btn-success icon-button tooltip-container" title="الدفع و تفعيل العقد" data-required="{{currency_format($contract->total + $contract->services_total)}}" data-contract-code="{{$contract->code}}" type="submit">الدفع</button>
    </form>
@endif

@if($contract->status && $contract->is_accepted)
    <a class="btn btn-primary icon-button tooltip-container" title="عرض العقد" href="{{investor_url('contract/'.$contract->code)}}"><i class="fa-regular fa-eye"></i></a>
@endif

@php
    $invalid = ['phone', 'accepted', 'rejected', 'unpaid'];
@endphp

@if(!in_array($contract->payment_type, $invalid) && acceptedNotCancelled($accepted, $cancelled))
    @if(is_valid($contract))
        <a class="btn btn-primary icon-button tooltip-container" title="تعديل العقد" href="{{investor_url('contract/'.$contract->code.'/edit')}}"><i class="fa-solid fa-pen-to-square"></i></a>
    @endif
@endif

@if(is_valid($contract) && is_null($contract->is_cancelled) && is_null($contract->reservation_id))
    <form onsubmit='return confirm()' style='margin: 0;padding: 0;display: inline-block' method='POST' action='{{investor_url("contract/cancel/".$contract->code)}}'>
        @csrf
        @method('PUT')

        <button class="btn btn-danger icon-button tooltip-container" title="إلغاء العقد" type="submit"><i class="fa-solid fa-xmark"></i></button>
    </form>
@endif

@if($contract->is_cancelled)
    <a class="btn btn-primary icon-button tooltip-container" title="عرض العقد" href="{{investor_url('contract/'.$contract->code)}}"><i class="fa-regular fa-eye"></i></a>
@endif
