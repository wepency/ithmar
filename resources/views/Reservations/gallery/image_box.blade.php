<?php
$index = rand(1111,9999);
$no = isset($no) ? $no : 1;
$trans = isset($trans) ? $trans : '';
?>
<h6><small>صور {{$trans}} {{$no}}</small></h6>

<div class="images-wrapper">
    @if(isset($objs) && isset($objs[$no]) && !empty($objs[$no]))
        @foreach($objs[$no] as $obj)
            <div class="uploaded-image">
                <label class="uploaded-image-label">
                    <a href="{{$obj['original']}}" class="single-image-link">
                        <img src="{{$obj['thumbnail']}}" alt="" />
                    </a>
                </label>
                <button class="remove-image"><i class="fa fa-times"></i></button>
                <input class="gallery-no-field" id="{{$name}}-no-field-{{$index}}" type="hidden" value="{{$no}}" name="{{$name}}_no[]" />
                <input class="gallery-image-path" id="{{$name}}-image-'+index+'" value="{{$obj['id']}}" type="hidden" name="{{$name}}[]" />
            </div>
        @endforeach
    @endif

    <div class="single-image-upload">
        <label class="" for="{{$data}}-image-field-{{$index}}">
            <i class="fa fa-plus"></i>
        </label>

        <input class="gallery-image-field" id="{{$data}}-image-field-{{$index}}" type="file" accept="image/*" data-name="{{$data}}" data-no="{{$no}}" name="{{$data}}_field[]" multiple />
    </div>
</div>
