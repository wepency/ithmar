"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_Pages_AhmedPrintPDF_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/Pages/AhmedPrintPDF.vue?vue&type=script&lang=js"
/*!**************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/Pages/AhmedPrintPDF.vue?vue&type=script&lang=js ***!
  \**************************************************************************************************************************************************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var vue3_print_nb__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue3-print-nb */ "./node_modules/vue3-print-nb/dist/vue3-print-nb.es.js");
/* harmony import */ var qrcode_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! qrcode.vue */ "./node_modules/qrcode.vue/dist/qrcode.vue.esm.js");


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  name: "AhmedPrintPDF",
  directives: {
    print: vue3_print_nb__WEBPACK_IMPORTED_MODULE_0__["default"]
  },
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

/***/ },

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/Pages/AhmedPrintPDF.vue?vue&type=template&id=6a384691&scoped=true"
/*!******************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/Pages/AhmedPrintPDF.vue?vue&type=template&id=6a384691&scoped=true ***!
  \******************************************************************************************************************************************************************************************************************************************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm-bundler.js");
function _toConsumableArray(r) { return _arrayWithoutHoles(r) || _iterableToArray(r) || _unsupportedIterableToArray(r) || _nonIterableSpread(); }
function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _iterableToArray(r) { if ("undefined" != typeof Symbol && null != r[Symbol.iterator] || null != r["@@iterator"]) return Array.from(r); }
function _arrayWithoutHoles(r) { if (Array.isArray(r)) return _arrayLikeToArray(r); }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }

var _hoisted_1 = {
  "class": "container-fluid"
};
var _hoisted_2 = {
  key: 0
};
var _hoisted_3 = {
  "class": "form-group"
};
var _hoisted_4 = {
  "class": "form-group"
};
var _hoisted_5 = {
  "class": "form-group"
};
var _hoisted_6 = {
  "class": "form-group"
};
var _hoisted_7 = {
  "class": "pdf",
  id: "printMe"
};
var _hoisted_8 = {
  "class": "section-one"
};
var _hoisted_9 = {
  "class": "info"
};
var _hoisted_10 = {
  "class": "form-group"
};
var _hoisted_11 = {
  "class": "form-group"
};
var _hoisted_12 = {
  "class": "form-group"
};
var _hoisted_13 = {
  "class": "form-group"
};
var _hoisted_14 = {
  "class": "section-two"
};
var _hoisted_15 = {
  "class": "info-section"
};
var _hoisted_16 = {
  "class": "form-group"
};
var _hoisted_17 = {
  "class": "cus-border-table cus-padding cus-width"
};
var _hoisted_18 = {
  "class": "form-group"
};
var _hoisted_19 = {
  "class": "cus-border-table cus-padding cus-width"
};
var _hoisted_20 = {
  "class": "form-group"
};
var _hoisted_21 = {
  "class": "price-section"
};
var _hoisted_22 = {
  "class": "table table-borderless"
};
var _hoisted_23 = {
  "class": "cus-border-table cus-padding"
};
var _hoisted_24 = {
  scope: "row",
  "class": "cus-border-table cus-padding"
};
var _hoisted_25 = {
  "class": "cus-border-table cus-padding"
};
var _hoisted_26 = {
  "class": "cus-border-table cus-padding"
};
var _hoisted_27 = {
  "class": "cus-border-table cus-padding"
};
var _hoisted_28 = {
  "class": "cus-border-table cus-padding"
};
var _hoisted_29 = {
  "class": "section-three"
};
var _hoisted_30 = {
  "class": "table table-bordered"
};
var _hoisted_31 = {
  scope: "col",
  "class": "cus-ceil"
};
var _hoisted_32 = {
  scope: "col",
  "class": "cus-ceil"
};
var _hoisted_33 = {
  "class": "cus-ceil"
};
var _hoisted_34 = {
  "class": "cus-ceil"
};
var _hoisted_35 = {
  "class": "cus-ceil"
};
var _hoisted_36 = {
  "class": "section-three"
};
var _hoisted_37 = {
  "class": "table table-bordered"
};
var _hoisted_38 = {
  "class": "cus-ceil"
};
var _hoisted_39 = {
  "class": "cus-ceil"
};
var _hoisted_40 = {
  "class": "cus-ceil"
};
var _hoisted_41 = {
  "class": "cus-ceil"
};
var _hoisted_42 = {
  "class": "cus-ceil"
};
var _hoisted_43 = {
  "class": "cus-ceil"
};
var _hoisted_44 = {
  "class": "section-five"
};
var _hoisted_45 = {
  "class": "table table-borderless mb-0"
};
var _hoisted_46 = {
  "class": "cus-ceil cus-padding"
};
function render(_ctx, _cache, $props, $setup, $data, $options) {
  var _component_qrcode_vue = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("qrcode-vue");
  var _directive_print = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveDirective)("print");
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("main", null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_1, [$data.show ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("form", _hoisted_2, [_cache[9] || (_cache[9] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    "class": "form-group"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h3", {
    "class": "text-center"
  }, "عقد جديد")], -1 /* CACHED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_3, [_cache[5] || (_cache[5] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", null, "Phone", -1 /* CACHED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.withDirectives)((0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
    type: "text",
    "class": "form-control",
    "onUpdate:modelValue": _cache[0] || (_cache[0] = function ($event) {
      return $data.mainInfo.phone = $event;
    })
  }, null, 512 /* NEED_PATCH */), [[vue__WEBPACK_IMPORTED_MODULE_0__.vModelText, $data.mainInfo.phone]])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_4, [_cache[6] || (_cache[6] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", null, "E-mail", -1 /* CACHED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.withDirectives)((0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
    type: "email",
    "class": "form-control",
    "onUpdate:modelValue": _cache[1] || (_cache[1] = function ($event) {
      return $data.mainInfo.email = $event;
    })
  }, null, 512 /* NEED_PATCH */), [[vue__WEBPACK_IMPORTED_MODULE_0__.vModelText, $data.mainInfo.email]])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_5, [_cache[7] || (_cache[7] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", null, "website", -1 /* CACHED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.withDirectives)((0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
    type: "text",
    "class": "form-control",
    "onUpdate:modelValue": _cache[2] || (_cache[2] = function ($event) {
      return $data.mainInfo.website = $event;
    })
  }, null, 512 /* NEED_PATCH */), [[vue__WEBPACK_IMPORTED_MODULE_0__.vModelText, $data.mainInfo.website]])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_6, [_cache[8] || (_cache[8] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", null, "Vat", -1 /* CACHED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.withDirectives)((0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
    type: "text",
    "class": "form-control",
    "onUpdate:modelValue": _cache[3] || (_cache[3] = function ($event) {
      return $data.mainInfo.vat = $event;
    })
  }, null, 512 /* NEED_PATCH */), [[vue__WEBPACK_IMPORTED_MODULE_0__.vModelText, $data.mainInfo.vat]])])])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_7, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" Start Section One Logo and main info section "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_8, [_cache[14] || (_cache[14] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    "class": "logo"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("img", {
    src: "https://via.placeholder.com/350x150",
    alt: ""
  })], -1 /* CACHED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_9, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_10, [_cache[10] || (_cache[10] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
    "class": "cus-width"
  }, "Phone :", -1 /* CACHED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.mainInfo.phone), 1 /* TEXT */)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_11, [_cache[11] || (_cache[11] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
    "class": "cus-width"
  }, "Email :", -1 /* CACHED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.mainInfo.email), 1 /* TEXT */)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_12, [_cache[12] || (_cache[12] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
    "class": "cus-width"
  }, "Website :", -1 /* CACHED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.mainInfo.website), 1 /* TEXT */)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_13, [_cache[13] || (_cache[13] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
    "class": "cus-width"
  }, "VAT :", -1 /* CACHED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.mainInfo.vat), 1 /* TEXT */)])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" End Section One Logo and main info section "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" Start Main Title "), _cache[56] || (_cache[56] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h3", {
    "class": "main-title"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", null, "عقد ايجار")], -1 /* CACHED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" End Main Title "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_14, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_15, [_cache[17] || (_cache[17] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    "class": "form-group"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", null, "25"), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", null, ": قطاع رقم ")], -1 /* CACHED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_16, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", _hoisted_17, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.unitInfo.price1), 1 /* TEXT */)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" <br> "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_18, [_cache[15] || (_cache[15] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
    "class": "cus-label"
  }, "Product Name :", -1 /* CACHED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", _hoisted_19, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.unitInfo.price2), 1 /* TEXT */)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_20, [_cache[16] || (_cache[16] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", null, "25", -1 /* CACHED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.unitInfo.cleanFees) + " : رسوم التعقيم ", 1 /* TEXT */)])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_21, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("table", _hoisted_22, [_cache[40] || (_cache[40] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("thead", null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("tr", null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("th", {
    scope: "col"
  }, "Unit Price"), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("th", {
    scope: "col"
  }, "Units"), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("th", {
    scope: "col"
  }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("th", {
    scope: "col"
  }, "Discount"), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("th", {
    scope: "col"
  }, "Price")])], -1 /* CACHED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("tbody", null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("tr", null, [_cache[18] || (_cache[18] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("th", {
    scope: "row",
    "class": "cus-border-table cus-padding"
  }, "55", -1 /* CACHED */)), _cache[19] || (_cache[19] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", {
    "class": "cus-padding"
  }, "1.00", -1 /* CACHED */)), _cache[20] || (_cache[20] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", null, null, -1 /* CACHED */)), _cache[21] || (_cache[21] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", {
    "class": "cus-padding"
  }, "-", -1 /* CACHED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", _hoisted_23, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.unitInfo.sectionNumber), 1 /* TEXT */)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("tr", null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("th", _hoisted_24, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.unitInfo.sectionName), 1 /* TEXT */), _cache[22] || (_cache[22] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", {
    "class": "cus-padding"
  }, "1.00", -1 /* CACHED */)), _cache[23] || (_cache[23] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", null, null, -1 /* CACHED */)), _cache[24] || (_cache[24] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", {
    "class": "cus-padding"
  }, "-", -1 /* CACHED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", _hoisted_25, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.unitInfo.totalAmount), 1 /* TEXT */)]), _cache[37] || (_cache[37] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("tr", null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td"), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", {
    "class": "cus-padding"
  }, "Total Discount"), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td"), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", {
    "class": "cus-padding"
  }, "-"), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td")], -1 /* CACHED */)), _cache[38] || (_cache[38] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("tr", null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td"), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td"), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td"), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", {
    "class": "cus-padding"
  }, "Sub Total"), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", {
    "class": "cus-padding"
  }, "Sub Total")], -1 /* CACHED */)), _cache[39] || (_cache[39] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("tr", null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td"), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td"), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td"), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", {
    "class": "cus-padding"
  }, "VAT 15%"), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", {
    "class": "cus-padding"
  }, "VAT 15%")], -1 /* CACHED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("tr", null, [_cache[25] || (_cache[25] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", null, null, -1 /* CACHED */)), _cache[26] || (_cache[26] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", null, null, -1 /* CACHED */)), _cache[27] || (_cache[27] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", null, null, -1 /* CACHED */)), _cache[28] || (_cache[28] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", {
    "class": "cus-padding"
  }, "Total Amount", -1 /* CACHED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", _hoisted_26, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.unitInfo.TotalPaid), 1 /* TEXT */)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("tr", null, [_cache[29] || (_cache[29] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", null, null, -1 /* CACHED */)), _cache[30] || (_cache[30] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", null, null, -1 /* CACHED */)), _cache[31] || (_cache[31] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", null, null, -1 /* CACHED */)), _cache[32] || (_cache[32] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", {
    "class": "cus-padding"
  }, "Total Paid", -1 /* CACHED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", _hoisted_27, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.unitInfo.Balance), 1 /* TEXT */)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("tr", null, [_cache[33] || (_cache[33] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", null, null, -1 /* CACHED */)), _cache[34] || (_cache[34] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", null, null, -1 /* CACHED */)), _cache[35] || (_cache[35] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", null, null, -1 /* CACHED */)), _cache[36] || (_cache[36] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", {
    "class": "cus-padding"
  }, "Balance", -1 /* CACHED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", _hoisted_28, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.unitInfo.productName), 1 /* TEXT */)])])])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_29, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("table", _hoisted_30, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("thead", null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("tr", null, [_cache[41] || (_cache[41] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("th", {
    scope: "col",
    "class": "cus-ceil title-ceil"
  }, "اسم المستأجر", -1 /* CACHED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("th", _hoisted_31, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.userInfo.username), 1 /* TEXT */), _cache[42] || (_cache[42] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("th", {
    scope: "col",
    "class": "cus-ceil title-ceil"
  }, "تاريخ الدخول", -1 /* CACHED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("th", _hoisted_32, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.userInfo.entryDate), 1 /* TEXT */), _cache[43] || (_cache[43] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("th", {
    scope: "col",
    "class": "cus-ceil title-ceil"
  }, "قيمة الإيجار", -1 /* CACHED */))])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("tbody", null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("tr", null, [_cache[44] || (_cache[44] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("th", {
    scope: "row",
    "class": "cus-ceil title-ceil"
  }, "بيانات المرافق", -1 /* CACHED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", _hoisted_33, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.userInfo.friendName), 1 /* TEXT */), _cache[45] || (_cache[45] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", {
    "class": "font-weight-bold cus-ceil title-ceil"
  }, " تاريخ المغادرة ", -1 /* CACHED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", _hoisted_34, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.userInfo.leavingDate), 1 /* TEXT */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", _hoisted_35, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.userInfo.rentFees), 1 /* TEXT */)])])])]), _cache[57] || (_cache[57] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createStaticVNode)("<div class=\"section-four\" data-v-6a384691><p class=\"header\" data-v-6a384691> عزيزي الضيف . رغبة من الإدارة في توفير سبل الراحة لضيوفها الكرام وحفاظاً على سلامتكم وراحتكم نرجو التقيد والالتزام بالتالي : </p><p class=\"point\" data-v-6a384691> 1. الإلتزام بتعاليم الدين الاسلامي ومايتناسب مع العادات والتقاليد المتبعة في المملكة العربية السعوديه. </p><p class=\"point\" data-v-6a384691> 2. الإلتزام بجميع الاجراءات الاحترازية الخاصة بجائحة كورونا بالتباعد الاجتماعي ولبس الكمامات وعدم التجمعات على الشاطئ وتطبيق كل ما يصدر من الجهات الرسمية . </p><p class=\"point\" data-v-6a384691> 3. يمنع استخدام مكبرات الصوت او ارعاج الجيران باي وسيلة وفي حال المخالفة يتحمل المستأجر تسديد مبلغ 1000 ريال لصالح ادارة القطاع. </p><p class=\"point\" data-v-6a384691> 4. السكن لعائلة واحدة فقط وتمنع الزياره بسكل عام للأفراد ) الشباب ( ويحق للادارة اخلاء الوحدة عند مغادرة العائلة ولا يحق للمستأجر استرجاع قيمة الأيجار بعد استلام الوحدة نهائياً. </p><p class=\"point\" data-v-6a384691> 5. يتحمل المستأجر المسئولية الكاملة عن كل ماياصدر من المرافقين ويحق للادارة اتخاذ الاجراء المناسب دون الرجوع للمستأجر. </p><p class=\"point\" data-v-6a384691> 6. اخلاء مسئولية الادارة تماما عن أي حالة إصابة او غرق للمستأجر او أي من مرافقيه لاسمح الله في المسابح او البحر وان السباحة تحت مسؤليتكم الشخصية. </p><p class=\"point\" data-v-6a384691> 7. يجب دفع مبلغ تأمين لضمان حسن استخدام الوحدة السكنية والمرافق العامة ويسترجع في حال عدم وجود اي تلفيات. </p><p class=\"point\" data-v-6a384691> 8. الوحدة أثثت لراحتكم واستمتاعكم لكل مافيها لذا فإن محتويات الوحدة ستكون تحت مسئوليتكم اثناء الإقامة وفي حالة وجود اي اضرار ناتجة عن سوء الاستخدام فسوف تضاف لحسابكم الخاص وتخصم من التأمين في حال عدم كفاية التأمين يلتزم النزيل بدفع المبلغ كاملاً. </p><p class=\"point\" data-v-6a384691> 9. الالتزام بالتقيد بموعد المغادرة الساعة 12 الظهر وسيتم حساب ليلة اضافية في حال التاخر عن هذا الموعد . </p><p class=\"point\" data-v-6a384691> 10. الادارة غير مسئولة عن فقدان او تلف متعلقاتكم الشخصية ) الثمينة او النقدية ( طول فت سريان عقد ايجاركم. </p><p class=\"point\" data-v-6a384691> 11. مكتب اثمار غير مسؤول عن تصاريح الدخول للأفراد ) الشباب ( والسيارات ، حيث ان أرقام اللوحات للسيارات المذكورة ادناه لدخول العوائل فقط ، ويمنع دخول أي سيارة تحتوي على شباب من بوابان القطاع. </p><p class=\"point\" data-v-6a384691> 12. قيمة العقد غير مستودة ولايمكن التعديل او تغيير العقد بعد إصدارة لأي سبب كان . </p></div>", 1)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_36, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("table", _hoisted_37, [_cache[50] || (_cache[50] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("caption", {
    "class": "text-center p-1"
  }, " لا يمكن تعديل أو تغيير ارقام لوحات السيارات المسجلة في الجدول ", -1 /* CACHED */)), _cache[51] || (_cache[51] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("thead", null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("tr", null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("th", {
    scope: "col",
    "class": "cus-ceil title-ceil"
  }, "رقم"), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("th", {
    scope: "col",
    "class": "font-weight-bold cus-ceil title-ceil"
  }, " نوع السيارة "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("th", {
    scope: "col",
    "class": "cus-ceil title-ceil"
  }, "بيانات اللوحة"), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("th", {
    scope: "col",
    "class": "font-weight-bold cus-ceil title-ceil"
  }, " ختم تصريح مدينة الدرة ")])], -1 /* CACHED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("tbody", null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("tr", null, [_cache[46] || (_cache[46] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("th", {
    scope: "row",
    "class": "cus-ceil title-ceil"
  }, "مستأجر الوحدة", -1 /* CACHED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", _hoisted_38, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.lastTable.f1), 1 /* TEXT */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", _hoisted_39, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.lastTable.f2), 1 /* TEXT */), _cache[47] || (_cache[47] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", {
    "class": "cus-ceil",
    rowspan: "3"
  }, null, -1 /* CACHED */))]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("tr", null, [_cache[48] || (_cache[48] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("th", {
    scope: "row",
    "class": "cus-ceil title-ceil"
  }, "مرافق 1", -1 /* CACHED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", _hoisted_40, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.lastTable.f3), 1 /* TEXT */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", _hoisted_41, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.lastTable.f4), 1 /* TEXT */)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("tr", null, [_cache[49] || (_cache[49] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("th", {
    scope: "row",
    "class": "cus-ceil title-ceil"
  }, "مرافق 2", -1 /* CACHED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", _hoisted_42, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.lastTable.f5), 1 /* TEXT */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", _hoisted_43, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.lastTable.f6), 1 /* TEXT */)])])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_44, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("table", _hoisted_45, [_cache[55] || (_cache[55] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("thead", null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("tr", null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("th", {
    scope: "col",
    "class": "cus-ceil title-ceil cus-padding"
  }, " توقيع المستأجر "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("th", {
    scope: "col",
    "class": "font-weight-bold cus-ceil title-ceil"
  }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("th", {
    scope: "col",
    "class": "cus-ceil title-ceil cus-padding"
  }, " توقيع المالك المفوض "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("th", {
    scope: "col",
    "class": "cus-ceil title-ceil cus-padding"
  }, " بادارة ")])], -1 /* CACHED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("tbody", null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("tr", null, [_cache[52] || (_cache[52] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", {
    "class": "cus-ceil"
  }, null, -1 /* CACHED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", _hoisted_46, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_qrcode_vue, {
    value: 'https://www.fpe.sa/contracts/' + $data.random,
    size: "100",
    level: "H"
  }, null, 8 /* PROPS */, ["value"])]), _cache[53] || (_cache[53] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", {
    "class": "cus-ceil"
  }, null, -1 /* CACHED */)), _cache[54] || (_cache[54] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("td", {
    "class": "cus-ceil cus-padding"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("img", {
    src: "https://via.placeholder.com/150x150",
    alt: ""
  })], -1 /* CACHED */))])])])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("button", {
    onClick: _cache[4] || (_cache[4] = function ($event) {
      return $data.show = !$data.show;
    })
  }, " اخفاء الفورم لتحميل الملف من خلال الهاتف "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.withDirectives)(((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("button", null, _toConsumableArray(_cache[58] || (_cache[58] = [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)("طباعة العقد", -1 /* CACHED */)])))), [[_directive_print, '#printMe']])])]);
}

