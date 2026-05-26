<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>مسودة عقد ايجار - {{ $contract->code }}</title>
    <meta content="width=1130, initial-scale=0.35, user-scalable=yes" name="viewport">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #333;
        }

        .buttons-wrap {
            display: flex;
            align-items: center;
        }

        .cancelled-celed {
            position: absolute;
            top: 100px;
            left: 50%;
            z-index: 99;
            transform: translate(-50%);
        }

        .cus-border-table {
            border: 2px dashed gray;
            padding-bottom: 0;
            padding-top: 0;
            vertical-align: middle;
            text-align: center;
        }

        .cus-width {
            min-width: 60px;
        }

        .cus-label {
            vertical-align: middle;
        }

        .cus-padding {
            padding: 5px;
        }

        .icon {
            max-width: 24px;
        }

        .pdf-container {
            position: relative;
        }

        .pdf {
            border: 4px solid red;
            padding: 13px;
            width: 1130px;
            margin: auto;
            background-color: white;
            direction: ltr;
        }

        .pdf * {
            font-family: "Cairo", sans-serif;
        }

        .blurred {
            filter: blur(5px);
            -webkit-filter: blur(5px);
        }

        h2 {
            text-align: center;
        }

        .contract-serial {
            display: inline-block;
            padding: 10px;
            border: 2px dashed gray;
        }

        /* Draft watermark overlay */
        #printMe {
            position: relative;
            overflow: hidden;
        }

        #printMe::before,
        #printMe::after {
            content: '';
            position: absolute;
        }

        #printMe::after {
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(52, 73, 94, .1);
        }

        #printMe::before {
            background-image: url('/images/draft-pattern.png');
            transform: rotate(-15deg);
            width: 150%;
            height: 150%;
            top: -200px;
            left: -200px;
            background-size: 310px;
            opacity: 0.3;
            background-repeat: space;
        }

        .section-one,
        .section-two {
            display: flex;
            justify-content: space-between;
        }

        .pdf .section-one .logo {
            width: 300px;
            height: 100px;
        }

        .pdf .section-one .logo img {
            width: auto;
            height: 100%;
        }

        .pdf .section-one .info .form-group {
            margin-bottom: 0;
        }

        .pdf .main-title {
            text-align: center;
            margin-bottom: 20px;
        }

        .pdf .main-title span {
            padding-bottom: 0;
            border-bottom: 5px solid red;
            font-family: "Cairo", sans-serif;
            font-size: 40px;
            font-weight: bold;
        }

        .section-two .price-section {
            width: 50%;
        }

        .section-two .price-section .table {
            margin-bottom: 0;
            width: 100%;
        }

        .price-section tr {
            text-align: center;
        }

        .section-three {
            direction: rtl;
            margin-top: 20px;
            text-align: right;
        }

        .section-three .table-bordered {
            border: 2px solid #a29b9b;
            margin-bottom: 0;
        }

        .section-three .table tr {
            min-height: 100px;
        }

        .section-three .table .cus-ceil {
            vertical-align: middle;
            min-height: 100px;
        }

        .section-three .table .title-ceil {
            text-align: center;
            background-color: #e1e2e3;
            font-size: 20px;
            font-family: "Cairo", sans-serif;
            padding: 7px;
        }

        .section-three .table thead th {
            border-bottom: 2px solid black;
        }

        .section-three .table-bordered th,
        .section-three .table-bordered td {
            border: 2px solid black;
        }

        .section-four {
            direction: rtl;
            text-align: right;
            margin-top: 15px;
        }

        .section-four p {
            font-family: "Cairo", sans-serif;
            margin-bottom: 0;
        }

        .section-four .header {
            font-weight: bold;
            font-size: 16px;
        }

        .section-four .point {
            font-size: 14px;
        }

        .section-five {
            direction: rtl;
            text-align: center;
        }

        button, a.btn-action {
            padding: 12px;
            background-color: #fff;
            border: 2px solid #555;
            color: #555;
            outline: none;
            margin-top: 10px;
            font-family: "Cairo", sans-serif;
            width: auto;
            cursor: pointer;
            width: 50%;
            margin: 10px;
            text-align: center;
            display: inline-block;
            text-decoration: none;
        }

        button .text, a.btn-action .text {
            margin-right: 10px;
        }

        a.share-network-whatsapp {
            background-color: #2ecc71;
            border: 2px solid #2ecc71;
            color: #fff;
            padding: 10px 20px;
            display: inline-block;
            border-radius: 15px;
            font-family: "Cairo", sans-serif;
            cursor: pointer;
            width: 50%;
            margin: 10px;
            text-align: center;
            text-decoration: none;
        }

        .print-pdf {
            background-color: #f1c40f;
            color: #ffffff;
            border-radius: 15px;
            padding: 10px 20px;
        }

        .barcode {
            height: 50px;
            width: auto;
            max-width: 300px;
        }

        .contract-header {
            font-family: 'Cairo', sans-serif;
        }

        @media (max-width: 1130px) {
            .buttons-wrap {
                width: 1130px;
            }
        }

        @media print {
            body {
                background-color: #fff;
            }
            .buttons-wrap, .no-print {
                display: none !important;
            }
            .pdf {
                border: none;
                width: 100%;
                padding: 0;
            }
            .blurred {
                filter: none;
                -webkit-filter: none;
            }
        }
    </style>
