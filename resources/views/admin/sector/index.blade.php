@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @can('can view sectors')
            <div class="col-md-12 col-xs-12">
                @if(is_admin())
                    @can('can add sectors')
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ClientM" style="margin-bottom: 20px">
                            قطاع جديد
                            <i class="fa fa-plus-circle"></i>
                        </button>
                    @endcan
                @endif
            </div>

            <div class="col-md-12 col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">القطاعات</h3>
                    </div>

                    <div class="box-body">
                        @include('admin.layouts.messages')

                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>الإسم</th>
                                    <th>المستخدم</th>
                                    <th>العمليات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($rows as $row)
                                    <tr>
                                        <td>{{$row->sector_name}}</td>

                                        <td>{{$row->user ? $row->user->name : 'غير معروف'}}</td>

                                        <td>
                                            @can('can view history sectors')
                                            <a href="{{admin_url('sector/'.$row->id)}}"><i class="fa fa-history"></i></a>
                                            @endcan

                                            @can('can edit sectors')
                                                <a href="" data-toggle="modal" data-target="#row-{{$row->id}}"><i class="fa fa-edit"></i></a>
                                            @endcan

{{--                                            @can('can delete sectors')--}}
{{--                                                <form id="destory-{{$row->id}}"--}}
{{--                                                      class="delete" style="display:inline-block"--}}
{{--                                                      action="{{ route('admin.sector.destroy',$row->id) }}" method="POST">--}}
{{--                                                    @csrf--}}
{{--                                                    @method('DELETE')--}}
{{--                                                    <button type="submit"  class="btn btn-danger"><span class="fa fa-trash"></span></button>--}}
{{--                                                </form>--}}
{{--                                            @endcan--}}
                                        </td>

                                    </tr>

                                    @can('can edit sectors')
                                    <div id="row-{{$row->id}}" class="modal fade" role="dialog">
                                        <div class="modal-dialog modal-lg">

                                            <!-- Modal content-->
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close pull-left" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">تعديل القطاع</h4>
                                                </div>
                                                <div class="modal-body">

                                                    <div class="box box-body box-primary">
                                                        <div class="">
                                                            <h3 class="box-title">معلومات أساسية</h3>
                                                        </div>
                                                        <hr>

                                                        <form action="{{route('admin.sector.update',$row->id)}}" method="post">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="row" style="margin: 0;padding: 0">
                                                                <div class="form-group col-11 " >
                                                                    <label for="sector-name"> الاسم </label>
                                                                    <input id="sector-name-{{$row->id}}" type="text" class="form-control" name="sector_name" value="{{old('sector_name') ?? $row->sector_name}}" placeholder=" " style="width: 100%;" />
                                                                </div>

                                                                <div class="form-group col-12 ">
                                                                    <label for="name-{{$row->id}}"> اسم مدير القطاع </label>
                                                                    <input type="text" id="name-{{$row->id}}" name="name" class="form-control" value="{{old('name') ?? ($row->user->name ?? '')}}" required />
                                                                </div>

                                                                <div class="form-group col-12 ">
                                                                    <label for="phonenumber-{{$row->id}}"> رقم الجوال </label>
                                                                    <input type="text" id="phonenumber-{{$row->id}}" value="{{old('phonenumber') ?? ($row->user->phonenumber ?? '')}}" name="phonenumber" class="form-control" required />
                                                                </div>

                                                                <div class="form-group col-12 ">
                                                                    <label for="email-{{$row->id}}">البريد الإلكتروني</label>
                                                                    <input type="text" id="email-{{$row->id}}" value="{{old('email') ?? ($row->user->email ?? '')}}" name="email" class="form-control" required />
                                                                </div>

                                                                <div class="form-group col-12 ">
                                                                    <label for="password-{{$row->id}}"> كلمة المرور </label>
                                                                    <input type="password" id="password-{{$row->id}}" name="password" class="form-control" />
                                                                </div>

                                                                <div class="form-group col-12 ">
                                                                    <label for="percentage-{{$row->id}}">نسبة مدير القطاع</label>
                                                                    <input id="percentage-{{$row->id}}" type="number" min="1" max="100" class="form-control" name="percentage" value="{{old('percentage') ?? $row->percentage}}" placeholder="نسبة مدير القطاع" style="width: 100%;" required />
                                                                </div>

                                                                <div class="form-group col-12 ">
                                                                    <label for="price-{{$row->id}}"> السعر </label>
                                                                    <input id="price-{{$row->id}}" type="number" step="0.01" class="form-control" name="price" value="{{old('price') ?? ($row->price != 0 ? $row->price : ($settings->price_before_vat ?? 0))}}" placeholder="السعر" style="width: 100%;" />
                                                                </div>

                                                                <div class="form-group col-12 ">
                                                                    <label for="vat-{{$row->id}}"> الضريبة </label>
                                                                    <input id="vat-{{$row->id}}" type="number" step="0.01" class="form-control" name="vat" value="{{old('vat') ?? ($row->vat != 0 ? $row->vat : 19.57)}}" placeholder="الضريبة" style="width: 100%;" />
                                                                </div>

                                                                <div class="form-group col-12 ">
                                                                    <label for="total-{{$row->id}}"> الإجمالي </label>
                                                                    <input id="total-{{$row->id}}" type="number" step="0.01" class="form-control" name="total" value="{{old('total') ?? ($row->total != 0 ? $row->total : ($settings->price_after_vat ?? 0))}}" placeholder="الإجمالي" style="width: 100%;" />
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
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                    {{$rows->appends(request()->all())->links()}}
            </div>

            @else
                <div class="alert alert-danger col-sm-12">ليس لديك الصلاحيات للإطلاع على هذه الصفحة</div>
            @endcan
        </div>
    </div>

    @if(is_admin())
        <div id="ClientM" class="modal fade" role="dialog">
            <!-- ... (modal content) ... -->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close pull-left" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">قطاع جديد</h4>
                    </div>
                    <div class="modal-body">

                        <div class="box box-body box-primary">
                            <div class="">
                                <h3 class="box-title">معلومات أساسية</h3>
                            </div>
                            <hr>

                            <form action="{{route('admin.sector.store')}}" method="post">
                                @csrf
                                <div class="row" style="margin: 0;padding: 0">
                                    <div class="form-group col-12 ">
                                        <label for="sector_name"> اسم القطاع </label>
                                        <input id="sector_name" type="text" class="form-control" name="sector_name" value="{{old('sector_name')}}" placeholder="اسم القطاع" style="width: 100%;" required />
                                    </div>

                                    <div class="form-group col-12 ">
                                        <label for="name"> اسم مدير القطاع </label>
                                        <input type="text" id="name" name="name" class="form-control" value="{{old('name')}}" required />
                                    </div>

                                    <div class="form-group col-12 ">
                                        <label for="phonenumber"> رقم الجوال </label>
                                        <input type="text" id="phonenumber" name="phonenumber" value="{{old('phonenumber')}}" class="form-control" required />
                                    </div>

                                    <div class="form-group col-12 ">
                                        <label for="email">البريد الإلكتروني</label>
                                        <input type="text" id="email" value="{{old('email')}}" name="email" class="form-control" required />
                                    </div>

                                    <div class="form-group col-12 ">
                                        <label for="password"> كلمة المرور </label>
                                        <input type="password" id="password" name="password" class="form-control" required />
                                    </div>

                                    <div class="form-group col-12 ">
                                        <label for="percentage">نسبة مدير القطاع</label>
                                        <input id="percentage" type="number" min="1" max="100" class="form-control" name="percentage" value="{{old('percentage')}}" placeholder="نسبة مدير القطاع" style="width: 100%;" required />
                                    </div>

                                    <div class="form-group col-12 ">
                                        <label for="price"> السعر </label>
                                        <input id="price" type="number" step="0.01" class="form-control" name="price" value="{{old('price') ?? ($settings->price_before_vat ?? 0)}}" placeholder="السعر" style="width: 100%;" />
                                    </div>

                                    <div class="form-group col-12 ">
                                        <label for="vat"> الضريبة </label>
                                        <input id="vat" type="number" step="0.01" class="form-control" name="vat" value="{{old('vat') ?? 19.57}}" placeholder="الضريبة" style="width: 100%;" />
                                    </div>

                                    <div class="form-group col-12 ">
                                        <label for="total"> الإجمالي </label>
                                        <input id="total" type="number" step="0.01" class="form-control" name="total" value="{{old('total') ?? ($settings->price_after_vat ?? 0)}}" placeholder="الإجمالي" style="width: 100%;" />
                                    </div>
                                </div>

                                <input type="submit" class="btn btn-success" value="اضافة" />
                            </form>

                        </div>

                    </div>

                </div>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calculateTotal = (container) => {
                const priceInput = container.querySelector('input[name="price"]');
                const vatInput = container.querySelector('input[name="vat"]');
                const totalInput = container.querySelector('input[name="total"]');

                if (priceInput && vatInput && totalInput) {
                    const update = () => {
                        const price = parseFloat(priceInput.value) || 0;
                        const vat = parseFloat(vatInput.value) || 0;
                        totalInput.value = (price + vat).toFixed(2);
                    };
                    priceInput.addEventListener('input', update);
                    vatInput.addEventListener('input', update);
                }
            };

            // For creation modal
            const createModal = document.querySelector('#ClientM');
            if (createModal) calculateTotal(createModal);

            // For edit modals
            document.querySelectorAll('.modal[id^="row-"]').forEach(modal => {
                calculateTotal(modal);
            });
        });
    </script>
@endsection
