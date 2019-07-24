/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports) {

throw new Error("Module build failed: Error: ENOENT: no such file or directory, open 'D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\vue-loader\\lib\\component-normalizer.js'");

/***/ }),
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(2);
module.exports = __webpack_require__(21);


/***/ }),
/* 2 */
/***/ (function(module, exports, __webpack_require__) {

Nova.booting(function (Vue, router, store) {
    Vue.component('index-nova-inline-relationship', __webpack_require__(3));
    Vue.component('detail-nova-inline-relationship', __webpack_require__(6));
    Vue.component('form-nova-inline-relationship', __webpack_require__(12));

    Vue.config.devtools = true;
});

/***/ }),
/* 3 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(4)
/* template */
var __vue_template__ = __webpack_require__(5)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/js/components/IndexField.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-9e63f81a", Component.options)
  } else {
    hotAPI.reload("data-v-9e63f81a", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 4 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    props: ['resourceName', 'field']
});

/***/ }),
/* 5 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("span", [
    _vm._v(
      _vm._s(Object.keys(_vm.field.value).length) +
        " " +
        _vm._s(_vm.field.name) +
        "(s)"
    )
  ])
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-9e63f81a", module.exports)
  }
}

/***/ }),
/* 6 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(7)
/* template */
var __vue_template__ = __webpack_require__(11)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/js/components/DetailField.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-0224618e", Component.options)
  } else {
    hotAPI.reload("data-v-0224618e", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 7 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__RelationshipDetailItem__ = __webpack_require__(8);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__RelationshipDetailItem___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__RelationshipDetailItem__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//



/* harmony default export */ __webpack_exports__["default"] = ({
    components: { RelationshipDetailItem: __WEBPACK_IMPORTED_MODULE_0__RelationshipDetailItem___default.a },

    props: ['resource', 'resourceName', 'resourceId', 'field'],

    computed: {
        collapsed: function collapsed() {
            return this.field.collapsed === true;
        },

        value: function value() {
            if (Array.isArray(this.field.value)) {
                return this.field.value;
            }

            return [];
        }
    }
});

/***/ }),
/* 8 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(9)
/* template */
var __vue_template__ = __webpack_require__(10)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/js/components/RelationshipDetailItem.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-7ddb7d87", Component.options)
  } else {
    hotAPI.reload("data-v-7ddb7d87", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 9 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    name: "RelationshipDetailItem",

    props: {
        'value': Object,
        'settings': Object,
        'collapsed': {
            type: Boolean,
            default: false
        },
        'label': String,
        'id': Number
    },

    methods: {
        getLabel: function getLabel(attrib) {
            return this.getSettings(attrib, 'label') || attrib;
        },

        getSettings: function getSettings(attrib, key) {
            return this.settings && this.settings.hasOwnProperty(attrib) && this.settings[attrib].hasOwnProperty(key) ? this.settings[attrib][key] : '';
        }
    }
});

