<template>
  <main>
    <div class="container-fluid">
      <form v-if="show">
        <div class="form-group">
          <h3 class="text-center">عقد جديد</h3>
        </div>
        <div class="form-group">
          <label>Phone</label>
          <input type="text" class="form-control" v-model="mainInfo.phone" />
        </div>

        <div class="form-group">
          <label>E-mail</label>
          <input type="email" class="form-control" v-model="mainInfo.email" />
        </div>

        <div class="form-group">
          <label>website</label>
          <input type="text" class="form-control" v-model="mainInfo.website" />
        </div>

        <div class="form-group">
          <label>Vat</label>
          <input type="text" class="form-control" v-model="mainInfo.vat" />
        </div>
      </form>

      <div class="pdf" id="printMe">
        <!-- Start Section One Logo and main info section -->
        <div class="section-one">
          <div class="logo">
            <img src="https://via.placeholder.com/350x150" alt="" />
          </div>

          <div class="info">
            <div class="form-group">
              <label class="cus-width">Phone :</label>
              <label>{{ mainInfo.phone }}</label>
            </div>

            <div class="form-group">
              <label class="cus-width">Email :</label>
              <label>{{ mainInfo.email }}</label>
            </div>

            <div class="form-group">
              <label class="cus-width">Website :</label>
              <label>{{ mainInfo.website }}</label>
            </div>

            <div class="form-group">
              <label class="cus-width">VAT :</label>
              <label>{{ mainInfo.vat }}</label>
            </div>
          </div>
        </div>
        <!-- End Section One Logo and main info section -->
        <!-- Start Main Title -->
        <h3 class="main-title"><span>عقد ايجار</span></h3>
        <!-- End Main Title -->
        <div class="section-two">
          <div class="info-section">
            <div class="form-group">
              <label>25</label>
              <label>: قطاع رقم </label>
            </div>
            <div class="form-group">
              <label class="cus-border-table cus-padding cus-width">{{
                unitInfo.price1
              }}</label>
            </div>
            <!-- <br> -->
            <div class="form-group">
              <label class="cus-label">Product Name :</label>
              <label class="cus-border-table cus-padding cus-width">{{
                unitInfo.price2
              }}</label>
            </div>
            <div class="form-group">
              <label>25</label>
              <label>{{ unitInfo.cleanFees }} : رسوم التعقيم </label>
            </div>
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
                  <th scope="row" class="cus-border-table cus-padding">55</th>
                  <td class="cus-padding">1.00</td>
                  <td></td>
                  <td class="cus-padding">-</td>
                  <td class="cus-border-table cus-padding">
                    {{ unitInfo.sectionNumber }}
                  </td>
                </tr>

                <tr>
                  <th scope="row" class="cus-border-table cus-padding">
                    {{ unitInfo.sectionName }}
                  </th>
                  <td class="cus-padding">1.00</td>
                  <td></td>
                  <td class="cus-padding">-</td>
                  <td class="cus-border-table cus-padding">
                    {{ unitInfo.totalAmount }}
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
                  <td class="cus-padding">Sub Total</td>
                </tr>

                <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td class="cus-padding">VAT 15%</td>
                  <td class="cus-padding">VAT 15%</td>
                </tr>

                <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td class="cus-padding">Total Amount</td>
                  <td class="cus-border-table cus-padding">
                    {{ unitInfo.TotalPaid }}
                  </td>
                </tr>

                <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td class="cus-padding">Total Paid</td>
                  <td class="cus-border-table cus-padding">
                    {{ unitInfo.Balance }}
                  </td>
                </tr>
                <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td class="cus-padding">Balance</td>
                  <td class="cus-border-table cus-padding">
                    {{ unitInfo.productName }}
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
                <th scope="col" class="cus-ceil">{{ userInfo.username }}</th>
                <th scope="col" class="cus-ceil title-ceil">تاريخ الدخول</th>
                <th scope="col" class="cus-ceil">{{ userInfo.entryDate }}</th>
                <th scope="col" class="cus-ceil title-ceil">قيمة الإيجار</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th scope="row" class="cus-ceil title-ceil">بيانات المرافق</th>
                <td class="cus-ceil">{{ userInfo.friendName }}</td>
                <td class="font-weight-bold cus-ceil title-ceil">
                  تاريخ المغادرة
                </td>
                <td class="cus-ceil">{{ userInfo.leavingDate }}</td>
                <td class="cus-ceil">{{ userInfo.rentFees }}</td>
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
                <td class="cus-ceil"></td>
                <td class="cus-ceil cus-padding">
                  <qrcode-vue
                    :value="contractsUrl + '/contracts/' + random"
                    size="100"
                    level="H"
                  />
                </td>
                <td class="cus-ceil"></td>
                <td class="cus-ceil cus-padding">
                  <img src="https://via.placeholder.com/150x150" alt="" />
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <button @click="show = !show">
        اخفاء الفورم لتحميل الملف من خلال الهاتف
      </button>
      <button v-print="'#printMe'">طباعة العقد</button>
    </div>
  </main>
