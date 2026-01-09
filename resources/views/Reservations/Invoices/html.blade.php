<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>{{$page_title}}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.rtl.min.css" integrity="sha384-gXt9imSW0VcJVHezoNQsP+TNrjYXoGcrqBZJpry9zJt8PCQjobwmhMGaDHTASo9N" crossorigin="anonymous">

    <style>
        body{
            margin-top:20px;
            background-color: #333;
            font-family: 'Cairo', sans-serif;
        }
        #invoice {
            padding: 0;
        }

        .invoice {
            position: relative;
            background-color: #FFF;
            min-height: 680px;
            padding: 15px
        }

        .invoice header {
            padding: 10px 0;
            margin-bottom: 20px;
            border-bottom: 1px solid #0d6efd
        }

        .invoice .company-details {
            text-align: left
        }

        .invoice .company-details .name {
            margin-top: 0;
            margin-bottom: 0
        }

        .invoice .contacts {
            margin-bottom: 20px
        }

        .invoice .invoice-to {
            text-align: right;
        }

        .invoice .invoice-to .to {
            margin-top: 0;
            margin-bottom: 0
        }

        .invoice .invoice-details {
            text-align: left
        }

        .invoice .invoice-details .invoice-id {
            margin-top: 0;
            color: #0d6efd
        }

        .invoice main {
            padding-bottom: 50px
        }

        .invoice main .thanks {
            margin-top: -100px;
            font-size: 2em;
            margin-bottom: 50px
        }

        .invoice main .notices {
            padding-left: 6px;
            border-left: 6px solid #0d6efd;
            background: #e7f2ff;
            padding: 10px;
        }

        .invoice main .notices .notice {
            font-size: 1.2em
        }

        .invoice table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 20px
        }

        .invoice table td,
        .invoice table th {
            padding: 15px;
            background: #eee;
            border-bottom: 1px solid #fff
        }

        .invoice table th {
            white-space: nowrap;
            font-weight: 400;
            font-size: 16px
        }

        .invoice table td h3 {
            margin: 0;
            font-weight: 400;
            color: #0d6efd;
            font-size: 1.2em
        }

        .invoice table .qty,
        .invoice table .total,
        .invoice table .unit {
            text-align: right;
            font-size: 1.2em
        }

        .invoice table .no {
            color: #fff;
            font-size: 1.6em;
            background: #0d6efd
        }

        .invoice table .unit {
            background: #ddd
        }

        .invoice table .total {
            background: #0d6efd;
            color: #fff
        }

        .invoice table tbody tr:last-child td {
            border: none
        }

        .invoice table tfoot td {
            background: 0 0;
            border-bottom: none;
            white-space: nowrap;
            text-align: right;
            padding: 10px 20px;
            font-size: 1.2em;
            border-top: 1px solid #aaa
        }

        .invoice table tfoot tr:first-child td {
            border-top: none
        }
        .card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 0 solid rgba(0, 0, 0, 0);
            border-radius: .25rem;
            margin-bottom: 1.5rem;
            /*box-shadow: 0 2px 6px 0 rgb(218 218 253 / 65%), 0 2px 6px 0 rgb(206 206 238 / 54%);*/
        }

        .invoice table tfoot tr:last-child td {
            color: #0d6efd;
            font-size: 1.4em;
            border-top: 1px solid #0d6efd
        }

        .invoice table tfoot tr td:first-child {
            border: none
        }

        .invoice footer {
            width: 100%;
            text-align: center;
            color: #777;
            border-top: 1px solid #aaa;
            padding: 8px 0
        }

        @media print {
            .invoice {
                font-size: 11px !important;
                overflow: hidden !important
            }
            .invoice footer {
                position: absolute;
                bottom: 10px;
                page-break-after: always
            }
            .invoice>div:last-child {
                page-break-before: always
            }
        }

        .invoice main .notices {
            padding-left: 6px;
            border-left: 6px solid #0d6efd;
            background: #e7f2ff;
            padding: 10px;
        }
        .toolbar .buttons button:first-child{
            margin-left: 5px;
        }
        .toolbar .buttons button{
            width: calc(50% - 5px);
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script>

    </script>
</head>
<body>
<div class="container">
    <div class="toolbar hidden-print mb-3">
        <div class="text-end buttons">
            <button type="button" class="btn btn-danger"><i class="fa fa-print"></i> طباعة العقد </button>
            <button type="button" class="btn btn-success"><i class="fa fa-file-pdf-o"></i> مشاركة عبر واتساب </button>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div id="invoice">
                <div class="invoice overflow-auto">
                    <div style="min-width: 600px">
                        <header>
                            <div class="row">
                                <div class="col-2">
                                    <a href="javascript:;">
                                        <img src="{{asset('images/logo-contract.jpg')}}" width="180" alt="">
                                    </a>
                                </div>
                                <div class="col company-details">
                                    <h2 class="name">
                                        <a target="_blank" href="javascript:;">
                                            إثمار النافورة لافونتين
                                        </a>
                                    </h2>

                                    <div>Website: {{url('/')}}</div>
                                    <div>Phone: +966548408369</div>
                                    <div>Email: arus@lafontaine.com.sa</div>
                                    <div>Vat: 300131063400003</div>
                                </div>
                            </div>
                        </header>
                        <main>
                            <div class="row contacts">
                                <div class="col invoice-to">
                                    <div class="text-gray-light">الفاتوره صاده لـ:</div>
                                    <h2 class="to">{{$invoice->unit->user->name ?? ''}}</h2>
{{--                                    <div class="address">796 Silver Harbour, TX 79273, US</div>--}}
                                    <div class="email"><a href="mailto:john@example.com">{{$invoice->unit->user->email ?? ''}}</a>
                                    </div>
                                </div>

                                <div class="col invoice-details">
                                    <h1 class="invoice-id">فاتورة رقم {{$invoice->id}}</h1>
                                    <div class="date">تاريخ صدور الفاتورة: {{$invoice->created_at->format('d/m/Y')}}</div>
                                </div>
                            </div>
                            <table>
                                <thead>
                                <tr>
{{--                                    <th>#</th>--}}
{{--                                    <th class="text-left">DESCRIPTION</th>--}}
{{--                                    <th class="text-right">HOUR PRICE</th>--}}
                                    <th colspan="2" class="text-right">إجمالي مبلغ الحجوزات</th>
                                    <th class="text-right">{{currency($invoice->total)}}</th>
                                </tr>
                                </thead>
                                <tbody>
{{--                                <tr>--}}
{{--                                    <td class="no">04</td>--}}
{{--                                    <td class="text-left">--}}
{{--                                        <h3>--}}
{{--                                            <a target="_blank" href="javascript:;">--}}
{{--                                                Youtube channel--}}
{{--                                            </a>--}}
{{--                                        </h3>--}}
{{--                                        <a target="_blank" href="javascript:;">--}}
{{--                                            Useful videos--}}
{{--                                        </a> to improve your Javascript skills. Subscribe and stay tuned :)</td>--}}
{{--                                    <td class="unit">$0.00</td>--}}
{{--                                    <td class="qty">100</td>--}}
{{--                                    <td class="total">$0.00</td>--}}
{{--                                </tr>--}}
{{--                                <tr>--}}
{{--                                    <td class="no">01</td>--}}
{{--                                    <td class="text-left">--}}
{{--                                        <h3>Website Design</h3>Creating a recognizable design solution based on the company's existing visual identity</td>--}}
{{--                                    <td class="unit">$40.00</td>--}}
{{--                                    <td class="qty">30</td>--}}
{{--                                    <td class="total">$1,200.00</td>--}}
{{--                                </tr>--}}
{{--                                <tr>--}}
{{--                                    <td class="no">02</td>--}}
{{--                                    <td class="text-left">--}}
{{--                                        <h3>Website Development</h3>Developing a Content Management System-based Website</td>--}}
{{--                                    <td class="unit">$40.00</td>--}}
{{--                                    <td class="qty">80</td>--}}
{{--                                    <td class="total">$3,200.00</td>--}}
{{--                                </tr>--}}
{{--                                <tr>--}}
{{--                                    <td class="no">03</td>--}}
{{--                                    <td class="text-left">--}}
{{--                                        <h3>Search Engines Optimization</h3>Optimize the site for search engines (SEO)</td>--}}
{{--                                    <td class="unit">$40.00</td>--}}
{{--                                    <td class="qty">20</td>--}}
{{--                                    <td class="total">$800.00</td>--}}
{{--                                </tr>--}}
                                </tbody>
                                <tfoot>
{{--                                <tr>--}}
{{--                                    <td colspan="2"></td>--}}
{{--                                    <td colspan="2">إجمالي مبلغ الحجوزات</td>--}}
{{--                                    <td>$5,200.00</td>--}}
{{--                                </tr>--}}
                                <tr>
{{--                                    <td colspan="2"></td>--}}
                                    <td colspan="2">العمولة</td>
                                    <td>{{currency($invoice->profit)}}</td>
                                </tr>

                                <tr>
{{--                                    <td colspan="2"></td>--}}
                                    <td colspan="2">ضريبة العمولة</td>
                                    <td>{{currency($invoice->tax)}}</td>
                                </tr>

                                @if($invoice->violations)
                                    <tr>
                                        <td colspan="2">المخالفات</td>
                                        <td>{{currency($invoice->violations)}}</td>
                                    </tr>
                                @endif

                                <tr>
{{--                                    <td colspan="2"></td>--}}
                                    <td colspan="2">إجمالي المبلغ المدفوع</td>
                                    <td>{{currency($invoice->profit+$invoice->tax+$invoice->violations)}}</td>
                                </tr>

                                </tfoot>
                            </table>

{{--                            <div class="thanks">شكرا لك</div>--}}

{{--                            <div class="notices">--}}
{{--                                <div>NOTICE:</div>--}}
{{--                                <div class="notice">A finance charge of 1.5% will be made on unpaid balances after 30 days.</div>--}}
{{--                            </div>--}}
                        </main>

{{--                        <footer>Invoice was created on a computer and is valid without the signature and seal.</footer>--}}
                    </div>
                    <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
                    <div></div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
