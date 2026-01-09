@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ContractM" style="margin-bottom: 20px">إضافة صلاحية جديدة <i class="fa fa-plus-circle"></i></button>
                </div>
                
                <div class="col-md-12 col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">الصلاحيات</h3>
                        </div>

                        <div class="box-body">

                            @include('admin.layouts.messages')

                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-striped mt-0">
                                    <thead>
                                    <tr>
                                        <th>مجموعة الصلاحيات</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($rows as $row)
                                        <tr>
                                            <td>{{$row->main_name}}</td>

                                            <td>
                                                <a href="{{admin_url('permissions/'.$row->id)}}"><i class="fa fa-history"></i></a>
                                                    
                                                <a href="{{admin_url('permissions/'.$row->id.'/edit')}}"><i class="fa fa-edit"></i></a>
                                                        
                                                @if($row->name != 'mdyr-aaam' && $row->name != 'mdyr-ktaaa')
                                                    <form onsubmit="return confirm('هل انت متأكد من حذف الصلاحيات؟ سيتسبب في مزعها من جميع المستخدمين المرتبطين بها.')" id="permission-{{$row->id}}" action="{{admin_url('permissions/'.$row->id)}}" method="post" style="margin: 0;display: inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button style="background-color: transparent;padding: 0!important;" type="submit"><i class="fa fa-trash"></i></button>
                                                    </form>
                                                @endif
                                            </td>
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
    </div>

    @if(is_admin())
        <div id="ContractM" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">

                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close pull-left" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">إضافة صلاحية جديدة</h4>
                    </div>
                    <div class="modal-body">
                        <div class="box box-body box-primary">
                            <div class="">
                                <h3 class="box-title">صلاحيات</h3>
                            </div>

                            @include('admin.layouts.messages')

                            <form action="{{admin_url('permissions')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group col-12">
                                    <label for="role_name"> اسم الصلاحية </label>

                                    <input type="text" id="role_name" class="form-control" name="role" style="width: 100%;" required />
                                </div>

                                <div class="form-group col-12">
                                    @foreach($permissions as $key => $permission)
                                        <h3>{{$key}}</h3>
                                        <br />
                                        @foreach($permission as $key => $term)
                                            @if(isset($term['name']) && isset($term['value']))
                                                <div class="form-inline">
                                                    <input type="checkbox" id="{{$term['value']}}" name="permission[]" value="{{$term['value']}}" {{old('permission') ? (in_array($term['value'], old('permission')) ? 'checked' : '') : ''}} />
                                                    <label for="{{$term['value']}}">{{$term['name']}}</label>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endforeach
                                </div>

                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary" value="إضافة الصلاحيات" />
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @endif
        </section>
@endsection
