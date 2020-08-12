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
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
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
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/scripts.js":
/*!***************************!*\
  !*** ./src/js/scripts.js ***!
  \***************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(jQuery) {function _createForOfIteratorHelper(o, allowArrayLike) { var it; if (typeof Symbol === "undefined" || o[Symbol.iterator] == null) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = o[Symbol.iterator](); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it.return != null) it.return(); } finally { if (didErr) throw err; } } }; }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

/**!
 * SaleXpresso Public Scripts
 *
 * @author SaleXpresso <support@salexpresso.com>
 * @package SaleXpresso
 * @version 1.0.0
 * @since 1.0.0
 */
// import _ from 'lodash';
(function ($, window, document, SaleXpresso, Cookies) {
  "use strict"; // Helper Functions.

  /**
   * Checks if input is valid email.
   *
   * @param email
   * @return {boolean}
   */

  var isEmail = function isEmail(email) {
    return /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(email);
  };
  /**
   * Find Nested Element.
   * Same as $(el)find().
   *
   * @param {jQuery|HTMLElement|String} el
   * @param {jQuery|HTMLElement|String} selector
   * @return {*|jQuery|HTMLElement}
   */


  var findEl = function findEl(el, selector) {
    return el.find(selector);
  };
  /**
   * Get Element.
   * Alternative to $(selector) with support for find nested element.
   *
   * @param {jQuery|HTMLElement|Array|String} el
   * @param {jQuery|HTMLElement|String} selector
   * @return {*|jQuery|HTMLElement}
   */


  var getEl = function getEl(el, selector) {
    if ('Array' === el.constructor.name && 2 === el.length) {
      selector = el[1];
      el = el[0];
    }

    if ('string' === typeof el) {
      el = $(el);
    }

    if (selector && 'string' === typeof selector) {
      return findEl(el, selector);
    }

    return el;
  };
  /**
   * Get Closest Element.
   * Same as $(el).closest().
   *
   * @param {jQuery|HTMLElement|Array|String} el
   * @param {jQuery|HTMLElement|String} selector
   * @return {*|jQuery|HTMLElement|null|Element}
   */


  var closestEl = function closestEl(el, selector) {
    return getEl(el).closest(selector);
  };
  /**
   * Get Element Value.
   * $(el).val()
   * @param {jQuery|HTMLElement|Array|String} el
   * @param {jQuery|HTMLElement|String} selector
   * @return {*}
   */


  var elVal = function elVal(el, selector) {
    return getEl(el, selector).val();
  };
  /**
   * Get Element Data attributes.
   * $(el).data()
   *
   * @param {jQuery|HTMLElement|Array|String} selector
   * @param {String} data
   * @return {*}
   */


  var elData = function elData(selector, data) {
    return getEl(selector).data(data);
  }; // Dyamic Options.


  var _wpnonce = SaleXpresso._wpnonce,
      gdpr = SaleXpresso.gdpr,
      ac_timeout = SaleXpresso.ac_timeout,
      _SaleXpresso$messages = SaleXpresso.messages,
      cart_email_gdpr = _SaleXpresso$messages.cart_email_gdpr,
      no_thanks = _SaleXpresso$messages.no_thanks; // @TODO handle GDPR.

  /**
   * Abandon Cart.
   * Save Cart Data in case of user didn't complete the checkout.
   * 
   * @class SaleXpressoCaptureUserData
   */

  var SaleXpressoCaptureUserData = /*#__PURE__*/function () {
    /**
     * Constructor
     */
    function SaleXpressoCaptureUserData() {
      _classCallCheck(this, SaleXpressoCaptureUserData);

      this._gdpr = gdpr;
      this._typingTimer;
      this._doneTypingInterval = 500; // this._oldData = "";

      this._init();
    }
    /**
     * Initialize.
     *
     * @private
     */


    _createClass(SaleXpressoCaptureUserData, [{
      key: "_init",
      value: function _init() {
        var self = this;
        $(document).on('keyup keypress change blur', 'input', self._saveData.bind(self));
        $(document).on('keydown', 'input', self._clearTheCountDown.bind(self));
        setTimeout(function () {
          self._saveData();
        }, 750);
      }
      /**
       * Save User Data.
       * Debunced with setTimeout
       *
       * @param {Event} event
       *
       * @private
       */

    }, {
      key: "_saveData",
      value: function _saveData(event) {
        var self = this;

        self._clearTheCountDown();

        self._typingTimer = setTimeout(function () {
          var email = $('#billing_email').val() || '';

          if (isEmail(email)) {
            var first_name = $("#billing_first_name").val() || '';
            var last_name = $("#billing_last_name").val() || '';
            var company = $("#billing_company").val() || '';
            var country = $("#billing_country").val() || '';
            var address_1 = $("#billing_address_1").val() || '';
            var address_2 = $("#billing_address_2").val() || '';
            var city = $("#billing_city").val() || '';
            var state = $("#billing_state").val() || '';
            var postcode = $("#billing_postcode").val() || '';
            var phone = $("#billing_phone").val() || '';
            var comments = $("#order_comments").val() || '';
            var data = {
              _wpnonce: _wpnonce,
              email: email,
              first_name: first_name,
              last_name: last_name,
              company: company,
              country: country,
              address_1: address_1,
              address_2: address_2,
              city: city,
              state: state,
              postcode: postcode,
              phone: phone,
              comments: comments
            };

            for (var _i = 0, _Object$keys = Object.keys(data); _i < _Object$keys.length; _i++) {
              var k = _Object$keys[_i];

              if ('_wpnonce' === k) {
                continue;
              }

              Cookies.set("sxp_ac_".concat(k), data[k], {
                expires: parseInt(ac_timeout)
              });
            } // const hash = JSON.stringify( data );
            // if ( self._oldData !== hash ) {
            // 	self._oldData = hash; // reduce backend call.
            // 	wp.ajax.post( 'sxp_save_abandon_cart_data', data );
            // }


            wp.ajax.post('sxp_save_abandon_cart_data', data);
          }
        }, this._doneTypingInterval);
      }
      /**
       * Clear Timer.
       *
       * @private
       */

    }, {
      key: "_clearTheCountDown",
      value: function _clearTheCountDown() {
        if (this._typingTimer) {
          clearTimeout(this._typingTimer);
        }
      }
    }]);

    return SaleXpressoCaptureUserData;
  }();

  new SaleXpressoCaptureUserData();
  $(document).on('ready', function () {
    $(document) // Add to cat on single product page.
    .on('click', '.single_add_to_cart_button', function () {
      var el = $(this);
      var form = $(this).closest('form.cart');
      var qtyEl = $('[name="quantity"]');
      var qty = 1;

      if (qtyEl.length) {
        qty = qtyEl.val();
      }

      var data = [{
        label: 'quantity',
        value: qty
      }];

      if (form.hasClass('variations_form')) {
        data.push({
          label: 'product_id',
          value: elVal(form, '[name="product_id"]')
        });
        data.push({
          label: 'variation_id',
          value: elVal(form, '[name="variation_id"]')
        });
      } else {
        data.push({
          label: 'product_id',
          value: elVal(el)
        });
      }

      sxpEvent('add-to-cart', data);
    }) // Add to cat on product archive page.
    .on('click', '.add_to_cart_button', function () {
      var el = $(this);
      sxpEvent('add-to-cart', [{
        label: 'product_id',
        value: elData(el, 'product_id')
      }, {
        label: 'quantity',
        value: elData(el, 'quantity ')
      }]);
    }).on('click', '.woocommerce-cart-form .product-remove > a', function () {
      var el = $(this);
      sxpEvent('remove-from-cart', [{
        label: 'product_id',
        value: elData(el, 'product_id')
      }]);
    }).on('click', '.woocommerce-cart .restore-item', function () {
      sxpEvent('undo-remove-from-cart');
    }); // Capture successfull checkout.

    var checkoutForm = $('form.checkout');
    checkoutForm.on('checkout_place_order_success', function () {
      var data = [{
        label: 'gateway_id',
        value: elVal('[name="payment_method"]:checked')
      }, {
        label: 'total',
        value: parseFloat($('.order-total').text().replace(/[^\d.]/gm, '')).toFixed(2)
      }];
      sxpEvent('checkout-completed', data);
    });

    if (checkoutForm.length) {}

    var keys = 'email,first_name,last_name,company,country,address_1,address_2,city,state,postcode,phone,comments';

    var _iterator = _createForOfIteratorHelper(keys.split(',')),
        _step;

    try {
      for (_iterator.s(); !(_step = _iterator.n()).done;) {
        var k = _step.value;

        if (k) {
          var id = "billing_".concat(k);

          if ('comments' === k) {
            id = "order_".concat(k);
          }

          $("#".concat(id)).val(Cookies.get("sxp_ac_".concat(k)));
        }
      }
    } catch (err) {
      _iterator.e(err);
    } finally {
      _iterator.f();
    }
  });
})(jQuery, window, document, SaleXpresso, Cookies);
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "jquery")))

