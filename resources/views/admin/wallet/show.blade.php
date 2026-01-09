@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">

            <div class="col-md-12 col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">{{$page_title}}</h3>
                    </div>

                    <div class="box-body">

                        @include('admin.layouts.messages')

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
