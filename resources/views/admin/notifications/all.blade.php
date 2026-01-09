@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">الإشعارات</h3>
                    </div>

                    <div class="box-body no-padding">
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-hover table-striped">
                                <tbody>
                                    @foreach($notifications as $notification)
                                        <tr>
                                            <td>{{$notification->data['message']}}</td>
                                            <td>{{$notification->created_at->diffForHumans(\Carbon\Carbon::now())}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div>
                </div><!-- /. box -->
            </div>
        </div>
    </div>
@endsection
