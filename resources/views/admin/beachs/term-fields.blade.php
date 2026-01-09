<?php
if (isset($row) && count($row->terms)){
    $terms = $row->terms ? $row->terms()->get()->toArray() : [];
}else{
    $terms[0]['term'] = '';
    $terms[0]['term_content'] = '';
}
?>

@for($i=0;$i<count($terms);$i++)
<div class="term">
    <div class="form-group">
        <label for="term-{{$i}}">الشرط {{$i+1}}</label>
        <input type="text" class="form-control" value="{{$terms[$i]['term']}}" id="term-{{$i}}" name="term[{{$i}}][term]" required />
    </div>

    <div class="form-group">
        <label for="term-content-{{$i}}">محتوى الشرح {{$i+1}}</label>
        <textarea class="form-control" id="term-content-{{$i}}" name="term[{{$i}}][term_content]" required>{{$terms[$i]['term_content']}}</textarea>
    </div>

    <div class="form-group">
        <button type="button" class="btn btn-danger text-white delete-term" data-toggle="tooltip" title="حذف الشرط نهائيا"><i class="fa fa-trash" style="color: #fff"></i></button>
        <button type="button" class="btn btn-success add-new-term" data-toggle="tooltip" title="اضافة شرط"><i class="fa fa-plus"></i></button>
    </div>
</div>
@endfor
