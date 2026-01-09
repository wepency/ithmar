<div class="row mb-4">
    <div class="col-xl-3 col-sm-6 stats-container">
        <a class="card card-stats">
            <div class="card-content">
                <div class="card-body">
                    <div class="media primary d-flex">
                        <div class="align-self-center">
                            <i class="icon_check"></i>
                        </div>
                        <div class="media-body text-left">
                            <h3>{{$all_bookings}}</h3>
                            <span>الحجوزات</span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-3 col-sm-6 stats-container">
        <a class="card card-stats">
            <div class="card-content">
                <div class="card-body">
                    <div class="media warning d-flex">
                        <div class="align-self-center">
                            <i class="icon_error-triangle_alt"></i>
                        </div>
                        <div class="media-body text-left">
                            <h3>{{$waiting_payment_count}}</h3>
                            <span>بانتظار تأكيد العربون</span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-3 col-sm-6 stats-container">
        <a class="card card-stats">
            <div class="card-content">
                <div class="card-body">
                    <div class="media warning d-flex">
                        <div class="align-self-center">
                            <i class="icon_error-triangle_alt"></i>
                        </div>
                        <div class="media-body text-left">
                            <h3>{{$waiting_verification_count}}</h3>
                            <span>بانتظار التفعيل</span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-3 col-sm-6 stats-container">
        <a class="card card-stats">
            <div class="card-content">
                <div class="card-body">
                    <div class="media warning d-flex">
                        <div class="align-self-center">
                            <i class="icon_error-triangle_alt"></i>
                        </div>
                        <div class="media-body text-left">
                            <h3>{{$verified_count}}</h3>
                            <span>مؤكد</span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