/***/ }),
/* 10 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    { staticClass: "card shadow-md mb-4" },
    [
      _c("div", { staticClass: "bg-30 flex p-2 border-b border-40" }, [
        _c("span", [
          _vm.collapsed
            ? _c(
                "button",
                {
                  staticClass: "btn btn-default btn-icon btn-white mr-3 p-1",
                  on: {
                    click: function($event) {
                      _vm.collapsed = false
                    }
                  }
                },
                [
                  _c(
                    "svg",
                    {
                      attrs: {
                        xmlns: "http://www.w3.org/2000/svg",
                        viewBox: "0 0 24 24",
                        width: "24",
                        height: "24"
                      }
                    },
                    [
                      _c("path", {
                        staticClass: "heroicon-ui",
                        attrs: {
                          d:
                            "M17 11a1 1 0 0 1 0 2h-4v4a1 1 0 0 1-2 0v-4H7a1 1 0 0 1 0-2h4V7a1 1 0 0 1 2 0v4h4z"
                        }
                      })
                    ]
                  )
                ]
              )
            : _c(
                "button",
                {
                  staticClass: "btn btn-default btn-icon btn-white mr-3 p-1",
                  on: {
                    click: function($event) {
                      _vm.collapsed = true
                    }
                  }
                },
                [
                  _c(
                    "svg",
                    {
                      attrs: {
                        xmlns: "http://www.w3.org/2000/svg",
                        viewBox: "0 0 24 24",
                        width: "24",
                        height: "24"
                      }
                    },
                    [
                      _c("path", {
                        staticClass: "heroicon-ui",
                        attrs: { d: "M17 11a1 1 0 0 1 0 2H7a1 1 0 0 1 0-2h10z" }
                      })
                    ]
                  )
                ]
              )
        ]),
        _vm._v(" "),
        _c("span", { staticClass: "font-normal text-90 py-2 px-2" }, [
          _vm._v(
            "\n      " + _vm._s(_vm.label) + " " + _vm._s(_vm.id + 1) + "\n    "
          )
        ])
      ]),
      _vm._v(" "),
      _c("transition", { attrs: { name: "slide-fade" } }, [
        !_vm.collapsed
          ? _c(
              "div",
              _vm._l(_vm.value, function(parameter, attrib) {
                return _c(
                  "div",
                  { key: attrib, staticClass: "w-full px-6" },
                  [
                    _c("detail-" + parameter.meta.component, {
                      tag: "component",
                      attrs: { field: parameter.meta }
                    })
                  ],
                  1
                )
              }),
              0
            )
          : _vm._e()
      ])
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-7ddb7d87", module.exports)
  }
}

/***/ }),
/* 11 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { staticClass: "flex border-b border-40" }, [
    _c(
      "div",
      { staticClass: "w-1/4 py-4" },
      [
        _vm._t("default", [
          _c("h4", { staticClass: "font-normal text-80" }, [
            _vm._v("\n        " + _vm._s(_vm.field.name) + "\n      ")
          ])
        ])
      ],
      2
    ),
    _vm._v(" "),
    _c("div", { staticClass: "w-3/4 py-4" }, [
      _c(
        "div",
        _vm._l(_vm.value, function(item, index) {
          return _c("RelationshipDetailItem", {
            key: index,
            attrs: {
              id: index,
              value: item,
              label: _vm.field.name,
              settings: _vm.field.settings,
              collapsed: _vm.collapsed
            }
          })
        }),
        1
      )
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-0224618e", module.exports)
  }
}

/***/ }),
/* 12 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(13)
/* template */
var __vue_template__ = __webpack_require__(20)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/js/components/FormField.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-c023248a", Component.options)
  } else {
    hotAPI.reload("data-v-c023248a", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 13 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_laravel_nova__ = __webpack_require__(14);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_laravel_nova___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_laravel_nova__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_vuedraggable__ = __webpack_require__(15);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_vuedraggable___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1_vuedraggable__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__RelationshipFormItem_vue__ = __webpack_require__(17);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__RelationshipFormItem_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_2__RelationshipFormItem_vue__);
var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//





/* harmony default export */ __webpack_exports__["default"] = ({
    components: {
        draggable: __WEBPACK_IMPORTED_MODULE_1_vuedraggable__["default"],
        RelationshipFormItem: __WEBPACK_IMPORTED_MODULE_2__RelationshipFormItem_vue___default.a
    },

    mixins: [__WEBPACK_IMPORTED_MODULE_0_laravel_nova__["FormField"], __WEBPACK_IMPORTED_MODULE_0_laravel_nova__["HandlesValidationErrors"]],

    props: ['resourceName', 'resourceId', 'field'],

    data: function data() {
        return {
            id: 0,
            errorList: new __WEBPACK_IMPORTED_MODULE_0_laravel_nova__["Errors"]()
        };
    },

    watch: {
        'errors': function errors(_errors) {
            var errObj = _errors.errors.hasOwnProperty(this.field.attribute) ? _errors.errors[this.field.attribute][0] : {};
            Object.keys(errObj).forEach(function (key) {
                errObj[key.replace(/\./g, '_')] = errObj[key];
                delete errObj[key];
            });
            this.errorList = new __WEBPACK_IMPORTED_MODULE_0_laravel_nova__["Errors"](errObj);
        }
    },

    methods: {
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue: function setInitialValue() {
            var _this = this;

            this.value = Array.isArray(this.field.value) ? this.field.value : [];
            this.value = this.value.map(function (item) {
                return { 'id': _this.getNextId(), 'fields': item };
            });

            if (this.field.singular) {
                this.value = this.value.splice(1);
            }

            if (this.field.addChildAtStart && this.value.length == 0) {
                this.value.push({ 'id': this.getNextId(), 'fields': _extends({}, this.field.defaults) });
            }
        },


        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill: function fill(formData) {
            try {
                this.fillValueFromChildren(formData);
            } catch (error) {
                console.log(error);
            }
        },


        fillValueFromChildren: function fillValueFromChildren(formData) {
            _(this.$refs).each(function (item) {
                item[0].fill(formData);
            });
        },

        /**
         * Update the field's internal value.
         */
        handleChange: function handleChange(value) {
            this.value = Array.isArray(value) ? value : [];
        },
        getNextId: function getNextId() {
            this.id += 1;
            return this.id;
        },
        removeItem: function removeItem(index) {
            var value = [].concat(_toConsumableArray(this.value));
            value.splice(index, 1);
            this.handleChange(value);
        },
        addItem: function addItem() {
            var value = [].concat(_toConsumableArray(this.value));
            value.push({ 'id': this.getNextId(), 'fields': _extends({}, this.field.defaults) });
            this.handleChange(value);
        }
    }
});

/***/ }),
/* 14 */
/***/ (function(module, exports) {

throw new Error("Module build failed: Error: ENOENT: no such file or directory, open 'D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\laravel-nova\\dist\\index.js'");

/***/ }),
/* 15 */
/***/ (function(module, exports) {

throw new Error("Module build failed: Error: ENOENT: no such file or directory, open 'D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\vuedraggable\\dist\\vuedraggable.common.js'");

/***/ }),
/* 16 */,
/* 17 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(18)
/* template */
var __vue_template__ = __webpack_require__(19)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/js/components/RelationshipFormItem.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-6255cdba", Component.options)
  } else {
    hotAPI.reload("data-v-6255cdba", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 18 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//


/* harmony default export */ __webpack_exports__["default"] = ({
    name: "RelationshipFormItem",

    props: ['value', 'label', 'id', 'errors', 'field'],

    computed: {
        fields: function fields() {
            var _this = this;

            return _.keyBy(Object.keys(_extends({}, this.value)).map(function (attrib) {
                return _extends({
                    'options': {}
                }, _this.value[attrib].meta, {
                    'attribute': _this.field.attribute + '_' + _this.id + '_' + attrib,
                    'name': _this.field.attribute + '[' + _this.id + '][' + attrib + ']',
                    'attrib': attrib
                });
            }), 'attrib');
        }
    },

    methods: {
        getValueFromChildren: function getValueFromChildren() {
            var _this2 = this;

            return _.tap(new FormData(), function (formData) {
                _(_this2.$refs).each(function (item) {
                    if (item[0].field.component === 'file-field') {
                        if (item[0].file) {
                            formData.append(item[0].field.attribute, item[0].file, item[0].fileName);
                        } else if (item[0].value) {
                            formData.append(item[0].field.attribute, String(item[0].value));
                        }
                    } else if (item[0].field.component === 'boolean-field') {
                        formData.append(item[0].field.attribute, item[0].trueValue);
                    } else {
                        item[0].fill(formData);
                    }
                });
            });
        },

        fill: function fill(formData) {
            var _this3 = this;

            this.getValueFromChildren().forEach(function (value, key) {
                var keyParts = key.split('_');
                var parentAttrib = keyParts.unshift();
                var keyId = keyParts.unshift();
                var attrib = key.join('_');
                formData.append(parentAttrib + '[' + _this3.id + '][' + attrib + ']', value);
            });
        },


        removeItem: function removeItem() {
            this.$emit('deleted', this.id);
        }
    }
});

/***/ }),
/* 19 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    { staticClass: "card shadow-md mb-4" },
    [
      _c("div", { staticClass: "bg-30 flex p-2 border-b border-40" }, [
        !_vm.field.singular
          ? _c("div", { staticClass: "w-1/8 text-left py-2 px-2" }, [
              _c(
                "span",
                {
                  staticClass: "relationship-item-handle py-2 px-2 cursor-move"
                },
                [
                  _c(
                    "svg",
                    {
                      attrs: {
                        xmlns: "http://www.w3.org/2000/svg",
                        viewBox: "0 0 24 24",
                        width: "24",
                        height: "24"
                      }
                    },
                    [
                      _c("path", {
                        staticClass: "heroicon-ui",
                        attrs: {
                          d:
                            "M4 5h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2z"
                        }
                      })
                    ]
                  )
                ]
              )
            ])
          : _vm._e(),
        _vm._v(" "),
        _c("div", { staticClass: "w-5/8 flex-grow text-left py-2 px-2" }, [
          _c("h4", { staticClass: "font-normal text-80" }, [
            _vm._v(
              "\n        " +
                _vm._s(_vm.field.name) +
                " " +
                _vm._s(_vm.id + 1) +
                "\n      "
            )
          ])
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "w-1/4 text-right" }, [
          _c(
            "button",
            {
              staticClass:
                "btn btn-default btn-icon btn bg-transparent hover:bg-danger text-danger hover:text-white border border-danger hover:border-transparent inline-flex items-center relative mr-3",
              attrs: { title: "Delete" },
              on: {
                click: function($event) {
                  $event.preventDefault()
                  return _vm.removeItem()
                }
              }
            },
            [
              _c(
                "svg",
                {
                  staticClass: "fill-current text-0",
                  attrs: {
                    xmlns: "http://www.w3.org/2000/svg",
                    width: "20",
                    height: "20",
                    viewBox: "0 0 20 20",
                    "aria-labelledby": "delete",
                    role: "presentation"
                  }
                },
                [
                  _c("path", {
                    attrs: {
                      "fill-rule": "nonzero",
                      d:
                        "M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"
                    }
                  })
                ]
              )
            ]
          )
        ])
      ]),
      _vm._v(" "),
      _vm._l(_vm.fields, function(field, attrib) {
        return _c(
          "div",
          { key: attrib, staticClass: "nova-items-field-input-wrapper w-full" },
          [
            _c("form-" + field.component, {
              ref: attrib,
              refInFor: true,
              tag: "component",
              attrs: {
                field: field,
                "full-width-content": true,
                errors: _vm.errors
              }
            })
          ],
          1
        )
      })
    ],
    2
  )
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-6255cdba", module.exports)
  }
}

/***/ }),
/* 20 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "default-field",
    {
      attrs: {
        field: _vm.field,
        errors: _vm.errors,
        "show-errors": false,
        "full-width-content": true
      }
    },
    [
      _c(
        "template",
        { slot: "field" },
        [
          _c(
            "draggable",
            {
              attrs: { handle: ".relationship-item-handle" },
              on: {
                start: function($event) {
                  _vm.drag = true
                },
                end: function($event) {
                  _vm.drag = false
                }
              },
              model: {
                value: _vm.value,
                callback: function($$v) {
                  _vm.value = $$v
                },
                expression: "value"
              }
            },
            _vm._l(_vm.value, function(items, index) {
              return _c("relationship-form-item", {
                key: items.id,
                ref: index,
                refInFor: true,
                attrs: {
                  id: index,
                  value: items.fields,
                  errors: _vm.errorList,
                  field: _vm.field
                },
                on: {
                  deleted: function($event) {
                    return _vm.removeItem(index)
                  }
                }
              })
            }),
            1
          ),
          _vm._v(" "),
          !_vm.field.singular
            ? _c(
                "div",
                { staticClass: "bg-30 flex p-2 border-b border-40 rounded-lg" },
                [
                  _c("div", { staticClass: "w-full text-right" }, [
                    _c(
                      "button",
                      {
                        staticClass:
                          "btn btn-default bg-transparent hover:bg-primary text-primary hover:text-white border border-primary hover:border-transparent inline-flex items-center relative mr-3",
                        attrs: { type: "button" },
                        on: {
                          click: function($event) {
                            return _vm.addItem()
                          }
                        }
                      },
                      [
                        _vm._v(
                          "\n          Add new " +
                            _vm._s(_vm.field.name) +
                            "\n        "
                        )
                      ]
                    )
                  ])
                ]
              )
            : _vm._e()
        ],
        1
      )
    ],
    2
  )
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-c023248a", module.exports)
  }
}

/***/ }),
/* 21 */
/***/ (function(module, exports) {

throw new Error("Module build failed: ModuleBuildError: Module build failed: Error: ENOENT: no such file or directory, open 'D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\sass-loader\\lib\\loader.js'\n    at Object.openSync (fs.js:447:3)\n    at Object.readFileSync (fs.js:349:35)\n    at Object.Module._extensions..js (internal/modules/cjs/loader.js:786:22)\n    at Module.load (internal/modules/cjs/loader.js:643:32)\n    at Function.Module._load (internal/modules/cjs/loader.js:556:12)\n    at Module.require (internal/modules/cjs/loader.js:683:19)\n    at require (internal/modules/cjs/helpers.js:16:16)\n    at loadLoader (D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\loader-runner\\lib\\loadLoader.js:18:17)\n    at iteratePitchingLoaders (D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\loader-runner\\lib\\LoaderRunner.js:169:2)\n    at iteratePitchingLoaders (D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\loader-runner\\lib\\LoaderRunner.js:165:10)\n    at D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\loader-runner\\lib\\LoaderRunner.js:176:18\n    at loadLoader (D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\loader-runner\\lib\\loadLoader.js:47:3)\n    at iteratePitchingLoaders (D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\loader-runner\\lib\\LoaderRunner.js:169:2)\n    at iteratePitchingLoaders (D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\loader-runner\\lib\\LoaderRunner.js:165:10)\n    at D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\loader-runner\\lib\\LoaderRunner.js:176:18\n    at loadLoader (D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\loader-runner\\lib\\loadLoader.js:47:3)\n    at iteratePitchingLoaders (D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\loader-runner\\lib\\LoaderRunner.js:169:2)\n    at iteratePitchingLoaders (D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\loader-runner\\lib\\LoaderRunner.js:165:10)\n    at D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\loader-runner\\lib\\LoaderRunner.js:176:18\n    at loadLoader (D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\loader-runner\\lib\\loadLoader.js:47:3)\n    at iteratePitchingLoaders (D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\loader-runner\\lib\\LoaderRunner.js:169:2)\n    at runLoaders (D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\loader-runner\\lib\\LoaderRunner.js:365:2)\n    at NormalModule.doBuild (D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\webpack\\lib\\NormalModule.js:182:3)\n    at NormalModule.build (D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\webpack\\lib\\NormalModule.js:275:15)\n    at Compilation.buildModule (D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\webpack\\lib\\Compilation.js:157:10)\n    at D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\webpack\\lib\\Compilation.js:460:10\n    at D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\webpack\\lib\\NormalModuleFactory.js:243:5\n    at D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\webpack\\lib\\NormalModuleFactory.js:94:13\n    at D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\tapable\\lib\\Tapable.js:268:11\n    at NormalModuleFactory.<anonymous> (D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\webpack\\lib\\CompatibilityPlugin.js:52:5)\n    at D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\webpack\\lib\\NormalModule.js:195:19\n    at D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\loader-runner\\lib\\LoaderRunner.js:367:11\n    at D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\loader-runner\\lib\\LoaderRunner.js:172:11\n    at loadLoader (D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\loader-runner\\lib\\loadLoader.js:32:11)\n    at iteratePitchingLoaders (D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\loader-runner\\lib\\LoaderRunner.js:169:2)\n    at iteratePitchingLoaders (D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\loader-runner\\lib\\LoaderRunner.js:165:10)\n    at D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\loader-runner\\lib\\LoaderRunner.js:176:18\n    at loadLoader (D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\loader-runner\\lib\\loadLoader.js:47:3)\n    at iteratePitchingLoaders (D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\loader-runner\\lib\\LoaderRunner.js:169:2)\n    at iteratePitchingLoaders (D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\loader-runner\\lib\\LoaderRunner.js:165:10)\n    at D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\loader-runner\\lib\\LoaderRunner.js:176:18\n    at loadLoader (D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\loader-runner\\lib\\loadLoader.js:47:3)\n    at iteratePitchingLoaders (D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\loader-runner\\lib\\LoaderRunner.js:169:2)\n    at iteratePitchingLoaders (D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\loader-runner\\lib\\LoaderRunner.js:165:10)\n    at D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\loader-runner\\lib\\LoaderRunner.js:176:18\n    at loadLoader (D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\loader-runner\\lib\\loadLoader.js:47:3)\n    at iteratePitchingLoaders (D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\loader-runner\\lib\\LoaderRunner.js:169:2)\n    at runLoaders (D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\loader-runner\\lib\\LoaderRunner.js:365:2)\n    at NormalModule.doBuild (D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\webpack\\lib\\NormalModule.js:182:3)\n    at NormalModule.build (D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\webpack\\lib\\NormalModule.js:275:15)\n    at Compilation.buildModule (D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\webpack\\lib\\Compilation.js:157:10)\n    at D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\webpack\\lib\\Compilation.js:460:10\n    at D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\webpack\\lib\\NormalModuleFactory.js:243:5\n    at D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\webpack\\lib\\NormalModuleFactory.js:94:13\n    at D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\tapable\\lib\\Tapable.js:268:11\n    at NormalModuleFactory.<anonymous> (D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\webpack\\lib\\CompatibilityPlugin.js:52:5)\n    at NormalModuleFactory.applyPluginsAsyncWaterfall (D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\tapable\\lib\\Tapable.js:272:13)\n    at D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\webpack\\lib\\NormalModuleFactory.js:69:10\n    at D:\\kdg\\projects\\kdg-nova\\nova-components\\NovaInlineRelationship\\node_modules\\webpack\\lib\\NormalModuleFactory.js:196:7\n    at processTicksAndRejections (internal/process/task_queues.js:72:11)");

/***/ })
/******/ ]);