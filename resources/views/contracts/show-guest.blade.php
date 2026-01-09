@extends('layouts.contract-front')

@section('title', '')

@section('content')
    @if($contract->status == 0 && !$contract->reservation_id)
        <div style="width: 80%; margin: auto; text-align: center; margin-top: 10px">
            <div class="alert alert-danger">
                لم يتم دفع العقد حتى الأن<br/>
                <form style="display: inline-block;margin: 0" method="POST" action="{{investor_url('pay/'.$contract->code)}}">
                    @csrf
                    <button class="btn btn-primary" type="submit" style="font-size: 15px;padding: 10px 5px;">برجاء الدفع لتفعيل العقد</button>
                </form>
            </div>
        </div>
    @else
        <div id="app">
            <router-view />
        </div>

        <script src="{{asset('js/app.js')}}"></script>
    @endif
@endsection
