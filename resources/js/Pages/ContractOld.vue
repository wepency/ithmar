<template>
    <main>
        <div class="container">
            <div class="buttons-wrap">
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

        <div class="pdf" id="printMe">
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
                        <label class="cus-label">رقم الوحدة</label>
                    </div>
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
                        <th scope="col" class="cus-ceil title-ceil">اسم المستأجر</th>
                        <th scope="col" class="cus-ceil">{{ userInfo.tenant_name }}</th>
                        <th scope="col" class="cus-ceil title-ceil">تاريخ الدخول</th>
                        <th scope="col" class="cus-ceil">{{ userInfo.from }}</th>
                        <th scope="col" class="cus-ceil title-ceil">قيمة الإيجار</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th scope="row" class="cus-ceil title-ceil">بيانات المرافق</th>
                        <td class="cus-ceil">{{ userInfo.with_tenant_name }}</td>
                        <td class="font-weight-bold cus-ceil title-ceil">
                            تاريخ المغادرة
                        </td>
                        <td class="cus-ceil">{{ userInfo.to }}</td>
                        <td class="cus-ceil text-center">{{ userInfo.rent_value }}</td>
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
                    4. السكن لعائلة واحدة فقط وتمنع الزياره بسكل عام للأفراد ) الشباب (
                    ويحق للادارة اخلاء الوحدة عند مغادرة العائلة ولا يحق للمستأجر
                    استرجاع قيمة الأيجار بعد استلام الوحدة نهائياً.
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
                    العوائل فقط ، ويمنع دخول أي سيارة تحتوي على شباب من بوابان القطاع.
                </p>
                <p class="point">
                    12. قيمة العقد غير مستودة ولايمكن التعديل او تغيير العقد بعد إصدارة
                    لأي سبب كان .
                </p>

                <p class="point">
                    13. خاص بتاجير الشباب الأحلام 6 اشخاص فقط (شباب) الحمراء 6 أشخاص فقط (شباب) المونتانا 3 أشخاص فقط (شباب) في حال لوحظ وجود أكثر من العدد المسموح يخصم 1000 ريال من التأمين.
                </p>

                <p class="point" v-if="unitInfo.sectorNumber == 5">
                    14. في حالة الافتراش او السباحة خارج الموقع المؤجر يتم خصم كامل مبلغ التأمين.
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
                        <th scope="col" class="font-weight-bold cus-ceil title-ceil">
                            ختم تصريح مدينة الدرة
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th scope="row" class="cus-ceil title-ceil">مستأجر الوحدة</th>
                        <td class="cus-ceil">{{ lastTable.f1 }}</td>
                        <td class="cus-ceil">{{ lastTable.f2 }}</td>
                        <td class="cus-ceil" rowspan="3"></td>
                    </tr>
                    <tr>
                        <th scope="row" class="cus-ceil title-ceil">مرافق 1</th>
                        <td class="cus-ceil">{{ lastTable.f3 }}</td>
                        <td class="cus-ceil">{{ lastTable.f4 }}</td>
                    </tr>
                    <tr>
                        <th scope="row" class="cus-ceil title-ceil">مرافق 2</th>
                        <td class="cus-ceil">{{ lastTable.f5 }}</td>
                        <td class="cus-ceil">{{ lastTable.f6 }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="section-five">
                <table class="table table-borderless mb-0">
                    <thead>
                    <tr>
                        <th scope="col" class="cus-ceil title-ceil cus-padding">
                            توقيع المستأجر
                        </th>
                        <th
                            scope="col"
                            class="font-weight-bold cus-ceil title-ceil"
                        ></th>
                        <th scope="col" class="cus-ceil title-ceil cus-padding">
                            توقيع المالك المفوض
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
                                size="150"
                                level="H"
                            />
                        </td>
                        <td class="cus-ceil"></td>
                        <td class="cus-ceil"></td>
                        <td class="cus-ceil cus-padding">
<!--                                <img src="https://via.placeholder.com/150x150" alt="" />-->
                            <img src="/images/signature-small.jpg" alt="" />
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</template>

<script>
import print from "vue3-print-nb";
import QrcodeVue from "qrcode.vue";
import axios from 'axios'

export default {
    name: "Contract",
    directives: {
        print
    },
    data() {
        return {
            url: '',
            code: '',
            mainInfo: {
                name: "",
                phone: "",
                email: "",
                website: "",
                vat: ""
            },
            unitInfo: {
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
                tenant_name: "",
                from: "",
                to: '',
                with_tenant_name: "",
                rent_value: 0
            },

            lastTable: {
                f1: "",
                f2: "",
                f3: "",
                f4: "",
                f5: "",
                f6: ""
            },

            QRCodeLink: "",
            printLoading: true,
            printObj: {
                id: "printMe",
                extraCss:
                    "https://cdn.bootcdn.net/ajax/libs/animate.css/4.1.1/animate.compat.css, https://cdn.bootcdn.net/ajax/libs/hover.css/2.3.1/css/hover-min.css",
                extraHead: '<meta http-equiv="Content-Language"content="zh-cn"/>',
                beforeOpenCallback(vue) {
                    vue.printLoading = true;
                    console.log("打开之前");
                },
                openCallback(vue) {
                    vue.printLoading = false;
                    console.log("执行了打印");
                },
                closeCallback(vue) {
                    console.log("关闭了打印工具");
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

                // $this.mainInfo.phone = data.data.user.phonenumber;
                // $this.mainInfo.email = data.data.user.email;
                // $this.mainInfo.website = data.data.user.website;
                // $this.mainInfo.vat = 15;

                $this.QRCodeLink = obj.qr_code;
                $this.unitInfo.sectorNumber = obj.sector_name;
                $this.unitInfo.beachName    = obj.beach_name;
                $this.unitInfo.unitName    = obj.unit_name;


                $this.userInfo.tenant_name = obj.tenant_name;
                $this.userInfo.from = obj.from;
                $this.userInfo.to = obj.to;
                $this.userInfo.with_tenant_name = obj.with_tenant_name;
                $this.userInfo.rent_value = obj.rent_value;

                $this.lastTable.f1 = obj.car_type1;
                $this.lastTable.f2 = obj.car_serial1;
                $this.lastTable.f3 = obj.car_type2;
                $this.lastTable.f4 = obj.car_serial2;
                $this.lastTable.f5 = obj.car_type3;
                $this.lastTable.f6 = obj.car_serial3;

                $this.code = obj.code;
                $this.url = obj.share_link;


                $this.unitInfo.unit_price = obj.price;
                $this.unitInfo.vat_percent = obj.vat;
                $this.unitInfo.TotalAmount = obj.total_amount;
                $this.unitInfo.Balance = obj.balance;
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
</style>
