"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_Pages_AhmedPrintPDF_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/AhmedPrintPDF.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/AhmedPrintPDF.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var vue_print_nb__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue-print-nb */ "./node_modules/vue-print-nb/lib/print.umd.min.js");
/* harmony import */ var vue_print_nb__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue_print_nb__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var qrcode_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! qrcode.vue */ "./node_modules/qrcode.vue/dist/qrcode.vue.esm.js");



directives: {
  (vue_print_nb__WEBPACK_IMPORTED_MODULE_0___default());
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  name: "AhmedPrintPDF",
  data: function data() {
    return {
      mainInfo: {
        phone: "01096613959",
        email: "",
        website: "",
        vat: ""
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
        cleanFees: 55
      },
      userInfo: {
        username: "5555555888899966 - عمر محمد محمود فودة",
        friendName: "5555555888899966 - محمد احمد السيد عوض الله",
        entryDate: '',
        leavingDate: "5/12/25",
        rentFees: 44
      },
      lastTable: {
        f1: "5555555888899966 - محمد احمد السيد عوض الله",
        f2: "5555555888899966 - عمر محمد محمود فودة",
        f3: "5555555888899966 - محمد احمد السيد عوض الله",
        f4: "5555555888899966 - عمر محمد محمود فودة",
        f5: "5555555888899966 - محمد احمد السيد عوض الله",
        f6: "5555555888899966 - عمر محمد محمود فودة"
      },
      random: "",

      /* Print State Related */
      printLoading: true,
      printObj: {
        id: "printMe",
        extraCss: "https://cdn.bootcdn.net/ajax/libs/animate.css/4.1.1/animate.compat.css, https://cdn.bootcdn.net/ajax/libs/hover.css/2.3.1/css/hover-min.css",
        extraHead: '<meta http-equiv="Content-Language"content="zh-cn"/>',
        beforeOpenCallback: function beforeOpenCallback(vue) {
          vue.printLoading = true;
          console.log("打开之前");
        },
        openCallback: function openCallback(vue) {
          vue.printLoading = false;
          console.log("执行了打印");
        },
        closeCallback: function closeCallback(vue) {
          console.log("关闭了打印工具");
        }
      }
    };
  },
  mounted: function mounted() {
    this.random = Math.ceil(Math.random() * 1000);
  },
  components: {
    QrcodeVue: qrcode_vue__WEBPACK_IMPORTED_MODULE_1__["default"]
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/AhmedPrintPDF.vue?vue&type=template&id=6a384691&scoped=true&":
/*!**************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/AhmedPrintPDF.vue?vue&type=template&id=6a384691&scoped=true& ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function render() {
  var _vm = this,
      _c = _vm._self._c;

  return _c("main", [_c("div", {
    staticClass: "container-fluid"
  }, [_vm.show ? _c("form", [_vm._m(0), _vm._v(" "), _c("div", {
    staticClass: "form-group"
  }, [_c("label", [_vm._v("Phone")]), _vm._v(" "), _c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.mainInfo.phone,
      expression: "mainInfo.phone"
    }],
    staticClass: "form-control",
    attrs: {
      type: "text"
    },
    domProps: {
      value: _vm.mainInfo.phone
    },
    on: {
      input: function input($event) {
        if ($event.target.composing) return;

        _vm.$set(_vm.mainInfo, "phone", $event.target.value);
      }
    }
  })]), _vm._v(" "), _c("div", {
    staticClass: "form-group"
  }, [_c("label", [_vm._v("E-mail")]), _vm._v(" "), _c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.mainInfo.email,
      expression: "mainInfo.email"
    }],
    staticClass: "form-control",
    attrs: {
      type: "email"
    },
    domProps: {
      value: _vm.mainInfo.email
    },
    on: {
      input: function input($event) {
        if ($event.target.composing) return;

        _vm.$set(_vm.mainInfo, "email", $event.target.value);
      }
    }
  })]), _vm._v(" "), _c("div", {
    staticClass: "form-group"
  }, [_c("label", [_vm._v("website")]), _vm._v(" "), _c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.mainInfo.website,
      expression: "mainInfo.website"
    }],
    staticClass: "form-control",
    attrs: {
      type: "text"
    },
    domProps: {
      value: _vm.mainInfo.website
    },
    on: {
      input: function input($event) {
        if ($event.target.composing) return;

        _vm.$set(_vm.mainInfo, "website", $event.target.value);
      }
    }
  })]), _vm._v(" "), _c("div", {
    staticClass: "form-group"
  }, [_c("label", [_vm._v("Vat")]), _vm._v(" "), _c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.mainInfo.vat,
      expression: "mainInfo.vat"
    }],
    staticClass: "form-control",
    attrs: {
      type: "text"
    },
    domProps: {
      value: _vm.mainInfo.vat
    },
    on: {
      input: function input($event) {
        if ($event.target.composing) return;

        _vm.$set(_vm.mainInfo, "vat", $event.target.value);
      }
    }
  })])]) : _vm._e(), _vm._v(" "), _c("div", {
    staticClass: "pdf",
    attrs: {
      id: "printMe"
    }
  }, [_c("div", {
    staticClass: "section-one"
  }, [_vm._m(1), _vm._v(" "), _c("div", {
    staticClass: "info"
  }, [_c("div", {
    staticClass: "form-group"
  }, [_c("label", {
    staticClass: "cus-width"
  }, [_vm._v("Phone :")]), _vm._v(" "), _c("label", [_vm._v(_vm._s(_vm.mainInfo.phone))])]), _vm._v(" "), _c("div", {
    staticClass: "form-group"
  }, [_c("label", {
    staticClass: "cus-width"
  }, [_vm._v("Email :")]), _vm._v(" "), _c("label", [_vm._v(_vm._s(_vm.mainInfo.email))])]), _vm._v(" "), _c("div", {
    staticClass: "form-group"
  }, [_c("label", {
    staticClass: "cus-width"
  }, [_vm._v("Website :")]), _vm._v(" "), _c("label", [_vm._v(_vm._s(_vm.mainInfo.website))])]), _vm._v(" "), _c("div", {
    staticClass: "form-group"
  }, [_c("label", {
    staticClass: "cus-width"
  }, [_vm._v("VAT :")]), _vm._v(" "), _c("label", [_vm._v(_vm._s(_vm.mainInfo.vat))])])])]), _vm._v(" "), _vm._m(2), _vm._v(" "), _c("div", {
    staticClass: "section-two"
  }, [_c("div", {
    staticClass: "info-section"
  }, [_vm._m(3), _vm._v(" "), _c("div", {
    staticClass: "form-group"
  }, [_c("label", {
    staticClass: "cus-border-table cus-padding cus-width"
  }, [_vm._v(_vm._s(_vm.unitInfo.price1))])]), _vm._v(" "), _c("div", {
    staticClass: "form-group"
  }, [_c("label", {
    staticClass: "cus-label"
  }, [_vm._v("Product Name :")]), _vm._v(" "), _c("label", {
    staticClass: "cus-border-table cus-padding cus-width"
  }, [_vm._v(_vm._s(_vm.unitInfo.price2))])]), _vm._v(" "), _c("div", {
    staticClass: "form-group"
  }, [_c("label", [_vm._v("25")]), _vm._v(" "), _c("label", [_vm._v(_vm._s(_vm.unitInfo.cleanFees) + " : رسوم التعقيم ")])])]), _vm._v(" "), _c("div", {
    staticClass: "price-section"
  }, [_c("table", {
    staticClass: "table table-borderless"
  }, [_vm._m(4), _vm._v(" "), _c("tbody", [_c("tr", [_c("th", {
    staticClass: "cus-border-table cus-padding",
    attrs: {
      scope: "row"
    }
  }, [_vm._v("55")]), _vm._v(" "), _c("td", {
    staticClass: "cus-padding"
  }, [_vm._v("1.00")]), _vm._v(" "), _c("td"), _vm._v(" "), _c("td", {
    staticClass: "cus-padding"
  }, [_vm._v("-")]), _vm._v(" "), _c("td", {
    staticClass: "cus-border-table cus-padding"
  }, [_vm._v("\n                  " + _vm._s(_vm.unitInfo.sectionNumber) + "\n                ")])]), _vm._v(" "), _c("tr", [_c("th", {
    staticClass: "cus-border-table cus-padding",
    attrs: {
      scope: "row"
    }
  }, [_vm._v("\n                  " + _vm._s(_vm.unitInfo.sectionName) + "\n                ")]), _vm._v(" "), _c("td", {
    staticClass: "cus-padding"
  }, [_vm._v("1.00")]), _vm._v(" "), _c("td"), _vm._v(" "), _c("td", {
    staticClass: "cus-padding"
  }, [_vm._v("-")]), _vm._v(" "), _c("td", {
    staticClass: "cus-border-table cus-padding"
  }, [_vm._v("\n                  " + _vm._s(_vm.unitInfo.totalAmount) + "\n                ")])]), _vm._v(" "), _vm._m(5), _vm._v(" "), _vm._m(6), _vm._v(" "), _vm._m(7), _vm._v(" "), _c("tr", [_c("td"), _vm._v(" "), _c("td"), _vm._v(" "), _c("td"), _vm._v(" "), _c("td", {
    staticClass: "cus-padding"
  }, [_vm._v("Total Amount")]), _vm._v(" "), _c("td", {
    staticClass: "cus-border-table cus-padding"
  }, [_vm._v("\n                  " + _vm._s(_vm.unitInfo.TotalPaid) + "\n                ")])]), _vm._v(" "), _c("tr", [_c("td"), _vm._v(" "), _c("td"), _vm._v(" "), _c("td"), _vm._v(" "), _c("td", {
    staticClass: "cus-padding"
  }, [_vm._v("Total Paid")]), _vm._v(" "), _c("td", {
    staticClass: "cus-border-table cus-padding"
  }, [_vm._v("\n                  " + _vm._s(_vm.unitInfo.Balance) + "\n                ")])]), _vm._v(" "), _c("tr", [_c("td"), _vm._v(" "), _c("td"), _vm._v(" "), _c("td"), _vm._v(" "), _c("td", {
    staticClass: "cus-padding"
  }, [_vm._v("Balance")]), _vm._v(" "), _c("td", {
    staticClass: "cus-border-table cus-padding"
  }, [_vm._v("\n                  " + _vm._s(_vm.unitInfo.productName) + "\n                ")])])])])])]), _vm._v(" "), _c("div", {
    staticClass: "section-three"
  }, [_c("table", {
    staticClass: "table table-bordered"
  }, [_c("thead", [_c("tr", [_c("th", {
    staticClass: "cus-ceil title-ceil",
    attrs: {
      scope: "col"
    }
  }, [_vm._v("اسم المستأجر")]), _vm._v(" "), _c("th", {
    staticClass: "cus-ceil",
    attrs: {
      scope: "col"
    }
  }, [_vm._v(_vm._s(_vm.userInfo.username))]), _vm._v(" "), _c("th", {
    staticClass: "cus-ceil title-ceil",
    attrs: {
      scope: "col"
    }
  }, [_vm._v("تاريخ الدخول")]), _vm._v(" "), _c("th", {
    staticClass: "cus-ceil",
    attrs: {
      scope: "col"
    }
  }, [_vm._v(_vm._s(_vm.userInfo.entryDate))]), _vm._v(" "), _c("th", {
    staticClass: "cus-ceil title-ceil",
    attrs: {
      scope: "col"
    }
  }, [_vm._v("قيمة الإيجار")])])]), _vm._v(" "), _c("tbody", [_c("tr", [_c("th", {
    staticClass: "cus-ceil title-ceil",
    attrs: {
      scope: "row"
    }
  }, [_vm._v("بيانات المرافق")]), _vm._v(" "), _c("td", {
    staticClass: "cus-ceil"
  }, [_vm._v(_vm._s(_vm.userInfo.friendName))]), _vm._v(" "), _c("td", {
    staticClass: "font-weight-bold cus-ceil title-ceil"
  }, [_vm._v("\n                تاريخ المغادرة\n              ")]), _vm._v(" "), _c("td", {
    staticClass: "cus-ceil"
  }, [_vm._v(_vm._s(_vm.userInfo.leavingDate))]), _vm._v(" "), _c("td", {
    staticClass: "cus-ceil"
  }, [_vm._v(_vm._s(_vm.userInfo.rentFees))])])])])]), _vm._v(" "), _vm._m(8), _vm._v(" "), _c("div", {
    staticClass: "section-three"
  }, [_c("table", {
    staticClass: "table table-bordered"
  }, [_c("caption", {
    staticClass: "text-center p-1"
  }, [_vm._v("\n            لا يمكن تعديل أو تغيير ارقام لوحات السيارات المسجلة في الجدول\n          ")]), _vm._v(" "), _vm._m(9), _vm._v(" "), _c("tbody", [_c("tr", [_c("th", {
    staticClass: "cus-ceil title-ceil",
    attrs: {
      scope: "row"
    }
  }, [_vm._v("مستأجر الوحدة")]), _vm._v(" "), _c("td", {
    staticClass: "cus-ceil"
  }, [_vm._v(_vm._s(_vm.lastTable.f1))]), _vm._v(" "), _c("td", {
    staticClass: "cus-ceil"
  }, [_vm._v(_vm._s(_vm.lastTable.f2))]), _vm._v(" "), _c("td", {
    staticClass: "cus-ceil",
    attrs: {
      rowspan: "3"
    }
  })]), _vm._v(" "), _c("tr", [_c("th", {
    staticClass: "cus-ceil title-ceil",
    attrs: {
      scope: "row"
    }
  }, [_vm._v("مرافق 1")]), _vm._v(" "), _c("td", {
    staticClass: "cus-ceil"
  }, [_vm._v(_vm._s(_vm.lastTable.f3))]), _vm._v(" "), _c("td", {
    staticClass: "cus-ceil"
  }, [_vm._v(_vm._s(_vm.lastTable.f4))])]), _vm._v(" "), _c("tr", [_c("th", {
    staticClass: "cus-ceil title-ceil",
    attrs: {
      scope: "row"
    }
  }, [_vm._v("مرافق 2")]), _vm._v(" "), _c("td", {
    staticClass: "cus-ceil"
  }, [_vm._v(_vm._s(_vm.lastTable.f5))]), _vm._v(" "), _c("td", {
    staticClass: "cus-ceil"
  }, [_vm._v(_vm._s(_vm.lastTable.f6))])])])])]), _vm._v(" "), _c("div", {
    staticClass: "section-five"
  }, [_c("table", {
    staticClass: "table table-borderless mb-0"
  }, [_vm._m(10), _vm._v(" "), _c("tbody", [_c("tr", [_c("td", {
    staticClass: "cus-ceil"
  }), _vm._v(" "), _c("td", {
    staticClass: "cus-ceil cus-padding"
  }, [_c("qrcode-vue", {
    attrs: {
      value: "https://www.fpe.sa/contracts/" + _vm.random,
      size: "100",
      level: "H"
    }
  })], 1), _vm._v(" "), _c("td", {
    staticClass: "cus-ceil"
  }), _vm._v(" "), _vm._m(11)])])])])]), _vm._v(" "), _c("button", {
    on: {
      click: function click($event) {
        _vm.show = !_vm.show;
      }
    }
  }, [_vm._v("\n      اخفاء الفورم لتحميل الملف من خلال الهاتف\n    ")]), _vm._v(" "), _c("button", {
    directives: [{
      name: "print",
      rawName: "v-print",
      value: "#printMe",
      expression: "'#printMe'"
    }]
  }, [_vm._v("طباعة العقد")])])]);
};

var staticRenderFns = [function () {
  var _vm = this,
      _c = _vm._self._c;

  return _c("div", {
    staticClass: "form-group"
  }, [_c("h3", {
    staticClass: "text-center"
  }, [_vm._v("عقد جديد")])]);
}, function () {
  var _vm = this,
      _c = _vm._self._c;

  return _c("div", {
    staticClass: "logo"
  }, [_c("img", {
    attrs: {
      src: "https://via.placeholder.com/350x150",
      alt: ""
    }
  })]);
}, function () {
  var _vm = this,
      _c = _vm._self._c;

  return _c("h3", {
    staticClass: "main-title"
  }, [_c("span", [_vm._v("عقد ايجار")])]);
}, function () {
  var _vm = this,
      _c = _vm._self._c;

  return _c("div", {
    staticClass: "form-group"
  }, [_c("label", [_vm._v("25")]), _vm._v(" "), _c("label", [_vm._v(": قطاع رقم ")])]);
}, function () {
  var _vm = this,
      _c = _vm._self._c;

  return _c("thead", [_c("tr", [_c("th", {
    attrs: {
      scope: "col"
    }
  }, [_vm._v("Unit Price")]), _vm._v(" "), _c("th", {
    attrs: {
      scope: "col"
    }
  }, [_vm._v("Units")]), _vm._v(" "), _c("th", {
    attrs: {
      scope: "col"
    }
  }), _vm._v(" "), _c("th", {
    attrs: {
      scope: "col"
    }
  }, [_vm._v("Discount")]), _vm._v(" "), _c("th", {
    attrs: {
      scope: "col"
    }
  }, [_vm._v("Price")])])]);
}, function () {
  var _vm = this,
      _c = _vm._self._c;

  return _c("tr", [_c("td"), _vm._v(" "), _c("td", {
    staticClass: "cus-padding"
  }, [_vm._v("Total Discount")]), _vm._v(" "), _c("td"), _vm._v(" "), _c("td", {
    staticClass: "cus-padding"
  }, [_vm._v("-")]), _vm._v(" "), _c("td")]);
}, function () {
  var _vm = this,
      _c = _vm._self._c;

  return _c("tr", [_c("td"), _vm._v(" "), _c("td"), _vm._v(" "), _c("td"), _vm._v(" "), _c("td", {
    staticClass: "cus-padding"
  }, [_vm._v("Sub Total")]), _vm._v(" "), _c("td", {
    staticClass: "cus-padding"
  }, [_vm._v("Sub Total")])]);
}, function () {
  var _vm = this,
      _c = _vm._self._c;

  return _c("tr", [_c("td"), _vm._v(" "), _c("td"), _vm._v(" "), _c("td"), _vm._v(" "), _c("td", {
    staticClass: "cus-padding"
  }, [_vm._v("VAT 15%")]), _vm._v(" "), _c("td", {
    staticClass: "cus-padding"
  }, [_vm._v("VAT 15%")])]);
}, function () {
  var _vm = this,
      _c = _vm._self._c;

  return _c("div", {
    staticClass: "section-four"
  }, [_c("p", {
    staticClass: "header"
  }, [_vm._v("\n          عزيزي الضيف . رغبة من الإدارة في توفير سبل الراحة لضيوفها الكرام\n          وحفاظاً على سلامتكم وراحتكم نرجو التقيد والالتزام بالتالي :\n        ")]), _vm._v(" "), _c("p", {
    staticClass: "point"
  }, [_vm._v("\n          1. الإلتزام بتعاليم الدين الاسلامي ومايتناسب مع العادات والتقاليد\n          المتبعة في المملكة العربية السعوديه.\n        ")]), _vm._v(" "), _c("p", {
    staticClass: "point"
  }, [_vm._v("\n          2. الإلتزام بجميع الاجراءات الاحترازية الخاصة بجائحة كورونا بالتباعد\n          الاجتماعي ولبس الكمامات وعدم التجمعات على الشاطئ وتطبيق كل ما يصدر\n          من الجهات الرسمية .\n        ")]), _vm._v(" "), _c("p", {
    staticClass: "point"
  }, [_vm._v("\n          3. يمنع استخدام مكبرات الصوت او ارعاج الجيران باي وسيلة وفي حال\n          المخالفة يتحمل المستأجر تسديد مبلغ 1000 ريال لصالح ادارة القطاع.\n        ")]), _vm._v(" "), _c("p", {
    staticClass: "point"
  }, [_vm._v("\n          4. الإلتزام بإستخدام الوحدة السكنية لعائلة واحدة فقط تمثل عائلة المستأجر ، ويمنع منعاً باتاً الزيارة للمستأجر من الأفراد ، ويحق للإدارة إخلاء الوحدة في حال مغادرة العائلة ولا يحق للمستأجر استرجاع قيمة الأيجار بعد استلام الوحدة نهائياً ويتحمل المستأجر دفع غرامة بمبلغ 1000 ريال لصالح القطاع.\n        ")]), _vm._v(" "), _c("p", {
    staticClass: "point"
  }, [_vm._v("\n          5. يتحمل المستأجر المسئولية الكاملة عن كل ماياصدر من المرافقين ويحق\n          للادارة اتخاذ الاجراء المناسب دون الرجوع للمستأجر.\n        ")]), _vm._v(" "), _c("p", {
    staticClass: "point"
  }, [_vm._v("\n          6. اخلاء مسئولية الادارة تماما عن أي حالة إصابة او غرق للمستأجر او\n          أي من مرافقيه لاسمح الله في المسابح او البحر وان السباحة تحت\n          مسؤليتكم الشخصية.\n        ")]), _vm._v(" "), _c("p", {
    staticClass: "point"
  }, [_vm._v("\n          7. يجب دفع مبلغ تأمين لضمان حسن استخدام الوحدة السكنية والمرافق\n          العامة ويسترجع في حال عدم وجود اي تلفيات.\n        ")]), _vm._v(" "), _c("p", {
    staticClass: "point"
  }, [_vm._v("\n          8. الوحدة أثثت لراحتكم واستمتاعكم لكل مافيها لذا فإن محتويات الوحدة\n          ستكون تحت مسئوليتكم اثناء الإقامة وفي حالة وجود اي اضرار ناتجة عن\n          سوء الاستخدام فسوف تضاف لحسابكم الخاص وتخصم من التأمين في حال عدم\n          كفاية التأمين يلتزم النزيل بدفع المبلغ كاملاً.\n        ")]), _vm._v(" "), _c("p", {
    staticClass: "point"
  }, [_vm._v("\n          9. الالتزام بالتقيد بموعد المغادرة الساعة 12 الظهر وسيتم حساب ليلة\n          اضافية في حال التاخر عن هذا الموعد .\n        ")]), _vm._v(" "), _c("p", {
    staticClass: "point"
  }, [_vm._v("\n          10. الادارة غير مسئولة عن فقدان او تلف متعلقاتكم الشخصية ) الثمينة\n          او النقدية ( طول فت سريان عقد ايجاركم.\n        ")]), _vm._v(" "), _c("p", {
    staticClass: "point"
  }, [_vm._v("\n          11. مكتب اثمار غير مسؤول عن تصاريح الدخول للأفراد ) الشباب (\n          والسيارات ، حيث ان أرقام اللوحات للسيارات المذكورة ادناه لدخول\n          العوائل فقط ، ويمنع دخول أي سيارة تحتوي على شباب من بوابان القطاع.\n        ")]), _vm._v(" "), _c("p", {
    staticClass: "point"
  }, [_vm._v("\n          12. قيمة العقد غير مستودة ولايمكن التعديل او تغيير العقد بعد إصدارة\n          لأي سبب كان .\n        ")])]);
}, function () {
  var _vm = this,
      _c = _vm._self._c;

  return _c("thead", [_c("tr", [_c("th", {
    staticClass: "cus-ceil title-ceil",
    attrs: {
      scope: "col"
    }
  }, [_vm._v("رقم")]), _vm._v(" "), _c("th", {
    staticClass: "font-weight-bold cus-ceil title-ceil",
    attrs: {
      scope: "col"
    }
  }, [_vm._v("\n                نوع السيارة\n              ")]), _vm._v(" "), _c("th", {
    staticClass: "cus-ceil title-ceil",
    attrs: {
      scope: "col"
    }
  }, [_vm._v("بيانات اللوحة")]), _vm._v(" "), _c("th", {
    staticClass: "font-weight-bold cus-ceil title-ceil",
    attrs: {
      scope: "col"
    }
  }, [_vm._v("\n                ختم تصريح مدينة الدرة\n              ")])])]);
}, function () {
  var _vm = this,
      _c = _vm._self._c;

  return _c("thead", [_c("tr", [_c("th", {
    staticClass: "cus-ceil title-ceil cus-padding",
    attrs: {
      scope: "col"
    }
  }, [_vm._v("\n                توقيع المستأجر\n              ")]), _vm._v(" "), _c("th", {
    staticClass: "font-weight-bold cus-ceil title-ceil",
    attrs: {
      scope: "col"
    }
  }), _vm._v(" "), _c("th", {
    staticClass: "cus-ceil title-ceil cus-padding",
    attrs: {
      scope: "col"
    }
  }, [_vm._v("\n                توقيع المالك المفوض\n              ")]), _vm._v(" "), _c("th", {
    staticClass: "cus-ceil title-ceil cus-padding",
    attrs: {
      scope: "col"
    }
  }, [_vm._v("\n                بادارة\n              ")])])]);
}, function () {
  var _vm = this,
      _c = _vm._self._c;

  return _c("td", {
    staticClass: "cus-ceil cus-padding"
  }, [_c("img", {
    attrs: {
      src: "https://via.placeholder.com/150x150",
      alt: ""
    }
  })]);
}];
render._withStripped = true;


/***/ }),

