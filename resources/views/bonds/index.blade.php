@extends('layouts.front-page')

@section('styles')
    <link rel="stylesheet" href="{{asset('css/selectize.bootstrap4.css')}}">

    <style>
        .show-bond table.info-table *{
            text-align: right !important;
        }

        @media print {
            .table-responsive{
                min-height: initial !important;
                overflow: initial !important;
            }

            .table>tbody>tr>td, .table>tbody>tr>th,
            .table>tfoot>tr>td, .table>tfoot>tr>th,
            .table>thead>tr>td, .table>thead>tr>th{
                padding: 3px 5px !important;
            }
        }
    </style>

    <style media="print">
        @page
        {
            size:  auto;   /* auto is the initial value */
            margin: 0;  /* this affects the margin in the printer settings */
        }

        html
        {
            background-color: #FFFFFF;
            margin: 0;  /* this affects the margin on the html before sending to printer */
        }

        body
        {
            margin: 10mm 15mm 10mm 15mm;
        }

        .show-bond table.info-table *{
            text-align: right !important;
        }
    </style>
@endsection

@section('content')
    <div class="container main-container">

        <div class="row mb-4">
            <div class="col-xl-3 col-sm-6 stats-container">
                <a href="#" style="height: 100%" data-toggle="modal" data-target="#create-bond" class="card card-stats">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="media success d-flex align-items-center">
                                <div class="align-self-center">
                                    <i class="icon_plus"></i>
                                </div>
                                <div class="media-body text-left">
                                    <h5 class="mb-0">أضف سند جديد</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6 stats-container">
                <a class="card card-stats">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="media success d-flex">
                                <div class="align-self-center">
                                    <i class="icon_documents"></i>
                                </div>
                                <div class="media-body text-left">
                                    <h3>{{$bonds->total()}}</h3>
                                    <span>عدد السندات</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        @include('admin.layouts.messages')

        @include('contracts.create-bond')

        <div class="row">
            <div class="col-12">
                <div class="table-responsive mt-2">
                    <table class="table table-striped table-hover ithmar-table {{$bonds->total() == 0 ? 'empty' : ''}}">
                        <thead>
                            <tr>
                                <th scope="col">
                                    <a href="#">#</a>
                                </th>

                                <th scope="col">
                                    <a href="#">رقم العقد</a>
                                </th>

                                <th scope="col">
                                    <a href="#">المستلم</a>
                                </th>

                                <th scope="col">
                                    <a href="#">المبلغ</a>
                                </th>

                                <th scope="col">
                                    <a>تاريخ الإنشاء</a>
                                </th>

                                <th scope="col">
                                    <a>تعديل</a>
                                </th>
                            </tr>
                            <tr class="spacer"><td colspan="100"></td></tr>
                        </thead>

                        <tbody>

                        @foreach($bonds as $bond)
                            <tr>
                                <td>{{str_pad($bond->id,6,'0',STR_PAD_LEFT)}}</td>

                                <td>
                                    <a href="{{investor_url('contract/'.$bond->contract->code)}}">{{$bond->contract->code ?? ''}}</a>
                                </td>

                                <td>{{$bond->name}}</td>

                                <td>{{$bond->value.' ر.س'}}</td>

                                <td>{{$bond->created_at->format('d/m/Y . H:i')}}</td>

                                <td>
                                    <a href="{{route('bond.show', base64_encode(base64_encode($bond->id*15965585478)))}}" class="btn btn-success icon-button tooltip-container" title="عرض السند"><i class="fa-regular fa-eye"></i></a>

                                    <form action="{{url('bonds/'.$bond->id.'/delete')}}" style="display:inline-block;margin:0" method="post">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-danger icon-button tooltip-container" onclick="return confirm('هل تريد حذف السند؟')" title="حذف السند"><i class="icon_trash_alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                            <tr class="spacer"><td colspan="100"></td></tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>

                @if($bonds->total() == 0)
                    @include('no-records')
                @endif
            </div>
        </div>

        <div class="text-center d-flex justify-content-center mt-2">
            {{$bonds->appends(request()->all())->links()}}
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('js/selectize.min.js')}}"></script>

    <script>
        $("select").selectize();
    </script>
@endsection
