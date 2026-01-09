@extends('layouts.front-page')

@section('styles')
    <style>
        :root {
            --omrs-color-ink-lowest-contrast: rgba(47, 60, 85, 0.18);
            --omrs-color-ink-low-contrast: rgba(60, 60, 67, 0.3);
            --omrs-color-ink-medium-contrast: rgba(19, 19, 21, 0.6);
            --omrs-color-interaction: #1e4bd1;
            --omrs-color-interaction-minus-two: rgba(73, 133, 224, 0.12);
            --omrs-color-danger: #b50706;
            --omrs-color-bg-low-contrast: #eff1f2;
            --omrs-color-ink-high-contrast: #121212;
            --omrs-color-bg-high-contrast: #ffffff;
        }
        /** END: Non Openmrs CSS **/
        div.omrs-input-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        /* Input*/
        .omrs-input-underlined > input,
        .omrs-input-filled > input {
            border: none;
            border-bottom: 0.125rem solid var(--omrs-color-ink-medium-contrast);
            width: 100%;
            /*height: 2rem;*/
            font-size: 1.0625rem;
            padding-left: 0.875rem;
            line-height: 147.6%;
            padding-top: 0.825rem;
            padding-bottom: 0.5rem;
        }

        .omrs-input-underlined > input:focus,
        .omrs-input-filled > input:focus {
            outline: none;
        }

        .omrs-input-underlined > .omrs-input-label,
        .omrs-input-filled > .omrs-input-label {
            position: absolute;
            top: 0.9375rem;
            left: 0.875rem;
            line-height: 147.6%;
            color: var(--omrs-color-ink-medium-contrast);
            transition: top .2s;
        }

        .omrs-input-underlined > svg,
        .omrs-input-filled > svg {
            position: absolute;
            top: 0.9375rem;
            right: 0.875rem;
            fill: var(--omrs-color-ink-medium-contrast);
        }

        .omrs-input-underlined > .omrs-input-helper,
        .omrs-input-filled > .omrs-input-helper {
            font-size: 0.9375rem;
            color: var(--omrs-color-ink-medium-contrast);
            letter-spacing: 0.0275rem;
            margin: 0.125rem 0.875rem;
        }

        .omrs-input-underlined > input:hover,
        .omrs-input-filled > input:hover {
            background: var(--omrs-color-interaction-minus-two);
            border-color: var(--omrs-color-ink-high-contrast);
        }

        .omrs-input-underlined > input:focus + .omrs-input-label,
        .omrs-input-underlined > input:valid + .omrs-input-label,
        .omrs-input-filled > input:focus + .omrs-input-label,
        .omrs-input-filled > input:valid + .omrs-input-label {
            top: 0;
            font-size: 0.9375rem;
            margin-bottom: 32px;;
        }

        .omrs-input-underlined:not(.omrs-input-danger) > input:focus + .omrs-input-label,
        .omrs-input-filled:not(.omrs-input-danger) > input:focus + .omrs-input-label {
            color: var(--omrs-color-interaction);
        }

        .omrs-input-underlined:not(.omrs-input-danger) > input:focus,
        .omrs-input-filled:not(.omrs-input-danger) > input:focus {
            border-color: var(--omrs-color-interaction);
        }

        .omrs-input-underlined:not(.omrs-input-danger) > input:focus ~ svg,
        .omrs-input-filled:not(.omrs-input-danger) > input:focus ~ svg {
            fill: var(--omrs-color-ink-high-contrast);
        }

        /** DISABLED **/

        .omrs-input-underlined > input:disabled {
            background: var(--omrs-color-bg-low-contrast);
            cursor: not-allowed;
        }

        .omrs-input-underlined > input:disabled + .omrs-input-label,
        .omrs-input-underlined > input:disabled ~ .omrs-input-helper{
            color: var(--omrs-color-ink-low-contrast);
        }

        .omrs-input-underlined > input:disabled ~ svg {
            fill: var(--omrs-color-ink-low-contrast);
        }


        /** DANGER **/

        .omrs-input-underlined.omrs-input-danger > .omrs-input-label, .omrs-input-underlined.omrs-input-danger > .omrs-input-helper,
        .omrs-input-filled.omrs-input-danger > .omrs-input-label, .omrs-input-filled.omrs-input-danger > .omrs-input-helper{
            color: var(--omrs-color-danger);
        }

        .omrs-input-danger > svg {
            fill: var(--omrs-color-danger);
        }

        .omrs-input-danger > input {
            border-color: var(--omrs-color-danger);
        }

        .omrs-input-underlined > input {
            background: var(--omrs-color-bg-high-contrast);
        }
        .omrs-input-filled > input {
            background: var(--omrs-color-bg-low-contrast);
        }
        header{
            display: none;
        }

        .header {
            position:relative;
            text-align:center;
        }

        .inner-header {
            height:10vh;
            width:100%;
            margin: 0;
            padding: 0;
        }

        img.logo{
            margin-top: 70px;
        }

        .flex {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .waves {
            position:relative;
            width: 100%;
            height:15vh;
            /*margin-bottom:-7px;*/
            min-height:100px;
            max-height:150px;
        }

        /* Animation */

        .parallax > use {
            animation: move-forever 25s cubic-bezier(.55,.5,.45,.5) infinite;
            opacity: .45;
        }
        .parallax > use:nth-child(1) {
            animation-delay: -2s;
            animation-duration: 7s;
        }
        .parallax > use:nth-child(2) {
            animation-delay: -3s;
            animation-duration: 10s;
        }
        .parallax > use:nth-child(3) {
            animation-delay: -4s;
            animation-duration: 13s;
        }
        .parallax > use:nth-child(4) {
            animation-delay: -5s;
            animation-duration: 20s;
        }
        .form-wrapper{
            background-color: #fff;
            padding: 20px;
            margin:10px auto 25px auto;
            max-width: 400px;
            border-radius: 10px;
            display: none;
        }
        .form-wrapper.active{
            display: block;
        }
        @keyframes move-forever {
            0% {
                transform: translate3d(-90px,0,0);
            }
            100% {
                transform: translate3d(85px,0,0);
            }
        }
        /*Shrinking for mobile*/
        @media (max-width: 768px) {
            .waves {
                height:80px;
                min-height:40px;
            }
            h1 {
                font-size:24px;
            }
        }
        .nav {
            overflow: hidden;
            margin: auto;
            width: 400px;
            border-radius: 10px;
        }
        .nav .options {
            display: flex;
            -moz-user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
            user-select: none;
            flex-direction: row-reverse;
            width: 100%;
        }
        .nav .options svg{
            height: 25px;
        }
        .nav .option {
            align-items: center;
            color: #444;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            min-height: 50px;
            min-width: 100px;
            -webkit-tap-highlight-color: transparent;
            text-align: center;
            transition: color 200ms, fill 200ms;
            background-color: #ffffff;
            width: 50%;
            padding: 10px;
        }
        .index0 .option:nth-child(1) {
            color: #4080ff;
            fill: #4080ff;
        }
        .index1 .option:nth-child(2) {
            color: #4080ff;
            fill: #4080ff;
        }
        .index2 .option:nth-child(3) {
            color: #4080ff;
            fill: #4080ff;
        }
        .bar {
            background: #4080ff;
            height: 3px;
            /*margin-bottom: 8px;*/
            position: relative;
            width: 100%;
        }
        .cover {
            background: #fff;
            height: 100%;
            left: 0;
            position: absolute;
            top: 0;
            width: 150%;
        }
        .left .cover1 {
            transition: transform 200ms;
        }
        .left .cover2 {
            transition: transform 200ms 120ms;
        }
        .right .cover1 {
            transition: transform 200ms 120ms;
        }
        .right .cover2 {
            transition: transform 200ms;
        }
        .index0 .cover1 {
            transform: translateX(-95%) skew(45deg);
        }
        .index0 .cover2 {
            transform: translateX(28.33%) skew(-45deg);
        }
        .index1 .cover1 {
            transform: translateX(-61.67%) skew(45deg);
        }
        .index1 .cover2 {
            transform: translateX(61.67%) skew(-45deg);
        }
        .index2 .cover1 {
            transform: translateX(-28.33%) skew(45deg);
        }
        .index2 .cover2 {
            transform: translateX(95%) skew(-45deg);
        }

        .hover-slide {
            transition: 0.6s all;
        }

        .slide-icon{
            font-size: 25px;
            margin-bottom: 5px;
        }

        @media (max-width: 500px) {
            .nav{
                width: 300px;
            }
            .form-wrapper{
                width: 300px;
            }
        }

        @media (max-width: 300px) {
            .nav{
                width: 250px;
            }
            .form-wrapper{
                width: 250px;
            }
        }
    </style>

    <script src="https://www.google.com/recaptcha/api.js"></script>
@endsection

@section('content')
    @php
        $nav_class = 'index0';

        if (request()->get('error-form') == 'request'){
            $nav_class = 'index1';
        }elseif (request()->get('error-form') == 'password'){
            $nav_class = '';
        }
    @endphp

    <div class="header">
        <div class="inner-header flex">
            <img src="{{asset('images/ithmar-logo.png')}}" class="logo" alt="Durrah" />
        </div>

        <div>
            <svg class="waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                 viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
                <defs>
                    <path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" />
                </defs>

                <g class="parallax">
                    <use xlink:href="#gentle-wave" x="48" y="0" fill="rgba(255,255,255,0.7" />
                    <use xlink:href="#gentle-wave" x="48" y="3" fill="rgba(255,255,255,0.5)" />
                    <use xlink:href="#gentle-wave" x="48" y="5" fill="rgba(255,255,255,0.3)" />
                    <use xlink:href="#gentle-wave" x="48" y="7" fill="#fff" />
                </g>
            </svg>
        </div>
    </div>

    <div class="container">

        <div class="row over-whatsapp">
            <div class="col-12 mt-5">
                <div class="nav">
                    <div class="bar right {{$nav_class}}">
                        <div class="cover cover1"></div>
                        <div class="cover cover2"></div>
                    </div>

                    <div class="options {{$nav_class}}">
                        <div class="option" data-hover="left index0" data-target="tab-1">
                            <i class="slide-icon fa fa-user-o"></i>
                            <span class="text">تسجيل دخول</span>
                        </div>

                        <div class="option" data-hover="right index1" data-target="tab-2">
                            <i class="slide-icon icon_folder-add_alt"></i>
                            <span class="text">طلب تأهيل جديد</span>
                        </div>
                    </div>
                </div>

                <div class="form-wrapper {{request()->get('error-form') == '' ? 'active' : ''}}" id="tab-1">
                    <form class="login-form form" id="login-form" method="post" action="{{url('login')}}">
                        @csrf

                        @include('admin.layouts.messages')

                        <div class="form-group">
                            <label for="phonenumber" class="form-label">البريد الإلكتروني أو رقم الجوال</label>
                            <input id="phonenumber" value="{{old('phonenumber')}}" name="phonenumber" type="text" class="form-control grey" />
                            <i class=""></i>
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label">كلمة المرور</label>
                            <input id="password" type="password" name="password" class="form-control grey" />
                            <i class=""></i>
                        </div>

                        <div class="text-center">
                            <a class="forgot-password" data-target="tab-3">استعادة كلمة المرور</a>
                        </div>

                        <div class="form-group">
                            <button type="submit"
                                    data-sitekey="{{env('reCaptch_site_key')}}"
                                    data-callback='onSubmitLogin'
                                    data-action='submit'
                                    class="g-recaptcha gb gb-bordered hover-slide w-100 gb9"><span class="text">تسجيل دخول</span></button>
                        </div>

                    </form>
                </div>

                <div class="form-wrapper {{request()->get('error-form') == 'request' ? 'active' : ''}}" id="tab-2">
                    @include('layouts.includes.request-form')
                </div>

                <div class="form-wrapper {{request()->get('error-form') == 'password' ? 'active' : ''}}" id="tab-3">
                    <form class="form" method="post" action="{{url('password/reset')}}">
                        @csrf

                        @include('admin.layouts.messages')

                        <h3 class="form-title"><span class="form-ribbon">استعادة كلمة المرور</span></h3>

                        <div class="form-group">
                            <label for="email" class="form-label">البريد الإلكتروني</label>
                            <input id="email" value="{{old('email')}}" name="email" type="email" class="form-control grey" />
                        </div>

                        <div class="form-group">
                            <button
                                data-sitekey="{{env('reCaptch_site_key')}}"
                                data-callback='onSubmitReset'
                                data-action='submit'
                                type="submit" class="gb gb-bordered hover-slide w-100">
                                <span class="text">استعادة كلمة المرور</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            var images = ["bg1.jpg", "bg2.jpg", "bg3.jpg"];

            var i = 0;

            function changeBackground() {
                jQuery('#dvImage').animate({'background-image': function() {
                    if (i >= images.length) {
                        i=0;
                    }

                    return 'url(images/' + images[i++] + ')';
                }}, 100);
            }
            changeBackground();

            setInterval(changeBackground, 300);
        });

        jQuery('.option').on('click', function (e){
            e.preventDefault();
            const eleHover = $(this).data('hover');

            $('.nav .bar').removeClass().addClass('bar').addClass(eleHover)
            $('.nav .options').removeClass().addClass('options').addClass(eleHover)
        })
    </script>

    <script>
        const option = $(".nav .option");

        $('[data-target]').on('click', function (e){
            e.preventDefault();

            const target = $(this).data('target')
            $('.form-wrapper').hide()
            $('#'+target).fadeIn()
        });

        // allOptions.each(function (i) {
        //     console.log(i)
        //
        //     $(this).on("click", () => {
        //         if (focus !== i) {
        //             bar.className = "bar";
        //             options.className = "options";
        //
        //             if (focus < i) {
        //                 bar.toggleClass("right");
        //             } else {
        //                 bar.toggleClass("left");
        //             }
        //             bar.toggleClass(`index${i}`);
        //             options.toggleClass(`index${i}`);
        //             focus = i;
        //         }
        //     });
        // });
    </script>

    <script>
        function onSubmitLogin(token) {
            document.getElementById('login-form').submit();
        }

        function onSubmitReset(token) {
            document.getElementById('reset-form').submit();
        }
    </script>

    <script src="{{asset('js/classie.js')}}"></script>

    <script>
        (function() {
            // trim polyfill : https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/Trim
            if (!String.prototype.trim) {
                (function() {
                    // Make sure we trim BOM and NBSP
                    var rtrim = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g;
                    String.prototype.trim = function() {
                        return this.replace(rtrim, '');
                    };
                })();
            }

            [].slice.call( document.querySelectorAll( 'input.input__field' ) ).forEach( function( inputEl ) {
                // in case the input is already filled..
                if( inputEl.value.trim() !== '' ) {
                    classie.add( inputEl.parentNode, 'input--filled' );
                }

                // events:
                inputEl.addEventListener( 'focus', onInputFocus );
                inputEl.addEventListener( 'blur', onInputBlur );
            } );

            function onInputFocus( ev ) {
                classie.add( ev.target.parentNode, 'input--filled' );
            }

            function onInputBlur( ev ) {
                if( ev.target.value.trim() === '' ) {
                    classie.remove( ev.target.parentNode, 'input--filled' );
                }
            }
        })();
    </script>
@endsection
