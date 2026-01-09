@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        @can('can view beaches')
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    @if(is_admin())
                        @can('can add beaches')
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ClientM" style="margin-bottom: 20px">
                                شاطئ جديد
                                <i class="fa fa-plus-circle"></i>
                            </button>
                        @endcan
                    @endif
                </div>
                <div class="col-md-12 col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">الشواطئ</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            @include('admin.layouts.messages')

                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>الإسم </th>

                                        @if(is_admin())
                                            <th>القطاع </th>
                                            <th>العمليات</th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($rows as $row)
                                        <tr>
                                            <td>{{$row->beach}}</td>

                                            @if(is_admin())
                                                <td><a href="{{admin_url('beaches?sector='.$row->sector_id)}}">{{@$row->sector->sector_name ?? ''}}</a></td>
                                                <td>
                                                    @can('can view history beaches')
                                                        <a href="{{admin_url('beaches/'.$row->id)}}"><i class="fa fa-history"></i></a>
                                                    @endcan

                                                    @can('can edit beaches')
                                                        <a href="" data-toggle="modal" data-target="#row-{{$row->id}}"><i class="fa fa-edit"></i></a>
                                                    @endcan

{{--                                                    @can('can delete beaches')--}}
{{--                                                    <form id="destory-{{$row->id}}"--}}
{{--                                                          class="delete" style="display:inline-block"--}}
{{--                                                          action="{{ route('admin.beaches.destroy',$row->id) }}" method="POST">--}}
{{--                                                        @csrf--}}
{{--                                                        @method('DELETE')--}}
{{--                                                        <input type="submit"  class="btn btn-danger" value="حذف" />--}}
{{--                                                    </form>--}}
{{--                                                    @endcan--}}

                                                        @if(is_admin())
                                                            @can('can edit beaches')
                                                                <div id="row-{{$row->id}}" class="modal fade" role="dialog">
                                                                    <div class="modal-dialog modal-lg">
                                                                        <!-- Modal content-->
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close pull-left" data-dismiss="modal">&times;</button>
                                                                                <h4 class="modal-title">تعديل الشاطئ</h4>
                                                                            </div>
                                                                            <div class="modal-body">

                                                                                <div class="box box-body box-primary">
                                                                                    <div class="">
                                                                                        <h3 class="box-title">معلومات أساسية</h3>
                                                                                    </div>

                                                                                    <form action="{{route('admin.beaches.update',$row->id)}}" class="text-right" method="post">
                                                                                        @csrf
                                                                                        @method('PUT')

                                                                                        <div class="row">
                                                                                            <div class="form-group col-md-6 col-xs-9 ">
                                                                                                <label for="edit-name-{{$row->id}}"> الاسم </label>
                                                                                                <input id="edit-name-{{$row->id}}" type="text" class="form-control" name="name" value="{{old('beach') ?? $row->beach}}" placeholder=" " style="width: 100%;" />
                                                                                            </div>

                                                                                            <div class="form-group col-md-6 col-xs-9 ">
                                                                                                <label for="sector-id-{{$row->id}}"> القطاع</label>
                                                                                                <select id="sector-id-{{$row->id}}" class="form-control select2" style="width: 100%;" name="sector_id">
                                                                                                    <option value="">اختر</option>

                                                                                                    @foreach($sectors as $sector)
                                                                                                        <option
                                                                                                            {{$row->sector_id == $sector->id ? 'selected' : ''}}
                                                                                                            value="{{$sector->id}}">{{$sector->sector_name}}</option>
                                                                                                    @endforeach
                                                                                                </select>
                                                                                            </div>

                                                                                            <div class="form-group col-md-6 col-xs-9 ">
                                                                                                <label for="allowed-cars-{{$row->id}}"> عدد السيارات المسموح بها </label>
                                                                                                <input id="allowed-cars-{{$row->id}}" type="number" class="form-control" name="allowed_cars" value="{{old('allowed_cars') ?? $row->allowed_cars}}" placeholder=" " style="width: 100%;" />
                                                                                            </div>
                                                                                        </div>


                                                                                        <div class="row">
                                                                                            <div class="col-md-6 col-xs-12">
                                                                                                <div class="terms">
                                                                                                    <h4>الشروط و الأحكام</h4>

                                                                                                    @include('admin.beachs.term-fields', compact('row'))
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <button type="submit" class="btn btn-success">تعديل</button>

                                                                                    </form>

                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                    </div>
                                                                </div>

                                                                <script>

                                                                </script>
                                                            @endcan
                                                        @endif
                                                </td>
                                            @endif

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{$rows->appends(request()->all())->links()}}

                <div class="clearfix"></div>
            </div>
        @else
            @include('admin.no-permissions')
        @endcan
    </div>

    @if(is_admin())
        @can('can add beaches')
            <div id="ClientM" class="modal fade" role="dialog">
                <div class="modal-dialog modal-lg">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close pull-left" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">شاطئ جديد</h4>
                        </div>
                        <div class="modal-body">
                            <div class="box box-body box-primary">
                                <div class="">
                                    <h3 class="box-title">معلومات أساسية</h3>
                                </div>
                                <hr>

                                <form action="{{route('admin.beaches.store')}}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-md-6 col-xs-9 ">
                                            <label for="add-name"> الاسم </label>
                                            <input id="add-name" type="text" class="form-control" name="name" value="{{old('name')}}" placeholder=" " style="width: 100%;" />

                                        </div>
                                        <div class="form-group col-md-6 col-xs-9 ">
                                            <label for="sector-name"> القطاع</label>
                                            <select id="sector-name" class="form-control select2" style="width: 100%;" name="sector_id">
                                                <option value="">اختر</option>
                                                @foreach($sectors as $sector)
                                                    <option value="{{$sector->id}}">{{$sector->sector_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6 col-xs-9 ">
                                            <label for="allowed-cars"> عدد السيارات المسموح بها </label>
                                            <input id="allowed-cars" type="number" class="form-control" name="allowed_cars" value="{{old('allowed_cars')}}" placeholder=" " style="width: 100%;" />
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            <div class="terms">
                                                <h4>الشروط و الأحكام</h4>

                                                @include('admin.beachs.term-fields')

                                            </div>
                                        </div>
                                    </div>

                                    <hr />
                                    <input type="submit" class="btn btn-success" value="اضافة" />

                                </form>

                            </div>

                        </div>

                    </div>

                </div>
            </div>

            <script>
                $('body').on('click', '.delete-term', function (){
                    if($(this).parents('.terms').find('.term').length > 1)
                        $(this).parents('.term').remove();
                    // else
                    //     alert('لا يمكنك حذف الشرط الوحيد')
                });

                $('body').on('click', '.add-new-term', function (){
                    let output;
                    const index = Math.floor(Math.random() * 1000);
                    const no = $(this).parents('.terms').find('.term').length + 1;

                    output = '<div class="term">';
                    output += '<div class="form-group">';
                    output += '<label for="term-'+index+'">الشرط '+no+'</label>';
                    output += '<input type="text" class="form-control" id="term-'+index+'" name="term['+index+'][term]" required />';
                    output += '</div>';

                    output += '<div class="form-group">';
                    output += '<label for="term-content-'+index+'">محتوى الشرح '+no+'</label>';
                    output += '<textarea class="form-control" id="term-content-'+index+'" name="term['+index+'][term_content]" required ></textarea>';
                    output += '</div>';

                    output += '<div class="form-group">';
                    output += '<button type="button" class="btn btn-danger text-white delete-term" data-toggle="tooltip" title="حذف الشرط نهائيا"><i class="fa fa-trash" style="color: #fff"></i></button>&nbsp';
                    output += '<button type="button" class="btn btn-success add-new-term" data-toggle="tooltip" title="اضافة شرط"><i class="fa fa-plus"></i></button>';
                    output += '</div>';
                    output += '</div>';

                    $(this).parents('.terms').append(output);
                });
            </script>
        @endcan
    @endif
@endsection
