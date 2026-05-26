@extends('layouts.admin')

@section('styles')
    <style>
        .unit-wrapper{
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .unit-wrapper label{
            font-size: 22px;
            margin-right: 10px;
        }
        ::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
            color: red;
            opacity: 1; /* Firefox */
        }

        :-ms-input-placeholder { /* Internet Explorer 10-11 */
            color: red;
        }

        ::-ms-input-placeholder { /* Microsoft Edge */
            color: red;
        }
        .text-danger {
  color: #ef4444 !important; /* Tailwind red-500 */
}

.text-warning {
  color: #f59e0b !important; /* Tailwind amber-500 */
}

.text-success {
  color: #10b981 !important; /* Tailwind green-500 */
}
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        @can('can view clients')
            <div class="row">
                @if(is_admin())
                    @can('can add clients')
                        <div class="col-md-12 col-xs-12">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ClientM" style="margin-bottom: 20px">إضافه حساب <i class="fa fa-plus-circle"></i></button>
                        </div>
                    @endcan
                @endif

                <div class="col-md-12 col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">الحسابات</h3>
                        </div>

                        <div class="box-body">tab
                            <div style="margin-top: 0">

                                @if(is_admin())
                                    @can('can filter clients')
                                        <div class="text-center">
                                            <ul class="nav nav-pills" style="margin-bottom: 25px;width: initial!important;display:inline-block;">
                                                <li class="{{empty(request()->all()) ? 'active' : ''}}"><a href="{{admin_url('users')}}">الكل</a></li>
                                                <li class="{{request()->type == 'investor' ? 'active' : ''}}"><a href="{{admin_url('users?type=investor')}}">المستثمرين</a></li>
                                                <li class="{{request()->type == 'admin' ? 'active' : ''}}"><a href="{{admin_url('users?type=admin')}}">الموظفين</a></li>
                                                <li class="{{request()->type == 'sector' ? 'active' : ''}}"><a href="{{admin_url('users?type=sector')}}">مدراء القطاعات</a></li>
                                            </ul>
                                        </div>
                                    @endcan
                                @endif

                                @can('can filter clients')
                                    <form method="get" style="margin:auto;background:#fff;padding:20px;margin-bottom:10px">

                                        <div class="row baseline-row">
                                            <div class="col-md-3 col-sm-6">
                                                <div class="form-group">
                                                    <label for="phonenumber">رقم جوال الحساب</label>
                                                    <input type="search" id="phonenumber" name="phonenumber" class="form-control" />
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-sm-6">
                                                <div class="form-group">
                                                    <label for="beach_id">الشاطئ</label>

                                                    <select id="beach_id" name="beach" class="form-control">
                                                        <option value=""></option>
                                                        @foreach($beaches as $beach)
                                                            <option value="{{$beach->id}}" {{request()->beach == $beach->id ? 'selected' : ''}}>{{$beach->beach}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-sm-6">
                                                <div class="form-group">
                                                    <label for="unit_id">الفيلا</label>

                                                    <select id="unit_id" name="unit" class="form-control"></select>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-sm-6">
                                                <div class="form-group">
                                                    <label for="unit_status">حالة الوحدات</label>

                                                    <select id="unit_status" name="unit_status" class="form-control">
                                                        <option value=""></option>
                                                        <option value="active" {{request()->unit_status == 'active' ? 'selected' : ''}}>فعال</option>
                                                        <option value="inactive" {{request()->unit_status == 'inactive' ? 'selected' : ''}}>غير فعال</option>
                                                        <option value="terminated" {{request()->unit_status == 'terminated' ? 'selected' : ''}}>مشطوب</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-3 col-xs-12">
                                                <button type="submit" class="btn btn-primary width-100">بحث</button>
                                            </div>

                                            @if(!empty(request()->all()))
                                                <div class="col-md-3 col-xs-12">
                                                    <a href="{{admin_url('users')}}" class="btn btn-danger width-100">إلغاء البحث</a>
                                                </div>
                                            @endif
                                        </div>
                                    </form>
                                @endcan
                            </div>

                            @include('admin.layouts.messages')

                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>الإسم</th>
                                        <th>رقم الجوال </th>
                                        <th>الايميل </th>
                                        <th>الصلاحية</th>
                                        @if(request()->type == 'sector')
                                        <th>القطاع </th>
                                        @endif
                                        <th>حالة العقود</th>
                                        <th>حالة الوحدات</th>
                                        <th>الوحدات</th>
{{--                                        @if(is_admin())--}}
                                        <th>الصلاحيات</th>
{{--                                        @endif--}}
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($rows as $row)
                                        <tr>
                                            <td>{{$row->name}}</td>
                                            <td>{{$row->phonenumber}}</td>
                                            <td>{{$row->email}}</td>
                                            <td>
                                                @if($row->role == 'admin' && $row->hasRole(7))
                                                    المدراء
                                                @else
                                                {{trans('admin.'.$row->role)}}
                                                @endif
                                            </td>

                                            @if(request()->type == 'sector')
                                                <td>
                                                    @if($row->role_id)
                                                        {{@$row->sector->sector_name ?? ''}}
                                                    @endif
                                                </td>
                                            @endif

                                            <td>{{$row->blocked ? 'ممنوع' : 'مقبول'}}</td>
                                            
                                            <td>
                                                
                                                @foreach($row->unit as $unit)
                                                    @if($unit->is_terminated)
                                                        <p class="text-danger mb-1">
                                                            {{ $unit->unit_number }} : مشطوب
                                                        </p>
                                                    @elseif($unit->valid_to > now() && $unit->status == 1)
                                                        <p class="text-success mb-1">
                                                            {{ $unit->unit_number }} : ساري حتى {{ $unit->valid_to }}
                                                        </p>
                                                    @else
                                                        <p class="text-warning mb-1">
                                                            {{ $unit->unit_number }} : غير فعال
                                                        </p>
                                                    @endif
                                                @endforeach
                                                
                                            </td>

                                            <td><a href="{{admin_url('units?user='.base64_encode($row->id))}}">عرض الوحدات</a></td>

                                            <td>

                                                @can('can control contract requests for clients')
                                                    <a href="" data-toggle="modal" data-target="#reasonM-{{$row->id}}"><i class="fa fa-times-circle-o" aria-hidden="true"></i>تجميد / إعادة الوحدات</a>
                                                @endcan

{{--                                                @can('can view full user history')--}}
                                                    <a href="{{route('admin.user.wallet.index', $row->id)}}"><i class="fa fa-credit-card" aria-hidden="true"></i></a>
{{--                                                @endcan--}}

                                                @can('can view full user history')
                                                    <a href="{{admin_url('users/'.$row->id.'/history')}}"><i class="fa fa-clipboard" aria-hidden="true"></i></a>
                                                @endcan

                                                @can('can view history clients')
                                                    <a href="{{admin_url('users/'.$row->id)}}"><i class="fa fa-history" aria-hidden="true"></i></a>
                                                @endcan

                                                @can('can edit clients')
                                                    <a href="" data-toggle="modal" data-target="#edit-user-{{$row->id}}"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                                @endcan
                                            </td>
                                        </tr>

                                        @can('can control contract requests for clients')
                                            <div id="reasonM-{{$row->id}}" class="modal fade" role="dialog">
                                                <div class="modal-dialog modal-md">

                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close pull-left" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title">تجميد / فتح الوحدات</h4>
                                                        </div>
                                                        <div class="modal-body">

                                                            <div class="box box-body box-primary">

                                                                <form action="{{admin_url('user/'.$row->id.'/unitStatus')}}" method="post">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="row">
                                                                        @foreach($row->unitOrder as $unit)
                                                                        <div class="form-group col-md-12 col-xs-12">

                                                                            <div class="unit-wrapper">
                                                                                <label class="switch">
                                                                                    <input type="checkbox" id="block-unit-{{$unit->id}}" name="units[{{$unit->id}}]" {{$unit->status === 2 ? 'checked' : ''}} />
                                                                                    <span class="slider round"></span>
                                                                                </label>

                                                                                <label style="font-size: 1.5rem" for="block-unit-{{$unit->id}}">{{$unit->unit_number}}</label>
                                                                            </div>


                                                                            <input id="note" type="text" class="form-control" name="notes[{{$unit->id}}]" value="{{old('note') ?? $unit->note }}" style="width: 100%;" placeholder="سبب غلق الوحدة إن وجد" />

                                                                            {{--                                                                            <select id="block-unit-{{$row->id}}" name="block_unit" class="form-control">--}}
{{--                                                                                @foreach($row->unit as $unit)--}}
{{--                                                                                    <option value="{{$unit->id}}">{{$unit->unit_number}}</option>--}}
{{--                                                                                @endforeach--}}
{{--                                                                            </select>--}}
                                                                        </div>
                                                                        @endforeach

{{--                                                                        <div class="form-group col-md-12 col-xs-12 ">--}}
{{--                                                                            <label for="reason"> السبب</label>--}}
{{--                                                                            <input id="reason" type="text" class="form-control" name="note" required value="{{old('note') ? old('note') : $row->blocked_note }}" style="width: 100%;" />--}}
{{--                                                                        </div>--}}
                                                                    </div>

                                                                    <div class="form-group">
{{--                                                                        @if($row->blocked)--}}
                                                                            <button type="submit" class="bt btn-success">
                                                                                <i class="fa fa-unlock"></i>  تعديل حالة الوحدات
                                                                            </button>
{{--                                                                        @else--}}
{{--                                                                            <button type="submit" class="bt btn-danger">--}}
{{--                                                                                <i class="fa fa-lock"></i> حظر--}}
{{--                                                                            </button>--}}
{{--                                                                        @endif--}}
                                                                    </div>

                                                                </form>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                        @endcan

                                        @if(is_admin())
                                            @can('can edit clients')
                                            <div id="edit-user-{{$row->id}}" class="modal fade" role="dialog">
                                                <div class="modal-dialog modal-lg">

                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close pull-left" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title">تعديل بيانات الحساب</h4>
                                                        </div>
                                                        <div class="modal-body">

                                                            <div class="box box-body box-primary">
                                                                <div class="">
                                                                    <h3 class="box-title">بيانات اساسية</h3>
                                                                </div>

                                                                <form action="{{route('admin.users.update', $row->id)}}" method="post">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="row">
                                                                        <div class="form-group col-md-6 col-xs-9 ">
                                                                            <label for="user-name-{{$row}}">اسم العميل</label>
                                                                            <input id="user-name-{{$row}}" title="" type="text" class="form-control" name="name" value="{{old('name') ?? $row->name}}" placeholder=" " style="width: 100%;" />

                                                                        </div>
                                                                        <div class="form-group col-md-6 col-xs-9 ">
                                                                            <label for="phonenumber-{{$row}}">رقم الجوال</label>
                                                                            <input id="phonenumber-{{$row}}" type="text" class="form-control" name="phonenumber" value="{{old('phonenumber') ?? $row->phonenumber}}" style="width: 100%;" />
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="form-group col-md-6 col-xs-9 ">
                                                                            <label for="email-{{$row}}">البريد الإلكتروني</label>
                                                                            <input id="email-{{$row}}" type="email" title="البريد الإلكتروني" class="form-control" name="email" value="{{old('email') ?? $row->email}}" placeholder=" " style="width: 100%;" />
                                                                        </div>

                                                                        <div class="form-group col-md-6 col-xs-9">
                                                                            <label>كلمة السر</label>
                                                                            <input type="password" class="form-control" name="password"  style="width: 100%;" />
                                                                        </div>
                                                                    </div>

                                                                    @if($row->role !== 'sector')
                                                                    <div class="row">
                                                                        <div class="form-group col-md-6 col-xs-9 ">
                                                                            <label for="role-{{$row}}">الصلاحيات</label>

                                                                            <select class="form-control" id="role-{{$row}}" name="role">
                                                                                <option value="investor">مستثمر</option>

                                                                                @foreach($roles as $role)
                                                                                    @if($role->name !== 'mdyr-ktaaa')
                                                                                        <option value="{{$role->id}}" {{$row->hasRole($role->name) ? 'selected' : ''}}>{{$role->main_name}}</option>
                                                                                    @endif
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    @endif

                                                                    @if($row->role !== 'sector')
                                                                    <div class="row">
                                                                        <div class="form-group col-md-6 col-xs-9 ">
                                                                            <input type="radio" name="user_payment" value="user_pays" id="user-free-{{$row}}" checked />
                                                                            <label for="user-free-{{$row}}">المستخدم يدفع بشكل طبيعي</label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="form-group col-md-6 col-xs-9 ">
                                                                            <input type="radio" name="user_payment" value="user_free" id="user-free-{{$row}}" {{old('user_payment') == 'user_free' || $row->user_free ? 'checked' : ''}} />
                                                                            <label for="user-free-{{$row}}">دفع أجل</label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="form-group col-md-6 col-xs-9 ">
                                                                            <input type="radio" name="user_payment" value="user_exempt" id="user_exempt-{{$row}}" {{old('user_payment') == 'user_exempt' || $row->user_exempt ? 'checked' : ''}} />
                                                                            <label for="user_exempt-{{$row}}">معفي من عمليات الدفع</label>
                                                                        </div>
                                                                    </div>
                                                                    @endif

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
                                            <h4 class="modal-title">إضافة حساب</h4>
                                        </div>
                                        <div class="modal-body">

                                            <div class="box box-body box-primary">
                                                <div class="">
                                                    <h3 class="box-title">معلومات أساسية</h3>
                                                </div>
                                                <hr>

                                                <form action="{{route('admin.users.store')}}" method="post">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="form-group col-md-6 col-xs-9 ">
                                                            <label for=""> اسم العميل </label>
                                                            <input id=" " type="text" class="form-control" name="name" value="{{old('name')}}" placeholder=" " style="width: 100%;" />

                                                        </div>
                                                        <div class="form-group col-md-6 col-xs-9 ">
                                                            <label for=""> رقم الجوال  </label>
                                                            <input id=" " type="text" class="form-control" name="phonenumber" value="{{old('phonenumber')}}" style="width: 100%;" />
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group col-md-6 col-xs-9 ">
                                                            <label for="">
                                                                البريد الالكتروني
                                                            </label>
                                                            <input id="" type="email" class="form-control" name="email" value="{{old('email')}}" placeholder=" " style="width: 100%;" />
                                                        </div>

                                                        <div class="form-group col-md-6 col-xs-9">
                                                            <label>
                                                                كلمة السر
                                                            </label>
                                                            <input type="password" class="form-control" name="password"  style="width: 100%;" />
                                                        </div>

                                                        <div class="row" style="margin: 0">
                                                            <div class="form-group col-md-6 col-xs-9">
                                                                <label for="role">الصلاحيات</label>

                                                                <select class="form-control" id="role" name="role">
                                                                    <option value="investor">مستثمر</option>

                                                                    @foreach($roles as $role)
                                                                        @if($role->name !== 'mdyr-ktaaa')
                                                                            <option value="{{$role->id}}">{{$role->main_name}}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="row" style="margin: 0">
                                                            <div class="form-group col-md-6 col-xs-9 ">
                                                                <input type="checkbox" name="user_free" id="user-free" />
                                                                <label for="user-free">دفع أجل</label>
                                                            </div>
                                                        </div>

                                                        <div class="row" style="margin: 0">
                                                            <div class="form-group col-md-6 col-xs-9 ">
                                                                <input type="checkbox" name="user_exempt" id="user_exempt" />
                                                                <label for="user_exempt">معفي من عمليات الدفع</label>
                                                            </div>
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

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>

    <script>
        @if(request()->beach)
            changeVillas('{{request()->beach}}')
        @endif

        $('body').on('change', '#beach_id', function () {
            const beach_id = $(this).val()
            changeVillas(beach_id)
        });

        function changeVillas(beach_id){
            let output = '';

            output += "<option value=''></option>"
            $.post('/api/get-villas/'+beach_id).done(function (data){
                for (let i=0;i<data.data.length;i++){
                    output += "<option value='"+data.data[i].id+"'>"+data.data[i].unit_number+"</option>"
                }
                $('.select2').select();
                $('#unit_id').html(output)
            })
        }
    </script>
@endsection