/***/ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/AhmedPrintPDF.vue?vue&type=style&index=0&id=6a384691&scoped=true&lang=css&":
/*!************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/AhmedPrintPDF.vue?vue&type=style&index=0&id=6a384691&scoped=true&lang=css& ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../node_modules/laravel-mix/node_modules/css-loader/dist/runtime/api.js */ "./node_modules/laravel-mix/node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, "\n/* Start Main Features */\n/* @import url(\"https://fonts.googleapis.com/css2?family=Almarai&display=swap\"); */\n.cus-border-table[data-v-6a384691] {\n  border: 2px dashed gray;\n  padding-bottom: 0;\n  padding-top: 0;\n  vertical-align: middle;\n  text-align: center;\n}\n.cus-width[data-v-6a384691] {\n  min-width: 60px;\n}\n/* End Main Features */\n.cus-label[data-v-6a384691] {\n  vertical-align: middle;\n}\n.cus-border[data-v-6a384691] {\n  border: 2px dashed gray;\n  padding: 5px;\n  height: 48px;\n  width: 185px;\n  vertical-align: middle;\n}\n.cus-padding[data-v-6a384691] {\n  padding: 5px;\n}\nform[data-v-6a384691] {\n  margin: 50px auto;\n  max-width: 500px;\n}\n.pdf[data-v-6a384691] {\n  border: 4px solid red;\n  padding: 13px;\n  width: 1130px;\n    margin: auto;\n  /* -webkit-print-color-adjust: exact; */\n}\n.pdf *[data-v-6a384691] {\n  font-family: \"Almarai\", sans-serif;\n}\n\n/* Start Section One Logo and main info section */\n.section-one[data-v-6a384691],\n.section-two[data-v-6a384691] {\n  display: flex;\n  justify-content: space-between;\n}\n.pdf .section-one .logo[data-v-6a384691] {\n  width: 300px;\n  height: 100px;\n}\n.pdf .section-one .logo img[data-v-6a384691] {\n  width: 100%;\n  height: 100%;\n}\n.pdf .section-one .info .form-group[data-v-6a384691] {\n  margin-bottom: 0;\n}\n/* End Section One Logo and main info section */\n\n/* Start Main Title */\n.pdf .main-title[data-v-6a384691] {\n  text-align: center;\n  margin-bottom: 20px;\n}\n.pdf .main-title span[data-v-6a384691] {\n  padding-bottom: 10px;\n  border-bottom: 5px solid red;\n  font-family: \"Almarai\", sans-serif;\n  font-size: 40px;\n  font-weight: bold;\n}\n/* End Main Title */\n/* Start Section Two */\n.section-two .price-section[data-v-6a384691] {\n  width: 50%;\n}\n.section-two .price-section .table[data-v-6a384691] {\n  margin-bottom: 0;\n  width: 100%;\n}\n/* End Section Two */\n\n/* Start Sectioon Three */\n.section-three[data-v-6a384691] {\n  direction: rtl;\n  margin-top: 20px;\n  text-align: right;\n}\n.section-three .table-bordered[data-v-6a384691] {\n  border: 2px solid #a29b9b;\n  margin-bottom: 0;\n}\n.section-three .table tr[data-v-6a384691] {\n  min-height: 100px;\n}\n.section-three .table .cus-ceil[data-v-6a384691] {\n  /* width: 20%; */\n  vertical-align: middle;\n  min-height: 100px;\n}\n.section-three .table .title-ceil[data-v-6a384691] {\n  text-align: center;\n  background-color: #e1e2e3;\n  font-size: 20px;\n  font-family: \"Almarai\", sans-serif;\n  padding: 7px;\n}\n.section-three .table thead th[data-v-6a384691] {\n  border-bottom: 2px solid black;\n}\n.section-three .table-bordered th[data-v-6a384691],\n.section-three .table-bordered td[data-v-6a384691] {\n  border: 2px solid black;\n}\n\n/* End Sectioon Three */\n\n/* Start Section Four */\n.section-four[data-v-6a384691] {\n  direction: rtl;\n  text-align: right;\n  margin-top: 15px;\n}\n.section-four p[data-v-6a384691] {\n  font-family: \"Almarai\", sans-serif;\n  margin-bottom: 0;\n}\n.section-four .header[data-v-6a384691] {\n  font-weight: bold;\n  font-size: 16px;\n}\n.section-four .point[data-v-6a384691] {\n  font-size: 14px;\n}\n/* End Section Four */\n\n/* Start Section Five */\n.section-five[data-v-6a384691] {\n  direction: rtl;\n  text-align: center;\n}\n/* End Section Five */\nbutton[data-v-6a384691] {\n  padding: 12px;\n  background-color: #fff;\n  border: 2px solid #555;\n  color: #555;\n  outline: none;\n  margin-top: 10px;\n  font-family: \"Almarai\", sans-serif;\n  width: auto;\n}\n", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/laravel-mix/node_modules/css-loader/dist/runtime/api.js":
/*!******************************************************************************!*\
  !*** ./node_modules/laravel-mix/node_modules/css-loader/dist/runtime/api.js ***!
  \******************************************************************************/
/***/ ((module) => {



/*
  MIT License http://www.opensource.org/licenses/mit-license.php
  Author Tobias Koppers @sokra
*/
// css base code, injected by the css-loader
// eslint-disable-next-line func-names
module.exports = function (cssWithMappingToString) {
  var list = []; // return the list of modules as css string

  list.toString = function toString() {
    return this.map(function (item) {
      var content = cssWithMappingToString(item);

      if (item[2]) {
        return "@media ".concat(item[2], " {").concat(content, "}");
      }

      return content;
    }).join("");
  }; // import a list of modules into the list
  // eslint-disable-next-line func-names


  list.i = function (modules, mediaQuery, dedupe) {
    if (typeof modules === "string") {
      // eslint-disable-next-line no-param-reassign
      modules = [[null, modules, ""]];
    }

    var alreadyImportedModules = {};

    if (dedupe) {
      for (var i = 0; i < this.length; i++) {
        // eslint-disable-next-line prefer-destructuring
        var id = this[i][0];

        if (id != null) {
          alreadyImportedModules[id] = true;
        }
      }
    }

    for (var _i = 0; _i < modules.length; _i++) {
      var item = [].concat(modules[_i]);

      if (dedupe && alreadyImportedModules[item[0]]) {
        // eslint-disable-next-line no-continue
        continue;
      }

      if (mediaQuery) {
        if (!item[2]) {
          item[2] = mediaQuery;
        } else {
          item[2] = "".concat(mediaQuery, " and ").concat(item[2]);
        }
      }

      list.push(item);
    }
  };

  return list;
};

/***/ }),

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/AhmedPrintPDF.vue?vue&type=style&index=0&id=6a384691&scoped=true&lang=css&":
/*!****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/AhmedPrintPDF.vue?vue&type=style&index=0&id=6a384691&scoped=true&lang=css& ***!
  \****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_AhmedPrintPDF_vue_vue_type_style_index_0_id_6a384691_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AhmedPrintPDF.vue?vue&type=style&index=0&id=6a384691&scoped=true&lang=css& */ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/AhmedPrintPDF.vue?vue&type=style&index=0&id=6a384691&scoped=true&lang=css&");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_AhmedPrintPDF_vue_vue_type_style_index_0_id_6a384691_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_AhmedPrintPDF_vue_vue_type_style_index_0_id_6a384691_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js":
/*!****************************************************************************!*\
  !*** ./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js ***!
  \****************************************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {



var isOldIE = function isOldIE() {
  var memo;
  return function memorize() {
    if (typeof memo === 'undefined') {
      // Test for IE <= 9 as proposed by Browserhacks
      // @see http://browserhacks.com/#hack-e71d8692f65334173fee715c222cb805
      // Tests for existence of standard globals is to allow style-loader
      // to operate correctly into non-standard environments
      // @see https://github.com/webpack-contrib/style-loader/issues/177
      memo = Boolean(window && document && document.all && !window.atob);
    }

    return memo;
  };
}();

var getTarget = function getTarget() {
  var memo = {};
  return function memorize(target) {
    if (typeof memo[target] === 'undefined') {
      var styleTarget = document.querySelector(target); // Special case to return head of iframe instead of iframe itself

      if (window.HTMLIFrameElement && styleTarget instanceof window.HTMLIFrameElement) {
        try {
          // This will throw an exception if access to iframe is blocked
          // due to cross-origin restrictions
          styleTarget = styleTarget.contentDocument.head;
        } catch (e) {
          // istanbul ignore next
          styleTarget = null;
        }
      }

      memo[target] = styleTarget;
    }

    return memo[target];
  };
}();

var stylesInDom = [];

function getIndexByIdentifier(identifier) {
  var result = -1;

  for (var i = 0; i < stylesInDom.length; i++) {
    if (stylesInDom[i].identifier === identifier) {
      result = i;
      break;
    }
  }

  return result;
}

function modulesToDom(list, options) {
  var idCountMap = {};
  var identifiers = [];

  for (var i = 0; i < list.length; i++) {
    var item = list[i];
    var id = options.base ? item[0] + options.base : item[0];
    var count = idCountMap[id] || 0;
    var identifier = "".concat(id, " ").concat(count);
    idCountMap[id] = count + 1;
    var index = getIndexByIdentifier(identifier);
    var obj = {
      css: item[1],
      media: item[2],
      sourceMap: item[3]
    };

    if (index !== -1) {
      stylesInDom[index].references++;
      stylesInDom[index].updater(obj);
    } else {
      stylesInDom.push({
        identifier: identifier,
        updater: addStyle(obj, options),
        references: 1
      });
    }

    identifiers.push(identifier);
  }

  return identifiers;
}

function insertStyleElement(options) {
  var style = document.createElement('style');
  var attributes = options.attributes || {};

  if (typeof attributes.nonce === 'undefined') {
    var nonce =  true ? __webpack_require__.nc : 0;

    if (nonce) {
      attributes.nonce = nonce;
    }
  }

  Object.keys(attributes).forEach(function (key) {
    style.setAttribute(key, attributes[key]);
  });

  if (typeof options.insert === 'function') {
    options.insert(style);
  } else {
    var target = getTarget(options.insert || 'head');

    if (!target) {
      throw new Error("Couldn't find a style target. This probably means that the value for the 'insert' parameter is invalid.");
    }

    target.appendChild(style);
  }

  return style;
}

function removeStyleElement(style) {
  // istanbul ignore if
  if (style.parentNode === null) {
    return false;
  }

  style.parentNode.removeChild(style);
}
/* istanbul ignore next  */


var replaceText = function replaceText() {
  var textStore = [];
  return function replace(index, replacement) {
    textStore[index] = replacement;
    return textStore.filter(Boolean).join('\n');
  };
}();

function applyToSingletonTag(style, index, remove, obj) {
  var css = remove ? '' : obj.media ? "@media ".concat(obj.media, " {").concat(obj.css, "}") : obj.css; // For old IE

  /* istanbul ignore if  */

  if (style.styleSheet) {
    style.styleSheet.cssText = replaceText(index, css);
  } else {
    var cssNode = document.createTextNode(css);
    var childNodes = style.childNodes;

    if (childNodes[index]) {
      style.removeChild(childNodes[index]);
    }

    if (childNodes.length) {
      style.insertBefore(cssNode, childNodes[index]);
    } else {
      style.appendChild(cssNode);
    }
  }
}

function applyToTag(style, options, obj) {
  var css = obj.css;
  var media = obj.media;
  var sourceMap = obj.sourceMap;

  if (media) {
    style.setAttribute('media', media);
  } else {
    style.removeAttribute('media');
  }

  if (sourceMap && typeof btoa !== 'undefined') {
    css += "\n/*# sourceMappingURL=data:application/json;base64,".concat(btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap)))), " */");
  } // For old IE

  /* istanbul ignore if  */


  if (style.styleSheet) {
    style.styleSheet.cssText = css;
  } else {
    while (style.firstChild) {
      style.removeChild(style.firstChild);
    }

    style.appendChild(document.createTextNode(css));
  }
}

