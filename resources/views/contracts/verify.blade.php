@extends('layouts.front-page')

@section('styles')
    <meta property="og:url"                content="{{url()->current()}}" />
    <meta property="og:type"               content="article" />
    <meta property="og:title"              content="تأكيد رقم جوال عقد" />
    <meta property="og:description"        content="اطلع على مسودة العقد و قم بتفعيل رقم الجوال لاتمام الحجز." />
    <meta property="og:image"              content="{{asset('images/ithmar-logo.png')}}" />

    <link rel="stylesheet" href="{{asset('css/contract.css')}}" />
    <link rel="stylesheet" href="{{asset('css/sharetastic.css')}}" />

    <style>
        :root {
            --spacing: 8px;
            --hue: 400;
            --background1: hsl(214, 14%, 20%);
            --background2: hsl(214, 14%, 13%);
            --background3: hsl(214, 14%, 5%);
            --brand1: hsl(var(--hue) 80% 60%);
            --text1: hsl(0,0%,100%);
            --text2: hsl(0,0%,90%);
        }
        #phone-verification-1{
            display: block;
        }
        #phone-verification-2{
            display: none;
        }
        .btn-transparent{
            background: transparent;
            color: #333;
            border: 2px solid #D39D48;
        }
        .btn-transparent:hover{
            background-color: #D39D48 !important;
            color: #FFF !important;
        }
        #counter{
            font-size: 25px;
            font-weight: bolder;
            color: #2196f3;
        }

        /* Verify Code */
        @media only screen and (max-width: 600px) {
            body {
                font-size: 1rem;
            }
        }
        a {
            text-decoration: none;
        }

        .number-code > div {
            display: flex;
            flex-direction: row-reverse;
        }
        .number-code > div > input:not(:last-child) {
            margin-right: calc(var(--spacing) * 2);
        }

        .content-area {
            display: flex;
            flex-direction: column;
            gap: calc(var(--spacing) * 2);
            background: var(--background2);
            padding: var(--spacing);
            border-radius: var(--spacing);
            max-width: min(100%, 50rem);
        }
        .content-area p {
            color: var(--text2);
            font-size: 0.8em;
        }

        form input.code-input {
            font-size: 1.5em;
            width: 1em;
            text-align: center;
            flex: 1 0 1em;
        }
        form input.code-input:disabled {
            background-color: #cccccc;
            opacity: .4;
        }
        form input[type=submit] {
            margin-left: auto;
            display: block;
            font-size: 1em;
            cursor: pointer;
            transition: all cubic-bezier(0.4, 0, 0.2, 1) 0.1s;
        }
        form input[type=submit]:hover {
            background: var(--background3);
        }
        form input {
            padding: var(--spacing);
            border-radius: calc(var(--spacing) / 2);
            color: #000;
            background: #f9f9f9;
            border: 1px solid #ccc;
        }
        form input:invalid {
            box-shadow: none;
        }
        form input:focus {
            outline: none;
            border: 1px solid #3498db;
        }

        .card .card-body{
            min-height: 370px;
        }

        @media (max-width: 600px) {
            .gb.gb-bordered{
                min-width: initial !important;
                font-size: 15px;
            }
            .card .card-body{
                min-height: initial !important;
            }
            .number-code > div > input:not(:last-child){
                margin-right: 5px !important;
            }
        }
    </style>
@endsection

@section('content')

    <div class="container main-container">
        <div class="form-container">
            <ul id="section-tabs">
                <li><span>1.</span> بيانات العقد </li>
                <li class="current active"><span>2.</span> تأكيد رقم الجوال</li>
                <li><span>3.</span> الدفع و التفعيل</li>
            </ul>

            <div class="card">

                <div class="card-body">
{{--                    <div class="alert alert-success alert-dismissible new2 p-4" role="alert">--}}
{{--                        <i class="alert-icon icon_close_alt2" aria-hidden="true"></i>--}}

{{--                        <div class="alert-body">هل تريد عرض مسودة العقد قبل الاستمرار؟</div>--}}

{{--                    </div>--}}

{{--                    <div class="form-group">--}}
{{--                        <a href="{{getDraftLink($contract)}}" class="gb gb-bordered hover-slide ml-2 hover-fill gb9"><i class="icon_document_alt"></i> <span class="text">عرض المسودة</span></a>--}}
{{--                    </div>--}}

                    <form id="confirmation-code" data-form-code="{{contractMix($contract)}}" action="{{url('confirm-code-validation/'.$contract->code)}}" method="POST">
                        @csrf
                        @method('PUT')

                        <div id="phone-verification-1" class="form-box">

                            <div class="row justify-content-center">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="phonenumber" class="form-label">رقم جوال المستأجر</label>
                                        <p class="field-description"><small>برجاء إدخال رقم جوال المستأجر المكون من 9 خانات لإستقبال كود التفعيل.</small></p>

                                        <label for="phonenumber" class="phone-key">+966</label>
                                        <input autofocus class="form-control grey is-phone-box" onkeyup="formatPhone(this, event)" id="phonenumber" max="9" type="number" name="phonenumber" placeholder="__ ___ ____" />
                                        <i class=""></i>
                                    </div>

                                    <div class="text-danger" id="phone-error"></div>

                                </div>
                            </div>


                            <div class="row justify-content-center mt-4">
                                <div class="form-group d-flex">
                                    <button class="g-recaptcha gb gb-bordered hover-slide gb9" id="verify-number" data-contract="{{contractMix($contract)}}" disabled><i class="icon_check"></i> <span class="text"> تأكيد الرقم </span></button>
                                    <a href="{{getDraftLink($contract)}}" class="gb gb-bordered hover-slide ml-2 hover-fill gb9"><i class="icon_document_alt"></i> <span class="text">عرض المسودة</span></a>

