<nav class="sidebar" id="mobile-sidebar">

    <!-- close sidebar menu -->
    <div class="dismiss">
        <i class="icon_close"></i>
    </div>

    <div class="logo">
        <h5>القائمة</h5>
    </div>

    <ul class="list-unstyled menu-elements">
        <li>
            <a href="{{url('contracts/add')}}">
                <div class="row m-0">
                    <div class="col-3 sidebar-icon">
                        <i class="icon_plus"></i>
                    </div>

                    <div class="col-9 content">إنشاء عقد</div>
                </div>
            </a>
        </li>

        <li>
            <a href="{{url('request')}}">
                <div class="row m-0">
                    <div class="col-3 sidebar-icon">
                        <i class="icon_plus-box"></i>
                    </div>

                    <div class="col-9 content">طلب تأهيل</div>
                </div>
            </a>
        </li>

        <li>
            <a href="{{url('contracts')}}">
                <div class="row m-0">
                    <div class="col-3 sidebar-icon">
                        <i class="icon_documents_alt"></i>
                    </div>

                    <div class="col-9 content">العقود</div>
                </div>
            </a>
        </li>

        <li>
            <a href="{{url('all-requests')}}">
                <div class="row m-0">
                    <div class="col-3 sidebar-icon">
                        <i class="icon_building"></i>
                    </div>

                    <div class="col-9 content">الوحدات</div>
                </div>
            </a>
        </li>
    </ul>
</nav>
