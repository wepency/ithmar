<nav class="sidebar" id="notifications-sidebar">

    <!-- close sidebar menu -->
    <div class="dismiss">
        <i class="icon_close"></i>
    </div>

    <div class="logo">
        <h5>الإشعارات</h5>
    </div>

    <ul class="list-unstyled menu-elements">
        @foreach(cache()->get('get-notifications-'.auth()->id()) as $notification)
        <li class="{{is_null($notification->read_at) ? 'unread' : ''}}">
            <a href="{{isset($notification->data['link']) ? $notification->data['link'].'?not='.$notification->id : ''}}">
                <div class="row m-0">
                    <div class="col-3 sidebar-icon">
                        <i class="icon_document_alt"></i>
                    </div>

                    <div class="col-9 content">
                        <p class="head">{{$notification->data['message']}}</p>
                        <p class="time">{{$notification->created_at->diffForHumans(\Carbon\Carbon::now())}}</p>
                    </div>
                </div>
            </a>
        </li>
        @endforeach
    </ul>
</nav>
