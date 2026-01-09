<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <?php

    if (auth()->check()) {
        if (is_sector_admin()) {
            echo '<script>window.location = "/dashboard";</script>';
        }
    }
//    ?>


    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <title>{{isset($page_title) ? $page_title.' - تطبيق إثمار الإلكتروني' : 'تطبيق إثمار الإلكتروني'}}</title>

    <link rel="stylesheet" href="{{asset('css/bootstrap.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/front-end.css')}}"/>

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;500;900&display=swap" rel="stylesheet">

    {{--    <link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css') }}">--}}
    {{--    <link rel="stylesheet" href="{{asset('font_icon/typicon.min.css') }}">--}}

    <link rel="stylesheet" href="{{asset('icons/elegant-icons.css')}}"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"/>

    <!--[if lte IE 7]><script src="{{asset('icons/lte-ie7.js')}}"></script><![endif]-->

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="57x57" href="{{asset('images/favicon/apple-icon-57x57.png')}}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{asset('images/favicon/apple-icon-60x60.png')}}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{asset('images/favicon/apple-icon-72x72.png')}}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{asset('images/favicon/apple-icon-76x76.png')}}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{asset('images/favicon/apple-icon-114x114.png')}}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{asset('images/favicon/apple-icon-120x120.png')}}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{asset('images/favicon/apple-icon-144x144.png')}}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{asset('images/favicon/apple-icon-152x152.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('images/favicon/apple-icon-180x180.png')}}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{asset('images/favicon/android-icon-192x192.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('images/favicon/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{asset('images/favicon/favicon-96x96.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('images/favicon/favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('images/favicon/manifest.json')}}">
    <meta name="msapplication-TileColor" content="#fdcb6e">
    <meta name="msapplication-TileImage" content="{{asset('images/favicon/ms-icon-144x144.png')}}">
    <meta name="theme-color" content="#fdcb6e">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="{{asset('js/jquery.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/swal.min.js')}}"></script>
    <script src="{{asset('js/scripts.js')}}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    @yield('styles')
</head>
{{--<body class="hold-transition" id="dvImage" style="background-attachment: fixed;width: 100%;background-image:url('{{asset('images/bg1.jpg')}}');background-size: cover;background-repeat: no-repeat;background-position: center center;min-height: 100vh;margin: 0;display: grid;grid-template-rows: auto 1fr auto;">--}}
<body class="hold-transition" id="dvImage"
      style="background-attachment: fixed;width: 100%;background-image:url('{{asset('images/bg1.jpg')}}');background-size: cover;background-repeat: no-repeat;background-position: center center;min-height: 100vh;margin: 0">

<div id="ithmar-preloader">
    <img src="{{asset('images/ithmar-logo.png')}}" alt="Ithmar Logo"/>
</div>

<div class="overlay"></div>

@include('layouts.includes.header')

<div class="container">
    @include('layouts.includes.messages.new-login ')
</div>

<div id="app"></div>

@yield('content')

@if(session()->has('old-login'))
    <div class="modal fade show" id="bookings-ad" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="modal-body p-0">
                    {{--                    <a href="{{url('gallery/create')}}" class="main-link">--}}
                    {{--                        <img src="{{asset('images/subscription-new.jpg')}}" style="max-width: 100%;" alt="" />--}}
                    {{--                    </a>--}}

                    {{--                    <img src="{{asset('images/cashback.jpg')}}" usemap="#image-map" />--}}

                    {{--                    <map name="image-map">--}}
                    {{--                        <area target="_blank" alt="First Step" title="First Step" href="https://youtu.be/dDlOn7wZ4tE" coords="1572,5288,312,3913" shape="rect">--}}
                    {{--                        <area target="_blank" alt="Second Step" title="Second Step" href="https://youtu.be/-_Cuy7Ho8ok" coords="1652,5284,2840,3920,1791,5173,1481,5404" shape="rect">--}}
                    {{--                        <area target="_blank" alt="Thrid Step" title="Thrid Step" href="https://youtu.be/we43ACeh-lQ" coords="4152,5280,2956,3916" shape="rect">--}}
                    {{--                        <area target="_blank" alt="Forth Step" title="Forth Step" href="https://youtu.be/Dntn25By1LE" coords="2868,6632,1632,5336" shape="rect">--}}
                    {{--                    </map>--}}


                    <div class="desktop-ad">
                        <img src="{{asset('images/cashback.jpg')}}" alt="Cashback" usemap="#image_map"/>

                        <map name="image_map">
                            <area target="_blank" alt="Third Step" title="" href="https://youtu.be/we43ACeh-lQ"
                                  coords="28,439,175,615" shape="rect">
                            <area target="_blank" alt="Second Step" title="" href="https://youtu.be/-_Cuy7Ho8ok"
                                  coords="185,439,321,594" shape="rect">
                            <area target="_blank" alt="First Step" title="" href="https://youtu.be/dDlOn7wZ4tE"
                                  coords="338,443,464,633" shape="rect">
                            <area target="_blank" alt="Forth Step" title="" href="https://youtu.be/Dntn25By1LE"
                                  coords="185,600,321,737" shape="rect">
                        </map>
                    </div>

                    <div class="d-none mobile-ad">
                        <img src="{{asset('images/cashback-mobile.jpg')}}" alt="Cashback" usemap="#image_map_mobile"/>

                        <map name="image_map_mobile">
                            <area target="_blank" alt="Third Step" title="First Step"
                                  href="https://youtu.be/we43ACeh-lQ" coords="12,251,97,335" shape="rect">
                            <area target="_blank" alt="Second Step" title="Second Step"
                                  href="https://youtu.be/-_Cuy7Ho8ok" coords="103,251,179,330" shape="rect">
                            <area target="_blank" alt="First Step" title="Thrid Step"
                                  href="https://youtu.be/dDlOn7wZ4tE" coords="184,251,263,336" shape="rect">
                            <area target="_blank" alt="Forth Step" title="Forth Step"
                                  href="https://youtu.be/Dntn25By1LE" coords="101,334,181,418" shape="rect">
                        </map>
                    </div>

                </div>

                <div class="modal-footer justify-content-center text-center">
                    <a href="{{url('gallery/create')}}" class="btn btn-success modal-button">انضمام</a>
                    <button data-dismiss="modal" type="button" class="btn btn-primary modal-button">اغلاق</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#bookings-ad').modal("show")
    </script>
@endif

<div class="container-fluid"
     style="background-color: rgba(255,255,255,.75);border-radius: 50px 50px 0 0;text-align: center;margin-top: 25px;padding: 10px;">
    @auth
        <a class="floating-credit tooltip-container" data-toggle="tooltip" title="المحفظه" href="{{url('credit')}}">
            <i class="icon_wallet"></i>
        </a>
    @endauth

    {{--    <a class="floating-whatsapp" target="_blank" href="https://wa.me/{{cache()->get('settings')['whatsapp']}}">--}}
    <a class="floating-whatsapp" target="_blank" href="https://wa.me/+966548408369">
        <i class="fab fa-whatsapp"></i>
    </a>

    <footer id="footer" style="height: 50px;font-size:12px;display: flex;align-items: center;justify-content: center;">
        <span>
{{--            <img src="{{asset('images/logo-footer1.png')}}" alt="First Platinum Logo" />--}}
            <img src="{{asset('images/logo-footer2.png')}}" alt="First Platinum Logo"/>
{{--            <img src="{{asset('images/logo-footer.png')}}" style="max-width: 100px;" alt="First Platinum Logo" />--}}
        </span>
    </footer>
</div>

{{--<script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>--}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/js/all.min.js"></script>
{{--<script src="https://kit.fontawesome.com/156ca9ced9.js" crossorigin="anonymous"></script>--}}
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>

{{--<script src="{{asset('js/app.js')}}"></script>--}}
<script>
    // $(".selectize-single").select2();

    $('.alert-dismissible .close-button').on('click', function (e) {
        e.preventDefault();

        $(this).parents('.alert').fadeOut()
    })

    $('.tooltip-container').tooltip();

</script>

@yield('scripts')

</body>
</html>
