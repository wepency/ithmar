@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        @can('can view contract requests')
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">طلبات العقود</h3>
                        </div>

                        <div class="box-body">

                            @include('admin.layouts.messages')

                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-striped mt-0">
                                    <thead>
                                    <tr>
                                        <th>رقم العقد</th>
                                        <th>المستثمر</th>
                                        <th>القطاع</th>
                                        <th>الشاطئ</th>
                                        <th>مدة الحجز</th>
                                        <th>قيمة الايجار</th>
                                        <th>باركود المستأجر</th>
                                        <th>المرافق</th>
                                        <th>العقد</th>
                                        <th>تعديل</th>

                                        @can('can control contract requests')
                                            <th>قبول / رفض</th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($rows as $row)
                                        <tr>
                                            <td>{{$row->code}}</td>
                                            <td>{{@$row->user->name ?? ''}}</td>
                                            <td>{{@$row->sector->sector_name ?? ''}}</td>
                                            <td>{{@$row->beach->beach ?? ''}}</td>
                                            <td>
                                                <p class="text-center">من: {{format_date($row->from)}}</p>
                                                <p class="text-center">إلى: {{format_date($row->to)}}</p>
                                            </td>
                                            {{--                                        <td>{{$row->to}}</td>--}}
                                            <td>{{$row->rent_value}}</td>
                                            <td>
                                                <a href="#" data-toggle="modal" data-target="#tenant-barcode-{{$row->id}}" style="margin-bottom: 20px"><i class="fa fa-eye"></i></a>

                                                <div id="tenant-barcode-{{$row->id}}" class="modal fade" role="dialog">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close pull-left" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title">باركود المستخدم</h4>
                                                            </div>

                                                            <div class="modal-body">
                                                                <div class="barcode">
                                                                    <img src="{{asset('uploads/'.$row->attachment_1)}}" style="max-width: 100%;max-height: 300px" alt="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="#" data-toggle="modal" data-target="#with-tenant-barcode-{{$row->id}}" style="margin-bottom: 20px"><i class="fa fa-eye"></i></a>

                                                <div id="with-tenant-barcode-{{$row->id}}" class="modal fade" role="dialog">

                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close pull-left" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title">باركود المرافق</h4>
                                                            </div>

                                                            <div class="modal-body">
                                                                <div class="barcode">
                                                                    <img src="{{asset('/uploads/'.$row->attachment_2)}}" style="max-width: 100%;max-height: 300px" alt="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{admin_url('contract/show/'.$row->code.'?back=requests')}}"><i class="fa fa-eye"></i></a>
                                            </td>
                                            <td>
                                                <a href="{{admin_url('contract/'.$row->id.'/edit')}}"><i class="fa fa-edit"></i></a>
                                            </td>
                                            @can('can control contract requests')
                                            <td>
                                                <form onsubmit="return confirm('هل انت متأكد من قبول العقد؟')" action="{{admin_url('contracts/request/'.$row->id.'/accepted')}}" method="POST" style="display: inline-block;margin: 0">
                                                    @csrf
                                                    @method('PUT')

                                                    <button type="submit" class="btn btn-success">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                </form>

                                                <form onsubmit="return confirm('هل انت متأكد من الغاء العقد؟')" action="{{admin_url('contracts/request/'.$row->id.'/reject')}}" method="POST" style="display: inline-block;margin: 0">
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
                    {{$rows->appends(request()->all())->links()}}
                </div>

                <div class="clearfix"></div>
            </div>
        @else
            @include('admin.no-permissions')
        @endcan
    </div>
</section>

@endsection
