<template>
    <main>
        <div class="container">
            <div v-if="!is_cancelled && !is_downpayment" class="buttons-wrap">
                <ShareNetwork
                    network="WhatsApp"
                    :url="url"
                    title="عقد إيجار"
                >
                    <span class="text">مشاركة عبر الواتساب</span>
                    <img class='icon' src="/images/icons/whatsapp.svg" alt="" />
                </ShareNetwork>


                <button class="print-pdf" v-print="'#printMe'">
                    <span class="text">طباعة العقد</span>
                    <img class="icon" src="/images/icons/printer.svg" alt="" />
                </button>
            </div>
        </div>

        <div class="pdf-container">
            <div v-if="is_cancelled" class="cancelled-celed">
                <img src="/images/cancelled.png" alt="Cancelled Contract" />
            </div>

            <div v-if="is_downpayment" class="downpayment-celed">
                <img src="/images/down_payment_message.png" alt="down payment message" />

                <h2 class="down-payment-messge">
                    برجاء دفع المبلغ المتبقي و هو {{remaining_payment}} ورفع صورة الحوالة
                </h2>

                <a :href="upload_image_link" class="upload-form">رفع صورة الحوالة</a>
            </div>

            <div class="pdf" :class="{'blurred': is_cancelled, 'blurred-light': is_downpayment, 'downpayment': is_downpayment}" id="printMe">
                <!-- Start Section One Logo and main info section -->
                <div class="section-one">
                    <div class="info text-left">
                        <div class="form-group">
                            <label class="cus-width">Name :</label>
                            <label>{{ mainInfo.name }}</label>
                        </div>

                        <div class="form-group">
                            <label class="cus-width">Phone :</label>
                            <label>{{ mainInfo.phone }}</label>
                        </div>

                        <div class="form-group">
                            <label class="cus-width">Website:</label>
                            <label>{{ mainInfo.email }}</label>
                        </div>

                        <div class="form-group">
                            <label class="cus-width">Vat:</label>
                            <label>{{ mainInfo.vat }}</label>
                        </div>
                    </div>

                    <div class="info text-right logo">
                        <img src="/images/logo-contract.jpg" alt="" />
                    </div>
                </div>


                <h6 class="text-center"><span>رقم العقد</span></h6>
                <h2><span class="contract-serial">{{code}}</span></h2>

                <h3 class="main-title">
                    <span class="contract-header">عقد ايجار</span>
                </h3>

                <div class="section-two">
                    <div class="info-section">
                        <div class="form-group">
                            <!--                            <label>{{  }}</label>-->
                            <label class="cus-border-table cus-padding cus-width">{{unitInfo.sectorNumber}}</label>
                            <label>: قطاع رقم </label>
                        </div>
                        <!-- <br> -->
                        <div class="form-group">
                            <label class="cus-border-table cus-padding cus-width">{{unitInfo.beachName}}</label>
                            <label class="cus-label">الشاطئ</label>
                        </div>

                        <div class="form-group">
                            <label class="cus-border-table cus-padding cus-width">{{unitInfo.unitName}}</label>
                            <label class="cus-label">الفيلا</label>
                        </div>

                        <table class="table table-borderless">
                            <tbody>
                            <tr v-for="(service, index) in services" :key="index">
                                <td>{{service.price}}</td>
                                <td>{{ service.service_name }}</td>
                            </tr>
                            </tbody>
                        </table>
                        <!--                        <div class="form-group">-->
                        <!--                            <label>25</label>-->
                        <!--                            <label>{{ unitInfo.cleanFees }} : رسوم التعقيم </label>-->
                        <!--                        </div>-->
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
                                <th scope="row" class="cus-border-table cus-padding">{{unitInfo.unit_price}}</th>
                                <td class="cus-padding">1</td>
                                <td></td>
                                <td class="cus-padding">-</td>
                                <td class="cus-border-table cus-padding">
                                    {{unitInfo.unit_price}}
                                </td>
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
                                <td class="cus-padding">{{unitInfo.unit_price}}</td>
                            </tr>

                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="cus-padding">VAT</td>
                                <td class="cus-padding">{{unitInfo.vat_percent}}</td>
                            </tr>

                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="cus-padding">Total Amount</td>
                                <td class="cus-border-table cus-padding">
                                    {{ unitInfo.TotalAmount }}
                                </td>
                            </tr>

                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="cus-padding">Balance</td>
                                <td class="cus-border-table cus-padding">
                                    {{ unitInfo.Balance }}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="section-three">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th scope="col" class="cus-ceil title-ceil">اسم المستأجر ({{tenant_title}})</th>
                            <td class="cus-ceil">{{ userInfo.tenant_name }}</td>
                            <th scope="col" class="cus-ceil title-ceil">رقم الهوية</th>
                            <td class="cus-ceil">{{ userInfo.tenant_name_code }}</td>
                            <th class="cus-ceil title-ceil">باركود المستأجر</th>
                            <td class="cus-ceil">
                                <img v-if="userInfo.tenant_barcode" class="barcode" :src="userInfo.tenant_barcode" alt="Tenant Barcode" />
                                <p v-if="!userInfo.tenant_barcode">----</p>
                            </td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th class="cus-ceil title-ceil">بيانات المرافق ({{with_tenant_title}})</th>
                            <td class="cus-ceil">{{ userInfo.with_tenant_name }}</td>
                            <th class="cus-ceil title-ceil">رقم الهوية</th>
                            <td class="cus-ceil">{{ userInfo.with_tenant_name_code }}</td>
                            <th class="cus-ceil title-ceil">باركود المرافق</th>
                            <td class="cus-ceil">
                                <img v-if="userInfo.with_tenant_barcode" class="barcode" :src="userInfo.with_tenant_barcode" alt="Tenant Barcode" />
                                <p v-if="!userInfo.with_tenant_barcode">----</p>
                            </td>
                        </tr>

                        <tr>
                            <th class="cus-ceil title-ceil">تاريخ الدخول</th>
                            <td class="cus-ceil">{{ userInfo.from }}</td>
                            <th class="font-weight-bold cus-ceil title-ceil">تاريخ المغادرة</th>
                            <td class="cus-ceil">{{ userInfo.to }}</td>
                            <th class="cus-ceil title-ceil">قيمة الإيجار</th>
                            <td class="cus-ceil text-center">{{ userInfo.rent_value }}</td>
                        </tr>

                        <tr>
                            <th class="cus-ceil title-ceil">جنسيه المستاجر</th>
                            <td class="cus-ceil">{{ userInfo.tenant_nationality }}</td>
                            <th class="font-weight-bold cus-ceil title-ceil">جنسيه المرافق</th>
                            <td class="cus-ceil">{{ userInfo.with_tenant_nationality }}</td>
                            <th class="cus-ceil title-ceil">مبلغ التامين</th>
                            <td class="cus-ceil text-center">{{ userInfo.insurance_value }}</td>
                        </tr>

                        </tbody>
                    </table>
                </div>

                <div class="section-four">
                    <p class="header">
                        عزيزي الضيف . رغبة من الإدارة في توفير سبل الراحة لضيوفها الكرام
                        وحفاظاً على سلامتكم وراحتكم نرجو التقيد والالتزام بالتالي :
                    </p>

                    <p class="point">
                        1. الإلتزام بتعاليم الدين الاسلامي ومايتناسب مع العادات والتقاليد
                        المتبعة في المملكة العربية السعوديه.
                    </p>

                    <p class="point">
                        2. الإلتزام بجميع الاجراءات الاحترازية الخاصة بجائحة كورونا بالتباعد
                        الاجتماعي ولبس الكمامات وعدم التجمعات على الشاطئ وتطبيق كل ما يصدر
                        من الجهات الرسمية .
                    </p>
                    <p class="point">
                        3. يمنع استخدام مكبرات الصوت او ارعاج الجيران باي وسيلة وفي حال
                        المخالفة يتحمل المستأجر تسديد مبلغ 1000 ريال لصالح ادارة القطاع.
                    </p>
                    <p class="point">
                        4. الإلتزام بإستخدام الوحدة السكنية لعائلة واحدة فقط تمثل عائلة المستأجر ، ويمنع منعاً باتاً الزيارة للمستأجر من الأفراد ، ويحق للإدارة إخلاء الوحدة في حال مغادرة العائلة ولا يحق للمستأجر استرجاع قيمة الأيجار بعد استلام الوحدة نهائياً ويتحمل المستأجر دفع غرامة بمبلغ 1000 ريال لصالح القطاع.
                    </p>
                    <p class="point">
                        5. يتحمل المستأجر المسئولية الكاملة عن كل ماياصدر من المرافقين ويحق
                        للادارة اتخاذ الاجراء المناسب دون الرجوع للمستأجر.
                    </p>
                    <p class="point">
                        6. اخلاء مسئولية الادارة تماما عن أي حالة إصابة او غرق للمستأجر او
                        أي من مرافقيه لاسمح الله في المسابح او البحر وان السباحة تحت
                        مسؤليتكم الشخصية.
                    </p>
                    <p class="point">
                        7. يجب دفع مبلغ تأمين لضمان حسن استخدام الوحدة السكنية والمرافق
                        العامة ويسترجع في حال عدم وجود اي تلفيات.
                    </p>
                    <p class="point">
                        8. الوحدة أثثت لراحتكم واستمتاعكم لكل مافيها لذا فإن محتويات الوحدة
                        ستكون تحت مسئوليتكم اثناء الإقامة وفي حالة وجود اي اضرار ناتجة عن
                        سوء الاستخدام فسوف تضاف لحسابكم الخاص وتخصم من التأمين في حال عدم
                        كفاية التأمين يلتزم النزيل بدفع المبلغ كاملاً.
                    </p>
                    <p class="point">
                        9. الالتزام بالتقيد بموعد المغادرة الساعة 12 الظهر وسيتم حساب ليلة
                        اضافية في حال التاخر عن هذا الموعد .
                    </p>
                    <p class="point">
                        10. الادارة غير مسئولة عن فقدان او تلف متعلقاتكم الشخصية ) الثمينة
                        او النقدية ( طول فت سريان عقد ايجاركم.
                    </p>
                    <p class="point">
                        11. مكتب اثمار غير مسؤول عن تصاريح الدخول للأفراد ) الشباب (
                        والسيارات ، حيث ان أرقام اللوحات للسيارات المذكورة ادناه لدخول
                        العوائل فقط ، ويمنع دخول أي سيارة تحتوي على شباب من بوابات القطاع.
                    </p>
                    <p class="point">
                        12. قيمة العقد غير مستردة ولايمكن التعديل او تغيير العقد بعد إصدارة
                        لأي سبب كان .
                    </p>

                    <p class="point">
                        13. حسب تعليمات المديرية العامة لسلاح الحدود يمنع السباحه على الشواطي ليلاً.
                    </p>

                    <p class="point">
                        14. السكن للعوائل فقط وعلى أن لا يزيد عدد الأشخاص عن 18 في جزيرة الأحلام وعن 8 أشخاص في شاطئ الحمراء وعن 4 أشخاص في منطقة المونتانا.
                    </p>

                    <p class="point">
                        15.دخول أي سيارة إضافية غير المصرح لها في العقد سيتم خصم مبلغ التأمين كاملاً.
                    </p>

                </div>

                <div class="section-three">
                    <table class="table table-bordered">
                        <caption class="text-center p-1">
                            لا يمكن تعديل أو تغيير ارقام لوحات السيارات المسجلة في الجدول
                        </caption>
                        <thead>
                        <tr>
                            <th scope="col" class="cus-ceil title-ceil">رقم</th>
                            <th scope="col" class="font-weight-bold cus-ceil title-ceil">
                                نوع السيارة
                            </th>
                            <th scope="col" class="cus-ceil title-ceil">بيانات اللوحة</th>
                            <th scope="col" class="cus-ceil title-ceil">اسم السائق</th>
                            <th scope="col" class="cus-ceil title-ceil">رقم الهوية</th>
                            <th scope="col" class="font-weight-bold cus-ceil title-ceil">
                                ختم تصريح مدينة الدرة
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(car, index) in cars">
                            <th scope="row" class="cus-ceil title-ceil">سيارة {{index+1}}</th>
                            <td class="cus-ceil">{{car.car_type}}</td>
                            <td class="cus-ceil">{{car.car_serial}}</td>
                            <td class="cus-ceil">{{car.passenger_name}}</td>
                            <td class="cus-ceil">{{car.identity}}</td>
                            <td v-if="index === 0" class="cus-ceil text-center" style="max-width: 140px" rowspan="3">
                                <img v-if="!is_downpayment" src="/images/paid.jpg" alt="" />
                            </td>
                        </tr>
                        <!--                    <tr>-->
                        <!--                        <th scope="row" class="cus-ceil title-ceil">مرافق 1</th>-->
                        <!--                        <td class="cus-ceil">{{ lastTable.f3 }}</td>-->
                        <!--                        <td class="cus-ceil">{{ lastTable.f4 }}</td>-->
                        <!--                    </tr>-->
                        <!--                    <tr>-->
                        <!--                        <th scope="row" class="cus-ceil title-ceil">مرافق 2</th>-->
                        <!--                        <td class="cus-ceil">{{ lastTable.f5 }}</td>-->
                        <!--                        <td class="cus-ceil">{{ lastTable.f6 }}</td>-->
                        <!--                    </tr>-->
                        </tbody>
                    </table>
                </div>

                <div class="section-five">
                    <table class="table table-borderless mb-0">
                        <thead>
                        <tr>
                            <th scope="col" class="cus-ceil title-ceil cus-padding">
                                مصدق بكود من جوال المستأجر
                            </th>
                            <th
                                scope="col"
                                class="font-weight-bold cus-ceil title-ceil"
                                style="direction: ltr;"
                            >{{userInfo.phonenumber}}</th>
                            <th scope="col" class="cus-ceil title-ceil cus-padding">
                                المالك المفوض: <i>{{userInfo.user_name}}</i>
                            </th>
                            <th scope="col" class="cus-ceil title-ceil cus-padding">
                                بادارة
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <!--                            <td class="cus-ceil"></td>-->
                            <td class="cus-ceil cus-padding">
                                <qrcode-vue
                                    :value="QRCodeLink"
                                    size="250"
                                    level="H"
                                />
                            </td>
                            <td class="cus-ceil"></td>

                            <td class="cus-ceil">
                                <h6 v-if="is_reservation">ملاحظات عقود الحجوزات الإلكترونية:</h6>
                                <h5 v-if="is_reservation">يلتزم المستأجر بدفع المبلغ المتبقي للمؤجر دون ادنى مسئولية على محرره</h5>
                                <h3 v-if="is_reservation" style="color: red">{{remaining_payment}} ر.س</h3>
                            </td>

                            <td class="cus-ceil cus-padding">
                                <!--                                <img src="https://via.placeholder.com/150x150" alt="" />-->
                                <img src="/images/signature-small.jpg" alt="" />
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
import print from "vue-print-nb";
import QrcodeVue from "qrcode.vue";
import axios from 'axios'

directives: {
    print;
}
export default {
    name: "Contract",
    data() {
        return {
            url: '',
            code: '',
            tenant_title: '',
            is_downpayment: '',
            is_reservation: '',
            remaining_payment: '',
            with_tenant_title: '',
            upload_image_link: '',
            is_cancelled: false,
            services: {},
            mainInfo: {
                name: "",
                phone: "",
                email: "",
                website: "",
                vat: ""
            },
            unitInfo: {
                sector_id: '',
                price1: 5,
                price2: 15,
                unitName: '',
                sectionNumber: 8,
                sectionName: 55,
                TotalPaid: 30,
                productName: 6857,
                cleanFees: 55,
                sectorNumber: '',
                beachName: '',
                unit_price: 0,
                vat_percent: 0,
                TotalAmount: 0,
                Balance: 0
            },
            userInfo: {
                user_name: "",
                tenant_name: "",
                tenant_name_code: "",
                tenant_barcode: "",
                from: "",
                to: '',
                with_tenant_name: "",
                with_tenant_name_code: "",
                with_tenant_barcode: "",
                rent_value: 0,
                tenant_nationality: "",
                with_tenant_nationality: "",
                insurance_value: "",
                phonenumber: ""
            },

            cars: {},

            QRCodeLink: "",
            printLoading: true,
            printObj: {
                id: "printMe",
                extraCss:
                    "https://cdn.bootcdn.net/ajax/libs/animate.css/4.1.1/animate.compat.css, https://cdn.bootcdn.net/ajax/libs/hover.css/2.3.1/css/hover-min.css",
                extraHead: '<meta http-equiv="Content-Language"content="zh-cn"/>',
                beforeOpenCallback(vue) {
                    vue.printLoading = true;
                },
                openCallback(vue) {
                    vue.printLoading = false;
                },
                closeCallback(vue) {
                },
            },
        };
    },
    mounted() {
        this.getContractData();
    },
    methods: {
        getContractData(){
            let contractCode = '';

            if (this.$route.params.code){
                contractCode = this.$route.params.code;
            }else if(window.contract_id){
                contractCode = window.contract_id;
            }

            const TOKEN = 'Token';
            const $this = this;

            axios.post('/get-settings')
            .then(function (result){
                const obj = result.data.data;

                $this.mainInfo.name = obj.name;
                $this.mainInfo.phone = obj.phonenumber;
                $this.mainInfo.email = obj.email;
                $this.mainInfo.website = obj.website;
                $this.mainInfo.vat = obj.vat;

            })

            axios.post('/get-single-contract/'+contractCode, {
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer '+TOKEN
                }
            })
            .then(function (result){
                const obj = result.data.data;

                // Cancelled
                $this.is_cancelled = obj.is_cancelled;

                // $this.mainInfo.phone = data.data.user.phonenumber;
                // $this.mainInfo.email = data.data.user.email;
                // $this.mainInfo.website = data.data.user.website;
                // $this.mainInfo.vat = 15;

                $this.QRCodeLink = obj.qr_code;
                $this.unitInfo.sector_id = obj.sector_id;
                $this.unitInfo.sectorNumber = obj.sector_name;
                $this.unitInfo.beachName    = obj.beach_name;
                $this.unitInfo.unitName    = obj.unit_name;

                $this.cars = obj.cars

                $this.userInfo.user_name = obj.user_name;
                $this.userInfo.tenant_name = obj.tenant_name;
                $this.userInfo.tenant_name_code = obj.tenant_name_code;
                $this.userInfo.from = obj.from;
                $this.userInfo.to = obj.to;
                $this.userInfo.with_tenant_name = obj.with_tenant_name;
                $this.userInfo.with_tenant_name_code = obj.with_tenant_name_code;
                $this.userInfo.rent_value = obj.rent_value;

                $this.userInfo.tenant_barcode = obj.tenant_barcode;
                $this.userInfo.with_tenant_barcode = obj.with_tenant_barcode;

                $this.userInfo.tenant_nationality = obj.tenant_nationality;
                $this.userInfo.with_tenant_nationality = obj.with_tenant_nationality;
                $this.userInfo.insurance_value = obj.insurance_value;
                $this.userInfo.phonenumber = obj.phonenumber;

                // $this.lastTable.f1 = obj.car_type1;
                // $this.lastTable.f2 = obj.car_serial1;
                // $this.lastTable.f3 = obj.car_type2;
                // $this.lastTable.f4 = obj.car_serial2;
                // $this.lastTable.f5 = obj.car_type3;
                // $this.lastTable.f6 = obj.car_serial3;

                $this.code = obj.code;
                $this.url = obj.share_link;


                $this.unitInfo.unit_price = obj.price;
                $this.unitInfo.vat_percent = obj.vat;
                $this.unitInfo.TotalAmount = obj.total_amount;
                $this.unitInfo.Balance = obj.balance;

                $this.services = obj.services;
                $this.with_tenant_title = obj.with_tenant_title;
                $this.tenant_title = obj.tenant_title;
                $this.is_downpayment = obj.reservation_status == 'down_payment';
                $this.is_reservation = obj.is_reservation;
                $this.remaining_payment = obj.remaining_payment;
                $this.upload_image_link = obj.upload_image_link;
            })
        }
    },
    components: {
        QrcodeVue,
    },
};
</script>