{{--                                    @if(auth()->check())--}}
{{--                                        <a href="{{url('contracts')}}" class="gb gb-bordered hover-slide hover-fill gb10"><i class="icon_documents_alt"></i> <span class="text"> العقود </span></a>--}}
{{--                                    @endif--}}
                                </div>
                            </div>

                        </div>

                        <div id="phone-verification-2" class="form-box">
                            <div class="row justify-content-center">
                                <div class="col-md-6 col-12">
                                    <div class="alert alert-danger" style="display: none" id="validate-code-error"></div>
                                </div>
                                <div class="col-12"></div>

                                <div class="col-md-6 col-12 text-left">
                                    <label for="code-cell-1" class="form-label">كود التحقق</label>
                                    <p class="field-description mb-0">لقد تم ارسال كود التفعيل إلى رقم <span id="phonenumber-value"></span> ، برجاء كتابة الكود بالاسفل للتحقق من صحة الرقم.</p>
                                    <p class="mb-1"><button class="link valid text-left" id="edit-phone">تعديل رقم الجوال</button></p>
                                </div>
                            </div>

                            <div class="row justify-content-center">
                                <div class="col-md-6 col-12">
                                    <fieldset class='number-code'>
                                        <div>
                                            <input id="code-cell-1" name='code' class='code-input' autocomplete="off" required/>
                                            <input id="code-cell-2" name='code' class='code-input' autocomplete="off" required/>
                                            <input id="code-cell-3" name='code' class='code-input' autocomplete="off" required/>
                                            <input id="code-cell-4" name='code' class='code-input' autocomplete="off" required/>
                                            <input id="code-cell-5" name='code' class='code-input' autocomplete="off" required/>
                                            <input id="code-cell-6" name='code' class='code-input' autocomplete="off" required/>
                                        </div>
                                    </fieldset>

                                    <div class="text-danger mt-2" id="code-error"></div>

                                    <p class="text-right mt-2 resend-wrap"><button class="link deactivated reverify-counter" data-seconds="60" type="button" disabled>ارسال كود أخر <span class="send-counter"> بعد 60 ثانية</span></button></p>
                                </div>
                            </div>

                            <div class="row justify-content-center mt-4">
                                <div class="form-group d-flex">
                                    <button class="g-recaptcha gb gb-bordered hover-slide gb9" id="verify-code" disabled><i class="icon_check"></i> <span class="text"> التحقق </span> <span class="loader"></span></button>
                                    <a href="{{getDraftLink($contract)}}" class="gb gb-bordered hover-slide ml-2 hover-fill gb9"><i class="icon_document_alt"></i> <span class="text">عرض المسودة</span></a>

{{--                                    @if(auth()->check())--}}
{{--                                        <a href="{{url('contracts')}}" class="gb gb-bordered hover-slide hover-fill gb10"><i class="icon_documents_alt"></i> <span class="text"> العقود </span></a>--}}
{{--                                    @endif--}}
                                </div>
                            </div>
                        </div>
                    </form>

                    <h6 class="text-center mt-4">مشاركه صفحه توثيق الجوال عبر:</h6>
                    <div class="text-center">
                        <a href="whatsapp://send?text= برجاء مراجعة المسودة و تفعيل الجوال من خلال اتباع الرابط ، {{url()->current()}}" class="btn btn-success social-share whatsapp">
                            <i class="fab fa-whatsapp mr-1"></i>
                            <span class="text">واتساب</span>
                        </a>

                        <button class="btn btn-dark social-share copy-link">
                            <i class="far fa-copy mr-1"></i>
                            <span class="text">نسح الرابط</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('js/sharetastic.js')}}"></script>
    <script src="{{asset('js/notify.min.js')}}"></script>

    <script>
        function share() {
            // collet the user input
            var message = $("input[name=message]").val();
            // JavaScript function to open URL in new window
            window.open( "whatsapp://send?text=" + message, '_blank');
        }

        $('.copy-link').on('click', function (){

            var sampleTextarea = document.createElement("textarea");
            document.body.appendChild(sampleTextarea);
            sampleTextarea.value = '{{url()->current()}}'; //save main text in it
            sampleTextarea.select(); //select textarea contenrs
            document.execCommand("copy");
            document.body.removeChild(sampleTextarea);

            $(this).notify(
                "تم نسخ الرابط بنجاح",
                {
                    position:"top",
                    className: 'success'
                },
                'success'
            );
        })
    </script>

    <script>
        const inputElements = [...document.querySelectorAll('input.code-input')]

        inputElements.forEach((ele,index)=>{
            ele.addEventListener('keydown',(e)=>{
                console.log(e.keyCode)
                if(e.keyCode === 8 && e.target.value==='') inputElements[Math.max(0,index-1)].focus()
            })
            ele.addEventListener('input',(e)=>{
                const [first,...rest] = e.target.value
                e.target.value = first ?? ''
                if(index!==inputElements.length-1 && first!==undefined) {
                    inputElements[index+1].focus()
                    inputElements[index+1].value = rest.join('')
                    inputElements[index+1].dispatchEvent(new Event('input'))
                }
            })
        })


        function onSubmit(e){
            e.preventDefault()
            const code = [...document.getElementsByTagName('input')]
                .filter(({name})=>name)
                .map(({value})=>value)
                .join('')
        }
    </script>
@endsection
