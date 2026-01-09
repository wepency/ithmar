@extends('layouts.front-page')

@section('styles')
    <style>
        .home-container a{
            text-align: center;
            border-radius: 8px;
            padding: 5px;
            font-weight: 500;
            height: 100px;
            display: flex;
            flex-direction: column;
            overflow: hidden;

            transition: all .25s ease-in-out;
            -webkit-transition: all .25s ease-in-out;
            -moz-transition: all .25s ease-in-out;
            -o-transition: all .25s ease-in-out;
        }
        .home-container a:hover{
            opacity: .9;
            text-decoration: none;
        }
        .home-container a i{
            font-size: 35px;
        }
        .investor-buttons{
            display: flex;
        }
        .investor-buttons a{
            width: calc(33.333% - 5px);
            background-color: #fff;
            box-shadow: 0 0 5px rgb(0 0 0 / 10%);
            -webkit-box-shadow: 0 0 5px rgb(0 0 0 / 10%);
            -moz-box-shadow: 0 0 5px rgb(0 0 0 / 10%);
            color: #333;
            margin: 0 5px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .investor-buttons a:hover{
            transform: scale(1.2);
            -webkit-transform: scale(1.2);
            -moz-transform: scale(1.2);
        }

         .investor-buttons a i{
            color: #fdcb6e
        }
        .investor-buttons .text {
            display: block;
        }
        .investor-buttons img{
            width: 40px
        }
        .investor-buttons a:last-child{
            margin-left: 0;
        }
        .investor-buttons a:first-child{
            margin-right: 0;
        }
        .investor-buttons.soon a{
            opacity: .75;
        }
        .mt-button{
            margin-top: 15px;
        }
        
        a[disabled] {
            opacity: .8;
            cursor: no-drop;
        }
    </style>
@endsection

@section('content')
{{--    YDL-428-70004--}}

{{--EmailPassword@#$2021--}}

    <div class="container home-container">

        <!-- Count Cards -->
        <div class="row">
            <div class="col-xl-3 col-sm-6 stats-container">
                <a href="{{url('all-requests')}}" class="card card-stats">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="media success d-flex">
                                <div class="align-self-center">
                                    <i class="icon_building"></i>
                                </div>
                                <div class="media-body text-left">
                                    <h3>{{cache()->get('units-count-'.auth()->id())}}</h3>
                                    <span>عدد الوحدات</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6 stats-container">
                <a href="{{url('contracts')}}" class="card card-stats">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="media primary d-flex">
                                <div class="align-self-center">
                                    <i class="icon_documents_alt"></i>
                                </div>
                                <div class="media-body text-left">
                                    <h3>{{cache()->get('contracts-count-'.auth()->id())}}</h3>
                                    <span>عدد العقود المنفذة</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-3 col-sm-6 col-xs-6 col-12 stats-container">
                <a href="{{url('all-requests?type=expired')}}" class="card card-stats">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="media warning d-flex">
                                <div class="align-self-center">
                                    <i class="icon_error-triangle_alt"></i>
                                </div>
                                <div class="media-body text-left">
                                    <h3>{{cache()->get('invalid-units-count-'.auth()->id())}}</h3>
                                    <span class="long-text">وحدات منتهية الصلاحية</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-3 col-sm-6 stats-container">
                <a href="{{url('all-requests?type=terminated')}}" class="card card-stats">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="media danger d-flex">
                                <div class="align-self-center">
                                    <i class="icon_close_alt2"></i>
                                </div>
                                <div class="media-body text-left">
                                    <h3>{{cache()->get('blocked-units-count-'.auth()->id())}}</h3>
                                    <span>وحدات موقوفة</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="investor-buttons  mt-button">
            <a href="{{url('contracts/add')}}">
                <span class="icon"><i class="icon_plus"></i></span>
                <span class="text">إنشاء عقد</span>
            </a>

            <a href="{{investor_url('contracts?type=signed')}}">
                <span class="icon"><i class="icon_box-checked"></i></span>
                <span class="text">العقود المصدقة</span>
            </a>

            <a href="{{investor_url('contracts?type=active')}}">
                <span class="icon"><i class="icon_document_alt"></i></span>
                <span class="text">العقود القائمة</span>
            </a>
        </div>

        <div class="investor-buttons mt-button">
            <a href="{{url('contracts')}}">
                <span class="icon"><i class="icon_documents_alt"></i></span>
                <span class="text">عرض جميع العقود</span>
            </a>

            <a href="{{url('request')}}">
                <span class="icon"><i class="icon_plus-box"></i></span>
                <span class="text">طلب تأهيل وحدة جديدة</span>
            </a>

            <a href="{{url('services')}}">
                <span class="icon"><i class="icon_briefcase"></i></span>
                <span class="text">الخدمات</span>
            </a>
        </div>

        <div class="investor-buttons mt-button">
            <a href="{{url('all-requests')}}">
                <span class="icon"><i class="icon_calulator"></i></span>
                <span class="text">حالة الوحدة</span>
            </a>

            <a href="{{url('credit')}}">
                <span class="icon"><i class="icon_wallet"></i></span>
                <span class="text">المحفظة</span>
            </a>

            <a style="position:relative;overflow:initial!important" href="{{url('bonds')}}">
                <span class="icon"><i class="icon_folder-alt "></i></span>
                <span class="text">سندات القبض</span>
            </a>
        </div>

        <div class="investor-buttons mt-button">
            <!--<a href="{{route('gallery.index')}}" style="position:relative;overflow:initial!important">-->
            <!--    <span class="badge-new">جديد</span>-->
            <!--    <span class="icon"><i class="icon_image"></i></span>-->
            <!--    <span class="text">صور الوحدة</span>-->
            <!--</a>-->
            
            <a href="{{route('gallery.index')}}" style="position:relative;overflow:initial!important">
                <span class="icon"><i class="icon_image"></i></span>
                <span class="text">صور الوحدة</span>
            </a>

            <!--<a href="{{route('availability.index')}}" style="position:relative;overflow:initial!important">-->
            <!--    <span class="badge-new">جديد</span>-->
            <!--    <span class="icon"><i class="far fa-dollar-sign"></i></span>-->
            <!--    <span class="text">الأسعار و التوافر</span>-->
            <!--</a>-->
            
            <a href="{{route('availability.index')}}" style="position:relative;overflow:initial!important">
                <span class="icon"><i class="far fa-dollar-sign"></i></span>
                <span class="text">الأسعار و التوافر</span>
            </a>

            <!--<a href="{{route('invoices.index')}}" style="position:relative;overflow:initial!important">-->
            <!--    <span class="badge-new">جديد</span>-->
            <!--    <span class="icon"><i class="far fa-file-alt"></i></span>-->
            <!--    <span class="text">الفواتير الشهرية</span>-->
            <!--</a>-->
            
            <a href="{{route('invoices.index')}}" style="position:relative;overflow:initial!important">
                <span class="icon"><i class="far fa-file-alt"></i></span>
                <span class="text">الفواتير الشهرية</span>
            </a>
        </div>

        <div class="investor-buttons mt-button">
            <!--<a href="{{route('online-reservation.index')}}" style="position:relative;overflow:initial!important">-->
            <!--    <span class="badge-new">جديد</span>-->
            <!--    <span class="icon"><i class="icon_book"></i></span>-->
            <!--    <span class="text">حجوزات أونلاين</span>-->
            <!--</a>-->
            
            <a href="{{route('online-reservation.index')}}" style="position:relative;overflow:initial!important">
                <span class="icon"><i class="icon_book"></i></span>
                <span class="text">حجوزات أونلاين</span>
            </a>
        </div>
    </div>
@endsection
