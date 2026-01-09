<?php
$vertime = cache()->get('vertime');
?>

@extends('layouts.front-page')

@section('styles')
    <script src="{{asset('js/moment.min.js')}}"></script>

    <style>
        body {
            margin: 40px 10px;
            padding: 0;
            font-size: 14px;
        }

        #count-down{
            font-size: 50px;
            font-weight: 600;
            text-align: center;
        }
    </style>
@endsection

@section('content')
    <div class="container home-container">
        <div class="card">
            <div class="cart-title p-2">حجز مبدئي من {{$date->from}} إلى {{$date->to}}</div>

            <div class="card-body">
                <h4 class="text-danger text-center mt-4 mb-4">مهلة الدفع لهذا العميل تنتهي بعد</h4>
                <div id='count-down'></div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var countDownDate = new Date('{{$date->created_at->addMinutes($vertime)->format('M d, Y H:i:s')}}').getTime();

        var x = setInterval(function() {

            // Get today's date and time
            var now = new Date().getTime();

            // Find the distance between now and the count down date
            var distance = countDownDate - now;

            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Display the result in the element with id="demo"
            $('#count-down').html(hours + "h " + minutes + "m " + seconds + "s ");

        }, 1000);
    </script>
@endsection
