// room_count
// halls_count
// toilets_count
// pools_count

const GalleryText = 'اضغط على المربع لتحميل الصور أو اسحبها',
    extensions = ['.jpg', '.jpeg', '.png', '.gif', '.svg', '.PNG', '.JPEG', '.JPG'],
    mimes = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml'];

let roomsImages = $('#rooms-images'),
    hallsImages = $('#halls-images'),
    toiletsImages = $('#toilets-images'),
    poolsImages = $('#pools-images');

$('.gallery-items-count').on('change', function (){
    let gallery = '';
    const galleryItemsCount = $(this).val();
    const fieldsName = $(this).data('name');
    const fieldArabicName = $(this).data('trans');

    for (let i = 0; i < galleryItemsCount; i++){
        gallery += '<h6 class="mb-2 mt-2"><small>صور '+fieldArabicName+' '+(i+1)+'</small></h6>';
        gallery += '<div class="images-wrapper">';
        gallery += '<div class="single-image-upload">';
        gallery += '<label class="" for="'+fieldsName+i+'-image-field">';
        gallery += '<i class="fa fa-plus"></i>';
        gallery += '</label>';
        gallery += '<input class="gallery-image-field" id="'+fieldsName+i+'-image-field" type="file" accept="image/*" data-name="'+fieldsName+'" data-no="'+(i+1)+'" name="'+fieldsName+'_field[]" multiple />';
        gallery += '</div>';
        gallery += '</div>';
    }

    $(this).parents('.step').find('.images-container').html(gallery);
});

$('body').on('change', '.gallery-image-field', function (){
    let output = '' ,
        loadingOutput = '';

    const fieldName = $(this).data('name');
    const $this = $(this);
    const $this_no = $this.data('no')

    let formData = new FormData();

    let TotalFiles = this.files.length;
    let images = [];

    // console.log(TotalFiles)
    for (let i = 0; i < TotalFiles; i++) {
        formData.append('file-'+i, this.files[i]);

        loadingOutput += '<div class="uploaded-image preloading">';
        loadingOutput += '<label class="uploaded-image-label">';
        loadingOutput += '<div class="button-loading"><span class="loader"></span></div>';
        loadingOutput += '</div>';
        loadingOutput += '</div>';

    }

    $this.parents('.single-image-upload').before(loadingOutput);

    formData.append('TotalFiles', TotalFiles);

    $('.next').addClass('button-loading');

    $.ajax({
        type:'POST',
        url: "/api/upload-multiple",
        data: formData,
        cache:false,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: (data) => {
            const index = Math.floor(Math.random() * 10);

            for (let i = 0; i < data.length; i++){
                output += '<div class="uploaded-image">';
                output += '<label class="uploaded-image-label">';
                output += '<a href="/temp/gallery/'+data[i]+'" class="single-image-link">';
                output += '<img src="/temp/gallery/'+data[i]+'" alt="" />';
                output += '</a>';
                output += '</label>';
                output += '<button class="remove-image"><i class="fa fa-times"></i></button>';
                output += '<input class="gallery-no-field" id="'+fieldName+'-no-field-'+index+'" type="hidden" value="'+$this_no+'" name="'+fieldName+'_no[]" />';
                output += '<input class="gallery-image-path" id="room-image-'+index+'" value="'+data[i]+'" type="hidden" name="'+fieldName+'[]" />';
                output += '</div>';
            }

            $this.parents('.single-image-upload').before(output);

            $this.val('');

            $('.next').removeClass('button-loading');
            $('.uploaded-image.preloading').remove()

            $("a.single-image-link").fancybox();
        },
        error: function(data){
            alert(data.responseJSON.errors.files[0]);
            console.log(data.responseJSON.errors);
        }
    });
});

$('body').on('click', '.remove-image', function (){
    $(this).parents('.uploaded-image').remove()
});

