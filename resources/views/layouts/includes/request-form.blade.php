<form style="background-color: #fff;" id="request-form" method="post" action="{{url('request')}}" enctype="multipart/form-data">
    @csrf

    @include('admin.layouts.messages')

{{--    @if(!auth()->check())--}}
    <h3 class="form-title"><span class="form-ribbon">1. بيانات الوحدة</span></h3>
{{--    @endif--}}

    <div class="form-group w-100 mt-0">
        <label class="mb-2 form-label" for="sector">رقم القطاع</label>

        <select class="nice-select w-100" id="sector" name="sector_id" required>
            <option value="">اختر قطاع</option>
            @foreach($sectors as $sector)
                <option value="{{$sector->id}}" {{$sector->id == old('sector_id') ? 'selected' : ''}}>{{$sector->sector_name}}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group w-100 mt-3">
        <label class="mb-2 form-label" for="beach">الشاطئ</label>

        <select class="nice-select w-100" id="beach" name="beach_id" required>
            <option value="">اختر شاطئ</option>
        </select>
    </div>

    <div class="form-group w-100 mt-3">
        <label for="unit" class="form-label">رقم الفيلا</label>
        <input type="text" class="form-control grey" value="{{old('unit_number')}}" id="unit" name="unit_number" required />
    </div>

    <div class="form-group">
        <label for="type" class="form-label">الحالة</label>

        <select name="type" id="type" class="nice-select w-100">
            <option value="investor" {{old('type') == 'investor' ? 'selected' : ''}}>مستثمر</option>
            <option value="owner" {{old('type') == 'investor' ? 'selected' : ''}}>مالك</option>
        </select>
    </div>

    <div class="form-group">
        <label for="attachment" class="form-label">مستند طلب تأهيل وحدة (<a href="{{url('terms')}}">اشتراطات التأهيل</a>) </label>

        <div class="upload-wrapper" style="position: relative;height: 40px">
            <input type="file" onchange="previewFile(this)" class="form-control d-none" id="attachment" name="attachment_1" accept=".doc,.docx,.ppt,application/pdf, image/*" required />

            <label class="custom-file-label" for="attachment">
                <i class="icon_cloud-upload_alt"></i>
                <span>رفع المستند</span>
            </label>
        </div>

        <span id="file-uploaded"></span>
    </div>

    @if(!auth()->check())

        <hr />

        <h3 class="form-title"><span class="form-ribbon">2. بيانات الحساب</span></h3>

        <div class="form-group">
            <label for="request-name" class="form-label">الاسم الثلاثي</label>
            <input type="text" class="form-control grey" value="{{old('name')}}" id="request-name" name="name" required />
        </div>

        <div class="form-group">
            <label for="request-phonenumber" class="form-label">رقم الجوال</label>
            <input type="text" class="form-control grey" value="{{old('phonenumber')}}" id="request-phonenumber" name="phonenumber" required />
        </div>

        <div class="form-group">
            <label for="request-email" class="form-label">البريد الإلكتروني</label>
            <input type="email" class="form-control grey" value="{{old('email')}}" id="request-email" name="email" required />
        </div>

        <div class="form-group">
            <label for="password-field" class="form-label">كلمة المرور</label>
            {{--                    <input type="password" class="form-control" id="password" name="password" required />--}}

            <div class="password-wrapper mt-2">
                <input id="password-field" type="password" class="input" name="password">

                <div class="icon-wrapper">
                    <span toggle="#password-field" class="far fa-eye field-icon toggle-password"></span>
                </div>

                <div class="strength-lines">
                    <div class="line"></div>
                    <div class="line"></div>
                    <div class="line"></div>
                </div>
            </div>
        </div>
    @endif

    <div class="form-group">
        <button
            data-sitekey="{{env('reCaptch_site_key')}}"
            data-callback='onResetForm'
            data-action='submit'
            type="submit" class="gb gb-bordered hover-slide gb9 {{!auth()->check() ? 'w-100' : ''}}">
            <span class="text">تقديم الطلب</span>
        </button>
    </div>
</form>

<script>
    function onResetForm(token) {
        document.getElementById('request-form').submit();
    }

    @if(old('beach_id'))
        const sector_id = $('#sector').val();

        $.post('/api/get-beaches/'+sector_id).done(function (data){
            getBeaches(data);
        })
    @endif

    $('#sector').on('change', function (){
        const sector_id = $(this).val()

        $.post('/api/get-beaches/'+sector_id).done(function (data){
            getBeaches(data);
        })

        // $('#unit').html('<option>اختر فيلا</option>')
    })

    function getBeaches(data){
        let output = '';
        const beach_id = '{{old('beach_id')}}'

        for (let i=0;i<data.data.length;i++){
            output += "<option value='"+data.data[i].id+"' "+ (data.data[i].id == beach_id ? 'selected' : '') +">"+data.data[i].beach+"</option>"
        }

        $('#beach').html(output);

        $('#beach').niceSelect('update');
    }

    $(document).ready(function() {

        // hide/show password
        $(".icon-wrapper").click(function() {
            $(".toggle-password").toggleClass(".ion-eye fa-ellipsis-h");
            var input = $($(".toggle-password").attr("toggle"));
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });

        // strength validation on keyup-event
        $("#password-field").on("keyup", function() {
            var val = $(this).val(),
                color = testPasswordStrength(val);

            styleStrengthLine(color, val);
        });

        // check password strength
        function testPasswordStrength(value) {
            var strongRegex = new RegExp(
                    '^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[=/\()%ยง!@#$%^&*])(?=.{8,})'
                ),
                mediumRegex = new RegExp(
                    '^(((?=.*[a-z])(?=.*[A-Z]))|((?=.*[a-z])(?=.*[0-9]))|((?=.*[A-Z])(?=.*[0-9])))(?=.{6,})'
                );

            if (strongRegex.test(value)) {
                return "green";
            } else if (mediumRegex.test(value)) {
                return "orange";
            } else {
                return "red";
            }
        }

        function styleStrengthLine(color, value) {
            $(".line")
                .removeClass("bg-red bg-orange bg-green")
                .addClass("bg-transparent");

            if (value) {

                if (color === "red") {
                    $(".line:nth-child(1)")
                        .removeClass("bg-transparent")
                        .addClass("bg-red");
                } else if (color === "orange") {
                    $(".line:not(:last-of-type)")
                        .removeClass("bg-transparent")
                        .addClass("bg-orange");
                } else if (color === "green") {
                    $(".line")
                        .removeClass("bg-transparent")
                        .addClass("bg-green");
                }
            }
        }
    });

    function previewFile(input){
        var file = $("input[type=file]").get(0).files[0];

        if(file){
            $("#file-uploaded").html(file.name);
        }
    }
</script>