var singleton = null;
var singletonCounter = 0;

function addStyle(obj, options) {
  var style;
  var update;
  var remove;

  if (options.singleton) {
    var styleIndex = singletonCounter++;
    style = singleton || (singleton = insertStyleElement(options));
    update = applyToSingletonTag.bind(null, style, styleIndex, false);
    remove = applyToSingletonTag.bind(null, style, styleIndex, true);
  } else {
    style = insertStyleElement(options);
    update = applyToTag.bind(null, style, options);

    remove = function remove() {
      removeStyleElement(style);
    };
  }

  update(obj);
  return function updateStyle(newObj) {
    if (newObj) {
      if (newObj.css === obj.css && newObj.media === obj.media && newObj.sourceMap === obj.sourceMap) {
        return;
      }

      update(obj = newObj);
    } else {
      remove();
    }
  };
}

module.exports = function (list, options) {
  options = options || {}; // Force single-tag solution on IE6-9, which has a hard limit on the # of <style>
  // tags it will allow on a page

  if (!options.singleton && typeof options.singleton !== 'boolean') {
    options.singleton = isOldIE();
  }

  list = list || [];
  var lastIdentifiers = modulesToDom(list, options);
  return function update(newList) {
    newList = newList || [];

    if (Object.prototype.toString.call(newList) !== '[object Array]') {
      return;
    }

    for (var i = 0; i < lastIdentifiers.length; i++) {
      var identifier = lastIdentifiers[i];
      var index = getIndexByIdentifier(identifier);
      stylesInDom[index].references--;
    }

    var newLastIdentifiers = modulesToDom(newList, options);

    for (var _i = 0; _i < lastIdentifiers.length; _i++) {
      var _identifier = lastIdentifiers[_i];

      var _index = getIndexByIdentifier(_identifier);

      if (stylesInDom[_index].references === 0) {
        stylesInDom[_index].updater();

        stylesInDom.splice(_index, 1);
      }
    }

    lastIdentifiers = newLastIdentifiers;
  };
};

