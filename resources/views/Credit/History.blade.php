@extends('layouts.front-page')

@section('styles')
    <link rel="stylesheet" href="{{asset('css/selectize.bootstrap4.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" />
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>

    <script>
        $("a.ver-image").fancybox();
    </script>
@endsection

@section('content')
    <div class="container main-container">
        @include('admin.layouts.messages')

        <div class="row">
            <div class="col-12">
                <div class="table-responsive mt-2">
                    <table class="table table-striped table-hover ithmar-table {{$requests->total() == 0 ? 'empty' : ''}}">
                        <thead>
                        <tr>
                            <th scope="col">
                                <a href="#">#</a>
                            </th>

                            <th scope="col">
                                <a href="#">المبلغ</a>
                            </th>

                            <th scope="col">
                                <a href="#">الحالة</a>
                            </th>

                            <th scope="col">
                                <a href="#">البيانات</a>
                            </th>

                            <th scope="col">
                                <a href="#">التاريخ</a>
                            </th>

                            <th scope="col">
                                <a>صورة التحويل</a>
                            </th>
                        </tr>
                        <tr class="spacer"><td colspan="100"></td></tr>
                        </thead>

                        <tbody>

                        @foreach($requests as $request)
                            <tr>
                                <td>{{str_pad($request->id,6,'0',STR_PAD_LEFT)}}</td>

                                <td>{{currency($request->amount)}}</td>

                                <td>
                                    @if($request->status == 2)
                                        <span class="badge badge-status badge-danger">مرفوض</span>
                                    @elseif($request->status == 1)
                                        <span class="badge badge-status badge-success">مقبول</span>
                                    @else
                                        <span class="badge badge-status badge-warning">قيد التنفيذ</span>
                                    @endif
                                </td>

                                <td>
                                    <p>اسم المستفيد: {{$request->holder_name}}</p>
                                    <p>اسم البنك: {{$request->bank_name}}</p>
                                    <p>رقم الحساب: {{$request->bank_account}}</p>
                                    <p>IBAN: {{$request->iban}}</p>
                                </td>

                                <td>{{$request->created_at->format('d/m/Y . H:i')}}</td>

                                <td>
                                    @if(!is_null($request->verification_image))
                                        <p class="mt-0"><a class="ver-image" href="{{asset(env('RESERVATION_APP_URL').'/uploads/verifications/'.$request->verification_image)}}">عرض صورة التحويل</a></p>
                                    @endif
                                </td>
                            </tr>
                            <tr class="spacer"><td colspan="100"></td></tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>

                @if($requests->total() == 0)
                    @include('no-records')
                @endif
            </div>
        </div>

        <div class="text-center d-flex justify-content-center mt-2">
            {{$requests->appends(request()->all())->links()}}
        </div>
    </div>
@endsection