/***/ },

/***/ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/Pages/AhmedPrintPDF.vue?vue&type=style&index=0&id=6a384691&scoped=true&lang=css"
/*!****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/Pages/AhmedPrintPDF.vue?vue&type=style&index=0&id=6a384691&scoped=true&lang=css ***!
  \****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
(module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../node_modules/laravel-mix/node_modules/css-loader/dist/runtime/api.js */ "./node_modules/laravel-mix/node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, "\n/* Start Main Features */\n/* @import url(\"https://fonts.googleapis.com/css2?family=Almarai&display=swap\"); */\n.cus-border-table[data-v-6a384691] {\n  border: 2px dashed gray;\n  padding-bottom: 0;\n  padding-top: 0;\n  vertical-align: middle;\n  text-align: center;\n}\n.cus-width[data-v-6a384691] {\n  min-width: 60px;\n}\n/* End Main Features */\n.cus-label[data-v-6a384691] {\n  vertical-align: middle;\n}\n.cus-border[data-v-6a384691] {\n  border: 2px dashed gray;\n  padding: 5px;\n  height: 48px;\n  width: 185px;\n  vertical-align: middle;\n}\n.cus-padding[data-v-6a384691] {\n  padding: 5px;\n}\nform[data-v-6a384691] {\n  margin: 50px auto;\n  max-width: 500px;\n}\n.pdf[data-v-6a384691] {\n  border: 4px solid red;\n  padding: 13px;\n  width: 1130px;\n    margin: auto;\n  /* -webkit-print-color-adjust: exact; */\n}\n.pdf[data-v-6a384691] * {\n  font-family: \"Almarai\", sans-serif;\n}\n\n/* Start Section One Logo and main info section */\n.section-one[data-v-6a384691],\n.section-two[data-v-6a384691] {\n  display: flex;\n  justify-content: space-between;\n}\n.pdf .section-one .logo[data-v-6a384691] {\n  width: 300px;\n  height: 100px;\n}\n.pdf .section-one .logo img[data-v-6a384691] {\n  width: 100%;\n  height: 100%;\n}\n.pdf .section-one .info .form-group[data-v-6a384691] {\n  margin-bottom: 0;\n}\n/* End Section One Logo and main info section */\n\n/* Start Main Title */\n.pdf .main-title[data-v-6a384691] {\n  text-align: center;\n  margin-bottom: 20px;\n}\n.pdf .main-title span[data-v-6a384691] {\n  padding-bottom: 10px;\n  border-bottom: 5px solid red;\n  font-family: \"Almarai\", sans-serif;\n  font-size: 40px;\n  font-weight: bold;\n}\n/* End Main Title */\n/* Start Section Two */\n.section-two .price-section[data-v-6a384691] {\n  width: 50%;\n}\n.section-two .price-section .table[data-v-6a384691] {\n  margin-bottom: 0;\n  width: 100%;\n}\n/* End Section Two */\n\n/* Start Sectioon Three */\n.section-three[data-v-6a384691] {\n  direction: rtl;\n  margin-top: 20px;\n  text-align: right;\n}\n.section-three .table-bordered[data-v-6a384691] {\n  border: 2px solid #a29b9b;\n  margin-bottom: 0;\n}\n.section-three .table tr[data-v-6a384691] {\n  min-height: 100px;\n}\n.section-three .table .cus-ceil[data-v-6a384691] {\n  /* width: 20%; */\n  vertical-align: middle;\n  min-height: 100px;\n}\n.section-three .table .title-ceil[data-v-6a384691] {\n  text-align: center;\n  background-color: #e1e2e3;\n  font-size: 20px;\n  font-family: \"Almarai\", sans-serif;\n  padding: 7px;\n}\n.section-three .table thead th[data-v-6a384691] {\n  border-bottom: 2px solid black;\n}\n.section-three .table-bordered th[data-v-6a384691],\n.section-three .table-bordered td[data-v-6a384691] {\n  border: 2px solid black;\n}\n\n/* End Sectioon Three */\n\n/* Start Section Four */\n.section-four[data-v-6a384691] {\n  direction: rtl;\n  text-align: right;\n  margin-top: 15px;\n}\n.section-four p[data-v-6a384691] {\n  font-family: \"Almarai\", sans-serif;\n  margin-bottom: 0;\n}\n.section-four .header[data-v-6a384691] {\n  font-weight: bold;\n  font-size: 16px;\n}\n.section-four .point[data-v-6a384691] {\n  font-size: 14px;\n}\n/* End Section Four */\n\n/* Start Section Five */\n.section-five[data-v-6a384691] {\n  direction: rtl;\n  text-align: center;\n}\n/* End Section Five */\nbutton[data-v-6a384691] {\n  padding: 12px;\n  background-color: #fff;\n  border: 2px solid #555;\n  color: #555;\n  outline: none;\n  margin-top: 10px;\n  font-family: \"Almarai\", sans-serif;\n  width: auto;\n}\n", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ },

