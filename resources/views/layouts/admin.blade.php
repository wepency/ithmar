<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title')</title>

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="stylesheet" href=" {{asset('plugins/datepicker/datepicker3.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css') }}">
    <!-- Theme style -->
    <link href=" {{asset('dist/css/Admin.css') }}" rel="stylesheet" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins
           folder instead of downloading all of them to reduce the load. -->
    <link href="{{asset('dist/css/skins/_all-skins.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('dist/css/style_arabic.css') }}">
    <link href="{{asset('plugins/datatables/jquery.dataTables.min.css') }}" rel="stylesheet" />

    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <link rel="stylesheet" href="{{asset('css/font-awesome.min.css') }}">

    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style-22ar.css') }}">

    <link href="{{asset('css/line-awesome-font-awesome.css') }}" rel="stylesheet" />
    <link href="{{asset('css/line-awesome.css') }}" rel="stylesheet" />

    <link rel="stylesheet" href="{{asset('css/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{asset('css/jquery.Wload.css') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        .datepicker.datepicker-dropdown{
            left: initial !important;
        }
    </style>
    <script src=" {{asset('plugins/jQuery/jQuery-2.2.0.min.js') }}"></script>
{{--    <script src=" {{asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>--}}

    <script src=" {{asset('js/SideBar.js') }}"></script>
    <script src="{{asset('js/sharedLayout.js') }}"></script>

{{--    <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>--}}

    <script src=" {{asset('Scripts/data.js') }}"></script>
    <script>

        $(function () {


            $('#datepicker').datepicker({
                autoclose: true,
                format:'yyyy/mm/dd'
            });

            $('#datepicker1').datepicker({
                autoclose: true,
                format: 'yyyy/mm/dd'

            });
            $('#datepicker2').datepicker({
                autoclose: true,
                format: 'yyyy/mm/dd'

            });
            $("#dateFrom").datepicker({

                maxDate: '0',
                format:'yyyy/mm/dd'

            });
            $("#dateFrom").keypress(function (event) { event.preventDefault(); });

            $("#dateTo").datepicker({
                maxDate: '0',
                format: 'yyyy/mm/dd'
            });

            $("#dateTo").keypress(function (event) { event.preventDefault(); });

            $('.select2').select2();
        })

    </script>

    @yield('styles')

    <style>
        label {
            font-size: 11px;
        }
        @media (min-width: 768px) {
            .baseline-row {
                display: flex;
                align-items: center;
            }
        }
        .width-100{
            width: 100%;
        }
        .btn,
        label{
            font-family: 'Cairo', sans-serif;
        }
        .info-box:hover{
            text-decoration: none;
            opacity: .8;
        }
        .info-box-icon i{
            font-size: 45px !important;
            color: #ffffff !important;
        }
        a:hover{
            cursor: pointer;
        }
        .box-body > .table{
            margin-top: 0;
        }
        a,
        table tr{
            font-family: 'Droid Arabic Kufi', sans-serif !important;
        }
        .sidebar-badge-big{
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex !important;
            justify-content: center;
            align-items: center;
            position: absolute;
            top: 0;
            left: 10px;
            background-color: #e74c3c;
            color: #fff;
            font-size: 12px;
        }
        /* The switch - the box around the slider */
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }
        .switch:hover {
            cursor: pointer;
            opacity: .8;
        }

        /* Hide default HTML checkbox */
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* The slider */
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>



    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    {{--    <script src="{{asset('Scripts/data.js')}}"></script>--}}

    <script>

        $(function () {
            $('#datepicker').datepicker({
                autoclose: true,
                format:'yyyy/mm/dd'
            });
            $('#datepicker1').datepicker({
                autoclose: true,
                format: 'yyyy/mm/dd'

            });
            $('#datepicker2').datepicker({
                autoclose: true,
                format: 'yyyy/mm/dd'
            });
            $('.select2').select2();
        })

    </script>

    <style>
        @media (min-width: 768px) {
            .sidebar-mini.sidebar-collapse .sidebar-menu > li:hover > a > span {
                background-color: transparent !important;
            }

            .sidebar-mini.sidebar-collapse .content-wrapper, .sidebar-mini.sidebar-collapse .right-side, .sidebar-mini.sidebar-collapse .main-footer {
                width: calc(100% - 200px) !important;
            }

            .sidebar-mini.sidebar-collapse .sidebar-menu > li:hover > a > span {
                padding: 0;
            }
        }
        .skin-blue .sidebar-menu > li > a {
            border-left: 3px solid transparent;
            font-family: 'Cairo', sans-serif;
        }
        .sidebar-mini.sidebar-collapse .sidebar-menu > li{
            cursor: pointer;
        }
        .nav__menu .nav__link{
            width: 100%;
        }
        .notifications-menu li.inactive a{
            background-color: #f9f9f9;
            display: block;
            color: #fff;
        }
    </style>
</head>



<body class="hold-transition skin-blue sidebar-mini  sidebar-collaps" id="body">
<div class="wrapper">
    <!-- Main Header -->
    <header class="main-header">
        <!-- Logo -->
        <a href="{{admin_url()}}" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->

            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>Ith</b>mar</span>
        </a>


        <nav id="nav" class="nav nav-mobile" role="navigation" >
            <ul class="nav__menu" id="menu" tabindex="-1" aria-label="main navigation" hidden>
                <li class="nav__link"><a class="nav__link" href="{{admin_url()}}"> <span>لوحة المعلومات</span><i class="fa fa-pie-chart"></i></a></li>
                <li class="nav__link"><a class="nav__link" href="{{admin_url('user/edit')}}"> <span>تعديل الملف</span><i class="fa fa-user"></i></a></li>
                @if(is_admin())
                    <li class="nav__link"><a class="nav__link" href="{{admin_url('sector')}}"> <span>القطاعات </span> <i class="fa fa-university"></i>        </a></li>
                @endif
                    <li class="nav__link"><a class="nav__link" href="{{admin_url('beaches')}}"> <span>الشواطئ </span> <i class="fa fa-university"></i>        </a></li>
                    <li class="nav__link"><a class="nav__link" href="{{admin_url('units')}}"> <span>الوحدات </span> <i class="fa fa-university"></i>        </a></li>
                @if(is_admin())
                    <li class="nav__link"><a class="nav__link" href="{{admin_url('requests')}}"> <span>طلبات التأهيل </span> <i class="fa fa-file-text-o"></i>        </a></li>
                @endif

                @can('can view contracts')
                    <li class="nav__link"><a class="nav__link" href="{{admin_url('contract')}}"> <span>العقود </span>  <i class="fa fa-calendar-minus-o"></i> </a></li>
                @endcan

                <li class="nav__link"><a class="nav__link" href="{{admin_url('users')}}"> <span>الحسابات </span> <i class=" fa fa-users"></i></a></li>
                <li class="nav__link"><a class="nav__link"  href="{{admin_url('reports')}}"><span>التقارير </span> <i class=" fa fa-file-text-o "></i></a></li>

                @can('can view bonds')
                    <li class="nav__link"><a class="nav__link"  href="{{admin_url('bonds')}}"><span>السندات </span> <i class=" fa fa-paste"></i></a></li>
                @endcan

                @if(is_admin())
                    <li class="nav__link"><a class="nav__link"  href="{{route('admin.services.index')}}"><span>الخدمات </span> <i class=" fa fa-server"></i>  </a></li>
                    @can('can view permissions')
                        <li class="nav__link"><a class="nav__link"  href="{{admin_url('permissions')}}"><span>الصلاحيات </span> <i class=" fa fa-lock"></i>  </a></li>
                    @endcan

                    @can('can view settings')
                        <li class="nav__link"><a class="nav__link"  href="{{admin_url('settings')}}"><span>الإعدادات </span> <i class=" fa fa-gears"></i>  </a></li>
                    @endcan
                    <li class="nav__link"><a class="nav__link"  href="{{admin_url('contracts/requests')}}"><span>طلبات العقود </span> <i class=" fa fa-file"></i>  </a></li>
                @endif
                <li class="nav__link"><a class="nav__link"  href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><span>تسجيل الخروج </span> <i class=" fa fa-sign-out"></i>  </a></li>
            </ul>

            <!-- MENU TOGGLE BUTTON -->
            <a href="#nav" class="nav__toggle" role="button" aria-expanded="false" aria-controls="menu">
                <svg class="menuicon" xmlns="https://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 50 50">
                    <title>Toggle Menu</title>
                    <g>
                        <line class="menuicon__bar" x1="13" y1="16.5" x2="37" y2="16.5"/>
                        <line class="menuicon__bar" x1="13" y1="24.5" x2="37" y2="24.5"/>
                        <line class="menuicon__bar" x1="13" y1="24.5" x2="37" y2="24.5"/>
                        <line class="menuicon__bar" x1="13" y1="32.5" x2="37" y2="32.5"/>
                        <circle class="menuicon__circle" r="23" cx="25" cy="25" />
                    </g>
                </svg>
            </a>

            <div class="splash"></div>

        </nav>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation" >

            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">

                    <!-- User Account Menu -->
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- The user image in the navbar-->
                            <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="user-image" alt="User Image">
                            <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <span class="hidden-xs">{{ auth()->user()->name }} - {{ auth()->user()->role }} </span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->
                            <li class="user-header">
                                <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">

                                <p>
                                    {{ auth()->user()->name }}
                                    <small>{{auth()->user()->created_at->diffForHumans()}} عضو من</small>
                                </p>
                            </li>

                            <li class="user-footer">
                                <div class="pull-left">
{{--                                    @if(is_admin())--}}
                                        <a href="{{admin_url('user/edit')}}" class="btn btn-default btn-flat">تعديل الملف</a>
{{--                                    @else--}}
{{--                                        <a href="{{admin_url()}}" class="btn btn-default btn-flat">لوحة التحكم</a>--}}
{{--                                    @endif--}}
                                </div>

                                <div class="pull-right">
                                    <a href="{{ route('admin.logout') }}" class="btn btn-sm btn-light-primary font-weight-bolder py-2 px-5" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">تسجيل الخروج</a>
                                </div>
                            </li>
                        </ul>
                    </li>

                    <?php
                        $user = auth()->user();

                        $notifications = $user->notifications()->limit(10)->get();
                        $notifications_count = $user->unreadNotifications()->count();
                    ?>

                    <li class="dropdown notifications-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-warning">{{$notifications_count}}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <ul class="menu">
                                    @if(count($notifications) == 0)
                                        <p style="padding: 10px;text-align: center">لا توجد إشعارات</p>
                                    @endif

                                    @foreach ($notifications as $notification)
                                        <?php
                                            $link = isset($notification->data['link']) ? $notification->data['link'].'?not='.$notification->id : admin_url('notifications');
                                        ?>
                                        <li class="{{!$notification->read_at ? 'inactive' : ''}}">
                                            <a href="{{$link}}">
                                                {{@$notification->data['message'] ?? ''}}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                            <li class="footer"><a href="{{admin_url('notifications')}}">عرض الكل</a></li>
                        </ul>
                    </li>

                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>

                    <!-- Control Sidebar Toggle Button -->

                </ul>
            </div>
        </nav>
    </header>
    <aside class="sidebar-mini sidebar-collapse main-sidebar">
        <section class="sidebar">
        @include('admin.layouts.sidebar')
        </section>
    </aside>

    <div class="content-wrapper">

        <div id="over" style="position:absolute; top:25%; left:50% ; z-index:9999"></div>

        <div id="pageContent">
            <section class="content" style="direction:rtl">

                @yield('content')

            </section>

            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

            <script src="{{ asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
            <script src="{{ asset('bower_components/bootstrap-timepicker/js/bootstrap-timepicker.js') }}"></script>

            <script src="{{ asset('bower_components/select2/dist/js/select2.full.min.js') }}"></script>
{{--            <script src="{{ asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>--}}

            <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>

            <!-- AdminLTE for demo purposes -->
            <script src="{{ asset('dist/js/demo.js') }}"></script>

{{--            <script src="{{asset('js/app.js')}}"></script>--}}

{{--            <script>--}}
{{--                Echo.channel('contractRequest')--}}
{{--                    .listen('NewContract', (e) => {--}}
{{--                        console.log('done')--}}
{{--                    });--}}
{{--            </script>--}}

            <script>

                $(document).on('click','.delete', function (e){
                    if( confirm('تاكيد ؟') )
                    {
                        return true;
                    }else{
                        return false;
                    }
                });

                $(function () {
                    //Initialize Select2 Elements
                    $('.select2').select2()

                    $('#datepicker').datepicker({
                        autoclose: true
                    });

                    $('[data-toggle="tooltip"]').tooltip()
                });
            </script>

    @yield('scripts')
</body>
</html>
