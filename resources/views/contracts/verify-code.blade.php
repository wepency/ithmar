@extends('layouts.front-page')

@section('styles')
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
            color: var(--text1);
            background: var(--background1);
            border: 0;
            border: 4px solid transparent;
        }
        form input:invalid {
            box-shadow: none;
        }
        form input:focus {
            outline: none;
            border: 4px solid var(--brand1);
            background: var(--background3);
        }


        .btn10{
            top: 40px;
            font-family: "proxima-nova", sans-serif;
            font-weight: 500;
            font-size: 13px;
            text-transform: uppercase!important;
            letter-spacing: 2px;
            color: #fff;
            cursor: hand;
            text-align: center;
            text-transform: capitalize;
            border: 1px solid #fff;
            border-radius:50px;
            position: relative;
            overflow: hidden!important;
            -webkit-transition: all .3s ease-in-out;
            -moz-transition: all .3s ease-in-out;
            -o-transition: all .3s ease-in-out;
            transition: all .3s ease-in-out;
            background: transparent!important;
            z-index:10;

        }


        .btn10:hover{
            border: 1px solid #071982;
            color: #80ffd3!important;
        }
        .btn10::before {
            content: '';
            width: 0%;
            height: 100%;
            display: block;
            background: #071982;
            position: absolute;
            -ms-transform: skewX(-20deg);
            -webkit-transform: skewX(-20deg);
            transform: skewX(-20deg);
            left: -10%;
            opacity: 1;
            top: 0;
            z-index: -12;
            -moz-transition: all .7s cubic-bezier(0.77, 0, 0.175, 1);
            -o-transition: all .7s cubic-bezier(0.77, 0, 0.175, 1);
            -webkit-transition: all .7s cubic-bezier(0.77, 0, 0.175, 1);
            transition: all .7s cubic-bezier(0.77, 0, 0.175, 1);
            box-shadow:2px 0px 14px rgba(0,0,0,.6);
        }

        .btn10::after {
            content: '';
            width: 0%;
            height: 100%;
            display: block;
            background: #80ffd3;
            position: absolute;
            -ms-transform: skewX(-20deg);
            -webkit-transform: skewX(-20deg);
            transform: skewX(-20deg);
            left: -10%;
            opacity: 0;
            top: 0;
            z-index: -15;
            -webkit-transition: all .94s cubic-bezier(.2,.95,.57,.99);
            -moz-transition: all .4s cubic-bezier(.2,.95,.57,.99);
            -o-transition: all .4s cubic-bezier(.2,.95,.57,.99);
            transition: all .4s cubic-bezier(.2,.95,.57,.99);
            box-shadow: 2px 0px 14px rgba(0,0,0,.6);
        }
        .btn10:hover::before, .btn1O:hover::before{
            opacity:1;
            width: 116%;
        }
        .btn10:hover::after, .btn1O:hover::after{
            opacity:1;
            width: 120%;
        }

        .transition{
            position: absolute;
            top: -10%;
            left: 0%;
            width: 100%;
            height: 0%;
            background: #80ffd3;
            z-index:-1;
            /*     -ms-transform: skewX(-50deg);
                -webkit-transform: skewX(-50deg);
                transform: skewX(-50deg); */
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3 class="page-title">{{$page_title}}</h3>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/')}}">الرئيسية</a></li>
                        <li class="breadcrumb-item"><a href="{{investor_url('contracts')}}">العقود</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{$page_title}}</li>
                    </ol>
                </nav>
            </div>

            <div class="card-body">
                <form id="confirmation-code" action="{{url('confirm-code-validation')}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div id="phone-verification-code" class="form-box">
                        <div class="row justify-content-center">
                            <div class="col-6">
                                <div class="alert alert-danger" style="display: none" id="validate-code-error"></div>
                            </div>
                            <div class="col-12"></div>

                            <h5 class="col-6 text-center">لقد تم ارسال كود التفعيل إلى رقم <span id="phonenumber-value"></span> ، برجاء كتابة الكود بالاسفل للتحقق من صحة الرقم.</h5>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-6">
                                <fieldset class='number-code'>
                                    <legend>كود التفعيل</legend>
                                    <div>
                                        <input name='code' class='code-input' autocomplete="off" required/>
                                        <input name='code' class='code-input' autocomplete="off" required/>
                                        <input name='code' class='code-input' autocomplete="off" required/>
                                        <input name='code' class='code-input' autocomplete="off" required/>
                                        <input name='code' class='code-input' autocomplete="off" required/>
                                        <input name='code' class='code-input' autocomplete="off" required/>
                                    </div>
                                </fieldset>
                            </div>
                        </div>

                        <div class="row justify-content-center mt-4">
                            <div class="form-group">
                                <a href="#" class="btn10">
                                    <span>button 10</span>
                                    <div class="transition"></div>
                                </a>

                                <button id="verify-code" class="btn btn-login">التحقق</button>
                                <button data-form="phone-verification-1" class="btn btn-transparent go-back">العودة للخلف</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const inputElements = [...document.querySelectorAll('input.code-input')]

        inputElements.forEach((ele,index)=>{
            ele.addEventListener('keydown',(e)=>{
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
            console.log(code)
        }
    </script>
@endsection
