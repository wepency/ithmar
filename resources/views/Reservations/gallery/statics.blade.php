<div class="row mb-4">
    <div class="col-xl-4 col-sm-6 stats-container">
        <a href="{{route('gallery.create')}}" style="height: 100%" class="card card-stats">
            <div class="card-content">
                <div class="card-body">
                    <div class="media success d-flex align-items-center">
                        <div class="align-self-center">
                            <i class="icon_plus"></i>
                        </div>
                        <div class="media-body text-left">
                            <h5 class="mb-0">أضف صور وحدة</h5>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-4 col-sm-6 stats-container">
        <a class="card card-stats">
            <div class="card-content">
                <div class="card-body">
                    <div class="media primary d-flex">
                        <div class="align-self-center">
                            <i class="icon_check"></i>
                        </div>
                        <div class="media-body text-left">
                            <h3>{{$active_count}}</h3>
                            <span>الوحدات الفعالة</span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-4 col-sm-6 stats-container">
        <a class="card card-stats">
            <div class="card-content">
                <div class="card-body">
                    <div class="media warning d-flex">
                        <div class="align-self-center">
                            <i class="icon_error-triangle_alt"></i>
                        </div>
                        <div class="media-body text-left">
                            <h3>{{$pending_count}}</h3>
                            <span>وحدات قيد الموافقة</span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
