{{--@extends('layouts.admin')--}}

{{--@section('styles')--}}
{{--    <style>--}}
{{--        a.share-network-whatsapp {--}}
{{--            background-color: #2ecc71;--}}
{{--            border: 2px solid #2ecc71;--}}
{{--            color: #fff;--}}
{{--            padding: 10px 20px;--}}
{{--            display: inline-block;--}}
{{--            border-radius: 15px;--}}
{{--        }--}}
{{--        .print-pdf{--}}
{{--            border-radius: 15px;--}}
{{--            padding: 10px 20px;--}}
{{--        }--}}
{{--        @media (max-width: 768px) {--}}
{{--            .pdf{--}}
{{--                -webkit-transform: scale(.3);--}}
{{--                -webkit-transform-origin: right top;--}}
{{--                width: 142.857143%;--}}
{{--            }--}}
{{--        }--}}
{{--        @media print {--}}
{{--            .pdf{--}}
{{--                -webkit-transform: scale(1);--}}
{{--            }--}}
{{--        }--}}
{{--        .contract-header{--}}
{{--            font-family: 'Cairo', sans-serif;--}}
{{--        }--}}
{{--    </style>--}}
{{--@endsection--}}

{{--@section('content')--}}
{{--    <div class="container-fluid">--}}
{{--        <a href="{{request()->back == 'requests' ? admin_url('contracts/requests') : admin_url('contract')}}" style="margin-bottom: 20px" class="btn btn-danger">العودة للخلف</a>--}}
{{--    </div>--}}

{{--    <div id="app">--}}
{{--        <router-view />--}}
{{--    </div>--}}
{{--@endsection--}}

{{--@section('scripts')--}}
{{--    <script>--}}
{{--        window.contract_id = {{$contract->code}};--}}
{{--    </script>--}}

{{--    <script src="{{asset('js/app.js')}}"></script>--}}
{{--@endsection--}}

<!doctype html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>عرض العقد | لوحة تحكم اثمار</title>

    <link href=" {{asset('dist/css/Admin.css') }}" rel="stylesheet" />

    <link href="{{asset('dist/css/skins/_all-skins.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('dist/css/style_arabic.css') }}">

    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style-22ar.css') }}">

    <link rel="stylesheet" href="{{asset('css/font-awesome.min.css') }}">

    <style>
        a.share-network-whatsapp {
            background-color: #2ecc71;
            border: 2px solid #2ecc71;
            color: #fff;
            padding: 10px 20px;
            display: inline-block;
            border-radius: 15px;
        }
        .print-pdf{
            border-radius: 15px;
            padding: 10px 20px;
        }
        @media (max-width: 768px) {
            .pdf{
                -webkit-transform: scale(.3);
                -webkit-transform-origin: left top;
                width: 142.857143%;
            }
        }
        @media print {
            .pdf{
                -webkit-transform: scale(1);
            }
        }
        .contract-header{
            font-family: 'Cairo', sans-serif;
        }
    </style>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    {{--@endsection--}}

    {{--@section('content')--}}

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

        <div class="container-fluid" style="text-align: center; margin-top: 10px;">
            <a href="{{request()->back == 'requests' ? admin_url('contracts/requests') : admin_url('contract')}}" class="btn btn-danger">العودة للخلف</a>
        </div>

        <div id="app">
            <router-view />
        </div>
    <script>
        window.contract_id = {{$contract->code}};
    </script>

    <script src="{{asset('js/app.js')}}"></script>
</body>
</html>
