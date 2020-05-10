!function(n){var e={};function t(a){if(e[a])return e[a].exports;var r=e[a]={i:a,l:!1,exports:{}};return n[a].call(r.exports,r,r.exports,t),r.l=!0,r.exports}t.m=n,t.c=e,t.d=function(n,e,a){t.o(n,e)||Object.defineProperty(n,e,{enumerable:!0,get:a})},t.r=function(n){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(n,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(n,"__esModule",{value:!0})},t.t=function(n,e){if(1&e&&(n=t(n)),8&e)return n;if(4&e&&"object"==typeof n&&n&&n.__esModule)return n;var a=Object.create(null);if(t.r(a),Object.defineProperty(a,"default",{enumerable:!0,value:n}),2&e&&"string"!=typeof n)for(var r in n)t.d(a,r,function(e){return n[e]}.bind(null,r));return a},t.n=function(n){var e=n&&n.__esModule?function(){return n.default}:function(){return n};return t.d(e,"a",e),e},t.o=function(n,e){return Object.prototype.hasOwnProperty.call(n,e)},t.p="",t(t.s="./src/js/admin.js")}({"./src/js/admin.js":
/*!*************************!*\
  !*** ./src/js/admin.js ***!
  \*************************/
/*! no exports provided */function(module,__webpack_exports__,__webpack_require__){"use strict";eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _components_tabs_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./components/_tabs.js */ \"./src/js/components/_tabs.js\");\n/**!\r\n * SaleXpresso Admin Scripts\r\n *\r\n * @author SaleXpresso <support@salexpresso.com>\r\n * @package SaleXpresso\r\n * @version 1.0.0\r\n * @since 1.0.0\r\n */\n // import $ from 'jquery';\n// import { sprintf, _n } from '@wordpress/i18n';\n\n(function ($, window, document, wp, pagenow, SaleXpresso) {\n  // const sxp_page = 0 === pagenow.indexOf( 'salexpresso_page_' ) ? pagenow.replace( 'salexpresso_page_', '' ) : false;\n  $(window).on('load', function () {\n    var sxhWrapper = $('.sxp-wrapper');\n    $(document).on('change', '.selector', function (event) {\n      event.preventDefault();\n    });\n\n    if (sxhWrapper.hasClass('sxp-has-tabs')) {\n      Object(_components_tabs_js__WEBPACK_IMPORTED_MODULE_0__[\"tabs\"])();\n    }\n  }); // date range picker\n\n  $(function () {\n    var start = moment().subtract(29, 'days');\n    var end = moment();\n\n    function cb(start, end) {\n      $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));\n    }\n\n    $('#reportrange').daterangepicker({\n      startDate: start,\n      endDate: end,\n      ranges: {\n        'Today': [moment(), moment()],\n        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],\n        'Last 7 Days': [moment().subtract(6, 'days'), moment()],\n        'Last 30 Days': [moment().subtract(29, 'days'), moment()],\n        'This Month': [moment().startOf('month'), moment().endOf('month')],\n        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]\n      }\n    }, cb);\n    cb(start, end);\n  }); // Accordion Table\n\n  $(function () {\n    $(\".sxp-table tr.has-fold\").on(\"click\", function () {\n      if ($(this).hasClass(\"open\")) {\n        $(this).removeClass(\"open\").next(\".fold\").removeClass(\"open\");\n      } else {\n        $(\".sxp-table tr.has-fold\").removeClass(\"open\").next(\".fold\").removeClass(\"open\");\n        $(this).addClass(\"open\").next(\".fold\").addClass(\"open\");\n      }\n    });\n  }); // Initiate Feather Icon\n\n  feather.replace({\n    'stroke-width': 2,\n    'width': 16,\n    'height': 16\n  });\n  /**\r\n   * --------------------------------------------------------------------------\r\n   * Bootstrap (v4.1.3): tab.js\r\n   * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)\r\n   * --------------------------------------------------------------------------\r\n   */\n\n  var Tab = function ($$$1) {\n    /**\r\n     * ------------------------------------------------------------------------\r\n     * Constants\r\n     * ------------------------------------------------------------------------\r\n     */\n    var NAME = 'tab';\n    var VERSION = '4.1.3';\n    var DATA_KEY = 'bs.tab';\n    var EVENT_KEY = \".\" + DATA_KEY;\n    var DATA_API_KEY = '.data-api';\n    var JQUERY_NO_CONFLICT = $$$1.fn[NAME];\n    var Event = {\n      HIDE: \"hide\" + EVENT_KEY,\n      HIDDEN: \"hidden\" + EVENT_KEY,\n      SHOW: \"show\" + EVENT_KEY,\n      SHOWN: \"shown\" + EVENT_KEY,\n      CLICK_DATA_API: \"click\" + EVENT_KEY + DATA_API_KEY\n    };\n    var ClassName = {\n      DROPDOWN_MENU: 'dropdown-menu',\n      ACTIVE: 'active',\n      DISABLED: 'disabled',\n      FADE: 'fade',\n      SHOW: 'show'\n    };\n    var Selector = {\n      DROPDOWN: '.dropdown',\n      NAV_LIST_GROUP: '.nav, .list-group',\n      ACTIVE: '.active',\n      ACTIVE_UL: '> li > .active',\n      DATA_TOGGLE: '[data-toggle=\"tab\"], [data-toggle=\"pill\"], [data-toggle=\"list\"]',\n      DROPDOWN_TOGGLE: '.dropdown-toggle',\n      DROPDOWN_ACTIVE_CHILD: '> .dropdown-menu .active'\n      /**\r\n       * ------------------------------------------------------------------------\r\n       * Class Definition\r\n       * ------------------------------------------------------------------------\r\n       */\n\n    };\n\n    var Tab = /*#__PURE__*/function () {\n      function Tab(element) {\n        this._element = element;\n      } // Getters\n\n\n      var _proto = Tab.prototype; // Public\n\n      _proto.show = function show() {\n        var _this = this;\n\n        if (this._element.parentNode && this._element.parentNode.nodeType === Node.ELEMENT_NODE && $$$1(this._element).hasClass(ClassName.ACTIVE) || $$$1(this._element).hasClass(ClassName.DISABLED)) {\n          return;\n        }\n\n        var target;\n        var previous;\n        var listElement = $$$1(this._element).closest(Selector.NAV_LIST_GROUP)[0];\n        var selector = Util.getSelectorFromElement(this._element);\n\n        if (listElement) {\n          var itemSelector = listElement.nodeName === 'UL' ? Selector.ACTIVE_UL : Selector.ACTIVE;\n          previous = $$$1.makeArray($$$1(listElement).find(itemSelector));\n          previous = previous[previous.length - 1];\n        }\n\n        var hideEvent = $$$1.Event(Event.HIDE, {\n          relatedTarget: this._element\n        });\n        var showEvent = $$$1.Event(Event.SHOW, {\n          relatedTarget: previous\n        });\n\n        if (previous) {\n          $$$1(previous).trigger(hideEvent);\n        }\n\n        $$$1(this._element).trigger(showEvent);\n\n        if (showEvent.isDefaultPrevented() || hideEvent.isDefaultPrevented()) {\n          return;\n        }\n\n        if (selector) {\n          target = document.querySelector(selector);\n        }\n\n        this._activate(this._element, listElement);\n\n        var complete = function complete() {\n          var hiddenEvent = $$$1.Event(Event.HIDDEN, {\n            relatedTarget: _this._element\n          });\n          var shownEvent = $$$1.Event(Event.SHOWN, {\n            relatedTarget: previous\n          });\n          $$$1(previous).trigger(hiddenEvent);\n          $$$1(_this._element).trigger(shownEvent);\n        };\n\n        if (target) {\n          this._activate(target, target.parentNode, complete);\n        } else {\n          complete();\n        }\n      };\n\n      _proto.dispose = function dispose() {\n        $$$1.removeData(this._element, DATA_KEY);\n        this._element = null;\n      }; // Private\n\n\n      _proto._activate = function _activate(element, container, callback) {\n        var _this2 = this;\n\n        var activeElements;\n\n        if (container.nodeName === 'UL') {\n          activeElements = $$$1(container).find(Selector.ACTIVE_UL);\n        } else {\n          activeElements = $$$1(container).children(Selector.ACTIVE);\n        }\n\n        var active = activeElements[0];\n        var isTransitioning = callback && active && $$$1(active).hasClass(ClassName.FADE);\n\n        var complete = function complete() {\n          return _this2._transitionComplete(element, active, callback);\n        };\n\n        if (active && isTransitioning) {\n          var transitionDuration = Util.getTransitionDurationFromElement(active);\n          $$$1(active).one(Util.TRANSITION_END, complete).emulateTransitionEnd(transitionDuration);\n        } else {\n          complete();\n        }\n      };\n\n      _proto._transitionComplete = function _transitionComplete(element, active, callback) {\n        if (active) {\n          $$$1(active).removeClass(ClassName.SHOW + \" \" + ClassName.ACTIVE);\n          var dropdownChild = $$$1(active.parentNode).find(Selector.DROPDOWN_ACTIVE_CHILD)[0];\n\n          if (dropdownChild) {\n            $$$1(dropdownChild).removeClass(ClassName.ACTIVE);\n          }\n\n          if (active.getAttribute('role') === 'tab') {\n            active.setAttribute('aria-selected', false);\n          }\n        }\n\n        $$$1(element).addClass(ClassName.ACTIVE);\n\n        if (element.getAttribute('role') === 'tab') {\n          element.setAttribute('aria-selected', true);\n        }\n\n        Util.reflow(element);\n        $$$1(element).addClass(ClassName.SHOW);\n\n        if (element.parentNode && $$$1(element.parentNode).hasClass(ClassName.DROPDOWN_MENU)) {\n          var dropdownElement = $$$1(element).closest(Selector.DROPDOWN)[0];\n\n          if (dropdownElement) {\n            var dropdownToggleList = [].slice.call(dropdownElement.querySelectorAll(Selector.DROPDOWN_TOGGLE));\n            $$$1(dropdownToggleList).addClass(ClassName.ACTIVE);\n          }\n\n          element.setAttribute('aria-expanded', true);\n        }\n\n        if (callback) {\n          callback();\n        }\n      }; // Static\n\n\n      Tab._jQueryInterface = function _jQueryInterface(config) {\n        return this.each(function () {\n          var $this = $$$1(this);\n          var data = $this.data(DATA_KEY);\n\n          if (!data) {\n            data = new Tab(this);\n            $this.data(DATA_KEY, data);\n          }\n\n          if (typeof config === 'string') {\n            if (typeof data[config] === 'undefined') {\n              throw new TypeError(\"No method named \\\"\" + config + \"\\\"\");\n            }\n\n            data[config]();\n          }\n        });\n      };\n\n      _createClass(Tab, null, [{\n        key: \"VERSION\",\n        get: function get() {\n          return VERSION;\n        }\n      }]);\n\n      return Tab;\n    }();\n    /**\r\n     * ------------------------------------------------------------------------\r\n     * Data Api implementation\r\n     * ------------------------------------------------------------------------\r\n     */\n\n\n    $$$1(document).on(Event.CLICK_DATA_API, Selector.DATA_TOGGLE, function (event) {\n      event.preventDefault();\n\n      Tab._jQueryInterface.call($$$1(this), 'show');\n    });\n    /**\r\n     * ------------------------------------------------------------------------\r\n     * jQuery\r\n     * ------------------------------------------------------------------------\r\n     */\n\n    $$$1.fn[NAME] = Tab._jQueryInterface;\n    $$$1.fn[NAME].Constructor = Tab;\n\n    $$$1.fn[NAME].noConflict = function () {\n      $$$1.fn[NAME] = JQUERY_NO_CONFLICT;\n      return Tab._jQueryInterface;\n    };\n\n    return Tab;\n  }($);\n})(jQuery, window, document, wp, pagenow, SaleXpresso);\n\n//# sourceURL=webpack:///./src/js/admin.js?")},"./src/js/components/_tabs.js":
/*!************************************!*\
  !*** ./src/js/components/_tabs.js ***!
  \************************************/
/*! exports provided: tabs */function(module,__webpack_exports__,__webpack_require__){"use strict";eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"tabs\", function() { return tabs; });\n/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ \"jquery\");\n/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);\n\n\nfunction tabs() {\n  return jquery__WEBPACK_IMPORTED_MODULE_0___default()(document).on('click', '[data-target]', function (event) {\n    var self = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this),\n        tab = self.closest('.tab-item'),\n        target = jquery__WEBPACK_IMPORTED_MODULE_0___default()(\"#\".concat(self.data('target')));\n\n    if (target.length) {\n      // Switch to the tab.\n      event.preventDefault();\n\n      if (!tab.hasClass('is-active')) {\n        jquery__WEBPACK_IMPORTED_MODULE_0___default()('.tab-item').removeClass('is-active');\n        tab.addClass('is-active');\n        jquery__WEBPACK_IMPORTED_MODULE_0___default()('.tab-content').removeClass('is-active');\n        target.addClass('is-active');\n      }\n\n      self.trigger('shown');\n    }\n  });\n}\n\n\n\n//# sourceURL=webpack:///./src/js/components/_tabs.js?")},jquery:
/*!*************************!*\
  !*** external "jQuery" ***!
  \*************************/
/*! no static exports found */function(module,exports){eval("module.exports = jQuery;\n\n//# sourceURL=webpack:///external_%22jQuery%22?")}});