@extends('layouts.admin')

@section('content')
    @if(auth()->id())
        @foreach($rows as $row)
            <div id="change-status-{{$row->id}}" class="modal fade" role="dialog">
                <div class="modal-dialog modal-lg">

                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close pull-left" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">تعديل حالة العقد {{$row->code}}</h4>
                        </div>

                        <div class="modal-body">
                            <div class="box box-body box-primary">

                                @include('admin.layouts.messages')

                                <form class="mt-4 mb-4" action="{{route('admin.contract.changeStatus', $row->id)}}"
                                      method="post" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-group col-md-4 col-xs-9">

                                        <label for="contract_status-{{$row->id}}"> حالة العقد </label>

                                        <select id="contract_status-{{$row->id}}" class="form-control select2"
                                                name="contract_status" style="width: 100%;">
                                            <option value="phone">الهاتف غير مفعل</option>
                                            <option value="paid">مدفوع</option>
                                            <option value="unpaid">غير مدفوع</option>
                                            <option value="pay_later">آجل</option>
                                            <option value="exempt">معفي</option>
                                            <option value="rejected">ملغي</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-xs-12">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> حفظ
                                            العقد
                                        </button>
                                    </div>

                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    <div class="container-fluid">
        @can('can view contracts')
            <div class="row">
                @if(is_admin())
                    @can('can add contracts')
                        <div class="col-md-12 col-xs-12">
                            {{--                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ContractM" style="margin-bottom: 20px">إضافه حجز <i class="fa fa-plus-circle"></i></button>--}}
                            <a href="{{admin_url('contract/create')}}" class="btn btn-primary"
                               style="margin-bottom: 20px">إضافه حجز <i class="fa fa-plus-circle"></i></a>
                        </div>
                    @endcan
                @endif


                <div class="col-md-12 col-xs-12">
                    @can('can filter contracts')
                        <form method="get" style="margin:auto;background:#fff;padding:20px;margin-bottom:10px">

                            <div class="row baseline-row">

                                <div class="col-md-3 col-xs-6">
                                    <div class="form-group">
                                        <label for="phonenumber">رقم الجوال</label>
                                        <input type="text" id="phonenumber" value="{{request()->phonenumber}}"
                                               class="form-control" name="phonenumber"/>
                                    </div>
                                </div>

                                <div class="col-md-3 col-xs-6">
                                    <div class="form-group">
                                        <label for="contract_number">رقم العقد</label>
                                        <input type="text" id="contract_number" value="{{request()->code}}"
                                               class="form-control" name="code"/>
                                    </div>
                                </div>

                                @if(is_admin())
                                    <div class="col-md-3 col-xs-6">
                                        <div class="form-group">
                                            <label for="sector_id">القطاع</label>

                                            <select id="sector_id" name="sector" class="form-control">
                                                <option value=""></option>
                                                @foreach($sectors as $sector)
                                                    <option value="{{$sector->id}}" {{request()->sector == $sector->id ? 'selected' : ''}}>{{$sector->sector_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-md-3 col-xs-6">
                                    <div class="form-group">
                                        <label for="beach_id">الشاطئ</label>

                                        <select id="beach_id" name="beach" class="form-control">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3 col-xs-6">
                                    <div class="form-group">
                                        <label for="unit_id">الفيلا</label>

                                        <select id="unit_id" name="unit" class="form-control">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row baseline-row">
                                <div class="col-md-3 col-xs-6">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">بحث من</label>
                                        <input type="date" value="{{request()->from}}" class="form-control"
                                               name="from"/>
                                    </div>
                                </div>

                                <div class="col-md-3 col-xs-6">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">الي</label>
                                        <input type="date" value="{{request()->to}}" class="form-control" name="to">
                                    </div>
                                </div>

                                @if(is_admin())
                                    <div class="col-md-3 col-xs-6">
                                        <div class="form-group">
                                            <label for="payment_type">حالة العقد</label>
                                            {{--                                    'phone','accepted','paid','unpaid','pay_later','exempt','rejected'--}}

                                            <select class="form-control" id="payment_type" name="payment_type"
                                                    style="margin-bottom: 25px">
                                                <option value="" {{request()->get('payment_type') == '' ? 'active' : ''}}>
                                                    الكل
                                                </option>
                                                <option value="phone" {{request()->get('payment_type') == 'phone' ? 'selected' : ''}}>
                                                    الهاتف غير مفعل
                                                </option>
                                                <option value="paid" {{request()->get('payment_type') == 'paid' ? 'selected' : ''}}>
                                                    مدفوع
                                                </option>
                                                <option value="unpaid" {{request()->get('payment_type') == 'unpaid' ? 'selected' : ''}}>
                                                    غير مدفوع
                                                </option>
                                                <option value="pay_later" {{request()->get('payment_type') == 'pay_later' ? 'selected' : ''}}>
                                                    آجل
                                                </option>
                                                <option value="exempt" {{request()->get('payment_type') == 'exempt' ? 'selected' : ''}}>
                                                    معفي
                                                </option>
                                                <option value="rejected" {{request()->get('payment_type') == 'rejected' ? 'selected' : ''}}>
                                                    ملغي
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-md-3 col-xs-6">
                                    <button type="submit" class="btn btn-primary width-100">بحث</button>
                                </div>

                                @if(!empty(request()->all()))
                                    <div class="col-md-3 col-xs-6">
                                        <a href="{{admin_url('contract')}}" class="btn btn-danger width-100">إلغاء
                                            البحث</a>
                                    </div>
                                @endif
                            </div>
                        </form>

                    @endcan

                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">الحجوزات</h3>
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
                                        @can('can view rent value')
                                            <th>قيمة الايجار</th>
                                        @endcan

                                        @can('can view rental barcode')
                                            <th>باركود المستأجر</th>
                                        @endcan

                                        <th>رقم الجوال</th>

                                        @if(is_admin())
                                            <th>الحالة</th>
                                        @endif

                                        @canany('can view history contracts', 'can view contract', 'can edit contract')
                                            <th>العقد</th>
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
                                                من{{format_date($row->from)}} <br/>
                                                إلى{{format_date($row->to)}}
                                            </td>
                                            @can('can view rent value')
                                                <td>{{$row->rent_value}}</td>
                                            @endcan

                                            @can('can view rental barcode')
                                                <td>
                                                    <a href="#" data-toggle="modal"
                                                       data-target="#tenant-barcode-{{$row->id}}"
                                                       style="margin-bottom: 20px"><i class="fa fa-eye"></i></a>

                                                    <div id="tenant-barcode-{{$row->id}}" class="modal fade"
                                                         role="dialog">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close pull-left"
                                                                            data-dismiss="modal">&times;
                                                                    </button>
                                                                    <h4 class="modal-title">باركود المستثمر</h4>
                                                                </div>

                                                                <div class="modal-body">
                                                                    <div class="barcode">
                                                                        <img src="{{asset('uploads/'.$row->attachment_1)}}"
                                                                             style="max-width: 100%;max-height: 300px"
                                                                             alt="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            @endcan

                                            <td>
                                                {{$row->user->phonenumber ?? ''}}
                                            </td>

                                            @if(is_admin())
                                                <td>
                                                    @if($row->is_cancelled)
                                                        <span class="text text-danger">ملغي - </span>
                                                    @endif

                                                    @if($row->payment_type)
                                                        {{trans('admin.'.$row->payment_type)}}
                                                    @else
                                                        غير معروف
                                                    @endif
                                                </td>
                                            @endif

                                            <td>
                                                @can('can view history contracts')
                                                    <a class="btn btn-primary"
                                                       href="{{admin_url('contract/'.$row->id.'/history')}}"><i
                                                                class="fa fa-history"></i></a>
                                                @endcan

                                                @can('can view contract')
                                                    <a class="btn btn-success"
                                                       href="{{admin_url('contract/show/'.$row->code)}}"><i
                                                                style="color: #fff" class="fa fa-eye"></i></a>
                                                @endcan

                                                @if(is_admin())
                                                    <!-- @can('can edit contract') -->
                                                        @if(is_null($row->is_cancelled))
                                                            <form onsubmit="return confirm('هل تريد حقا إلغاء العقد؟')"
                                                                  style="display: inline-block;margin: 0"
                                                                  action="{{admin_url('contract/'.$row->id.'/cancel')}}"
                                                                  method="POST">
                                                                @csrf
                                                                @method('PUT')

                                                                <button class="btn btn-danger" type="submit"><i
                                                                            class="fa fa-times"></i></button>
                                                            </form>
                                                        @endif
                                                        <a class="btn btn-primary"
                                                           href="{{admin_url('contract/'.$row->id.'/edit')}}"><i
                                                                    style="color: #fff" class="fa fa-edit"></i></a>
                                                    <!-- @endcan -->
                                                @endif

                                                @if(is_admin() && (auth()->id() == 75 || auth()->id() == 389))
                                                    <button class="btn btn-warning tooltip-container"
                                                            data-toggle="modal"
                                                            data-target="#change-status-{{$row->id}}"><i
                                                                class="fa fa-adjust"></i></button>
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

            @if(is_admin())
                @can('can add contracts')
                    <div id="ContractM" class="modal fade" role="dialog">
                        <div class="modal-dialog modal-lg">

                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close pull-left" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">إضافة حجز</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="box box-body box-primary">
                                        <div class="">
                                            <h3 class="box-title">معلومات الحجز</h3>
                                        </div>

                                        @include('admin.layouts.messages')

                                        <form action="{{route('admin.contract.store')}}" method="post"
                                              enctype="multipart/form-data">
                                            @csrf

                                            <div class="form-group col-md-4 col-xs-9 ">
                                                <label for="add_sector_id"> رقم القطاع </label>
                                                <select id="add_sector_id" class="form-control select2" name="sector_id"
                                                        style="width: 100%;">
                                                    @foreach($sectors as $sector)
                                                        <option
                                                                {{old('sector_id') == $sector->id ? 'selected' : ''}}
                                                                value="{{$sector->id}}">{{$sector->sector_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group col-md-4 col-xs-9 ">
                                                <label for="add_beach_id"> الشاطئ </label>
                                                <select id="add_beach_id" class="form-control select2" name="beach_id"
                                                        style="width: 100%;"></select>
                                            </div>

                                            <div class="form-group col-md-4 col-xs-9 ">
                                                <label for="add_unit_id"> الوحدة </label>
                                                <select id="add_unit_id" class="form-control select2" name="unit_id"
                                                        style="width: 100%;"></select>
                                            </div>

                                            <div class="form-group col-md-6 col-xs-12">
                                                <label for="datepicker">من </label>
                                                <div class="input-group date">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                    <input type="text" name="from" autocomplete="off" required
                                                           value="{{old('from')}}" class="form-control pull-right"
                                                           id="datepicker">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6 col-xs-12">
                                                <label for="datepicker2">الى </label>
                                                <div class="input-group date">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                    <input type="text" name="to" autocomplete="off" required
                                                           value="{{old('to')}}" class="form-control pull-right"
                                                           id="datepicker2">
                                                </div>
                                            </div>

                                            <div class=" with-border">
                                                <h3 class="box-title">بيانات المستأجر / المرافق</h3>

                                                <div class="row">
                                                    <div class="form-group col-md-4 col-xs-9 ">
                                                        <label for="tenant_name"> اسم المستأجر </label>
                                                        <input id="tenant_name" type="text" class="form-control "
                                                               required name="tenant_name"
                                                               value="{{old('tenant_name')}}" placeholder=" "
                                                               style="width: 100%;"/>
                                                    </div>

                                                    <div class="form-group col-md-4 col-xs-9 ">
                                                        <label for="phonenumber"> رقم جوال المستأجر </label>
                                                        <input id="phonenumber" type="text" class="form-control "
                                                               required name="phonenumber"
                                                               value="{{old('phonenumber')}}" placeholder=""
                                                               style="width: 100%;"/>
                                                    </div>

                                                    <div class="form-group col-md-4 col-xs-9 ">
                                                        <label for="attachment_1"> باركود المستأجر </label>
                                                        <input id="attachment_1" type="file" class="form-control"
                                                               required name="attachment_1" style="width: 100%;"/>
                                                    </div>

                                                    <div class="form-group col-md-4 col-xs-9 ">
                                                        <label for="tenant_name_code"> هوية المستأجر </label>
                                                        <input id="tenant_name_code" type="text" class="form-control"
                                                               required name="tenant_name_code" style="width: 100%;"/>
                                                    </div>

                                                    <div class="form-group col-md-4 col-xs-9">
                                                        <label for="tenant_nationality"> جنسية المستأجر </label>
                                                        <input id="tenant_nationality"
                                                               value="{{old('tenant_nationality')}}" type="text"
                                                               class="form-control" required name="tenant_nationality"
                                                               style="width: 100%;"/>
                                                    </div>

                                                    <div class="form-group col-md-4 col-xs-9 ">
                                                        <label for="with_tenant_name"> بيانات المرافق </label>
                                                        <input id="with_tenant_name" type="text" class="form-control"
                                                               required name="with_tenant_name"
                                                               value="{{old('with_tenant_name')}}"
                                                               style="width: 100%;"/>
                                                    </div>

                                                    <div class="form-group col-md-4 col-xs-9 ">
                                                        <label for="attachment_2"> باركود المرافق </label>
                                                        <input id="attachment_2" type="file" class="form-control"
                                                               required name="attachment_2" style="width: 100%;"/>
                                                    </div>

                                                    <div class="form-group col-md-4 col-xs-9 ">
                                                        <label for="with_tenant_name_code"> هوية المرافق </label>
                                                        <input id="with_tenant_name_code"
                                                               value="{{old('with_tenant_name_code')}}" type="text"
                                                               class="form-control" required
                                                               name="with_tenant_name_code" style="width: 100%;"/>
                                                    </div>

                                                    <div class="form-group col-md-4 col-xs-9">
                                                        <label for="with_tenant_nationality"> جنسية المرافق </label>
                                                        <input id="with_tenant_nationality"
                                                               value="{{old('with_tenant_nationality')}}" type="text"
                                                               class="form-control" required
                                                               name="with_tenant_nationality" style="width: 100%;"/>
                                                    </div>

                                                    <div class="form-group col-md-4 col-xs-9 ">
                                                        <label for="rent_value">قيمة الايجار </label>
                                                        <input id="rent_value" type="number" class="form-control"
                                                               name="rent_value" value="{{old('rent_value')}}"
                                                               style="width: 100%;"/>
                                                    </div>

                                                    <div class="form-group col-md-4 col-xs-9 ">
                                                        <label for="insurance_value">قيمة التأمين </label>
                                                        <input id="insurance_value" type="number" class="form-control"
                                                               name="insurance_value" value="{{old('insurance_value')}}"
                                                               style="width: 100%;"/>
                                                    </div>

                                                </div>

                                                <div id="cars"></div>

                                                {{--                                    @foreach($)--}}
                                                {{--                                    <div class="row">--}}
                                                {{--                                        <div class="form-group col-md-6 col-xs-9 ">--}}
                                                {{--                                            <label for="car_type">نوع السيارة 1</label>--}}
                                                {{--                                            <input id="car_type" type="text" class="form-control" name="car_type" style="width: 100%;" />--}}
                                                {{--                                        </div>--}}
                                                {{--                                        <div class="form-group col-md-6 col-xs-9 ">--}}
                                                {{--                                            <label for="car_serial">بيانات اللوحة</label>--}}
                                                {{--                                            <input id="car_serial" type="text" class="form-control" name="car_serial" style="width: 100%;" />--}}
                                                {{--                                        </div>--}}
                                                {{--                                    </div>--}}
                                                {{--                                    @endforeach--}}

                                                {{--                                    <div class="row">--}}
                                                {{--                                        <div class="form-group col-md-6 col-xs-9 ">--}}
                                                {{--                                            <label for="">نوع السيارة 2 </label>--}}
                                                {{--                                            <input id="" type="text" class="form-control" name="car_type2" value="{{old('car_type2')}}" style="width: 100%;" />--}}
                                                {{--                                        </div>--}}
                                                {{--                                        <div class="form-group col-md-6 col-xs-9 ">--}}
                                                {{--                                            <label for="">بينات اللوحة </label>--}}
                                                {{--                                            <input id=" " type="text" class="form-control" name="car_serial2" value="{{old('car_serial2')}}" style="width: 100%;" />--}}
                                                {{--                                        </div>--}}
                                                {{--                                    </div>--}}
                                                {{--                                    <div class="row">--}}
                                                {{--                                        <div class="form-group col-md-6 col-xs-9 ">--}}
                                                {{--                                            <label for="">نوع السيارة 3 </label>--}}
                                                {{--                                            <input id="" type="text" class="form-control" name="car_type3" value="{{old('car_type3')}}" style="width: 100%;" />--}}
                                                {{--                                        </div>--}}
                                                {{--                                        <div class="form-group col-md-6 col-xs-9 ">--}}
                                                {{--                                            <label for="">بينات اللوحة </label>--}}
                                                {{--                                            <input id="" type="text" class="form-control" name="car_serial3" value="{{old('car_serial3')}}" style="width: 100%;" />--}}
                                                {{--                                        </div>--}}
                                                {{--                                    </div>--}}

                                            </div>

                                            <div class="form-group col-md-1">
                                                <button type="submit" class="btn btn-success"><i class="fa fa-plus"></i>
                                                </button>
                                            </div>

                                            <div style="clear:both"></div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @endcan
            @endif
        @else
            @include('admin.no-permissions')
        @endcan
    </div>
    </section>

@endsection

@section('scripts')
    <script>

        @if(count($sectors) > 0)
        changeBeaches({{$sectors[0]->id}}, 'beach_id')
        changeBeaches({{$sectors[0]->id}}, 'add_beach_id')
        @endif

        $('#sector_id').on('change', function () {
            const sector_id = $(this).val()

            changeBeaches(sector_id, 'beach_id')
            // $('#unit').html('<option>اختر فيلا</option>')
        })

        $('body').on('change', '#beach_id', function () {
            const beach_id = $(this).val()
            changeVillas(beach_id, 'unit_id')
        });

        $('#add_sector_id').on('change', function () {
            const sector_id = $(this).val()

            changeBeaches(sector_id, 'add_beach_id')
            // $('#unit').html('<option>اختر فيلا</option>')
        })

        $('body').on('change', '#add_beach_id', function () {
            const beach_id = $(this).val()
            changeVillas(beach_id, 'add_unit_id')
        });

        function changeBeaches(sector_id, element) {
            let output = "<option value=''></option>";

            $.post('/api/get-beaches/' + sector_id).done(function (data) {
                for (let i = 0; i < data.data.length; i++) {
                    output += "<option value='" + data.data[i].id + "'>" + data.data[i].beach + "</option>"
                }

                console.log(data)
                $('#' + element).html(output)
                $('.select2').select();

                if (data.data.length > 0)
                    changeVillas(data.data[0].id)
            })
        }

        function changeVillas(beach_id, element) {
            let output = '';

            $.post('/api/get-villas/' + beach_id).done(function (data) {
                output = "<option value=''></option>";
                for (let i = 0; i < data.data.length; i++) {
                    output += "<option value='" + data.data[i].id + "'>" + data.data[i].unit_number + "</option>"
                }
                $('.select2').select();
                $('#' + element).html(output)
            })
        }

        $('#from').on('change', function () {
            const toDate = $('#to')
            const startDate = $(this).val()
            const newVal = moment(startDate).add(1, 'days').format('YYYY-MM-DD');

            console.log($(toDate).attr('min'))
            $(toDate).attr('min', newVal)
            $(toDate).val(newVal)
        });

        $('#beach_id').on('change', function () {
            let output = '';

            output += '<div class="row">';
            output += '<div class="form-group col-md-6 col-xs-9 ">';
            output += '<label for="car_type">نوع السيارة 1</label>';
            output += '<input id="car_type" type="text" class="form-control" name="car_type" style="width: 100%;" />';
            output += '</div>';
            output += '<div class="form-group col-md-6 col-xs-9">';
            output += '<label for="car_serial">بيانات اللوحة</label>';
            output += '<input id="car_serial" type="text" class="form-control" name="car_serial" style="width: 100%;" />';
            output += '</div>';
            output += '</div>';


            $('#cars').html(output);
        })
    </script>
@endsection
