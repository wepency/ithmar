@extends('layouts.admin')

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
                                        <li><a href="{{admin_url('reports')}}">تقارير العقود</a></li>
                                        <li class="active"><a href="{{admin_url('reports/services')}}">تقارير الخدمات</a></li>
                                    </ul>
                                </div>
                            @endif

                            @can('can filter reports')
                                <form method="get" style="margin:auto;background:#fff;padding:20px;margin-bottom:10px">

                                    <div class="row baseline-row">
                                        <div class="col-md-3 col-sm-6">
                                            <div class="form-group">
                                                <label for="phonenumber">رقم جوال</label>
                                                <input type="text" name="phonenumber" id="phonenumber" value="{{request()->phonenumber}}" class="form-control" />
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-6">
                                            <div class="form-group">
                                                <label for="code">رقم العقد</label>
                                                <input type="text" name="code" id="code" value="{{request()->code}}" class="form-control" />
                                            </div>
                                        </div>

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

                                        <div class="col-md-3 col-sm-6">
                                            <div class="form-group">
                                                <label for="beach_id">الشاطئ</label>

                                                <select id="beach_id" name="beach" class="form-control">
                                                    <option value="">اختر شاطئ</option>

                                                    @if(is_sector_admin())
                                                        @foreach($beaches as $beach)
                                                            <option value="{{$beach->id}}">{{$beach->beach}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row baseline-row">
                                        <div class="col-md-3 col-xs-12">
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">بحث من</label>
                                                <input type="date" id="from" value="{{request()->from}}" class="form-control" name="from" >
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-xs-12">
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">الي</label>
                                                <input type="date" id="to" value="{{request()->to}}" class="form-control" name="to">
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-xs-12">
                                            <button type="submit" class="btn btn-primary width-100">بحث</button>
                                        </div>

                                        @if(!empty(request()->all()))
                                            <div class="col-md-3 col-xs-12">
                                                <a href="{{admin_url('reports/services')}}" class="btn btn-danger width-100">إلغاء البحث</a>
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
                                        <th>اسم المستثمر</th>
                                        <th>رقم الجوال</th>
                                        <th>القطاع</th>
                                        <th>الشاطئ</th>
                                        <th>رقم الفيلا</th>
                                        <th>الخدمات</th>
                                        <th>إجمالي الخدمات</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($rows as $row)
                                        <tr>
                                            <td>{{@$row->user->name ?? ''}}</td>
                                            <td>{{@$row->user->phonenumber ?? ''}}</td>
                                            <td>{{@$row->sector->sector_name ?? ''}}</td>
                                            <td>{{@$row->beach->beach ?? ''}}</td>

                                            <td>{{$row->unit->unit_number ?? ''}}</td>

                                            @can('can view rental barcode in reports')
                                                <td>
                                                    <a href="#" data-toggle="modal" data-target="#barcode-rent-{{$row->id}}" style="margin-bottom: 20px">الخدمات</a>

                                                    <div id="barcode-rent-{{$row->id}}" class="modal fade" role="dialog">
                                                        <div class="modal-dialog">
                                                            <!-- Modal content-->
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close pull-left" data-dismiss="modal">&times;</button>
                                                                    <h4 class="modal-title">الخدمات المرتبطه بالعقد</h4>
                                                                </div>

                                                                <div class="modal-body">
                                                                    <div class="box box-body box-primary">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-striped">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>الخدمة</th>
                                                                                        <th>السعر</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                <?php
                                                                                    $services = unserialize($row->services->service_data);
                                                                                ?>


                                                                                @if(is_array($services))
                                                                                    @foreach($services as $service)
                                                                                        <tr>
                                                                                            <td>{{$service['service_name']}}</td>
                                                                                            <td>{{currency_format($service['price'])}}</td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                @endif
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            @endcan

                                            <td>{{currency_format($row->services_total)}}</td>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>

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

        $('#from').on('change', function (){
            const toDate = $('#to')
            const startDate = $(this).val()
            const newVal = moment(startDate).add(1, 'days').format('YYYY-MM-DD');

            $(toDate).attr('min', newVal)
            $(toDate).val(newVal)
        })
    </script>
@endsection