/***/ "./node_modules/laravel-mix/node_modules/css-loader/dist/runtime/api.js"
/*!******************************************************************************!*\
  !*** ./node_modules/laravel-mix/node_modules/css-loader/dist/runtime/api.js ***!
  \******************************************************************************/
(module) {



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

/***/ },

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/Pages/AhmedPrintPDF.vue?vue&type=style&index=0&id=6a384691&scoped=true&lang=css"
/*!********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/Pages/AhmedPrintPDF.vue?vue&type=style&index=0&id=6a384691&scoped=true&lang=css ***!
  \********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_use_1_node_modules_vue_loader_dist_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_use_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_AhmedPrintPDF_vue_vue_type_style_index_0_id_6a384691_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!../../../node_modules/vue-loader/dist/stylePostLoader.js!../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./AhmedPrintPDF.vue?vue&type=style&index=0&id=6a384691&scoped=true&lang=css */ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/Pages/AhmedPrintPDF.vue?vue&type=style&index=0&id=6a384691&scoped=true&lang=css");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_use_1_node_modules_vue_loader_dist_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_use_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_AhmedPrintPDF_vue_vue_type_style_index_0_id_6a384691_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_use_1_node_modules_vue_loader_dist_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_use_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_AhmedPrintPDF_vue_vue_type_style_index_0_id_6a384691_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ },

