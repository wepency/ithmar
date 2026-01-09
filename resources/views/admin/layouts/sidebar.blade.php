<ul class="sidebar-menu" data-widget="tree">

  <li class=""><a href="{{admin_url()}}"><i class="fa fa-pie-chart"></i> <span>لوحة المعلومات</span></a></li>

  @if(is_admin())
      <li class="treeview">
        <a href="#">
          <i class="fa fa-university"></i>
          <span>القطاعات</span>
        </a>
        <ul class="treeview-menu menu-open" style="display: block;">
          <li><a href="{{route('admin.sector.index')}}"><i class="fa fa-circle-o"></i>القطاعات</a></li>
          <li><a href="{{route('admin.beaches.index')}}"><i class="fa fa-circle-o"></i>الشواطئ</a></li>
          <li><a href="{{route('admin.units.index')}}"><i class="fa fa-circle-o"></i>الوحدات</a></li>
          <li><a href="{{route('admin.requests')}}"><i class="fa fa-circle-o"></i>طلبات التأهيل</a></li>
        </ul>
      </li>
  @else
        <li class="treeview active">
            <a href="#">
                <i class="fa fa-university"></i>
                <span>القطاع</span>
            </a>
            <ul class="treeview-menu menu-open" style="display: block;">
                <li><a href="{{route('admin.beaches.index')}}"><i class="fa fa-circle-o"></i>الشواطئ</a></li>
                <li><a href="{{route('admin.units.index')}}"><i class="fa fa-circle-o"></i>الوحدات</a></li>
            </ul>
        </li>
  @endif

  <li><a href="{{route('admin.contract.index')}}"><i class="fa fa-calendar-minus-o"></i> <span>العقود </span></a></li>

  @if(is_admin())
        <li><a href="{{route('admin.services.index')}}"><i class="fa fa-server"></i> <span>الخدمات </span></a></li>
        <li><a href="{{url('dashboard/contracts/requests')}}"><i class="fa fa-file"></i> <span>طلبات العقود</span> <div class="sidebar-badge-big">{{cache()->get('contracts-count')}}</div></a></li>
  @endif

  <li><a href="{{route('admin.users.index')}}"><i class="fa fa-users"></i> <span>الحسابات </span></a></li>
  <li><a href="{{url('dashboard/reports')}}"><i class="fa fa-file-text-o"></i> <span>التقارير </span></a></li>

  @can('can view bonds')
        <li><a href="{{admin_url('bonds')}}"><i class="fa fa-paste"></i> <span>السندات </span></a></li>
  @endcan

  @if(is_admin())
    <li><a href="{{admin_url('permissions')}}"><i class="fa fa-lock"></i><span>الصلاحيات </span></a></li>

    @can('can view settings')
        <li><a href="{{url('dashboard/settings')}}"><i class="fa fa-gear"></i><span>الاعدادات </span></a></li>
    @endcan

      @if(auth()->id() == 75)
            <li><a href="{{url('dashboard/settings/notifications')}}"><i class="fa fa-bell"></i><span>الاشعارات </span></a></li>
      @endif
  @endif

</ul>
