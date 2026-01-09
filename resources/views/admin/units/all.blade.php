@extends('layouts.admin')

@section('styles')
    <style>
        .upload-single label{
            width: 100px;
            height: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: auto;
            border: 3px dashed #e7e7e7;
            border-radius: 15px;
            font-size: 4rem;
            color: #e7e7e7;
            cursor: pointer;
        }
        .upload-single label:hover{
            opacity: .75;
        }
        .upload-attachment-btn{
            display: none;
            width: 50%;
            margin: 5px auto;
        }
        .progress{
            display: none;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        @can('can view units')
            <div class="row">
                <div class="col-md-12 col-xs-12"></div>
                <div class="col-md-12 col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">الوحدات</h3>
                        </div>

                        <div class="box-body">

                            <div class="row">

                                <div class="col-md-12 col-xs-12">
                                    @can('can filter contracts')
                                        <form method="get" style="margin:auto;background:#fff;padding:20px;margin-bottom:10px">

                                            <div class="row baseline-row">

                                                <div class="col-md-3 col-xs-6">
                                                    <div class="form-group">
                                                        <label for="unit_number">رقم الوحدة</label>
                                                        <input type="text" id="unit_number" value="{{request()->unit_number}}" class="form-control" name="unit_number" />
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-xs-6">
                                                    <div class="form-group">
                                                        <label for="beach_id">الشاطئ</label>

                                                        <select id="beach_id" name="beach" class="form-control">
                                                            <option value=""></option>

                                                            @foreach($beaches as $beach)
                                                                <option value="{{$beach->id}}">{{$beach?->beach}} - {{$beach?->sector?->sector_name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-xs-6">
                                                    <button type="submit" class="btn btn-primary width-100">بحث</button>
                                                </div>

                                                @if(!empty(request()->all()))
                                                    <div class="col-md-3 col-xs-6">
                                                        <a href="{{admin_url('units')}}" class="btn btn-danger width-100">إلغاء البحث</a>
                                                    </div>
                                                @endif
                                            </div>
                                        </form>

                                    @endcan

                                </div>

                                <div class="clearfix"></div>
                            </div>

                            <div class="text-center" style="margin:auto;">
                                <ul class="nav nav-pills" style="width: initial!important;display:inline-block;">
                                    <li class="{{request()->type == '' ? 'active' : ''}}"><a href="{{admin_url('units')}}">الوحدات</a></li>
                                    <li class="{{request()->type == 'expired' ? 'active' : ''}}"><a href="{{admin_url('units?type=expired')}}">منتهية الصلاحية</a></li>
                                    <li class="{{request()->type == 'terminated' ? 'active' : ''}}"><a href="{{admin_url('units?type=terminated')}}">المشطوبة</a></li>
                                </ul>
                            </div>

                            @include('admin.layouts.messages')

                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
{{--                                        <th>القطاع</th>--}}
{{--                                        <th>الشاطئ</th>--}}
                                        <th>#</th>
                                        <th>الفيلا</th>
                                        <th>الاسم الثلاثي</th>
                                        <th>رقم الجوال</th>
                                        @can('can add units')
                                        <th>المرفقات</th>
                                        @endcan
                                        <th>الحالة</th>

                                        @can('can view history units')
                                            <th>السجل</th>
                                        @endcan

                                        @canany('can edit units')
                                            <th>الصلاحيات</th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($rows as $row)
                                        <tr>
{{--                                            <td>{{@$row->sector->sector_name ?? ''}}</td>--}}
{{--                                            <td>{{@$row->beach->beach ?? ''}}</td>--}}
                                            <td>{{pad_code($row->id)}}</td>
                                            <td>
                                                <h3 class="text-success">{{$row->unit_number}}</h3>
                                                <h5 class="text-warning">{{@$row->beach->beach ?? ''}}</h5>
                                                <h6 class="text-danger">{{@$row->sector->sector_name ?? ''}}</h6>
                                            </td>
                                            <td>{{@$row->user->name ?? ''}}</td>
                                            <td>{{@$row->user->phonenumber ?? ''}}</td>
                                            @can('can add units')
                                            <td>
                                                @if(count($row->attachments) > 0)
                                                    @if(file_exists(public_path($row->attachments[0]->path)))
{{--                                                        <a href="{{admin_url('attachment/download/'.$row->attachments[0]->id)}}">المرفق</a>--}}
                                                        <a class="download-attachment" data-image-code="{{base64_encode($row->attachments[0]->id)}}">المرفق</a>
                                                    @else
                                                        ----
                                                    @endif
                                                @else
                                                    ----
                                                @endif
                                            </td>
                                            @endcan

                                            <td>
                                                @if($row->status == 0)
                                                    بانتظار المراجعة
                                                @elseif($row->is_terminated == 1)
                                                    مشطوب
                                                @elseif($row->status == 1)
                                                    مقبول
                                                @elseif($row->status == 2)
                                                    مرفوض
                                                @endif
                                            </td>
                                            @can('can view history units')
                                                <td>
                                                    <a class="btn btn-primary" href="{{admin_url('units/'.$row->id)}}"><i class="fa fa-history"></i></a>
                                                </td>
                                            @endcan

                                            <td>

                                                @can('can edit units')
{{--                                                    @if($row->type == 'investor')--}}
                                                        <a class="btn btn-primary" style="color: white" data-toggle="modal" data-target="#edit-user-{{$row->id}}" href="#">
                                                            <i style="color: white" class="fa fa-edit"></i>
                                                        </a>

                                                        <div id="edit-user-{{$row->id}}" class="modal fade" role="dialog">
                                                                <div class="modal-dialog modal-md">

                                                                    <!-- Modal content-->
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close pull-left" data-dismiss="modal">&times;</button>
                                                                            <h4 class="modal-title">تحديث بيانات الوحدة</h4>
                                                                        </div>
                                                                        <div class="modal-body">

                                                                            <div class="box box-body unit-attachment-window box-primary">
                                                                                <form action="{{admin_url('units/'.$row->id)}}" method="post">
                                                                                    @csrf
                                                                                    @method('PUT')

                                                                                    <div class="form-group">
                                                                                        <label for="user-name-{{$row->id}}">هذا العقار متاح حتى:</label>
                                                                                        <input id="user-name-{{$row->id}}" type="text" class="form-control valid_to" name="valid_to" value="{{old('valid_to') ?? Carbon\Carbon::parse($row->valid_to)->format('d-m-Y')}}" style="width: 100%;" />
                                                                                    </div>

                                                                                    <div class="form-group">
                                                                                        <label for="edit-attachment-{{$row->id}}">رفع مرفق جديد</label>

                                                                                        <div class="upload-single">
                                                                                            <label for="edit-attachment-{{$row->id}}"><i class="fa fa-upload"></i></label>
                                                                                            <div class="attachment-info" style="margin-top: 25px"></div>
                                                                                            <input class="attachment-upload" id="edit-attachment-{{$row->id}}" type="file" style="display: none" />
                                                                                        </div>

                                                                                        <div class="btn btn-primary upload-attachment-btn" data-number="{{$row->id}}"><i class="fa fa-upload"></i> رفع</div>

                                                                                        <div class="progress">
                                                                                            <div class="progress-bar bg-success" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="form-group">
                                                                                        <label>المرفقات</label>

                                                                                        <div class="attachments-table">
                                                                                            @include('admin.units.attachments', ['attachments' => $row->attachments])
                                                                                        </div>
                                                                                    </div>

                                                                                    <input type="submit" class="btn btn-success" value="تحديث" />
                                                                                </form>
                                                                            </div>
                                                                        </div>

                                                                    </div>

                                                                </div>
                                                            </div>
{{--                                                    @endif--}}

                                                    @if(!$row->is_terminated)
                                                        @if($row->status == 2)
                                                            <form style="margin: 0;display: inline-block" action="{{route('admin.request.status', ['1', $row->id])}}" method="POST">
                                                                @csrf
                                                                @method('PUT')

                                                                <button type="submit" class="btn btn-success">
                                                                    <i class="fa fa-check"></i>
                                                                </button>
                                                            </form>
                                                        @endif

                                                        @if($row->status == 1)
                                                            <form style="margin: 0;display: inline-block" action="{{route('admin.request.status',['2', $row->id])}}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <button type="submit" class="btn btn-danger" href="">
                                                                    <i class="fa fa-close"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endif
                                                @endcan

                                                @if(!$row->is_terminated)
                                                    @can('can delete units')
                                                        <form style="margin: 0;display: inline-block" onsubmit="return confirm('هل انت متأكد من شطب الوحده؟ هذه الخطوه لا يمكن الرجوع فيها.')" action="{{route('admin.request.terminate', ['unit_id' => $row->id])}}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Tooltip on top">
                                                                <i style="color: #ffffff !important;" class="fa fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endcan
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{$rows->appends(request()->all())->links()}}
                        </div>

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

        $('document').ready(function (){
            $('.attachment-upload').on('change', function (){
                const $this = $(this)
                $this.prev().html(this.files && this.files.length ? this.files[0].name : '');
                $this.parents('.upload-single').next().show()
            })

            $('.upload-attachment-btn').on('click', function (e){
                e.preventDefault();

                const attachments = $(this).prev().find('input[type="file"]')[0].files[0];

                const formData = new FormData();
                const $this = $(this);

                $this.attr('disabled', true)

                formData.append('files', attachments)
                formData.append('unit_number', $this.data('number'))

                $.ajax({
                    url: '/api/upload-attachment',
                    xhr: function() {
                        var xhr = new window.XMLHttpRequest();

                        // Upload progress
                        xhr.upload.addEventListener("progress", function(evt){
                            if (evt.lengthComputable) {
                                var percentComplete = (evt.loaded / evt.total) * 100;
                                $this.next().show().find('.progress-bar').css('width', percentComplete+'%')

                                if (percentComplete == 100){
                                    $this.next().hide()
                                    $this.attr('disabled', false)
                                    $('.attachment-info').html('')
                                    $('input[type="file"]').html('')
                                }
                            }
                        }, false);

                        return xhr;
                    },
                    data: formData,
                    type: 'POST',
                    contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
                    processData: false, // NEEDED, DON'T OMIT THIS
                    success: function (data){
                        loadAttachTable($this.data('number'), $this.parents('.box-body').find('.attachments-table'))
                    }
                });
            })

            $('body').on('click', '.download-attachment', function (e){
                e.preventDefault();

                const $this = $(this);
                const imageCode = $this.data('image-code')

                $.ajax({
                    url: '/api/download-attachment/' + imageCode,
                    type: 'POST',
                    contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
                    processData: false, // NEEDED, DON'T OMIT THIS
                    success: function (data) {
                        // var blob = new Blob([data]);
                        // var link = document.createElement('a');
                        // link.href = window.URL.createObjectURL(blob);
                        // link.download = "barcode.jpg";
                        // link.click();

                        const link = document.createElement('a');
                        link.setAttribute('href', data.link);
                        link.setAttribute('download', data.name);
                        link.click();
                    }
                })
            })

            $('body').on('click', '.remove-attachment', function (e){
                e.preventDefault();

                const x = confirm('هل تريد حذف المرفق؟ لا يمكن التراجع في تلك الخطوه.')
                const imageCode = $(this).data('name');
                const $this = $(this);

                console.log($(this).data('name'));

                if (x){
                    $.ajax({
                        url: '/api/remove-attachment/'+imageCode,
                        type: 'DELETE',
                        contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
                        processData: false, // NEEDED, DON'T OMIT THIS
                        success: function (data){
                            loadAttachTable($this.data('number'), $this.parents('.box-body').find('.attachments-table'))
                        }
                    });
                }
            })
        })

        function loadAttachTable(id, table){
            $.get('/api/get-attachments/'+id).done(function (data){
                table.html(data)
            })
        }
    </script>
@endsection