$('#room_count').on('change', function (e){
    e.preventDefault();

    // const count = $(this).val();
    // let i, output;
    //
    // for (i=1;i<=count;i++){
    //     output += '<div class="single-image-upload">';
    //     output += '<label class="upload-image-field" for="room-image-'+i+'">';
    //     output += '<i class="fa fa-plus"></i>';
    //     output += '<img src="" alt="" />';
    //     output += '</label>';
    //     output += '<input class="gallery-image-field" id="room-image-'+i+'" type="file" accept="image/*" name="rooms[]" required />';
    //     output += '</div>';
    // }
    //
    // let ImagesWrapper = $(this).parents('.image-with-select').find('.images-wrapper');
    //
    // ImagesWrapper.empty();
    // ImagesWrapper.append(output);

    roomsImages.empty();

    roomsImages.imageUploader({
        imagesInputName:'rooms',
        preloadedInputName:'roomsPreloaded',
        label:GalleryText,
        maxFiles: $(this).val(),
        extensions,
        mimes
    });
});

$('#halls_count').on('change', function (e){
    e.preventDefault();

    // const count = $(this).val();
    // let i, output;
    //
    // for (i=1;i<=count;i++){
    //     output += '<div class="single-image-upload">';
    //     output += '<label class="upload-image-field" for="hall-image-'+i+'">';
    //     output += '<i class="fa fa-plus"></i>';
    //     output += '<img src="" alt="" />';
    //     output += '</label>';
    //     output += '<input class="gallery-image-field" id="hall-image-'+i+'" type="file" accept="image/*" name="halls[]" required />';
    //     output += '</div>';
    // }
    //
    // let ImagesWrapper = $(this).parents('.image-with-select').find('.images-wrapper');
    //
    // ImagesWrapper.empty();
    // ImagesWrapper.append(output);

    hallsImages.empty();

    if ($(this).val() > 0){
        $('#halls-note').empty();

        hallsImages.imageUploader({
            imagesInputName:'halls',
            preloadedInputName:'hallsPreloaded',
            label:GalleryText,
            maxFiles: $(this).val(),
            extensions,
            mimes
        });
    }else{
        $('#halls-note').html('برجاء اختيار رقم أكبر من 0 لاضافة الصور')
    }
});

$('#toilets_count').on('change', function (e){
    e.preventDefault();

    // const count = $(this).val();
    // let i, output;
    //
    // for (i=1;i<=count;i++){
    //     output += '<div class="single-image-upload">';
    //     output += '<label class="upload-image-field" for="toilet-image-'+i+'">';
    //     output += '<i class="fa fa-plus"></i>';
    //     output += '<img src="" alt="" />';
    //     output += '</label>';
    //     output += '<input class="gallery-image-field" id="toilet-image-'+i+'" type="file" accept="image/*" name="toilets[]" required />';
    //     output += '</div>';
    // }
    //
    // let ImagesWrapper = $(this).parents('.image-with-select').find('.images-wrapper');
    //
    // ImagesWrapper.empty();
    // ImagesWrapper.append(output);

    toiletsImages.empty();

    toiletsImages.imageUploader({
        imagesInputName:'toilets',
        preloadedInputName:'toiletsPreloaded',
        label:GalleryText,
        maxFiles: $(this).val(),
        extensions,
        mimes
    });
});

$('#pools_count').on('change', function (e){
    e.preventDefault();

    // const count = $(this).val();
    // let i, output;
    //
    // for (i=1;i<=count;i++){
    //     output += '<div class="single-image-upload">';
    //     output += '<label class="upload-image-field" for="pool-image-'+i+'">';
    //     output += '<i class="fa fa-plus"></i>';
    //     output += '<img src="" alt="" />';
    //     output += '</label>';
    //     output += '<input class="gallery-image-field" id="pool-image-'+i+'" type="file" accept="image/*" name="pools[]" required />';
    //     output += '</div>';
    // }
    //
    // let ImagesWrapper = $(this).parents('.image-with-select').find('.images-wrapper');
    //
    // ImagesWrapper.empty();
    // ImagesWrapper.append(output);

    poolsImages.empty();

    poolsImages.imageUploader({
        imagesInputName:'pools',
        preloadedInputName:'poolsPreloaded',
        label:GalleryText,
        maxFiles: $(this).val(),
        extensions,
        mimes
    });
});

$('body').on('change', '.single-image-field', function (){
    const file = $(this).get(0).files[0];
    const image = $(this).parents('.main-image-upload').find('img');
    const icon = $(this).parents('.main-image-upload').find('i');

    if(file){
        var reader = new FileReader();

        reader.onload = function(){
            image.show().attr("src", reader.result);
            icon.hide();
        }

        reader.readAsDataURL(file);
    }
});
