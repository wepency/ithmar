<div class="table-responsive">
    <table style="margin-top: 10px" class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>المرفق</th>
            <th>الوقت</th>
            <th>تحميل / حذف</th>
        </tr>
        </thead>
        <tbody>
        @foreach($attachments as $attachment)
            <tr>
                <td>{{$attachment->image_name}}</td>
                <td>{{$attachment->created_at}}</td>

                <td style="width: 150px">
                    <button class="btn btn-success download-attachment" data-image-code="{{base64_encode($attachment->id)}}" style="width: 35px; height: 35px"><i style="position:relative;left:3px;" class="fa fa-download"></i></button>
                    <a class="btn btn-primary" href="{{admin_url('attachments/'.$attachment->id)}}"><i class="fa fa-history"></i></a>
                    <button class="btn btn-danger remove-attachment" data-name="{{base64_encode($attachment->id)}}" data-number="{{$attachment->type_id}}" style="width: 35px; height: 35px"><i style="position:relative;left:3px;" class="fa fa-times"></i></button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
