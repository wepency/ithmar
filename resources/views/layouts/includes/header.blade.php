@if(!auth()->check() || !is_factor_auth())
    <header style="margin-bottom: 50px;">
        <nav class="navbar navbar-icon-top navbar-expand-lg">
            <div class="container">

                <div class="navbar-brand" href="{{url('/')}}">
                    <a href="{{url('/')}}">
                        <img src="{{asset('images/ithmar-logo.png')}}" alt="Durrah" />
                    </a>
                </div>

                <div class="main-nav">
                    <ul class="navbar-nav main-nav-menu mr-auto"></ul>

                    <ul class="navbar-nav notifications-bar">
                        <li class="nav-item">
                            <a class="nav-link" href="{{url('/')}}">
                                <i class="fa-icon fa fa-user-o"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
@else
    <header style="margin-bottom: 50px;">
        <nav class="navbar navbar-icon-top navbar-expand-lg">
            <div class="container">

                <div class="navbar-brand" href="{{url('/')}}">
                    <button class="navbar-toggler nav-item open-menu" type="button" data-target="mobile-sidebar">
                        <i class="icon_menu"></i>
                    </button>

                    <a href="{{url('/')}}">
                        <img src="{{asset('images/ithmar-logo.png')}}" alt="Durrah" />
                    </a>
                </div>

                <div class="main-nav">
                    <ul class="navbar-nav main-nav-menu mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{url('contracts/add')}}">
                                <i class="fa-icon icon_plus"></i>
                                <span>انشاء عقد</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{url('request')}}">
                                <i class="fa-icon icon_plus-box"></i>
                                <span>طلب تأهيل</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{url('contracts')}}">
                                <i class="fa-icon icon_documents_alt"></i>
                                <span>العقود</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{url('all-requests')}}">
                                <i class="fa-icon icon_building"></i>
                                <span>الوحدات</span>
                            </a>
                        </li>

                    </ul>

                    <ul class="navbar-nav notifications-bar">
                        <li class="nav-item">
                            <a class="nav-link open-menu" data-target="notifications-sidebar" href="#">
                                <i class="fa-icon far fa-bell">
                                    @if(cache()->get('notifications-count-'.auth()->id()) > 0)
                                        <span class="badge badge-danger">{{cache()->get('notifications-count-'.auth()->id())}}</span>
                                    @endif
                                </i>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link open-menu" data-target="units-sidebar" href="#">
                                <i class="fa-icon icon_error-triangle_alt">
                                    <span class="badge badge-warning">0</span>
                                </i>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link open-menu" data-target="user-sidebar" href="#">
                                <i class="fa-icon fa fa-user-o"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    @include('layouts.sidebar.user')
    @include('layouts.sidebar.notifications')
    @include('layouts.sidebar.units-status')
    @include('layouts.sidebar.mobile-sidebar')
@endif