</head>
<body>
    <main>
        <div class="container">
            @if(!$is_cancelled)
                <div class="buttons-wrap">
                    <a class="share-network-whatsapp" href="https://api.whatsapp.com/send?text=%D8%B9%D9%82%D8%AF%20%D8%A5%D9%8A%D8%AC%D8%A7%D8%B1%20{{ urlencode($draft_share_link) }}" target="_blank">
                        <span class="text">مشاركة عبر الواتساب</span>
                        <img class="icon" src="/images/icons/whatsapp.svg" alt="" />
                    </a>

                    <button class="print-pdf" onclick="window.print()">
                        <span class="text">طباعة العقد</span>
                        <img class="icon" src="/images/icons/printer.svg" alt="" />
                    </button>
                </div>
            @endif
        </div>

        <div class="pdf-container">
            @if($is_cancelled)
                <div class="cancelled-celed">
                    <img src="/images/cancelled.png" alt="Cancelled Contract" />
                </div>
            @endif

            <div class="pdf {{ $is_cancelled ? 'blurred' : '' }}" id="printMe">

                {{-- Section One: Logo and main info --}}
                <div class="section-one">
                    <div class="info text-left">
                        <div class="form-group">
                            <label class="cus-width">Name :</label>
                            <label>{{ $settings->name }}</label>
                        </div>
                        <div class="form-group">
                            <label class="cus-width">Website:</label>
                            <label>{{ $settings->email }}</label>
                        </div>
                        <div class="form-group">
                            <label class="cus-width">Vat:</label>
                            <label>{{ $settings->vat }}</label>
                        </div>
                    </div>
                    <div class="info text-right logo">
                        <img src="/images/logo-contract.jpg" alt="" />
                    </div>
                </div>

                <h6 class="text-center"><span>رقم العقد</span></h6>
                <h2><span class="contract-serial">{{ $contract->code }}</span></h2>

                <h3 class="main-title">
                    <span class="contract-header">عقد ايجار</span>
                </h3>

                {{-- Section Two: Unit info and pricing --}}
                <div class="section-two">
                    <div class="info-section">
                        <div class="form-group">
                            <label class="cus-border-table cus-padding cus-width">{{ $contract->sector->sector_name ?? '' }}</label>
                            <label>: قطاع رقم </label>
                        </div>
                        <div class="form-group">
                            <label class="cus-border-table cus-padding cus-width">{{ $contract->beach->beach ?? '' }}</label>
                            <label class="cus-label">الشاطئ</label>
                        </div>
                        <div class="form-group">
                            <label class="cus-border-table cus-padding cus-width">{{ $contract->unit->unit_number ?? '' }}</label>
                            <label class="cus-label">رقم الوحدة</label>
                        </div>

                        @if($services && count($services) > 0)
                            <table class="table table-borderless">
                                <tbody>
                                @foreach($services as $service)
                                    <tr>
                                        <td>{{ $service['price'] }}</td>
                                        <td>{{ $service['service_name'] }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>

                    <div class="price-section">
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th scope="col">Unit Price</th>
                                    <th scope="col">Units</th>
                                    <th scope="col"></th>
                                    <th scope="col">Discount</th>
                                    <th scope="col">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row" class="cus-border-table cus-padding">{{ $contract->price }}</th>
                                    <td class="cus-padding">1</td>
                                    <td></td>
                                    <td class="cus-padding">-</td>
                                    <td class="cus-border-table cus-padding">{{ $contract->price }}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="cus-padding">Total Discount</td>
                                    <td></td>
                                    <td class="cus-padding">-</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="cus-padding">Sub Total</td>
                                    <td class="cus-padding">{{ $contract->price }}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="cus-padding">VAT</td>
                                    <td class="cus-padding">{{ number_format($contract->vat, 2) }} %</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="cus-padding">Total Amount</td>
                                    <td class="cus-border-table cus-padding">{{ total_amount($contract->price, $contract->total) }}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="cus-padding">Balance</td>
                                    <td class="cus-border-table cus-padding">{{ $contract->total }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Section Three: Tenant info --}}
                <div class="section-three">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col" class="cus-ceil title-ceil">اسم المؤجر</th>
                                <td class="cus-ceil">{{ $contract->user->name ?? '' }}</td>
                                <th scope="col" class="cus-ceil title-ceil">رقم الجوال</th>
                                <td class="cus-ceil" style="direction: ltr;">{{ $contract->user->phonenumber ?? '' }}</td>
                                <th class="cus-ceil title-ceil"></th>
                                <td class="cus-ceil"></td>
                            </tr>
                            <tr>
                                <th scope="col" class="cus-ceil title-ceil">اسم المستأجر ({{ $tenant_title }})</th>
                                <td class="cus-ceil">{{ $contract->tenant_name }}</td>
                                <th scope="col" class="cus-ceil title-ceil">رقم الهوية</th>
                                <td class="cus-ceil">{{ $contract->tenant_name_code }}</td>
                                <th class="cus-ceil title-ceil">باركود المستأجر</th>
                                <td class="cus-ceil">
                                    @if($tenant_barcode)
                                        <img class="barcode" src="{{ $tenant_barcode }}" alt="Tenant Barcode" />
                                    @else
                                        <p>----</p>
                                    @endif
                                </td>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($contract->companions as $companion)
                                <tr>
                                    <th class="cus-ceil title-ceil">بيانات المرافق ({{ trans('admin.' . $companion->title) }})</th>
                                    <td class="cus-ceil">{{ $companion->name }}</td>
                                    <th class="cus-ceil title-ceil">رقم الهوية</th>
                                    <td class="cus-ceil">{{ $companion->id_number }}</td>
                                    <th class="cus-ceil title-ceil">جنسية المرافق</th>
                                    <td class="cus-ceil">{{ $companion->nationality }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <th class="cus-ceil title-ceil">بيانات المرافق ({{ $with_tenant_title }})</th>
                                    <td class="cus-ceil">{{ $contract->with_tenant_name }}</td>
                                    <th class="cus-ceil title-ceil">رقم الهوية</th>
                                    <td class="cus-ceil">{{ $contract->with_tenant_name_code }}</td>
                                    <th class="cus-ceil title-ceil">باركود المرافق</th>
                                    <td class="cus-ceil">
                                        @if($with_tenant_barcode)
                                            <img class="barcode" src="{{ $with_tenant_barcode }}" alt="Tenant Barcode" />
                                        @else
                                            <p>----</p>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                            <tr>
                                <th class="cus-ceil title-ceil">تاريخ الدخول</th>
                                <td class="cus-ceil">{{ \Carbon\Carbon::parse($contract->from)->format(get_format()) }}</td>
                                <th class="font-weight-bold cus-ceil title-ceil">تاريخ المغادرة</th>
                                <td class="cus-ceil">{{ \Carbon\Carbon::parse($contract->to)->format(get_format()) }}</td>
                                <th class="cus-ceil title-ceil">قيمة الإيجار</th>
                                <td class="cus-ceil text-center">{{ currency($contract->rent_value) }}</td>
                            </tr>
                            <tr>
                                <th class="cus-ceil title-ceil">جنسيه المستاجر</th>
                                <td class="cus-ceil" colspan="3">{{ $contract->tenant_nationality }}</td>
                                <th class="cus-ceil title-ceil">مبلغ التامين</th>
                                <td class="cus-ceil text-center">{{ currency($contract->insurance_value) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Section Four: Terms and conditions --}}
                <div class="section-four">
                    <p class="header">
                        عزيزي الضيف . رغبة من الإدارة في توفير سبل الراحة لضيوفها الكرام
                        وحفاظاً على سلامتكم وراحتكم نرجو التقيد والالتزام بالتالي :
                    </p>
                    @if($beach_terms->count() > 0)
                        @foreach($beach_terms as $index => $term)
                            <p class="point">{{ $index + 1 }}. {{ $term->term_content }}</p>
                        @endforeach
                    @else
                        <p class="point">1. الإلتزام بتعاليم الدين الاسلامي ومايتناسب مع العادات والتقاليد المتبعة في المملكة العربية السعوديه.</p>
                        <p class="point">2. الإلتزام بجميع الاجراءات الاحترازية الخاصة بجائحة كورونا بالتباعد الاجتماعي ولبس الكمامات وعدم التجمعات على الشاطئ وتطبيق كل ما يصدر من الجهات الرسمية .</p>
                        <p class="point">3. يمنع استخدام مكبرات الصوت او ارعاج الجيران باي وسيلة وفي حال المخالفة يتحمل المستأجر تسديد مبلغ 1000 ريال لصالح ادارة القطاع.</p>
                        <p class="point">4. السكن لعائلة واحدة فقط وتمنع الزياره بشكل عام للأفراد ) الشباب ( ويحق للادارة اخلاء الوحدة عند مغادرة العائلة ولا يحق للمستأجر استرجاع قيمة الأيجار بعد استلام الوحدة نهائياً.</p>
                        <p class="point">5. يتحمل المستأجر المسئولية الكاملة عن كل ماياصدر من المرافقين ويحق للادارة اتخاذ الاجراء المناسب دون الرجوع للمستأجر.</p>
                        <p class="point">6. اخلاء مسئولية الادارة تماما عن أي حالة إصابة او غرق للمستأجر او أي من مرافقيه لاسمح الله في المسابح او البحر وان السباحة تحت مسؤليتكم الشخصية.</p>
                        <p class="point">7. يجب دفع مبلغ تأمين لضمان حسن استخدام الوحدة السكنية والمرافق العامة ويسترجع في حال عدم وجود اي تلفيات.</p>
                        <p class="point">8. الوحدة أثثت لراحتكم واستمتاعكم لكل مافيها لذا فإن محتويات الوحدة ستكون تحت مسئوليتكم اثناء الإقامة وفي حالة وجود اي اضرار ناتجة عن سوء الاستخدام فسوف تضاف لحسابكم الخاص وتخصم من التأمين في حال عدم كفاية التأمين يلتزم النزيل بدفع المبلغ كاملاً.</p>
                        <p class="point">9. الالتزام بالتقيد بموعد المغادرة الساعة 12 الظهر وسيتم حساب ليلة اضافية في حال التاخر عن هذا الموعد .</p>
                        <p class="point">10. الادارة غير مسئولة عن فقدان او تلف متعلقاتكم الشخصية ) الثمينة او النقدية ( طول فت سريان عقد ايجاركم.</p>
                        <p class="point">11. مكتب اثمار غير مسؤول عن تصاريح الدخول للأفراد ) الشباب ( والسيارات ، حيث ان أرقام اللوحات للسيارات المذكورة ادناه لدخول العوائل فقط ، ويمنع دخول أي سيارة تحتوي على شباب من بوابات القطاع.</p>
                        <p class="point">12. قيمة العقد غير مستردة ولايمكن التعديل او تغيير العقد بعد إصدارة لأي سبب كان .</p>
                        <p class="point">13. حسب تعليمات المديرية العامة لسلاح الحدود يمنع السباحه على الشواطي ليلاً.</p>
                        <p class="point">14. السكن للعوائل فقط وعلى أن لا يزيد عدد الأشخاص عن 18 في جزيرة الأحلام وعن 8 أشخاص في شاطئ الحمراء وعن 4 أشخاص في منطقة المونتانا.</p>
                        <p class="point">15.دخول أي سيارة إضافية غير المصرح لها في العقد سيتم خصم مبلغ التأمين كاملاً.</p>
                        <p class="point">16. خاص بتاجير الشباب الأحلام 6 اشخاص فقط (شباب) الحمراء 6 أشخاص فقط (شباب) المونتانا 3 أشخاص فقط (شباب) في حال لوحظ وجود أكثر من العدد المسموح يخصم 1000 ريال من التأمين.</p>
                        @if($contract->sector_id == 5)
                        <p class="point">17. في حالة الافتراش او السباحة خارج الموقع المؤجر يتم خصم كامل مبلغ التأمين.</p>
                        @endif
                    @endif
                </div>

                {{-- Cars table --}}
                <div class="section-three">
                    <table class="table table-bordered">
                        <caption class="text-center p-1">
                            لا يمكن تعديل أو تغيير ارقام لوحات السيارات المسجلة في الجدول
                        </caption>
                        <thead>
                            <tr>
                                <th scope="col" class="cus-ceil title-ceil">رقم</th>
                                <th scope="col" class="font-weight-bold cus-ceil title-ceil">نوع السيارة</th>
                                <th scope="col" class="cus-ceil title-ceil">بيانات اللوحة</th>
                                <th scope="col" class="cus-ceil title-ceil">اسم السائق</th>
                                <th scope="col" class="cus-ceil title-ceil">رقم الهوية</th>
                                <th scope="col" class="font-weight-bold cus-ceil title-ceil">ختم تصريح مدينة الدرة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contract->cars as $index => $car)
                                <tr>
                                    <th scope="row" class="cus-ceil title-ceil">سيارة {{ $index + 1 }}</th>
                                    <td class="cus-ceil">{{ $car->car_type }}</td>
                                    <td class="cus-ceil">{{ $car->car_serial }}</td>
                                    <td class="cus-ceil">{{ $car->passenger_name }}</td>
                                    <td class="cus-ceil">{{ $car->identity }}</td>
                                    @if($index === 0)
                                        <td class="cus-ceil text-center" style="max-width: 140px" rowspan="{{ $contract->cars->count() }}"></td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Section Five: Signature --}}
                <div class="section-five">
                    <table class="table table-borderless mb-0">
                        <thead>
                            <tr>
                                <th scope="col" class="cus-ceil title-ceil cus-padding">مصدق بكود من جوال المستأجر</th>
                                <th scope="col" class="font-weight-bold cus-ceil title-ceil" style="direction: ltr;">{{ $contract->phonenumber }}</th>
                                <th scope="col" class="cus-ceil title-ceil cus-padding">توقيع المالك المفوض</th>
                                <th scope="col" class="cus-ceil title-ceil cus-padding">بادارة</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="cus-ceil cus-padding"></td>
                                <td class="cus-ceil"></td>
                                <td class="cus-ceil"></td>
                                <td class="cus-ceil cus-padding">
                                    <img src="/images/signature-small.jpg" alt="" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
