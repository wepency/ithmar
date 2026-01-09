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
        <td valign="middle" class="hero bg_white" style="padding:2em 0 2em 0;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;mso-table-lspace:0 !important;mso-table-rspace:0 !important;background:#ffffff;position:relative;z-index:0;">
            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;mso-table-lspace:0 !important;mso-table-rspace:0 !important;border-spacing:0 !important;border-collapse:collapse !important;table-layout:fixed !important;margin:0 auto !important;">
                <tr style="-ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;">
                    <td style="padding:0 2.5em;text-align:right;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;mso-table-lspace:0 !important;mso-table-rspace:0 !important;">
                        <div class="text" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;color:rgba(0,0,0,.3);">
                            <h2 style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;font-family:'Work Sans', sans-serif;color:#000;margin-top:0;font-weight:300;text-align:right;font-size:34px;margin-bottom:15px;line-height:1.2;"></h2>
                            <h3 style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;font-family:'Work Sans', sans-serif;color:#000000;margin-top:0;font-weight:200;text-align:right;font-size:24px;"></h3>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
        <table class="bg_white" role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;mso-table-lspace:0 !important;mso-table-rspace:0 !important;border-spacing:0 !important;border-collapse:collapse !important;table-layout:fixed !important;margin:0 auto !important;background:#ffffff;">
            <tr style="border-bottom:1px solid rgba(0,0,0,.05);-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;">
                <th width="80%" style="text-align:right;padding:0 2.5em;color:#000;padding-bottom:20px;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;">Item</th>
                <th width="20%" style="text-align:right;padding:0 2.5em;color:#000;padding-bottom:20px;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;">Price</th>
            </tr>

            <tr style="border-bottom:1px solid rgba(0,0,0,.05);-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;">
                <td valign="middle" width="80%" style="text-align:right;padding:0 2.5em;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;mso-table-lspace:0 !important;mso-table-rspace:0 !important;">
                    <div class="product-entry" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;display:block;position:relative;float:left;padding-top:20px;">
                        <img src="images/prod-1.jpg" alt style="width:100px;max-width:600px;height:auto;margin-bottom:20px;display:block;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;-ms-interpolation-mode:bicubic;float:left;"><div class="text" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;width:calc(100% - 125px);padding-left:20px;float:left;">
                            <h3 style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;font-family:'Work Sans', sans-serif;color:#000000;margin-top:0;font-weight:400;text-align:right;margin-bottom:0;padding-bottom:0;">Analog Wrest Watch</h3>
                            <span style="-ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;">Small</span>
                            <p style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;margin-top:0;">A small river named Duden flows by their place and supplies it with the necessary regelialia.</p>
                        </div>
                    </div>
                </td>
                <td valign="middle" width="20%" style="text-align:right;padding:0 2.5em;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;mso-table-lspace:0 !important;mso-table-rspace:0 !important;">
                    <span class="price" style="color:#000;font-size:20px;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;">$120</span>
                </td>
            </tr>
            <tr style="border-bottom:1px solid rgba(0,0,0,.05);-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;">
                <td valign="middle" width="80%" style="text-align:right;padding:0 2.5em;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;mso-table-lspace:0 !important;mso-table-rspace:0 !important;">
                    <div class="product-entry" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;display:block;position:relative;float:left;padding-top:20px;">
                        <img src="images/prod-2.jpg" alt style="width:100px;max-width:600px;height:auto;margin-bottom:20px;display:block;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;-ms-interpolation-mode:bicubic;float:left;"><div class="text" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;width:calc(100% - 125px);padding-left:20px;float:left;">
                            <h3 style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;font-family:'Work Sans', sans-serif;color:#000000;margin-top:0;font-weight:400;text-align:right;margin-bottom:0;padding-bottom:0;">Analog Wrest Watch</h3>
                            <span style="-ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;">Small</span>
                            <p style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;margin-top:0;">A small river named Duden flows by their place and supplies it with the necessary regelialia.</p>
                        </div>
                    </div>
                </td>
                <td valign="middle" width="20%" style="text-align:right;padding:0 2.5em;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;mso-table-lspace:0 !important;mso-table-rspace:0 !important;">
                    <span class="price" style="color:#000;font-size:20px;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;">$120</span>
                </td>
            </tr>
            <tr>
                <td style="text-align:right;padding:1em 2.5em;">
                    <p style="text-align: center"><a href="#" class="btn btn-primary" style="text-decoration:none;color:#ffffff;padding:10px 15px;display:inline-block;border-radius:5px;background:#17bebb;"></a></p>
                </td>
            </tr>
        </table>
    </tr>
@endsection
