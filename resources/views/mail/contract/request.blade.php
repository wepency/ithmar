{{--@component('mail::message')--}}
{{--# {{$header}}--}}

{{--{{$body}}--}}

{{--@component('mail::button', ['url' => $url])--}}
{{--    {{$button_text}}--}}
{{--@endcomponent--}}

{{--شكرا, <br/>--}}
{{--إثمار--}}
{{--@endcomponent--}}

@extends('mail.layout')

@section('content')
    <tr style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
        <td valign="middle" class="hero bg_white" style="padding:1em 0 2em 0;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;mso-table-lspace:0 !important;mso-table-rspace:0 !important;background:#ffffff;position:relative;z-index:0;">
            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;mso-table-lspace:0 !important;mso-table-rspace:0 !important;border-spacing:0 !important;border-collapse:collapse !important;table-layout:fixed !important;margin:0 auto !important;">
                <tr style="-ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;">
                    <td style="padding:0 2.5em;text-align:right;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;mso-table-lspace:0 !important;mso-table-rspace:0 !important;">
                        <div class="text" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;color:rgba(0,0,0,.3);">
                            <h2 style="font-family:tahoma,sans-serif;color:#000;margin-top:0;font-weight:300;text-align:right;font-size:34px;margin-bottom:15px;line-height:1.2;direction:rtl;">عقد جديد بانتظار المراجعة</h2>
                            <h3 style="font-family:Tahoma,sans-serif;color:#777;margin-top:0;font-weight:100;text-align:right;font-size:16px;direction:rtl;">هناك عقد جديد بإنتظار التفعيل برجاء المراجعة و اتخاذ الإجراء المناسب.</h3>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
        <table class="bg_white" role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;mso-table-lspace:0 !important;mso-table-rspace:0 !important;border-spacing:0 !important;border-collapse:collapse !important;table-layout:fixed !important;margin:0 auto !important;background:#ffffff;">
            <tr>
                <td style="text-align:right;padding:1em 2.5em;">
                    <p style="text-align:center"><a href="http://durrah.fpe.sa" class="btn btn-primary" style="text-decoration:none;color:#ffffff;padding:10px 15px;display:inline-block;border-radius:5px;background:#17bebb;">العقود بإنتظار المراجعة</a></p>
                </td>
            </tr>
        </table>
    </tr>
@endsection
