@include('admin.layouts.messages')

@if(request()->ajax())
    <div class="alert alert-success">
        <h6>هناك تحديث جديد للعقود في الساعة {{\Carbon\Carbon::now()->format('H:i:s')}}</h6>
    </div>
@endif

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
                    من{{format_date($row->from)}} <br />
                    إلى{{format_date($row->to)}}
                </td>
                @can('can view rent value')
                    <td>{{$row->rent_value}}</td>
                @endcan

                @can('can view rental barcode')
                    <td>
                        <a href="#" data-toggle="modal" data-target="#tenant-barcode-{{$row->id}}" style="margin-bottom: 20px"><i class="fa fa-eye"></i></a>

                        <div id="tenant-barcode-{{$row->id}}" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close pull-left" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">باركود المستثمر</h4>
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
                        <a class="btn btn-primary" href="{{admin_url('contract/'.$row->id.'/history')}}"><i class="fa fa-history"></i></a>
                    @endcan

                    @can('can view contract')
                        <a class="btn btn-success" href="{{admin_url('contract/show/'.$row->code)}}"><i style="color: #fff" class="fa fa-eye"></i></a>
                    @endcan

                    @if(is_admin())
                        @can('can edit contract')
                            @if(is_null($row->is_cancelled))
                                <form onsubmit="return confirm('هل تريد حقا إلغاء العقد؟')" style="display: inline-block;margin: 0" action="{{admin_url('contract/'.$row->id.'/cancel')}}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <button class="btn btn-danger" type="submit"><i class="fa fa-times"></i></button>
                                </form>
                            @endif
                            <a class="btn btn-primary" href="{{admin_url('contract/'.$row->id.'/edit')}}"><i style="color: #fff" class="fa fa-edit"></i></a>
                        @endcan
                    @endif

                    @if(is_admin() && (auth()->id() == 75 || auth()->id() == 23))
                        <button class="btn btn-warning tooltip-container" data-toggle="modal" data-target="#change-status-{{$row->id}}"><i class="fa fa-adjust"></i></button>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

{{$rows->appends(request()->all())->links()}}