/***/ }),

/***/ "./src/scss/styles.scss":
/*!******************************!*\
  !*** ./src/scss/styles.scss ***!
  \******************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = (__webpack_require__.p + "./assets/css/styles.css");

/***/ }),

/***/ 0:
/*!********************************************************!*\
  !*** multi ./src/js/scripts.js ./src/scss/styles.scss ***!
  \********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! ./src/js/scripts.js */"./src/js/scripts.js");
module.exports = __webpack_require__(/*! ./src/scss/styles.scss */"./src/scss/styles.scss");


/***/ }),

/***/ "jquery":
/*!*************************!*\
  !*** external "jQuery" ***!
  \*************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = jQuery;

/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vLy4vc3JjL2pzL3NjcmlwdHMuanMiLCJ3ZWJwYWNrOi8vLy4vc3JjL3Njc3Mvc3R5bGVzLnNjc3MiLCJ3ZWJwYWNrOi8vL2V4dGVybmFsIFwialF1ZXJ5XCIiXSwibmFtZXMiOlsiJCIsIndpbmRvdyIsImRvY3VtZW50IiwiU2FsZVhwcmVzc28iLCJDb29raWVzIiwiaXNFbWFpbCIsImVtYWlsIiwidGVzdCIsImZpbmRFbCIsImVsIiwic2VsZWN0b3IiLCJmaW5kIiwiZ2V0RWwiLCJjb25zdHJ1Y3RvciIsIm5hbWUiLCJsZW5ndGgiLCJjbG9zZXN0RWwiLCJjbG9zZXN0IiwiZWxWYWwiLCJ2YWwiLCJlbERhdGEiLCJkYXRhIiwiX3dwbm9uY2UiLCJnZHByIiwiYWNfdGltZW91dCIsIm1lc3NhZ2VzIiwiY2FydF9lbWFpbF9nZHByIiwibm9fdGhhbmtzIiwiU2FsZVhwcmVzc29DYXB0dXJlVXNlckRhdGEiLCJfZ2RwciIsIl90eXBpbmdUaW1lciIsIl9kb25lVHlwaW5nSW50ZXJ2YWwiLCJfaW5pdCIsInNlbGYiLCJvbiIsIl9zYXZlRGF0YSIsImJpbmQiLCJfY2xlYXJUaGVDb3VudERvd24iLCJzZXRUaW1lb3V0IiwiZXZlbnQiLCJmaXJzdF9uYW1lIiwibGFzdF9uYW1lIiwiY29tcGFueSIsImNvdW50cnkiLCJhZGRyZXNzXzEiLCJhZGRyZXNzXzIiLCJjaXR5Iiwic3RhdGUiLCJwb3N0Y29kZSIsInBob25lIiwiY29tbWVudHMiLCJPYmplY3QiLCJrZXlzIiwiayIsInNldCIsImV4cGlyZXMiLCJwYXJzZUludCIsIndwIiwiYWpheCIsInBvc3QiLCJjbGVhclRpbWVvdXQiLCJmb3JtIiwicXR5RWwiLCJxdHkiLCJsYWJlbCIsInZhbHVlIiwiaGFzQ2xhc3MiLCJwdXNoIiwic3hwRXZlbnQiLCJjaGVja291dEZvcm0iLCJwYXJzZUZsb2F0IiwidGV4dCIsInJlcGxhY2UiLCJ0b0Zp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LE9BREw7QUFFRkMsYUFBSyxFQUFFSyxVQUFVLENBQUV0RSxDQUFDLENBQUMsY0FBRCxDQUFELENBQWtCdUUsSUFBbEIsR0FBeUJDLE9BQXpCLENBQWtDLFVBQWxDLEVBQThDLEVBQTlDLENBQUYsQ0FBVixDQUFpRUMsT0FBakUsQ0FBeUUsQ0FBekU7QUFGTCxPQUhVLENBQWI7QUFPQUwsY0FBUSxDQUFFLG9CQUFGLEVBQXdCL0MsSUFBeEIsQ0FBUjtBQUNBLEtBVEQ7O0FBVUEsUUFBS2dELFlBQVksQ0FBQ3RELE1BQWxCLEVBQTJCLENBRTFCOztBQUNELFFBQU1xQyxJQUFJLEdBQUcsbUdBQWI7O0FBcERxQywrQ0FxRHBCQSxJQUFJLENBQUNzQixLQUFMLENBQVksR0FBWixDQXJEb0I7QUFBQTs7QUFBQTtBQXFEckMsMERBQXFDO0FBQUEsWUFBekJyQixDQUF5Qjs7QUFDcEMsWUFBS0EsQ0FBTCxFQUFTO0FBQ1IsY0FBSXNCLEVBQUUscUJBQWN0QixDQUFkLENBQU47O0FBQ0EsY0FBSyxlQUFlQSxDQUFwQixFQUF3QjtBQUN2QnNCLGNBQUUsbUJBQVl0QixDQUFaLENBQUY7QUFDQTs7QUFDRHJELFdBQUMsWUFBSzJFLEVBQUwsRUFBRCxDQUFZeEQsR0FBWixDQUFpQmYsT0FBTyxDQUFDd0UsR0FBUixrQkFBdUJ2QixDQUF2QixFQUFqQjtBQUNBO0FBQ0Q7QUE3RG9DO0FBQUE7QUFBQTtBQUFBO0FBQUE7QUE4RHJDLEdBOUREO0FBK0RBLENBNVBDLEVBNFBDd0IsTUE1UEQsRUE0UFM1RSxNQTVQVCxFQTRQaUJDLFFBNVBqQixFQTRQMkJDLFdBNVAzQixFQTRQd0NDLE9BNVB4QyxDQUFGLEM7Ozs7Ozs7Ozs7Ozs7QUNUQTtBQUFlLG9GQUF1Qiw0QkFBNEIsRTs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7O0FDQWxFLHdCIiwiZmlsZSI6Ii4vYXNzZXRzL2pzL3NjcmlwdHMuanMiLCJzb3VyY2VzQ29udGVudCI6WyIgXHQvLyBUaGUgbW9kdWxlIGNhY2hlXG4gXHR2YXIgaW5zdGFsbGVkTW9kdWxlcyA9IHt9O1xuXG4gXHQvLyBUaGUgcmVxdWlyZSBmdW5jdGlvblxuIFx0ZnVuY3Rpb24gX193ZWJwYWNrX3JlcXVpcmVfXyhtb2R1bGVJZCkge1xuXG4gXHRcdC8vIENoZWNrIGlmIG1vZHVsZSBpcyBpbiBjYWNoZVxuIFx0XHRpZihpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSkge1xuIFx0XHRcdHJldHVybiBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXS5leHBvcnRzO1xuIFx0XHR9XG4gXHRcdC8vIENyZWF0ZSBhIG5ldyBtb2R1bGUgKGFuZCBwdXQgaXQgaW50byB0aGUgY2FjaGUpXG4gXHRcdHZhciBtb2R1bGUgPSBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSA9IHtcbiBcdFx0XHRpOiBtb2R1bGVJZCxcbiBcdFx0XHRsOiBmYWxzZSxcbiBcdFx0XHRleHBvcnRzOiB7fVxuIFx0XHR9O1xuXG4gXHRcdC8vIEV4ZWN1dGUgdGhlIG1vZHVsZSBmdW5jdGlvblxuIFx0XHRtb2R1bGVzW21vZHVsZUlkXS5jYWxsKG1vZHVsZS5leHBvcnRzLCBtb2R1bGUsIG1vZHVsZS5leHBvcnRzLCBfX3dlYnBhY2tfcmVxdWlyZV9fKTtcblxuIFx0XHQvLyBGbGFnIHRoZSBtb2R1bGUgYXMgbG9hZGVkXG4gXHRcdG1vZHVsZS5sID0gdHJ1ZTtcblxuIFx0XHQvLyBSZXR1cm4gdGhlIGV4cG9ydHMgb2YgdGhlIG1vZHVsZVxuIFx0XHRyZXR1cm4gbW9kdWxlLmV4cG9ydHM7XG4gXHR9XG5cblxuIFx0Ly8gZXhwb3NlIHRoZSBtb2R1bGVzIG9iamVjdCAoX193ZWJwYWNrX21vZHVsZXNfXylcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubSA9IG1vZHVsZXM7XG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlIGNhY2hlXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmMgPSBpbnN0YWxsZWRNb2R1bGVzO1xuXG4gXHQvLyBkZWZpbmUgZ2V0dGVyIGZ1bmN0aW9uIGZvciBoYXJtb255IGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uZCA9IGZ1bmN0aW9uKGV4cG9ydHMsIG5hbWUsIGdldHRlcikge1xuIFx0XHRpZighX193ZWJwYWNrX3JlcXVpcmVfXy5vKGV4cG9ydHMsIG5hbWUpKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIG5hbWUsIHsgZW51bWVyYWJsZTogdHJ1ZSwgZ2V0OiBnZXR0ZXIgfSk7XG4gXHRcdH1cbiBcdH07XG5cbiBcdC8vIGRlZmluZSBfX2VzTW9kdWxlIG9uIGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uciA9IGZ1bmN0aW9uKGV4cG9ydHMpIHtcbiBcdFx0aWYodHlwZW9mIFN5bWJvbCAhPT0gJ3VuZGVmaW5lZCcgJiYgU3ltYm9sLnRvU3RyaW5nVGFnKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIFN5bWJvbC50b1N0cmluZ1RhZywgeyB2YWx1ZTogJ01vZHVsZScgfSk7XG4gXHRcdH1cbiBcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsICdfX2VzTW9kdWxlJywgeyB2YWx1ZTogdHJ1ZSB9KTtcbiBcdH07XG5cbiBcdC8vIGNyZWF0ZSBhIGZha2UgbmFtZXNwYWNlIG9iamVjdFxuIFx0Ly8gbW9kZSAmIDE6IHZhbHVlIGlzIGEgbW9kdWxlIGlkLCByZXF1aXJlIGl0XG4gXHQvLyBtb2RlICYgMjogbWVyZ2UgYWxsIHByb3BlcnRpZXMgb2YgdmFsdWUgaW50byB0aGUgbnNcbiBcdC8vIG1vZGUgJiA0OiByZXR1cm4gdmFsdWUgd2hlbiBhbHJlYWR5IG5zIG9iamVjdFxuIFx0Ly8gbW9kZSAmIDh8MTogYmVoYXZlIGxpa2UgcmVxdWlyZVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy50ID0gZnVuY3Rpb24odmFsdWUsIG1vZGUpIHtcbiBcdFx0aWYobW9kZSAmIDEpIHZhbHVlID0gX193ZWJwYWNrX3JlcXVpcmVfXyh2YWx1ZSk7XG4gXHRcdGlmKG1vZGUgJiA4KSByZXR1cm4gdmFsdWU7XG4gXHRcdGlmKChtb2RlICYgNCkgJiYgdHlwZW9mIHZhbHVlID09PSAnb2JqZWN0JyAmJiB2YWx1ZSAmJiB2YWx1ZS5fX2VzTW9kdWxlKSByZXR1cm4gdmFsdWU7XG4gXHRcdHZhciBucyA9IE9iamVjdC5jcmVhdGUobnVsbCk7XG4gXHRcdF9fd2VicGFja19yZXF1aXJlX18ucihucyk7XG4gXHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShucywgJ2RlZmF1bHQnLCB7IGVudW1lcmFibGU6IHRydWUsIHZhbHVlOiB2YWx1ZSB9KTtcbiBcdFx0aWYobW9kZSAmIDIgJiYgdHlwZW9mIHZhbHVlICE9ICdzdHJpbmcnKSBmb3IodmFyIGtleSBpbiB2YWx1ZSkgX193ZWJwYWNrX3JlcXVpcmVfXy5kKG5zLCBrZXksIGZ1bmN0aW9uKGtleSkgeyByZXR1cm4gdmFsdWVba2V5XTsgfS5iaW5kKG51bGwsIGtleSkpO1xuIFx0XHRyZXR1cm4gbnM7XG4gXHR9O1xuXG4gXHQvLyBnZXREZWZhdWx0RXhwb3J0IGZ1bmN0aW9uIGZvciBjb21wYXRpYmlsaXR5IHdpdGggbm9uLWhhcm1vbnkgbW9kdWxlc1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5uID0gZnVuY3Rpb24obW9kdWxlKSB7XG4gXHRcdHZhciBnZXR0ZXIgPSBtb2R1bGUgJiYgbW9kdWxlLl9fZXNNb2R1bGUgP1xuIFx0XHRcdGZ1bmN0aW9uIGdldERlZmF1bHQoKSB7IHJldHVybiBtb2R1bGVbJ2RlZmF1bHQnXTsgfSA6XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0TW9kdWxlRXhwb3J0cygpIHsgcmV0dXJuIG1vZHVsZTsgfTtcbiBcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kKGdldHRlciwgJ2EnLCBnZXR0ZXIpO1xuIFx0XHRyZXR1cm4gZ2V0dGVyO1xuIFx0fTtcblxuIFx0Ly8gT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm8gPSBmdW5jdGlvbihvYmplY3QsIHByb3BlcnR5KSB7IHJldHVybiBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGwob2JqZWN0LCBwcm9wZXJ0eSk7IH07XG5cbiBcdC8vIF9fd2VicGFja19wdWJsaWNfcGF0aF9fXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnAgPSBcIlwiO1xuXG5cbiBcdC8vIExvYWQgZW50cnkgbW9kdWxlIGFuZCByZXR1cm4gZXhwb3J0c1xuIFx0cmV0dXJuIF9fd2VicGFja19yZXF1aXJlX18oX193ZWJwYWNrX3JlcXVpcmVfXy5zID0gMCk7XG4iLCIvKiohXG4gKiBTYWxlWHByZXNzbyBQdWJsaWMgU2NyaXB0c1xuICpcbiAqIEBhdXRob3IgU2FsZVhwcmVzc28gPHN1cHBvcnRAc2FsZXhwcmVzc28uY29tPlxuICogQHBhY2thZ2UgU2FsZVhwcmVzc29cbiAqIEB2ZXJzaW9uIDEuMC4wXG4gKiBAc2luY2UgMS4wLjBcbiAqL1xuLy8gaW1wb3J0IF8gZnJvbSAnbG9kYXNoJztcbiggZnVuY3Rpb24oICQsIHdpbmRvdywgZG9jdW1lbnQsIFNhbGVYcHJlc3NvLCBDb29raWVzICkge1xuXHRcInVzZSBzdHJpY3RcIjtcblx0XG5cdC8vIEhlbHBlciBGdW5jdGlvbnMuXG5cdFxuXHQvKipcblx0ICogQ2hlY2tzIGlmIGlucHV0IGlzIHZhbGlkIGVtYWlsLlxuXHQgKlxuXHQgKiBAcGFyYW0gZW1haWxcblx0ICogQHJldHVybiB7Ym9vbGVhbn1cblx0ICovXG5cdGNvbnN0IGlzRW1haWwgPSBlbWFpbCA9PiAvXihbYS16QS1aMC05Xy4rLV0pK1xcQCgoW2EtekEtWjAtOS1dKStcXC4pKyhbYS16QS1aMC05XXsyLDR9KSskLy50ZXN0KCBlbWFpbCApO1xuXHRcblx0LyoqXG5cdCAqIEZpbmQgTmVzdGVkIEVsZW1lbnQuXG5cdCAqIFNhbWUgYXMgJChlbClmaW5kKCkuXG5cdCAqXG5cdCAqIEBwYXJhbSB7alF1ZXJ5fEhUTUxFbGVtZW50fFN0cmluZ30gZWxcblx0ICogQHBhcmFtIHtqUXVlcnl8SFRNTEVsZW1lbnR8U3RyaW5nfSBzZWxlY3RvclxuXHQgKiBAcmV0dXJuIHsqfGpRdWVyeXxIVE1MRWxlbWVudH1cblx0ICovXG5cdGNvbnN0IGZpbmRFbCA9ICggZWwsIHNlbGVjdG9yICkgPT4gZWwuZmluZCggc2VsZWN0b3IgKTtcblx0XG5cdC8qKlxuXHQgKiBHZXQgRWxlbWVudC5cblx0ICogQWx0ZXJuYXRpdmUgdG8gJChzZWxlY3Rvcikgd2l0aCBzdXBwb3J0IGZvciBmaW5kIG5lc3RlZCBlbGVtZW50LlxuXHQgKlxuXHQgKiBAcGFyYW0ge2pRdWVyeXxIVE1MRWxlbWVudHxBcnJheXxTdHJpbmd9IGVsXG5cdCAqIEBwYXJhbSB7alF1ZXJ5fEhUTUxFbGVtZW50fFN0cmluZ30gc2VsZWN0b3Jcblx0ICogQHJldHVybiB7KnxqUXVlcnl8SFRNTEVsZW1lbnR9XG5cdCAqL1xuXHRjb25zdCBnZXRFbCA9ICggZWwsIHNlbGVjdG9yICkgPT4ge1xuXHRcdGlmICggJ0FycmF5JyA9PT0gZWwuY29uc3RydWN0b3IubmFtZSAmJiAyID09PSBlbC5sZW5ndGggKSB7XG5cdFx0XHRzZWxlY3RvciA9IGVsWzFdO1xuXHRcdFx0ZWwgPSBlbFswXTtcblx0XHR9XG5cdFx0aWYgKCAnc3RyaW5nJyA9PT0gdHlwZW9mIGVsICkge1xuXHRcdFx0ZWwgPSAkKCBlbCApO1xuXHRcdH1cblx0XHRpZiAoIHNlbGVjdG9yICYmICdzdHJpbmcnID09PSB0eXBlb2Ygc2VsZWN0b3IgKSB7XG5cdFx0XHRyZXR1cm4gZmluZEVsKCBlbCwgc2VsZWN0b3IgKTtcblx0XHR9XG5cdFx0cmV0dXJuIGVsO1xuXHR9O1xuXHRcblx0LyoqXG5cdCAqIEdldCBDbG9zZXN0IEVsZW1lbnQuXG5cdCAqIFNhbWUgYXMgJChlbCkuY2xvc2VzdCgpLlxuXHQgKlxuXHQgKiBAcGFyYW0ge2pRdWVyeXxIVE1MRWxlbWVudHxBcnJheXxTdHJpbmd9IGVsXG5cdCAqIEBwYXJhbSB7alF1ZXJ5fEhUTUxFbGVtZW50fFN0cmluZ30gc2VsZWN0b3Jcblx0ICogQHJldHVybiB7KnxqUXVlcnl8SFRNTEVsZW1lbnR8bnVsbHxFbGVtZW50fVxuXHQgKi9cblx0Y29uc3QgY2xvc2VzdEVsID0gKCBlbCwgc2VsZWN0b3IgKSA9PiB7XG5cdFx0cmV0dXJuIGdldEVsKCBlbCApLmNsb3Nlc3QoIHNlbGVjdG9yICk7XG5cdH1cblx0XG5cdC8qKlxuXHQgKiBHZXQgRWxlbWVudCBWYWx1ZS5cblx0ICogJChlbCkudmFsKClcblx0ICogQHBhcmFtIHtqUXVlcnl8SFRNTEVsZW1lbnR8QXJyYXl8U3RyaW5nfSBlbFxuXHQgKiBAcGFyYW0ge2pRdWVyeXxIVE1MRWxlbWVudHxTdHJpbmd9IHNlbGVjdG9yXG5cdCAqIEByZXR1cm4geyp9XG5cdCAqL1xuXHRjb25zdCBlbFZhbCA9ICggZWwsIHNlbGVjdG9yICkgPT4gZ2V0RWwoIGVsLCBzZWxlY3RvciApLnZhbCgpO1xuXHRcblx0LyoqXG5cdCAqIEdldCBFbGVtZW50IERhdGEgYXR0cmlidXRlcy5cblx0ICogJChlbCkuZGF0YSgpXG5cdCAqXG5cdCAqIEBwYXJhbSB7alF1ZXJ5fEhUTUxFbGVtZW50fEFycmF5fFN0cmluZ30gc2VsZWN0b3Jcblx0ICogQHBhcmFtIHtTdHJpbmd9IGRhdGFcblx0ICogQHJldHVybiB7Kn1cblx0ICovXG5cdGNvbnN0IGVsRGF0YSA9ICggc2VsZWN0b3IsIGRhdGEgKSA9PiBnZXRFbCggc2VsZWN0b3IgKS5kYXRhKCBkYXRhICk7XG5cdFxuXHQvLyBEeWFtaWMgT3B0aW9ucy5cblx0Y29uc3Qge1xuXHRcdF93cG5vbmNlLFxuXHRcdGdkcHIsXG5cdFx0YWNfdGltZW91dCxcblx0XHRtZXNzYWdlczogeyBjYXJ0X2VtYWlsX2dkcHIsIG5vX3RoYW5rcyB9LFxuXHR9ID0gU2FsZVhwcmVzc287XG5cdC8vIEBUT0RPIGhhbmRsZSBHRFBSLlxuXHRcblx0LyoqXG5cdCAqIEFiYW5kb24gQ2FydC5cblx0ICogU2F2ZSBDYXJ0IERhdGEgaW4gY2FzZSBvZiB1c2VyIGRpZG4ndCBjb21wbGV0ZSB0aGUgY2hlY2tvdXQuXG5cdCAqIFxuXHQgKiBAY2xhc3MgU2FsZVhwcmVzc29DYXB0dXJlVXNlckRhdGFcblx0ICovXG5cdGNsYXNzIFNhbGVYcHJlc3NvQ2FwdHVyZVVzZXJEYXRhIHtcblx0XHQvKipcblx0XHQgKiBDb25zdHJ1Y3RvclxuXHRcdCAqL1xuXHRcdGNvbnN0cnVjdG9yICgpIHtcblx0XHRcdHRoaXMuX2dkcHIgPSBnZHByO1xuXHRcdFx0dGhpcy5fdHlwaW5nVGltZXI7XG5cdFx0XHR0aGlzLl9kb25lVHlwaW5nSW50ZXJ2YWwgPSA1MDA7XG5cdFx0XHQvLyB0aGlzLl9vbGREYXRhID0gXCJcIjtcblx0XHRcdHRoaXMuX2luaXQoKTtcblx0XHR9XG5cdFx0XG5cdFx0LyoqXG5cdFx0ICogSW5pdGlhbGl6ZS5cblx0XHQgKlxuXHRcdCAqIEBwcml2YXRlXG5cdFx0ICovXG5cdFx0X2luaXQoKSB7XG5cdFx0XHRjb25zdCBzZWxmID0gdGhpcztcblx0XHRcdCQoZG9jdW1lbnQpLm9uKCAna2V5dXAga2V5cHJlc3MgY2hhbmdlIGJsdXInLCAnaW5wdXQnLCBzZWxmLl9zYXZlRGF0YS5iaW5kKCBzZWxmICkgKTtcblx0XHRcdCQoZG9jdW1lbnQpLm9uKCAna2V5ZG93bicsICdpbnB1dCcsIHNlbGYuX2NsZWFyVGhlQ291bnREb3duLmJpbmQoIHNlbGYgKSApO1xuXHRcdFx0c2V0VGltZW91dCggKCkgPT4ge1xuXHRcdFx0XHRzZWxmLl9zYXZlRGF0YSgpO1xuXHRcdFx0fSwgNzUwICk7XG5cdFx0fVxuXHRcdFxuXHRcdC8qKlxuXHRcdCAqIFNhdmUgVXNlciBEYXRhLlxuXHRcdCAqIERlYnVuY2VkIHdpdGggc2V0VGltZW91dFxuXHRcdCAqXG5cdFx0ICogQHBhcmFtIHtFdmVudH0gZXZlbnRcblx0XHQgKlxuXHRcdCAqIEBwcml2YXRlXG5cdFx0ICovXG5cdFx0X3NhdmVEYXRhKCBldmVudCApIHtcblx0XHRcdGNvbnN0IHNlbGYgPSB0aGlzO1xuXHRcdFx0c2VsZi5fY2xlYXJUaGVDb3VudERvd24oKTtcblx0XHRcdHNlbGYuX3R5cGluZ1RpbWVyID0gc2V0VGltZW91dCggKCkgPT4ge1xuXHRcdFx0XHRjb25zdCBlbWFpbCA9ICQoICcjYmlsbGluZ19lbWFpbCcgKS52YWwoKSB8fCAnJztcblx0XHRcdFx0aWYgKCBpc0VtYWlsKCBlbWFpbCApICkge1xuXHRcdFx0XHRcdFxuXHRcdFx0XHRcdGNvbnN0IGZpcnN0X25hbWUgPSAkKFwiI2JpbGxpbmdfZmlyc3RfbmFtZVwiKS52YWwoKSB8fCAnJztcblx0XHRcdFx0XHRjb25zdCBsYXN0X25hbWUgPSAkKFwiI2JpbGxpbmdfbGFzdF9uYW1lXCIpLnZhbCgpIHx8ICcnO1xuXHRcdFx0XHRcdGNvbnN0IGNvbXBhbnkgPSAkKFwiI2JpbGxpbmdfY29tcGFueVwiKS52YWwoKSB8fCAnJztcblx0XHRcdFx0XHRjb25zdCBjb3VudHJ5ID0gJChcIiNiaWxsaW5nX2NvdW50cnlcIikudmFsKCkgfHwgJyc7XG5cdFx0XHRcdFx0Y29uc3QgYWRkcmVzc18xID0gJChcIiNiaWxsaW5nX2FkZHJlc3NfMVwiKS52YWwoKSB8fCAnJztcblx0XHRcdFx0XHRjb25zdCBhZGRyZXNzXzIgPSAkKFwiI2JpbGxpbmdfYWRkcmVzc18yXCIpLnZhbCgpIHx8ICcnO1xuXHRcdFx0XHRcdGNvbnN0IGNpdHkgPSAkKFwiI2JpbGxpbmdfY2l0eVwiKS52YWwoKSB8fCAnJztcblx0XHRcdFx0XHRjb25zdCBzdGF0ZSA9ICQoXCIjYmlsbGluZ19zdGF0ZVwiKS52YWwoKSB8fCAnJztcblx0XHRcdFx0XHRjb25zdCBwb3N0Y29kZSA9ICQoXCIjYmlsbGluZ19wb3N0Y29kZVwiKS52YWwoKSB8fCAnJztcblx0XHRcdFx0XHRjb25zdCBwaG9uZSA9ICQoXCIjYmlsbGluZ19waG9uZVwiKS52YWwoKSB8fCAnJztcblx0XHRcdFx0XHRjb25zdCBjb21tZW50cyA9ICQoXCIjb3JkZXJfY29tbWVudHNcIikudmFsKCkgfHwgJyc7XG5cdFx0XHRcdFx0XG5cdFx0XHRcdFx0Y29uc3QgZGF0YSA9IHtcblx0XHRcdFx0XHRcdF93cG5vbmNlLFxuXHRcdFx0XHRcdFx0ZW1haWwsXG5cdFx0XHRcdFx0XHRmaXJzdF9uYW1lLFxuXHRcdFx0XHRcdFx0bGFzdF9uYW1lLFxuXHRcdFx0XHRcdFx0Y29tcGFueSxcblx0XHRcdFx0XHRcdGNvdW50cnksXG5cdFx0XHRcdFx0XHRhZGRyZXNzXzEsXG5cdFx0XHRcdFx0XHRhZGRyZXNzXzIsXG5cdFx0XHRcdFx0XHRjaXR5LFxuXHRcdFx0XHRcdFx0c3RhdGUsXG5cdFx0XHRcdFx0XHRwb3N0Y29kZSxcblx0XHRcdFx0XHRcdHBob25lLFxuXHRcdFx0XHRcdFx0Y29tbWVudHMsXG5cdFx0XHRcdFx0fTtcblx0XHRcdFx0XHRmb3IgKCBjb25zdCBrIG9mIE9iamVjdC5rZXlzKCBkYXRhICkgKSB7XG5cdFx0XHRcdFx0XHRpZiAoICdfd3Bub25jZScgPT09IGsgKSB7XG5cdFx0XHRcdFx0XHRcdGNvbnRpbnVlO1xuXHRcdFx0XHRcdFx0fVxuXHRcdFx0XHRcdFx0Q29va2llcy5zZXQoIGBzeHBfYWNfJHtrfWAsIGRhdGFba10sIHsgZXhwaXJlczogcGFyc2VJbnQoIGFjX3RpbWVvdXQgKSB9ICk7XG5cdFx0XHRcdFx0fVxuXHRcdFx0XHRcdC8vIGNvbnN0IGhhc2ggPSBKU09OLnN0cmluZ2lmeSggZGF0YSApO1xuXHRcdFx0XHRcdC8vIGlmICggc2VsZi5fb2xkRGF0YSAhPT0gaGFzaCApIHtcblx0XHRcdFx0XHQvLyBcdHNlbGYuX29sZERhdGEgPSBoYXNoOyAvLyByZWR1Y2UgYmFja2VuZCBjYWxsLlxuXHRcdFx0XHRcdC8vIFx0d3AuYWpheC5wb3N0KCAnc3hwX3NhdmVfYWJhbmRvbl9jYXJ0X2RhdGEnLCBkYXRhICk7XG5cdFx0XHRcdFx0Ly8gfVxuXHRcdFx0XHRcdHdwLmFqYXgucG9zdCggJ3N4cF9zYXZlX2FiYW5kb25fY2FydF9kYXRhJywgZGF0YSApO1xuXHRcdFx0XHR9XG5cdFx0XHR9LCB0aGlzLl9kb25lVHlwaW5nSW50ZXJ2YWwgKTtcblx0XHR9XG5cdFx0XG5cdFx0LyoqXG5cdFx0ICogQ2xlYXIgVGltZXIuXG5cdFx0ICpcblx0XHQgKiBAcHJpdmF0ZVxuXHRcdCAqL1xuXHRcdF9jbGVhclRoZUNvdW50RG93bigpIHtcblx0XHRcdGlmICggdGhpcy5fdHlwaW5nVGltZXIgKSB7XG5cdFx0XHRcdGNsZWFyVGltZW91dCggdGhpcy5fdHlwaW5nVGltZXIgKTtcblx0XHRcdH1cblx0XHR9XG5cdH1cblx0XG5cdG5ldyBTYWxlWHByZXNzb0NhcHR1cmVVc2VyRGF0YSgpO1xuXHRcblx0JCggZG9jdW1lbnQgKS5vbiggJ3JlYWR5JywgZnVuY3Rpb24oKSB7XG5cdFx0XG5cdFx0JCggZG9jdW1lbnQgKVxuXHRcdFx0Ly8gQWRkIHRvIGNhdCBvbiBzaW5nbGUgcHJvZHVjdCBwYWdlLlxuXHRcdFx0Lm9uKCAnY2xpY2snLCAnLnNpbmdsZV9hZGRfdG9fY2FydF9idXR0b24nLCBmdW5jdGlvbiAoKSB7XG5cdFx0XHRcdGNvbnN0IGVsID0gJCggdGhpcyApO1xuXHRcdFx0XHRjb25zdCBmb3JtID0gJCggdGhpcyApLmNsb3Nlc3QoICdmb3JtLmNhcnQnICk7XG5cdFx0XHRcdGNvbnN0IHF0eUVsID0gJCggJ1tuYW1lPVwicXVhbnRpdHlcIl0nICk7XG5cdFx0XHRcdGxldCBxdHkgPSAxO1xuXHRcdFx0XHRpZiAoIHF0eUVsLmxlbmd0aCApIHtcblx0XHRcdFx0XHRxdHkgPSBxdHlFbC52YWwoKTtcblx0XHRcdFx0fVxuXHRcdFx0XHRsZXQgZGF0YSA9IFsgeyBsYWJlbDogJ3F1YW50aXR5JywgdmFsdWU6IHF0eSB9IF07XG5cdFx0XHRcdGlmICggZm9ybS5oYXNDbGFzcyggJ3ZhcmlhdGlvbnNfZm9ybScgKSApIHtcblx0XHRcdFx0XHRkYXRhLnB1c2goIHsgbGFiZWw6ICdwcm9kdWN0X2lkJywgdmFsdWU6IGVsVmFsKCBmb3JtLCAnW25hbWU9XCJwcm9kdWN0X2lkXCJdJyApIH0gKTtcblx0XHRcdFx0XHRkYXRhLnB1c2goIHsgbGFiZWw6ICd2YXJpYXRpb25faWQnLCB2YWx1ZTogZWxWYWwoIGZvcm0sICdbbmFtZT1cInZhcmlhdGlvbl9pZFwiXScgKSB9ICk7XG5cdFx0XHRcdH0gZWxzZSB7XG5cdFx0XHRcdFx0ZGF0YS5wdXNoKCB7IGxhYmVsOiAncHJvZHVjdF9pZCcsIHZhbHVlOiBlbFZhbCggZWwgKSB9ICk7XG5cdFx0XHRcdH1cblx0XHRcdFx0XG5cdFx0XHRcdHN4cEV2ZW50KCAnYWRkLXRvLWNhcnQnLCBkYXRhICk7XG5cdFx0XHR9IClcblx0XHRcdC8vIEFkZCB0byBjYXQgb24gcHJvZHVjdCBhcmNoaXZlIHBhZ2UuXG5cdFx0XHQub24oICdjbGljaycsICcuYWRkX3RvX2NhcnRfYnV0dG9uJywgZnVuY3Rpb24gKCkge1xuXHRcdFx0XHRjb25zdCBlbCA9ICQoIHRoaXMgKTtcblx0XHRcdFx0c3hwRXZlbnQoICdhZGQtdG8tY2FydCcsIFtcblx0XHRcdFx0XHR7IGxhYmVsOiAncHJvZHVjdF9pZCcsIHZhbHVlOiBlbERhdGEoIGVsLCAncHJvZHVjdF9pZCcgKSB9LFxuXHRcdFx0XHRcdHsgbGFiZWw6ICdxdWFudGl0eScsIHZhbHVlOiBlbERhdGEoIGVsLCAncXVhbnRpdHkgJykgfSxcblx0XHRcdFx0XSApO1xuXHRcdFx0fSApXG5cdFx0XHQub24oICdjbGljaycsICcud29vY29tbWVyY2UtY2FydC1mb3JtIC5wcm9kdWN0LXJlbW92ZSA+IGEnLCBmdW5jdGlvbiAoKSB7XG5cdFx0XHRcdGNvbnN0IGVsID0gJCggdGhpcyApO1xuXHRcdFx0XHRzeHBFdmVudCggJ3JlbW92ZS1mcm9tLWNhcnQnLCBbIHsgbGFiZWw6ICdwcm9kdWN0X2lkJywgdmFsdWU6IGVsRGF0YSggZWwsICdwcm9kdWN0X2lkJyApIH0gXSApO1xuXHRcdFx0fSApXG5cdFx0XHQub24oICdjbGljaycsICcud29vY29tbWVyY2UtY2FydCAucmVzdG9yZS1pdGVtJywgZnVuY3Rpb24gKCkge1xuXHRcdFx0XHRzeHBFdmVudCggJ3VuZG8tcmVtb3ZlLWZyb20tY2FydCcgKTtcblx0XHRcdH0gKTtcblx0XHQvLyBDYXB0dXJlIHN1Y2Nlc3NmdWxsIGNoZWNrb3V0LlxuXHRcdGNvbnN0IGNoZWNrb3V0Rm9ybSA9ICQoICdmb3JtLmNoZWNrb3V0JyApO1xuXHRcdGNoZWNrb3V0Rm9ybS5vbiggJ2NoZWNrb3V0X3BsYWNlX29yZGVyX3N1Y2Nlc3MnLCBmdW5jdGlvbiAoKSB7XG5cdFx0XHRjb25zdCBkYXRhID0gWyB7XG5cdFx0XHRcdGxhYmVsOiAnZ2F0ZXdheV9pZCcsXG5cdFx0XHRcdHZhbHVlOiBlbFZhbCggJ1tuYW1lPVwicGF5bWVudF9tZXRob2RcIl06Y2hlY2tlZCcgKSxcblx0XHRcdH0sIHtcblx0XHRcdFx0bGFiZWw6ICd0b3RhbCcsXG5cdFx0XHRcdHZhbHVlOiBwYXJzZUZsb2F0KCAkKCcub3JkZXItdG90YWwnKS50ZXh0KCkucmVwbGFjZSggL1teXFxkLl0vZ20sICcnICkgKS50b0ZpeGVkKDIpLFxuXHRcdFx0fSBdO1xuXHRcdFx0c3hwRXZlbnQoICdjaGVja291dC1jb21wbGV0ZWQnLCBkYXRhICk7XG5cdFx0fSApO1xuXHRcdGlmICggY2hlY2tvdXRGb3JtLmxlbmd0aCApIHtcblx0XHRcblx0XHR9XG5cdFx0Y29uc3Qga2V5cyA9ICdlbWFpbCxmaXJzdF9uYW1lLGxhc3RfbmFtZSxjb21wYW55LGNvdW50cnksYWRkcmVzc18xLGFkZHJlc3NfMixjaXR5LHN0YXRlLHBvc3Rjb2RlLHBob25lLGNvbW1lbnRzJztcblx0XHRmb3IgKCBjb25zdCBrIG9mIGtleXMuc3BsaXQoICcsJyApICkge1xuXHRcdFx0aWYgKCBrICkge1xuXHRcdFx0XHRsZXQgaWQgPSBgYmlsbGluZ18ke2t9YDtcblx0XHRcdFx0aWYgKCAnY29tbWVudHMnID09PSBrICkge1xuXHRcdFx0XHRcdGlkID0gYG9yZGVyXyR7a31gO1xuXHRcdFx0XHR9XG5cdFx0XHRcdCQoYCMke2lkfWApLnZhbCggQ29va2llcy5nZXQoIGBzeHBfYWNfJHtrfWAgKSApO1xuXHRcdFx0fVxuXHRcdH1cblx0fSApO1xufSggalF1ZXJ5LCB3aW5kb3csIGRvY3VtZW50LCBTYWxlWHByZXNzbywgQ29va2llcyApICk7XG4iLCJleHBvcnQgZGVmYXVsdCBfX3dlYnBhY2tfcHVibGljX3BhdGhfXyArIFwiLi9hc3NldHMvY3NzL3N0eWxlcy5jc3NcIjsiLCJtb2R1bGUuZXhwb3J0cyA9IGpRdWVyeTsiXSwic291cmNlUm9vdCI6IiJ9