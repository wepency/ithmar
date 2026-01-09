@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        @can('can view clients')
            <div class="row">
                @if(is_admin())
                    @can('can add clients')
                        <div class="col-md-12 col-xs-12">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ClientM" style="margin-bottom: 20px">إضافه خدمة <i class="fa fa-plus-circle"></i></button>
                        </div>
                    @endcan
                @endif

                <div class="col-md-12 col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">الخدمات</h3>
                        </div>

                        <div class="box-body">
                            @include('admin.layouts.messages')

                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>الخدمة</th>
                                        <th>السعر</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($rows as $row)
                                        <tr>
                                            <td>{{$row->service_name}}</td>
                                            <td>{{$row->price}}</td>

                                            <td>
                                                @can('can edit clients')
                                                    <a href="" data-toggle="modal" data-target="#edit-service-{{$row->id}}"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                                @endcan

                                                @can('can delete beaches')
                                                    <form id="destory-{{$row->id}}"
                                                          class="delete" style="display:inline-block"
                                                          action="{{ route('admin.services.destroy',$row->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="submit"  class="btn btn-danger" value="حذف" />
                                                    </form>
                                                @endcan
                                            </td>
                                        </tr>

                                        @if(is_admin())
                                            @can('can edit clients')
                                                <div id="edit-service-{{$row->id}}" class="modal fade" role="dialog">
                                                    <div class="modal-dialog modal-lg">

                                                        <!-- Modal content-->
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close pull-left" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title">تعديل الخدمة</h4>
                                                            </div>
                                                            <div class="modal-body">

                                                                <div class="box box-body box-primary">

                                                                    <form action="{{route('admin.services.update', $row->id)}}" method="post">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <div class="row">
                                                                            <div class="form-group col-md-6 col-xs-9 ">
                                                                                <label for="user-name-{{$row}}">اسم الخدمة</label>
                                                                                <input id="user-name-{{$row}}" type="text" class="form-control" name="service_name" value="{{old('service_name') ?? $row->service_name}}" placeholder="" style="width: 100%;" />

                                                                            </div>
                                                                            <div class="form-group col-md-6 col-xs-9 ">
                                                                                <label for="phonenumber-{{$row}}">السعر</label>
                                                                                <input id="phonenumber-{{$row}}" type="number" class="form-control" name="price" value="{{old('price') ?? $row->price}}" style="width: 100%;" />
                                                                            </div>
                                                                        </div>

                                                                        <input type="submit" class="btn btn-success" value="تعديل" />
                                                                    </form>
                                                                </div>
                                                            </div>

                                                        </div>

                                                    </div>
                                                </div>
                                            @endcan
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{$rows->appends(request()->all())->links()}}
                        </div>
                    </div>

                    @if(is_admin())
                        @can('can add clients')
                            <div id="ClientM" class="modal fade" role="dialog">
                                <div class="modal-dialog modal-lg">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close pull-left" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">إضافة خدمة</h4>
                                        </div>
                                        <div class="modal-body">

                                            <div class="box box-body box-primary">

                                                <form action="{{route('admin.services.store')}}" method="post">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="form-group col-md-6 col-xs-9 ">
                                                            <label for="service_name"> اسم الخدمة </label>
                                                            <input id="service_name" type="text" class="form-control" name="service_name" value="{{old('service_name')}}" style="width: 100%;" />

                                                        </div>
                                                        <div class="form-group col-md-6 col-xs-9 ">
                                                            <label for="price">السعر</label>
                                                            <input id="price" type="number" class="form-control" name="price" value="{{old('price')}}" style="width: 100%;" />
                                                        </div>
                                                    </div>

                                                    <input type="submit" class="btn btn-success" value="اضافة" />
                                                </form>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        @endcan
                    @endif
                </div>
            </div>
        @else
            @include('admin.no-permissions')
        @endcan
    </div>
@endsection
