@extends('layouts.front-page')

@section('styles')
    <style>
        .bond-wrap{
            background-color: #ffffff;
            border-radius: 25px;
            width: 800px !important;
            margin: auto;
            padding: 10px 40px;
            border: 2px solid #3498db;
            box-shadow: 0 0 30px rgb(52 152 219 / 40%);
            -webkit-box-shadow: 0 0 30px rgb(52 152 219 / 40%);
            -moz-box-shadow: 0 0 30px rgb(52 152 219 / 40%);
            -o-box-shadow: 0 0 30px rgb(52 152 219 / 40%);
        }
        .bond-header{
            display: flex;
            align-items: center;
        }
        .bond-header .logo,
        .bond-header .headline,
        .bond-header .date{
            flex: 1;
        }
        .bond-header .date{
            text-align: left;
        }
        .bond-header .headline{
            text-align: center;
            color: #000e7b;
        }
        .bond-header .headline .bond-line{
            width: 200px;
            height: 3px;
            background-color: #000e7b;
            margin: auto;
        }
        footer#bond-footer{
            text-align: left;
            margin-top: 2rem;
        }
        footer#bond-footer .receiver{
            color: #000e7b;
            font-weight: 600
        }
        .highlight{
            color: #3498db;
            text-decoration: underline;
            font-style: italic
        }
        .text{
            line-height: 2.1rem;
            font-size: 17px;
        }

        @media (max-width: 768px) {
            .bond-container{
                overflow: scroll;
            }
        }
    </style>
@endsection

@section('content')

    <div class="main-container" style="">
        <div class="bond-container">
            <div class="bond-wrap">
                <div class="bond-header">
                    <div class="logo"></div>

                    <div class="headline">
                        <h2 class="ar-bond-headline">سند قبض</h2>
                        <h4 class="en-bond-headline">Receipt Voucher</h4>
                        <div class="bond-line"></div>
                    </div>
                    <div class="date">
                        <span class="ar-date">تاريخ</span>
                        <span class="ml-2 mr-2">{{$bond->created_at->format('d / m / Y')}}</span>
                        <span class="en-date">Date</span>
                    </div>
                </div>

                <div class="bond-content">
                    <p>سند رقم: <span class="highlight">{{str_pad($bond->id,6,'0',STR_PAD_LEFT)}}</span></p>

                    <p class="text">انه في يوم <span class="highlight">{{$bond->created_at->format('d-m-Y')}}</span> و بناء على العقد المبرم رقم <span class="highlight">{{$bond->contract->code ?? ''}}</span> فقد تم استلام عربون بقيمة <span class="highlight">{{$bond->value." (".num_to_chars($bond->value)." ريال سعودي)"}}</span> ، تشمل قيمة العقد من السيد/ <span class="highlight">{{$bond->contract->tenant_name ?? ''}}</span> ، وذلك مقابل ايجار الوحدة رقم <span class="highlight">{{$bond->contract->unit->unit_number ?? ''}}</span> في شاطئ <span class="highlight">{{$bond->contract->beach->beach ?? ''}}</span> بدرة العروس (على ان يتم إكتمال المبلغ المتعاقد علية قبل الدخول).</p>

                    @if(!is_null($bond->notes))
                        <p class="mt-3 mb-0"><i>ملاحظة: {{$bond->notes}}</i></p>
                    @endif
                </div>
                <footer id="bond-footer">
                    <p class="receiver mb-0">المستلم</p>
                    <p class="receiver mb-0">Receiver</p>

                    <h6><i>{{$bond->user->name}}</i></h6>
                </footer>
            </div>
        </div>

        <div class="buttons text-center mt-4">
            <h6>مشاركة السند عبر:</h6>
            <a href="whatsapp://send?text= سند قبض رقم {{str_pad($bond->id,6,'0',STR_PAD_LEFT)}} ، {{url()->current()}}" class="btn btn-success social-share whatsapp">
                <i class="fab fa-whatsapp mr-1"></i>
                <span class="text">واتساب</span>
            </a>

            <button class="btn btn-dark social-share copy-link">
                <i class="far fa-copy mr-1"></i>
                <span class="text">نسح الرابط</span>
            </button>
        </div>
    </div>
@endsection

@section('scripts')
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
@endsection