/***/ }),

/***/ "./resources/js/Pages/AhmedPrintPDF.vue":
/*!**********************************************!*\
  !*** ./resources/js/Pages/AhmedPrintPDF.vue ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _AhmedPrintPDF_vue_vue_type_template_id_6a384691_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AhmedPrintPDF.vue?vue&type=template&id=6a384691&scoped=true& */ "./resources/js/Pages/AhmedPrintPDF.vue?vue&type=template&id=6a384691&scoped=true&");
/* harmony import */ var _AhmedPrintPDF_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AhmedPrintPDF.vue?vue&type=script&lang=js& */ "./resources/js/Pages/AhmedPrintPDF.vue?vue&type=script&lang=js&");
/* harmony import */ var _AhmedPrintPDF_vue_vue_type_style_index_0_id_6a384691_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./AhmedPrintPDF.vue?vue&type=style&index=0&id=6a384691&scoped=true&lang=css& */ "./resources/js/Pages/AhmedPrintPDF.vue?vue&type=style&index=0&id=6a384691&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! !../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");



;


/* normalize component */

var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _AhmedPrintPDF_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _AhmedPrintPDF_vue_vue_type_template_id_6a384691_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _AhmedPrintPDF_vue_vue_type_template_id_6a384691_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "6a384691",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/AhmedPrintPDF.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/AhmedPrintPDF.vue?vue&type=script&lang=js&":
/*!***********************************************************************!*\
  !*** ./resources/js/Pages/AhmedPrintPDF.vue?vue&type=script&lang=js& ***!
  \***********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AhmedPrintPDF_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AhmedPrintPDF.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/AhmedPrintPDF.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AhmedPrintPDF_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/AhmedPrintPDF.vue?vue&type=template&id=6a384691&scoped=true&":
/*!*****************************************************************************************!*\
  !*** ./resources/js/Pages/AhmedPrintPDF.vue?vue&type=template&id=6a384691&scoped=true& ***!
  \*****************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_AhmedPrintPDF_vue_vue_type_template_id_6a384691_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_AhmedPrintPDF_vue_vue_type_template_id_6a384691_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_AhmedPrintPDF_vue_vue_type_template_id_6a384691_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AhmedPrintPDF.vue?vue&type=template&id=6a384691&scoped=true& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/AhmedPrintPDF.vue?vue&type=template&id=6a384691&scoped=true&");


/***/ }),

/***/ "./resources/js/Pages/AhmedPrintPDF.vue?vue&type=style&index=0&id=6a384691&scoped=true&lang=css&":
/*!*******************************************************************************************************!*\
  !*** ./resources/js/Pages/AhmedPrintPDF.vue?vue&type=style&index=0&id=6a384691&scoped=true&lang=css& ***!
  \*******************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_AhmedPrintPDF_vue_vue_type_style_index_0_id_6a384691_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/style-loader/dist/cjs.js!../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AhmedPrintPDF.vue?vue&type=style&index=0&id=6a384691&scoped=true&lang=css& */ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/AhmedPrintPDF.vue?vue&type=style&index=0&id=6a384691&scoped=true&lang=css&");


/***/ })

}]);