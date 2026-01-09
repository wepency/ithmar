@extends('layouts.admin')

@section('styles')
    <link href="{{asset('css/bootstrap-datetimepicker.css')}}" rel="stylesheet" />

    <style>
        .bootstrap-datetimepicker-widget{
            /*width: 100%;*/
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        @can('can view unit requests')
        <div class="row">
            <div class="col-md-12 col-xs-12"></div>

            <div class="col-md-12 col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">الطلبات</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>القطاع</th>
                                    <th>الشاطئ</th>
                                    <th>الفيلا</th>
                                    <th>الاسم الثلاثي</th>
                                    <th>رقم الجوال</th>
                                    <th>المرفقات</th>
                                    <th>ملاحظات</th>
                                    @can('can control unit requests')
                                        <th>العمليات</th>
                                    @endcan
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($rows as $row)
                                    <tr>
                                        <td>{{$row->sector->sector_name ?? ''}}</td>
                                        <td>{{$row->beach->beach ?? ''}}</td>
                                        <td>{{$row->unit_number}}</td>
                                        <td>{{$row->user->name ?? ''}}</td>
                                        <td>{{$row->user->phonenumber ?? ''}}</td>
                                        <td>
                                            @if(file_exists(public_path('uploads/'.$row->attachment_1)))
                                                <a href="{{asset('uploads/'.$row->attachment_1)}}">المرفق</a>
                                            @endif
                                        </td>
                                        <td>
                                            @if($row->type == 'investor')
                                                مستثمر
                                            @else
                                                مالك
                                            @endif

                                            @if($row->renewed)
                                                <br />
                                                <div class="message">تم التحديث عدد {{$row->count}}</div>
                                            @endif
                                        </td>
                                        @can('can control unit requests')
                                        <td>
                                            <?php
                                                $route = route('admin.request.status',['1' ,$row->id]);
                                                $today = \Carbon\Carbon::today()->format('Y-m-d');
                                            ?>

                                            @if($row->type == 'investor')
                                                <a class="btn btn-success" data-toggle="modal" data-target="#edit-user-{{$row->id}}" href="#">
                                                    <i class="fa fa-check"></i>
                                                </a>

                                                <div id="edit-user-{{$row->id}}" class="modal fade" role="dialog">
                                                    <div class="modal-dialog modal-md">

                                                        <!-- Modal content-->
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close pull-left" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title">تحديد تاريخ الإتاحة</h4>
                                                            </div>
                                                            <div class="modal-body">

                                                                <div class="box box-body box-primary">


                                                                    <form action="{{$route}}" method="post">
                                                                        @csrf
                                                                        @method('PUT')

                                                                        <div class="form-group">
                                                                            <label for="user-name-{{$row->id}}">هذا العقار متاح حتى:</label>
                                                                            <input id="user-name-{{$row->id}}" type="text" class="form-control valid_to" name="valid_to" value="{{old('valid_to')}}" style="width: 100%;" />
                                                                        </div>

                                                                        <input type="submit" class="btn btn-success" value="قبول" />
                                                                    </form>
                                                                </div>
                                                            </div>

                                                        </div>

                                                    </div>
                                                </div>
                                            @else
                                                <form action="{{$route}}" method="POST" style="display: inline-block;margin: 0">
                                                    @csrf
                                                    @method('PUT')

                                                    <button type="submit" class="btn btn-success">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{route('admin.request.status', ['4', $row->id])}}" method="POST" style="display: inline-block;margin: 0">
                                                @csrf
                                                @method('PUT')

                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fa fa-close"></i>
                                                </button>
                                            </form>
                                        </td>
                                        @endcan
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>
        @else
            @include('admin.no-permissions')
        @endcan
    </div>
@endsection

@section('scripts')
    <script src="{{asset('js/moment.min.js')}}"></script>
    <script src="{{asset('js/bootstrap-hijri-datetimepicker.min.js')}}"></script>

    <script>
        $(".valid_to").hijriDatePicker({
            locale:'ar-SA',
            useCurrent: true,
            format:'DD-MM-YYYY',
            hijriFormat:'DD-MM-YYYY',
            hijriText: "عرض التاريخ الهجري",
            gregorianText: "عرض التاريخ الميلادي"
        });

        // $(".valid_to").on('change', function (arg) {
        //     let date = arg.date;
        //     console.log(date.format("YYYY/M/D"))
        //     // $(this).val(date.format("YYYY/M/D"))
        // });
    </script>
@endsection
