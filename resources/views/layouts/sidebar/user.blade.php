<nav class="sidebar" id="user-sidebar">

    <!-- close sidebar menu -->
    <div class="dismiss">
        <i class="icon_close"></i>
    </div>

    <div class="logo">
        <h5>الملف الشخصي</h5>
    </div>

    <ul class="list-unstyled menu-elements">
        <li>
            <a href="{{url('user/data')}}">
                <div class="row m-0">
                    <div class="col-3">
                        <i class="fa fa-user-o"></i>
                    </div>

                    <div class="col-9 content">
                        الملف الشخصي
                    </div>
                </div>
            </a>
        </li>

        <li>
            <a href="{{url('user/bank')}}">
                <div class="row m-0">
                    <div class="col-3">
                        <i class="fa fa-user-o"></i>
                    </div>

                    <div class="col-9 content">
                        الحسابات البنكية
                    </div>
                </div>
            </a>
        </li>

        @if(is_admin())
            <li>
                <a href="{{admin_url('/')}}">
                    <div class="row m-0">
                        <div class="col-3">
                            <i class="fa fa-dashboard"></i>
                        </div>

                        <div class="col-9 content">
                            لوحة التحكم
                        </div>
                    </div>
                </a>
            </li>
        @endif

        <li>
            <a href="#" onclick="document.getElementById('logout-form').submit()">
                <div class="row m-0">
                    <div class="col-3">
                        <i class="fas fa-sign-out-alt"></i>
                    </div>

                    <div class="col-9 content">
                        تسجيل الخروج
                    </div>
                </div>

                <form id="logout-form" style="display: none;margin: 0!important;" action="{{route('front.logout')}}" method="post">
                    @csrf
                </form>
            </a>
        </li>
    </ul>
</nav>