/***/ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js"
/*!****************************************************************************!*\
  !*** ./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js ***!
  \****************************************************************************/
(module, __unused_webpack_exports, __webpack_require__) {



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

/***/ },

/***/ "./resources/js/Pages/AhmedPrintPDF.vue"
/*!**********************************************!*\
  !*** ./resources/js/Pages/AhmedPrintPDF.vue ***!
  \**********************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _AhmedPrintPDF_vue_vue_type_template_id_6a384691_scoped_true__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AhmedPrintPDF.vue?vue&type=template&id=6a384691&scoped=true */ "./resources/js/Pages/AhmedPrintPDF.vue?vue&type=template&id=6a384691&scoped=true");
/* harmony import */ var _AhmedPrintPDF_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AhmedPrintPDF.vue?vue&type=script&lang=js */ "./resources/js/Pages/AhmedPrintPDF.vue?vue&type=script&lang=js");
/* harmony import */ var _AhmedPrintPDF_vue_vue_type_style_index_0_id_6a384691_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./AhmedPrintPDF.vue?vue&type=style&index=0&id=6a384691&scoped=true&lang=css */ "./resources/js/Pages/AhmedPrintPDF.vue?vue&type=style&index=0&id=6a384691&scoped=true&lang=css");
/* harmony import */ var _node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;


