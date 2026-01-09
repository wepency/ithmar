<?php
    $carbon = new \Carbon\Carbon;
    $now = $carbon->now();
    $is_going = $now < $contract->from;
    $is_valid = is_valid($contract);
    $before_ends =  $contract->to > $now;
?>
@extends('layouts.front-page')

@section('styles')
    <style>
        #counter{
            font-size: 25px;
            font-weight: bolder;
            color: #2196f3;
        }
        .card{
            border: none;
            box-shadow: 0 0 10px rgb(255 255 255 / 50%) !important;
            -webkit-box-shadow: 0 0 10px rgb(255 255 255 / 50%) !important;
            -moz-box-shadow: 0 0 10px rgb(255 255 255 / 50%) !important;
            -o-box-shadow: 0 0 10px rgb(255 255 255 / 50%) !important;
        }
    </style>
@endsection

@section('content')
    <div class="container" style="min-height: calc(100vh - 200px);">
        <div class="card">
{{--            <div class="card-header">--}}
{{--                <h3 class="page-title">{{$page_title}}</h3>--}}

{{--                <nav aria-label="breadcrumb">--}}
{{--                    <ol class="breadcrumb">--}}
{{--                        <li class="breadcrumb-item"><a href="{{url('/')}}">الرئيسية</a></li>--}}
{{--                        <li class="breadcrumb-item active" aria-current="page">{{$page_title}}</li>--}}
{{--                    </ol>--}}
{{--                </nav>--}}
{{--            </div>--}}

            <div class="card-body text-center">

                <h3>{{$page_title}}</h3>
                <h5 class="subheader" style="color: #cccccc">يمكنك الإطلاع على حالة العقد بالاسفل.</h5>

                @if($contract->is_cancelled)
                    <div class="alert alert-danger alert-dismissible new2 p-4" role="alert">
                        <i class="alert-icon icon_close_alt2" aria-hidden="true"></i>

                        <div class="alert-body">
                            <h4 class="mt-2">هذا العقد ملغي</h4>
                        </div>
                    </div>
                @elseif($before_ends)
                    <div class="alert alert-success alert-dismissible new2 p-4" role="alert">
                        <i class="alert-icon icon_check_alt" aria-hidden="true"></i>

                        @if($is_going)
                            <div class="alert-body">
                                <h4 class="mt-2">هذا العقد فعال و لكن لم يحن تاريخه بعد</h4>
                            </div>
                        @elseif(is_valid($contract))
                            <div class="alert-body">
                                <h4 class="mt-2">هذا العقد فعال و ساري حتى:</h4>
                            </div>
                        @endif

                    </div>

                    <div class="row">
                        <div class="col-12">
                            <h5 class="text-center">
                                من: {{format_date($contract->from)}} &nbsp;&nbsp;إلى: {{format_date($contract->to)}}
                            </h5>
                        </div>
                    </div>

                    @if(is_valid($contract))
                        <p id="counter"></p>
                    @endif

                    <p><a href="{{url('contract/'.$contract->code.'/'.$contract->token)}}">لعرض هذا العقد اضغط هنا</a></p>
                @else
                    <div class="alert alert-danger alert-dismissible new2 p-4" role="alert">
                        <i class="alert-icon icon_close_alt2" aria-hidden="true"></i>

                        <div class="alert-body">
                            <h4 class="mt-2">هذا العقد منتهي و غير فعال</h4>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @if($is_valid)

    <?php
        $to = \Carbon\Carbon::parse($contract->to)->format('M d, Y H:i:s')
    ?>
    <script>
        // Set the date we're counting down to
        var countDownDate = new Date("{{$to}}").getTime();

        // Update the count down every 1 second
        var x = setInterval(function() {

            // Get today's date and time
            var now = new Date().getTime();

            // Find the distance between now and the count down date
            var distance = countDownDate - now;

            // Time calculations for days, hours, minutes and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Display the result in the element with id="demo"
            const daysLabel = days > 2 && days < 11 ? 'أيام' : 'يوم';
            document.getElementById("counter").innerHTML = days + " "+ daysLabel +" " + hours + ":"
                + minutes + ":" + seconds;

            // If the count down is finished, write some text
            if (distance < 0) {
                clearInterval(x);
                document.getElementById("counter").innerHTML = "العقد منتهي";
            }
        }, 1000);
    </script>
    @endif
@endsection
