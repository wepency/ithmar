@extends('layouts.admin')

@section('styles')
    <style>
        .info-box-text{
            font-family: 'Droid Arabic Kufi', sans-serif;
        }
        .info-box{
            display: block;
            color: #ffffff;
        }
    </style>
@endsection

@section('scripts')
    <script src="{{ asset('bower_components/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('bower_components/morris.js/morris.min.js') }}"></script>

    <script>
        var donut = new Morris.Donut({
            element: 'sales-chart',
            resize: true,
            colors: ["#3c8dbc",  "#00a65a"],
            data: [
                {label: "محجوز", value: {{$reserved}}},

                {label: "شاغر", value: {{$empty}}}
            ],
            hideHover: 'auto'
        });

        const nav = document.querySelector('#nav');
        const menu = document.querySelector('#menu');
        const menuToggle = document.querySelector('.nav__toggle');
        let isMenuOpen = false;


        // TOGGLE MENU ACTIVE STATE
        menuToggle.addEventListener('click', e => {
            e.preventDefault();
            isMenuOpen = !isMenuOpen;

            // toggle a11y attributes and active class
            menuToggle.setAttribute('aria-expanded', String(isMenuOpen));
            menu.hidden = !isMenuOpen;
            nav.classList.toggle('nav--open');
        });


        // TRAP TAB INSIDE NAV WHEN OPEN
        nav.addEventListener('keydown', e => {
            // abort if menu isn't open or modifier keys are pressed
            if (!isMenuOpen || e.ctrlKey || e.metaKey || e.altKey) {
                return;
            }

            // listen for tab press and move focus
            // if we're on either end of the navigation
            const menuLinks = menu.querySelectorAll('.nav__link');
            if (e.keyCode === 9) {
                if (e.shiftKey) {
                    if (document.activeElement === menuLinks[0]) {
                        menuToggle.focus();
                        e.preventDefault();
                    }
                } else if (document.activeElement === menuToggle) {
                    menuLinks[0].focus();
                    e.preventDefault();
                }
            }
        });

    </script>
@endsection

@section('content')
    <section class="content-header" style="
    box-shadow: 0 1px 1px rgb(0 0 0 / 10%);">
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <h2 style="color: #089de3;">
                    {{\Carbon\Carbon::now()->format('H:i:s')}}
                    <p>{{\Carbon\Carbon::now()->format('D')}} - {{\Carbon\Carbon::now()->format('d / m / Y')}}</p>
                </h2>
            </div>

            @canany('see arrive today' , 'view new reservations' , 'view leaving' , 'view beaches chart')
            <div class="col-md-6 col-xs-12">
                <div class="form-group">
                    <form action="" method="get">
                        <label for=""> الشاطئ </label>
                        <div class="baseline-row">
                            <select class="form-control select2" name="beach" style="width: 100%;">
                                <option value="">اختر شاطي</option>
                                @foreach($rows as $row)
                                    <option {{request()->beach == $row->id ? 'selected' : ''}} value="{{$row->id}}">{{$row->beach}}</option>
                                @endforeach
                            </select>

                            <input type="submit" class="btn btn-success" value="بحث" style="margin-right: 10px" />
                        </div>
                    </form>
                </div>
            </div>
            @endcan
        </div>
    </section>

    <section class="content">
        <div class="cpl-md-12">
            @can('see arrive today')
            <div class="col-md-4 col-sm-6 col-xs-12">
                <a href="{{admin_url('contract?type=enter-today')}}" class="info-box bg-aqua">
                    <span class="info-box-icon"><i class="fa fa-check"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">وصول اليوم</span>
                        <span class="info-box-number">{{$contactEnterTodayCount}}</span>
                    </div>
                </a>
            </div>
            @endcan

            @can('view new reservations')
            <div class="col-md-4 col-sm-6 col-xs-12">
                <a href="{{admin_url('contract?type=last')}}" class="info-box bg-yellow">

                    <span class="info-box-icon"><i class="fa fa-calendar"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">حجوزات جديده</span>
                        <span class="info-box-number">{{$ContractDayCount}}</span>
                    </div>
                </a>
            </div>
            @endcan

            @can('view leaving')
            <div class="col-md-4 col-sm-6 col-xs-12">
                <a href="{{admin_url('contract?type=leave-today')}}" class="info-box bg-red">
                    <span class="info-box-icon"><i class="fa fa-sign-out"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">المغادره</span>
                        <span class="info-box-number">{{$contactLeaveTodayCount}}</span>

                    </div>
                    <!-- /.info-box-content -->
                </a>
                <!-- /.info-box -->
            </div>
            @endcan
        </div>

        <div class="row"></div>

        @can('view beaches chart')
        <div class="container-fluid">
            <div style="margin-top: 40px">
                <!-- DONUT CHART -->
                <div class="box box-danger">

                    <div class="box-header with-border">
                        <h3 class="box-title">حاله الشواطئ </h3>

                    </div>
                    <div class="box-body chart-responsive">
                        <div class="chart" id="sales-chart" style="height: 300px; position: relative;"></div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
        @endcan
    </section>
@endsection