const __exports__ = /*#__PURE__*/(0,_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_3__["default"])(_AhmedPrintPDF_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_AhmedPrintPDF_vue_vue_type_template_id_6a384691_scoped_true__WEBPACK_IMPORTED_MODULE_0__.render],['__scopeId',"data-v-6a384691"],['__file',"resources/js/Pages/AhmedPrintPDF.vue"]])
/* hot reload */
if (false) // removed by dead control flow
{}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ },

/***/ "./resources/js/Pages/AhmedPrintPDF.vue?vue&type=script&lang=js"
/*!**********************************************************************!*\
  !*** ./resources/js/Pages/AhmedPrintPDF.vue?vue&type=script&lang=js ***!
  \**********************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_AhmedPrintPDF_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_AhmedPrintPDF_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./AhmedPrintPDF.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/Pages/AhmedPrintPDF.vue?vue&type=script&lang=js");
 

/***/ },

/***/ "./resources/js/Pages/AhmedPrintPDF.vue?vue&type=style&index=0&id=6a384691&scoped=true&lang=css"
/*!******************************************************************************************************!*\
  !*** ./resources/js/Pages/AhmedPrintPDF.vue?vue&type=style&index=0&id=6a384691&scoped=true&lang=css ***!
  \******************************************************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_use_1_node_modules_vue_loader_dist_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_use_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_AhmedPrintPDF_vue_vue_type_style_index_0_id_6a384691_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/style-loader/dist/cjs.js!../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!../../../node_modules/vue-loader/dist/stylePostLoader.js!../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./AhmedPrintPDF.vue?vue&type=style&index=0&id=6a384691&scoped=true&lang=css */ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/Pages/AhmedPrintPDF.vue?vue&type=style&index=0&id=6a384691&scoped=true&lang=css");


/***/ },

/***/ "./resources/js/Pages/AhmedPrintPDF.vue?vue&type=template&id=6a384691&scoped=true"
/*!****************************************************************************************!*\
  !*** ./resources/js/Pages/AhmedPrintPDF.vue?vue&type=template&id=6a384691&scoped=true ***!
  \****************************************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_AhmedPrintPDF_vue_vue_type_template_id_6a384691_scoped_true__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_AhmedPrintPDF_vue_vue_type_template_id_6a384691_scoped_true__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./AhmedPrintPDF.vue?vue&type=template&id=6a384691&scoped=true */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/Pages/AhmedPrintPDF.vue?vue&type=template&id=6a384691&scoped=true");


/***/ }

}]);