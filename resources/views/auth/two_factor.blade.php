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

        form input.code-input-validate {
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
    </style>
@endsection

@section('content')
    <div class="container home-container">
        <div class="card">

            <div class="card-body">
                @include('admin.layouts.messages')

                <form id="confirmation-code" action="{{route('factor.validate')}}" method="POST">
                    @csrf

                    <div id="code" class="form-box">
                        <div class="row justify-content-center">
                            <div class="col-12">
                                <div class="alert alert-danger" style="display: none" id="validate-code-error"></div>
                            </div>
                            <div class="col-12"></div>

                            <h5 class="col-12 text-center">لقد تم ارسال كود التفعيل إلى رقم <b> {{auth()->user()->phonenumber}}</b> , برجاء كتابة الكود بالأسفل لتسجيل الدخول.</h5>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-sm-12 col-md-6">
                                <fieldset class='number-code'>
                                    <legend>كود التفعيل</legend>
                                    <div>
                                        <input name='code[]' class='code-input-validate' autofocus autocomplete="off" required />
                                        <input name='code[]' class='code-input-validate' autocomplete="off" required />
                                        <input name='code[]' class='code-input-validate' autocomplete="off" required />
                                        <input name='code[]' class='code-input-validate' autocomplete="off" required />
                                    </div>
                                </fieldset>
                            </div>
                        </div>

                        <div class="row justify-content-center mt-4">
                            <div class="form-group">
                                <button type="submit" id="verify-code-factor" class="gb gb-bordered hover-slide gb9"><i class="arrow_right"></i> <span class="text">التحقق</span></button>
                            </div>
                        </div>
                    </div>
                </form>

                <form action="{{route('factor.validate.resend')}}" class="text-center" method="POST">
                    @csrf
                    <button type="submit" id="resend-code-button" class="gb gb-bordered hover-slide ml-2 hover-fill prev gb10"><i class="fa fa-recycle"></i> <span class="text">اعادة ارسال الكود</span></button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const inputElements = [...document.querySelectorAll('input.code-input-validate')]

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
