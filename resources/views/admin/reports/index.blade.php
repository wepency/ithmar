@extends('layouts.admin')

@section('styles')
    <link href="{{asset("css/daterangepicker.css")}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="container-fluid">
        @can('can view reports')
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">التقارير</h3>
                        </div>

                        <div class="box-body">

                            @if(is_admin())
                                <div class="text-center" style="margin:auto;">
                                    <ul class="nav nav-pills" style="margin-bottom:25px;width: initial!important;display:inline-block;">
                                        <li class="active"><a href="{{admin_url('reports')}}">تقارير العقود</a></li>
                                        <li><a href="{{admin_url('reports/services')}}">تقارير الخدمات</a></li>
                                    </ul>
                                </div>
                            @endif

                            @can('can filter reports')
                                <form method="get" style="margin:auto;background:#fff;padding:20px;margin-bottom:10px">

                                    <div class="row baseline-row">
                                        <div class="col-md-3 col-sm-6">
                                            <div class="form-group">
                                                <label for="phonenumber">رقم الجوال</label>

                                                <input type="text" value="{{request()->phonenumber}}" name="phonenumber" id="phonenumber" class="form-control" />
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-6">
                                            <div class="form-group">
                                                <label for="code">رقم العقد</label>
                                                <input type="text" value="{{request()->code}}" name="code" id="code" class="form-control" />
                                            </div>
                                        </div>

                                        @if(is_admin())
                                            <div class="col-md-3 col-sm-6">
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

                                        <div class="col-md-3 col-sm-6">
                                            <div class="form-group">
                                                <label for="beach_id">الشاطئ</label>

                                                <select id="beach_id" name="beach" class="form-control">
                                                    <option value="">اختر شاطئ</option>

                                                    @if(is_sector_admin() || request()->sector != '')
                                                        @foreach($beaches as $beach)
                                                            <option value="{{$beach->id}}" {{request()->beach == $beach->id ? 'selected' : ''}}>{{$beach->beach}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row baseline-row">
                                        <div class="col-md-3 col-xs-12">
                                            <div class="form-group">
                                                <label for="from">بحث من</label>
                                                @if(request()->from != '')
                                                    <input type="text" id="from" value="{{request()->from}}" class="form-control" name="from" autocomplete="off" />
                                                @else
                                                    <input type="text" id="from" class="form-control" name="from" autocomplete="off" />
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-xs-12">
                                            <div class="form-group">
                                                <label for="to">الي</label>
                                                <input type="text" id="to" value="{{request()->to}}" class="form-control" name="to" autocomplete="off" />
                                            </div>
                                        </div>
                                        @if(is_admin())
                                        <div class="col-md-3 col-sm-6">
                                            <div class="form-group">
                                                <label for="payment_type">حالة العقد</label>

                                                <select id="payment_type" name="payment_type" class="form-control">
                                                    <option value=""></option>
                                                    <option value="paid" {{request()->payment_type == 'paid' ? 'selected' : ''}}>مدفوع</option>
                                                    <option value="pay_later" {{request()->payment_type == 'pay_later' ? 'selected' : ''}}>آجل</option>
                                                    <option value="exempt" {{request()->payment_type == 'exempt' ? 'selected' : ''}}>معفي</option>
                                                </select>
                                            </div>
                                        </div>
                                        @endif

                                        <div class="col-md-3 col-xs-12">
                                            <button type="submit" class="btn btn-primary width-100">بحث</button>
                                        </div>

                                        @if(!empty(request()->all()))
                                            <div class="col-md-3 col-xs-12">
                                                <a href="{{admin_url('reports')}}" class="btn btn-danger width-100">إلغاء البحث</a>
                                            </div>
                                        @endif
                                    </div>
                                </form>
                            @endcan

                            <div class="row" style="text-align: center; margin-top: 25px">
                                {{--                                @if(is_admin())--}}
                                @can('can view total')
                                    <div class="col-md-4 col-xs-12">
                                        <div class="info-box bg-aqua">
                                            <span class="info-box-icon"><i class="fa fa-dollar"></i></span>

                                            <div class="info-box-content">
                                                <span class="info-box-text">إجمالي الارباح</span>
                                                <span class="info-box-number">{{currency($sum)}}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endcan
                                {{--                                @endif--}}

                                @if(request()->get('sector') != '' || is_sector_admin())
                                    @can('can view percentage')
                                        <div class="col-md-4 col-xs-12">
                                            <div class="info-box bg-yellow">

                                                <span class="info-box-icon"><i class="fa fa-percentage">%</i></span>

                                                <div class="info-box-content">
                                                    <span class="info-box-text">نسبة القطاع</span>
                                                    <span class="info-box-number">{{$percentage.'%'}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endcan
                                @endif

                                @if(request()->get('sector') != '' || is_sector_admin())
                                    @can('can view total sector')
                                        <div class="col-md-4 col-xs-12">
                                            <div class="info-box bg-red">
                                                <span class="info-box-icon"><i class="fa fa-money"></i></span>

                                                <div class="info-box-content">
                                                    <span class="info-box-text">ارباح القطاع</span>
                                                    <span class="info-box-number">{{currency($total)}}</span>

                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                        </div>
                                    @endcan
                                @endif
                            </div>

                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>رقم التقرير</th>
                                        <th>اسم المستثمر</th>
                                        <th>رقم الجوال</th>
                                        <th>الدخول</th>
                                        <th>الخروج</th>
                                        <th>تاريخ الاصدار</th>

                                        @if(is_admin())
                                        <th>الدفع</th>
                                        @endif

                                        @can('can view contract in reports')
                                            <th>عرض العقد</th>
                                        @endcan

                                        @can('can view rental barcode in reports')
                                            <th>باركود المستأجر</th>
                                        @endcan
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($rows as $row)
                                        <tr>
                                            <td>{{$row->id}}</td>
                                            <td>{{$row->user->name}}</td>
                                            <td>{{$row->user->phonenumber}}</td>
                                            <td>{{format_date($row->from)}}</td>
                                            <td>{{format_date($row->to)}}</td>
                                            <td>{{$row->created_at}}</td>

                                            @if(is_admin())
                                            <td>
                                                @if($row->payment_type)
                                                    {{trans('admin.'.$row->payment_type)}}
                                                @else
                                                    غير معروف
                                                @endif
                                            </td>
                                            @endif

                                            @can('can view contract in reports')
                                                <td><a href="{{admin_url('contract/show/'.$row->code)}}"><i class="fa fa-eye"></i></a></td>
                                            @endcan

                                            @can('can view rental barcode in reports')
                                                <td>
                                                    <a href="#" data-toggle="modal" data-target="#barcode-rent-{{$row->id}}" style="margin-bottom: 20px">باركوود المستأجر</a>

                                                    <div id="barcode-rent-{{$row->id}}" class="modal fade" role="dialog">
                                                        <div class="modal-dialog">
                                                            <!-- Modal content-->
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close pull-left" data-dismiss="modal">&times;</button>
                                                                    <h4 class="modal-title">باردكود المستأجر</h4>
                                                                </div>

                                                                <div class="modal-body">
                                                                    <div class="box box-body box-primary">
                                                                        <h3>{{$row->tenant_name}}</h3>
                                                                        <div class="barcode">
                                                                            <img src="{{asset('/uploads/'.$row->attachment_1)}}" style="max-width: 100%;max-height: 300px" alt="">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            @endcan
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{$rows->appends(request()->all())->links()}}
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
    <script src="{{asset('js/daterangepicker.js')}}"></script>

    <script>
        $('#sector_id').on('change', function (){
            const sector_id = $(this).val()
            changeBeaches(sector_id)
            // $('#unit').html('<option>اختر فيلا</option>')
        })

        $('body').on('change', '#beach_id', function () {
            const beach_id = $(this).val()
            changeVillas(beach_id)
        });

        function changeBeaches(sector_id){
            let output = '';

            $.post('/api/get-beaches/'+sector_id).done(function (data){
                output += "<option value=''></option>";
                for (let i=0;i<data.data.length;i++){
                    output += "<option value='"+data.data[i].id+"'>"+data.data[i].beach+"</option>"
                }

                console.log(data)
                $('#beach_id').html(output)
                $('.select2').select();
            })
        }

        function changeVillas(beach_id){
            let output = '';

            $.post('/api/get-villas/'+beach_id).done(function (data){
                for (let i=0;i<data.data.length;i++){
                    output += "<option value='"+data.data[i].id+"'>"+data.data[i].unit_number+"</option>"
                }
                $('.select2').select();
                $('#unit_id').html(output)
            })
        }

        const opt = {
            singleDatePicker: true,
            timePicker: true,
            timePickerSeconds: true,
            timePicker24Hour: true,
            locale: {
                format: 'YYYY-MM-DD HH:mm:ss'
            }
        }

        $('#from').daterangepicker(opt);

        $('#to').daterangepicker({
            singleDatePicker: true,
            timePicker: true,
            timePickerSeconds: true,
            timePicker24Hour: true,
            locale: {
                format: 'YYYY-MM-DD HH:mm:ss'
            },
            startDate: moment(new Date()).add(1,'days')
        });
        @if(!request()->has('from'))
        $('#from').val('')
        @endif

        @if(!request()->has('to'))
        $('#to').val('')
        @endif

        $('#from').on('change', function (){
            const toDate = $('#to')
            const startDate = $(this).val()
            const newVal = moment(startDate).add(1, 'days').format('YYYY-MM-DD HH:mm:ss');

            console.log($(toDate).attr('min'))
            $(toDate).attr('min', newVal)
            $(toDate).val(newVal)
        })
    </script>
@endsection