<style scoped>
.buttons-wrap{
    display: flex;
    align-items: center
}
.upload-form{
    min-width: 300px;
    display: inline-block;
    margin: auto;
    background-color: #2980b9;
    border: 2px solid #2980b9;
    padding: 10px;
    border-radius: 10px;
    color: #fff;
    font-family: 'Tajawal', sans-serif;
    font-weight: 500;
    text-decoration: none;
}
.upload-form:hover{
    background-color: transparent;
    color: #2980b9;
}
.cancelled-celed,
.downpayment-celed{
    position: absolute;
    top: 100px;
    left: 50%;
    z-index: 99;
    transform: translate(-50%);
    -webkit-transform: translate(-50%);
    -moz-transform: translate(-50%);
    -o-transform: translate(-50%);
}
.downpayment-celed{
    top: 10px;
    text-align: center;
}
.down-payment-messge{
    width: 400px;
    color: #c0392b;
    font-weight: 900;
    font-family: 'Tajawal', sans-serif;
    background-color: rgba(255,255,255,.9);
    margin-top: 6px;
    padding: 10px;
}
@media (max-width: 768px) {
    .buttons-wrap{
        margin: 10px 0;
    }
    .container{
        padding: 0;
    }
    a.share-network-whatsapp{
        margin-left: 10px !important;
        padding: 10px !important;
    }
    button,
    a{
        margin: 0 10px 0 0 !important;
    }
    .icon{
        max-width: 10px !important;
    }
    button .text,
    a .text{
        margin-right: 3px !important;
        font-size: 12px !important;
    }
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
/* End Main Features */
.cus-label {
    vertical-align: middle;
}
.cus-border {
    border: 2px dashed gray;
    padding: 5px;
    height: 48px;
    width: 185px;
    vertical-align: middle;
}

.cus-padding {
    padding: 5px;
}

form {
    margin: 50px auto;
    max-width: 500px;
}

.icon{
    max-width: 24px;
}
.pdf-container{
    position: relative;
}
.pdf {
    border: 4px solid red;
    padding: 13px;
    width: 1130px;
    margin: auto;
    background-color: white;
    direction: ltr;
    /* -webkit-print-color-adjust: exact; */
}

.pdf * {
    font-family: "Cairo", sans-serif;
}
.blurred{
    /* Add the blur effect */
    filter: blur(5px);
    -webkit-filter: blur(5px);
}
.blurred-light{
    /* Add the blur effect */
    filter: blur(2px);
    -webkit-filter: blur(2px)
}
h2{
    text-align: center;
}
.contract-serial{
    display: inline-block;
    padding: 10px;
    border: 2px dashed gray;
}
/* Start Section One Logo and main info section */
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
/* End Section One Logo and main info section */

/* Start Main Title */
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
/* End Main Title */
/* Start Section Two */

.section-two .price-section {
    width: 50%;
}

.section-two .price-section .table {
    margin-bottom: 0;
    width: 100%;
}
.price-section tr{
    text-align: center;
}
/* End Section Two */

/* Start Sectioon Three */
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
    /* width: 20%; */
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

button {
    padding: 12px;
    background-color: #fff;
    border: 2px solid #555;
    color: #555;
    outline: none;
    margin-top: 10px;
    font-family: "Cairo", sans-serif;
    width: auto;
}
a.share-network-whatsapp{
    background-color: #2ecc71;
    border: 2px solid #2ecc71;
    color: #fff;
    padding: 10px 20px;
    display: inline-block;
    border-radius: 15px;
    font-family: "Cairo", sans-serif;
}
button,
a{
    cursor: pointer;
    width: 50%;
    margin: 10px;
    text-align: center
}
button .text,
a .text{
    margin-right: 10px;
}
.print-pdf{
    background-color: #f1c40f;
    color: #ffffff;
}
.barcode{
    height: 50px;
    width: auto;
    max-width: 300px;
}
</style>