</template>

<script>
import print from "vue3-print-nb";
import QrcodeVue from "qrcode.vue";

export default {
  name: "AhmedPrintPDF",
  directives: {
    print
  },
  data() {
    return {
      mainInfo: {
        phone: "01096613959",
        email: "",
        website: "",
        vat: "",
      },
      show: 1,
      unitInfo: {
        price1: 5,
        price2: 15,
        sectionNumber: 8,
        sectionName: 55,
        totalAmount: 20,
        TotalPaid: 30,
        Balance: 55,
        productName: 6857,
        cleanFees: 55,
      },

      userInfo: {
        username: "5555555888899966 - عمر محمد محمود فودة",
        friendName: "5555555888899966 - محمد احمد السيد عوض الله",
        entryDate: '',
        leavingDate: "5/12/25",
        rentFees: 44,
      },

      lastTable: {
        f1: "5555555888899966 - محمد احمد السيد عوض الله",
        f2: "5555555888899966 - عمر محمد محمود فودة",
        f3: "5555555888899966 - محمد احمد السيد عوض الله",
        f4: "5555555888899966 - عمر محمد محمود فودة",
        f5: "5555555888899966 - محمد احمد السيد عوض الله",
        f6: "5555555888899966 - عمر محمد محمود فودة",
      },
      random: "",
      contractsUrl: process.env.MIX_CONTRACTS_URL || "https://ihost-homeksa.com",
      /* Print State Related */
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
    this.random = Math.ceil(Math.random() * 1000);
  },
  components: {
    QrcodeVue,
  },
};
</script>

<style scoped>
/* Start Main Features */
/* @import url("https://fonts.googleapis.com/css2?family=Almarai&display=swap"); */
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

.pdf {
  border: 4px solid red;
  padding: 13px;
  width: 1130px;
    margin: auto;
  /* -webkit-print-color-adjust: exact; */
}

.pdf * {
  font-family: "Almarai", sans-serif;
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
  width: 100%;
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
  padding-bottom: 10px;
  border-bottom: 5px solid red;
  font-family: "Almarai", sans-serif;
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
  font-family: "Almarai", sans-serif;
  padding: 7px;
}

.section-three .table thead th {
  border-bottom: 2px solid black;
}
.section-three .table-bordered th,
.section-three .table-bordered td {
  border: 2px solid black;
}

/* End Sectioon Three */

/* Start Section Four */
.section-four {
  direction: rtl;
  text-align: right;
  margin-top: 15px;
}
.section-four p {
  font-family: "Almarai", sans-serif;
  margin-bottom: 0;
}
.section-four .header {
  font-weight: bold;
  font-size: 16px;
}

.section-four .point {
  font-size: 14px;
}
/* End Section Four */

/* Start Section Five */
.section-five {
  direction: rtl;
  text-align: center;
}
/* End Section Five */

button {
  padding: 12px;
  background-color: #fff;
  border: 2px solid #555;
  color: #555;
  outline: none;
  margin-top: 10px;
  font-family: "Almarai", sans-serif;
  width: auto;
}
</style>
