@extends('layouts.front-page')

@section('styles')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@section('scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $('input[name="dates"]').daterangepicker();
    </script>
@endsection

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3 class="page-title">{{$page_title}}</h3>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/')}}">الرئيسية</a></li>
                        <li class="breadcrumb-item"><a href="{{route('availability.index')}}">الأسعار و التوافر</a></li>
                        <li class="breadcrumb-item"><a href="{{route('availability.show', $unit_id)}}">الوحدة</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{$page_title}}</li>
                    </ol>
                </nav>
            </div>

            <div class="card-body">
                <form style="background-color: #fff;padding: 10px;margin-bottom: 25px" method="post" enctype="multipart/form-data" action="{{$price->exists ? route('availability.update', [$unit_id, base64_encode(base64_encode($price->id))]) : route('availability.store', $unit_id)}}">
                    @csrf

                    @if($price->exists)
                        @method('PUT')
                    @endif

                    @include('admin.layouts.messages')

                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <label for="min_stay">الحد الأدنى للحجز</label>

                                <select class="nice-select w-100" id="min_stay" name="min_stay">
                                    @for($i=1;$i<10;$i++)
                                        <option value="{{$i}}" {{$price->min_stay == $i ? 'selected' : ''}}>{{$i}}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="price">سعر الليلة</label>

                                <input type="number" name="price" id="price" class="form-control" value="{{old('price') ?? $price->price}}" />
                            </div>

                            <div class="form-group">
                                <label for="dates">المدة</label>
                                <input name="dates" id="dates" class="form-control" type="text" value="{{old('dates') ?? $dates}}" />
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <button type="submit" class="btn btn-success">حفظ التعديل</button>

                        @if($price->exists)
                            <button type="submit" class="btn btn-danger">حذف</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
