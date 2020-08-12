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
   * @class SaleXpressoAbandonCart
   */

  var SaleXpressoAbandonCart = /*#__PURE__*/function () {
    /**
     * Constructor
     */
    function SaleXpressoAbandonCart() {
      _classCallCheck(this, SaleXpressoAbandonCart);

      this._gdpr = gdpr;
      this._typingTimer;
      this._input_ids = 'email,first_name,last_name,company,country,address_1,address_2,city,state,postcode,phone,comments'.split(',');
      this._doneTypingInterval = 500;
      this._billing = 'billing'; // this._oldData = "";

      this._init();
    }
    /**
     * Initialize.
     *
     * @private
     */


    _createClass(SaleXpressoAbandonCart, [{
      key: "_init",
      value: function _init() {
        var self = this;
        var selectors = 'input, select, textarea';
        $(document).on('keyup keypress change blur', selectors, self._saveData.bind(self));
        $(document).on('keydown', selectors, self._clearTheCountDown.bind(self));
        $(document).on('ready', self._autofil_checkout.bind(self));
        setTimeout(function () {
          self._saveData();
        }, 750);
      }
    }, {
      key: "_autofil_checkout",
      value: function _autofil_checkout() {
        var self = this;

        var _iterator = _createForOfIteratorHelper(self._input_ids),
            _step;

        try {
          for (_iterator.s(); !(_step = _iterator.n()).done;) {
            var k = _step.value;

            if (k) {
              var id = "".concat(self._billing, "_").concat(k);

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
        var _this = this;

        var self = this;

        self._clearTheCountDown();

        self._typingTimer = setTimeout(function () {
          var email = $("#".concat(self._billing, "_email")).val() || '';

          if (isEmail(email)) {
            var data = {
              _wpnonce: _wpnonce,
              email: email
            };

            var _iterator2 = _createForOfIteratorHelper(_this._input_ids),
                _step2;

            try {
              for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
                var k = _step2.value;

                if (k) {
                  var id = "billing_".concat(k);

                  if ('comments' === k) {
                    id = "order_".concat(k);
                  }

                  var __VALUE__ = $("#".concat(id)).val() || '';

                  data[k] = __VALUE__;
                  Cookies.set("sxp_ac_".concat(k), __VALUE__, {
                    expires: parseInt(ac_timeout)
                  });
                }
              }
            } catch (err) {
              _iterator2.e(err);
            } finally {
              _iterator2.f();
            }

            console.log({
              data: data,
              event: event
            }); // const hash = JSON.stringify( data );
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

    return SaleXpressoAbandonCart;
  }();

  new SaleXpressoAbandonCart();
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
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vLy4vc3JjL2pzL3NjcmlwdHMuanMiLCJ3ZWJwYWNrOi8vLy4vc3JjL3Njc3Mvc3R5bGVzLnNjc3MiLCJ3ZWJwYWNrOi8vL2V4dGVybmFsIFwialF1ZXJ5XCIiXSwibmFtZXMiOlsiJCIsIndpbmRvdyIsImRvY3VtZW50IiwiU2FsZVhwcmVzc28iLCJDb29raWVzIiwiaXNFbWFpbCIsImVtYWlsIiwidGVzdCIsImZpbmRFbCIsImVsIiwic2VsZWN0b3IiLCJmaW5kIiwiZ2V0RWwiLCJjb25zdHJ1Y3RvciIsIm5hbWUiLCJsZW5ndGgiLCJjbG9zZXN0RWwiLCJjbG9zZXN0IiwiZWxWYWwiLCJ2YWwiLCJlbERhdGEiLCJkYXRhIiwiX3dwbm9uY2UiLCJnZHByIiwiYWNfdGltZW91dCIsIm1lc3NhZ2VzIiwiY2FydF9lbWFpbF9nZHByIiwibm9fdGhhbmtzIiwiU2FsZVhwcmVzc29BYmFuZG9uQ2FydCIsIl9nZHByIiwiX3R5cGluZ1RpbWVyIiwiX2lucHV0X2lkcyIsInNwbGl0IiwiX2RvbmVUeXBpbmdJbnRlcnZhbCIsIl9iaWxsaW5nIiwiX2luaXQiLCJzZWxmIiwic2VsZWN0b3JzIiwib24iLCJfc2F2ZURhdGEiLCJiaW5kIiwiX2NsZWFyVGhlQ291bnREb3duIiwiX2F1dG9maWxfY2hlY2tvdXQiLCJzZXRUaW1lb3V0IiwiayIsImlkIiwiZ2V0IiwiZXZlbnQiLCJfX1ZBTFVFX18iLCJzZXQiLCJleHBpcmVzIiwicGFyc2VJbnQiLCJjb25zb2xlIiwibG9nIiwid3AiLCJhamF4IiwicG9zdCIsImNsZWFyVGltZW91dCIsImZvcm0iLCJxdHlFbCIsInF0eSIsImxhYmVsIiwidmFsdWUiLCJoYXNDbGFzcyIsInB1c2giLCJzeHBFdmVudCIsImNoZWNrb3V0Rm9ybSIsInBhcnNlRmxvYXQiLCJ0ZXh0IiwicmVwbGFjZSIsInRvRml4ZWQiLCJqUXVlcnkiXSwibWFwcGluZ3MiOiI7UUFB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LEVBQXdCNUMsSUFBeEIsQ0FBUjtBQUNBLEtBVEQ7QUFVQSxHQWhERDtBQWlEQSxDQTFPQyxFQTBPQ2tELE1BMU9ELEVBME9TdEUsTUExT1QsRUEwT2lCQyxRQTFPakIsRUEwTzJCQyxXQTFPM0IsRUEwT3dDQyxPQTFPeEMsQ0FBRixDOzs7Ozs7Ozs7Ozs7O0FDVEE7QUFBZSxvRkFBdUIsNEJBQTRCLEU7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7OztBQ0FsRSx3QiIsImZpbGUiOiIuL2Fzc2V0cy9qcy9zY3JpcHRzLmpzIiwic291cmNlc0NvbnRlbnQiOlsiIFx0Ly8gVGhlIG1vZHVsZSBjYWNoZVxuIFx0dmFyIGluc3RhbGxlZE1vZHVsZXMgPSB7fTtcblxuIFx0Ly8gVGhlIHJlcXVpcmUgZnVuY3Rpb25cbiBcdGZ1bmN0aW9uIF9fd2VicGFja19yZXF1aXJlX18obW9kdWxlSWQpIHtcblxuIFx0XHQvLyBDaGVjayBpZiBtb2R1bGUgaXMgaW4gY2FjaGVcbiBcdFx0aWYoaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0pIHtcbiBcdFx0XHRyZXR1cm4gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0uZXhwb3J0cztcbiBcdFx0fVxuIFx0XHQvLyBDcmVhdGUgYSBuZXcgbW9kdWxlIChhbmQgcHV0IGl0IGludG8gdGhlIGNhY2hlKVxuIFx0XHR2YXIgbW9kdWxlID0gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0gPSB7XG4gXHRcdFx0aTogbW9kdWxlSWQsXG4gXHRcdFx0bDogZmFsc2UsXG4gXHRcdFx0ZXhwb3J0czoge31cbiBcdFx0fTtcblxuIFx0XHQvLyBFeGVjdXRlIHRoZSBtb2R1bGUgZnVuY3Rpb25cbiBcdFx0bW9kdWxlc1ttb2R1bGVJZF0uY2FsbChtb2R1bGUuZXhwb3J0cywgbW9kdWxlLCBtb2R1bGUuZXhwb3J0cywgX193ZWJwYWNrX3JlcXVpcmVfXyk7XG5cbiBcdFx0Ly8gRmxhZyB0aGUgbW9kdWxlIGFzIGxvYWRlZFxuIFx0XHRtb2R1bGUubCA9IHRydWU7XG5cbiBcdFx0Ly8gUmV0dXJuIHRoZSBleHBvcnRzIG9mIHRoZSBtb2R1bGVcbiBcdFx0cmV0dXJuIG1vZHVsZS5leHBvcnRzO1xuIFx0fVxuXG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlcyBvYmplY3QgKF9fd2VicGFja19tb2R1bGVzX18pXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm0gPSBtb2R1bGVzO1xuXG4gXHQvLyBleHBvc2UgdGhlIG1vZHVsZSBjYWNoZVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5jID0gaW5zdGFsbGVkTW9kdWxlcztcblxuIFx0Ly8gZGVmaW5lIGdldHRlciBmdW5jdGlvbiBmb3IgaGFybW9ueSBleHBvcnRzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmQgPSBmdW5jdGlvbihleHBvcnRzLCBuYW1lLCBnZXR0ZXIpIHtcbiBcdFx0aWYoIV9fd2VicGFja19yZXF1aXJlX18ubyhleHBvcnRzLCBuYW1lKSkge1xuIFx0XHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBuYW1lLCB7IGVudW1lcmFibGU6IHRydWUsIGdldDogZ2V0dGVyIH0pO1xuIFx0XHR9XG4gXHR9O1xuXG4gXHQvLyBkZWZpbmUgX19lc01vZHVsZSBvbiBleHBvcnRzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnIgPSBmdW5jdGlvbihleHBvcnRzKSB7XG4gXHRcdGlmKHR5cGVvZiBTeW1ib2wgIT09ICd1bmRlZmluZWQnICYmIFN5bWJvbC50b1N0cmluZ1RhZykge1xuIFx0XHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBTeW1ib2wudG9TdHJpbmdUYWcsIHsgdmFsdWU6ICdNb2R1bGUnIH0pO1xuIFx0XHR9XG4gXHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCAnX19lc01vZHVsZScsIHsgdmFsdWU6IHRydWUgfSk7XG4gXHR9O1xuXG4gXHQvLyBjcmVhdGUgYSBmYWtlIG5hbWVzcGFjZSBvYmplY3RcbiBcdC8vIG1vZGUgJiAxOiB2YWx1ZSBpcyBhIG1vZHVsZSBpZCwgcmVxdWlyZSBpdFxuIFx0Ly8gbW9kZSAmIDI6IG1lcmdlIGFsbCBwcm9wZXJ0aWVzIG9mIHZhbHVlIGludG8gdGhlIG5zXG4gXHQvLyBtb2RlICYgNDogcmV0dXJuIHZhbHVlIHdoZW4gYWxyZWFkeSBucyBvYmplY3RcbiBcdC8vIG1vZGUgJiA4fDE6IGJlaGF2ZSBsaWtlIHJlcXVpcmVcbiBcdF9fd2VicGFja19yZXF1aXJlX18udCA9IGZ1bmN0aW9uKHZhbHVlLCBtb2RlKSB7XG4gXHRcdGlmKG1vZGUgJiAxKSB2YWx1ZSA9IF9fd2VicGFja19yZXF1aXJlX18odmFsdWUpO1xuIFx0XHRpZihtb2RlICYgOCkgcmV0dXJuIHZhbHVlO1xuIFx0XHRpZigobW9kZSAmIDQpICYmIHR5cGVvZiB2YWx1ZSA9PT0gJ29iamVjdCcgJiYgdmFsdWUgJiYgdmFsdWUuX19lc01vZHVsZSkgcmV0dXJuIHZhbHVlO1xuIFx0XHR2YXIgbnMgPSBPYmplY3QuY3JlYXRlKG51bGwpO1xuIFx0XHRfX3dlYnBhY2tfcmVxdWlyZV9fLnIobnMpO1xuIFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkobnMsICdkZWZhdWx0JywgeyBlbnVtZXJhYmxlOiB0cnVlLCB2YWx1ZTogdmFsdWUgfSk7XG4gXHRcdGlmKG1vZGUgJiAyICYmIHR5cGVvZiB2YWx1ZSAhPSAnc3RyaW5nJykgZm9yKHZhciBrZXkgaW4gdmFsdWUpIF9fd2VicGFja19yZXF1aXJlX18uZChucywga2V5LCBmdW5jdGlvbihrZXkpIHsgcmV0dXJuIHZhbHVlW2tleV07IH0uYmluZChudWxsLCBrZXkpKTtcbiBcdFx0cmV0dXJuIG5zO1xuIFx0fTtcblxuIFx0Ly8gZ2V0RGVmYXVsdEV4cG9ydCBmdW5jdGlvbiBmb3IgY29tcGF0aWJpbGl0eSB3aXRoIG5vbi1oYXJtb255IG1vZHVsZXNcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubiA9IGZ1bmN0aW9uKG1vZHVsZSkge1xuIFx0XHR2YXIgZ2V0dGVyID0gbW9kdWxlICYmIG1vZHVsZS5fX2VzTW9kdWxlID9cbiBcdFx0XHRmdW5jdGlvbiBnZXREZWZhdWx0KCkgeyByZXR1cm4gbW9kdWxlWydkZWZhdWx0J107IH0gOlxuIFx0XHRcdGZ1bmN0aW9uIGdldE1vZHVsZUV4cG9ydHMoKSB7IHJldHVybiBtb2R1bGU7IH07XG4gXHRcdF9fd2VicGFja19yZXF1aXJlX18uZChnZXR0ZXIsICdhJywgZ2V0dGVyKTtcbiBcdFx0cmV0dXJuIGdldHRlcjtcbiBcdH07XG5cbiBcdC8vIE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbFxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5vID0gZnVuY3Rpb24ob2JqZWN0LCBwcm9wZXJ0eSkgeyByZXR1cm4gT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsKG9iamVjdCwgcHJvcGVydHkpOyB9O1xuXG4gXHQvLyBfX3dlYnBhY2tfcHVibGljX3BhdGhfX1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5wID0gXCJcIjtcblxuXG4gXHQvLyBMb2FkIGVudHJ5IG1vZHVsZSBhbmQgcmV0dXJuIGV4cG9ydHNcbiBcdHJldHVybiBfX3dlYnBhY2tfcmVxdWlyZV9fKF9fd2VicGFja19yZXF1aXJlX18ucyA9IDApO1xuIiwiLyoqIVxuICogU2FsZVhwcmVzc28gUHVibGljIFNjcmlwdHNcbiAqXG4gKiBAYXV0aG9yIFNhbGVYcHJlc3NvIDxzdXBwb3J0QHNhbGV4cHJlc3NvLmNvbT5cbiAqIEBwYWNrYWdlIFNhbGVYcHJlc3NvXG4gKiBAdmVyc2lvbiAxLjAuMFxuICogQHNpbmNlIDEuMC4wXG4gKi9cbi8vIGltcG9ydCBfIGZyb20gJ2xvZGFzaCc7XG4oIGZ1bmN0aW9uKCAkLCB3aW5kb3csIGRvY3VtZW50LCBTYWxlWHByZXNzbywgQ29va2llcyApIHtcblx0XCJ1c2Ugc3RyaWN0XCI7XG5cdFxuXHQvLyBIZWxwZXIgRnVuY3Rpb25zLlxuXHRcblx0LyoqXG5cdCAqIENoZWNrcyBpZiBpbnB1dCBpcyB2YWxpZCBlbWFpbC5cblx0ICpcblx0ICogQHBhcmFtIGVtYWlsXG5cdCAqIEByZXR1cm4ge2Jvb2xlYW59XG5cdCAqL1xuXHRjb25zdCBpc0VtYWlsID0gZW1haWwgPT4gL14oW2EtekEtWjAtOV8uKy1dKStcXEAoKFthLXpBLVowLTktXSkrXFwuKSsoW2EtekEtWjAtOV17Miw0fSkrJC8udGVzdCggZW1haWwgKTtcblx0XG5cdC8qKlxuXHQgKiBGaW5kIE5lc3RlZCBFbGVtZW50LlxuXHQgKiBTYW1lIGFzICQoZWwpZmluZCgpLlxuXHQgKlxuXHQgKiBAcGFyYW0ge2pRdWVyeXxIVE1MRWxlbWVudHxTdHJpbmd9IGVsXG5cdCAqIEBwYXJhbSB7alF1ZXJ5fEhUTUxFbGVtZW50fFN0cmluZ30gc2VsZWN0b3Jcblx0ICogQHJldHVybiB7KnxqUXVlcnl8SFRNTEVsZW1lbnR9XG5cdCAqL1xuXHRjb25zdCBmaW5kRWwgPSAoIGVsLCBzZWxlY3RvciApID0+IGVsLmZpbmQoIHNlbGVjdG9yICk7XG5cdFxuXHQvKipcblx0ICogR2V0IEVsZW1lbnQuXG5cdCAqIEFsdGVybmF0aXZlIHRvICQoc2VsZWN0b3IpIHdpdGggc3VwcG9ydCBmb3IgZmluZCBuZXN0ZWQgZWxlbWVudC5cblx0ICpcblx0ICogQHBhcmFtIHtqUXVlcnl8SFRNTEVsZW1lbnR8QXJyYXl8U3RyaW5nfSBlbFxuXHQgKiBAcGFyYW0ge2pRdWVyeXxIVE1MRWxlbWVudHxTdHJpbmd9IHNlbGVjdG9yXG5cdCAqIEByZXR1cm4geyp8alF1ZXJ5fEhUTUxFbGVtZW50fVxuXHQgKi9cblx0Y29uc3QgZ2V0RWwgPSAoIGVsLCBzZWxlY3RvciApID0+IHtcblx0XHRpZiAoICdBcnJheScgPT09IGVsLmNvbnN0cnVjdG9yLm5hbWUgJiYgMiA9PT0gZWwubGVuZ3RoICkge1xuXHRcdFx0c2VsZWN0b3IgPSBlbFsxXTtcblx0XHRcdGVsID0gZWxbMF07XG5cdFx0fVxuXHRcdGlmICggJ3N0cmluZycgPT09IHR5cGVvZiBlbCApIHtcblx0XHRcdGVsID0gJCggZWwgKTtcblx0XHR9XG5cdFx0aWYgKCBzZWxlY3RvciAmJiAnc3RyaW5nJyA9PT0gdHlwZW9mIHNlbGVjdG9yICkge1xuXHRcdFx0cmV0dXJuIGZpbmRFbCggZWwsIHNlbGVjdG9yICk7XG5cdFx0fVxuXHRcdHJldHVybiBlbDtcblx0fTtcblx0XG5cdC8qKlxuXHQgKiBHZXQgQ2xvc2VzdCBFbGVtZW50LlxuXHQgKiBTYW1lIGFzICQoZWwpLmNsb3Nlc3QoKS5cblx0ICpcblx0ICogQHBhcmFtIHtqUXVlcnl8SFRNTEVsZW1lbnR8QXJyYXl8U3RyaW5nfSBlbFxuXHQgKiBAcGFyYW0ge2pRdWVyeXxIVE1MRWxlbWVudHxTdHJpbmd9IHNlbGVjdG9yXG5cdCAqIEByZXR1cm4geyp8alF1ZXJ5fEhUTUxFbGVtZW50fG51bGx8RWxlbWVudH1cblx0ICovXG5cdGNvbnN0IGNsb3Nlc3RFbCA9ICggZWwsIHNlbGVjdG9yICkgPT4ge1xuXHRcdHJldHVybiBnZXRFbCggZWwgKS5jbG9zZXN0KCBzZWxlY3RvciApO1xuXHR9XG5cdFxuXHQvKipcblx0ICogR2V0IEVsZW1lbnQgVmFsdWUuXG5cdCAqICQoZWwpLnZhbCgpXG5cdCAqIEBwYXJhbSB7alF1ZXJ5fEhUTUxFbGVtZW50fEFycmF5fFN0cmluZ30gZWxcblx0ICogQHBhcmFtIHtqUXVlcnl8SFRNTEVsZW1lbnR8U3RyaW5nfSBzZWxlY3RvclxuXHQgKiBAcmV0dXJuIHsqfVxuXHQgKi9cblx0Y29uc3QgZWxWYWwgPSAoIGVsLCBzZWxlY3RvciApID0+IGdldEVsKCBlbCwgc2VsZWN0b3IgKS52YWwoKTtcblx0XG5cdC8qKlxuXHQgKiBHZXQgRWxlbWVudCBEYXRhIGF0dHJpYnV0ZXMuXG5cdCAqICQoZWwpLmRhdGEoKVxuXHQgKlxuXHQgKiBAcGFyYW0ge2pRdWVyeXxIVE1MRWxlbWVudHxBcnJheXxTdHJpbmd9IHNlbGVjdG9yXG5cdCAqIEBwYXJhbSB7U3RyaW5nfSBkYXRhXG5cdCAqIEByZXR1cm4geyp9XG5cdCAqL1xuXHRjb25zdCBlbERhdGEgPSAoIHNlbGVjdG9yLCBkYXRhICkgPT4gZ2V0RWwoIHNlbGVjdG9yICkuZGF0YSggZGF0YSApO1xuXHRcblx0Ly8gRHlhbWljIE9wdGlvbnMuXG5cdGNvbnN0IHtcblx0XHRfd3Bub25jZSxcblx0XHRnZHByLFxuXHRcdGFjX3RpbWVvdXQsXG5cdFx0bWVzc2FnZXM6IHsgY2FydF9lbWFpbF9nZHByLCBub190aGFua3MgfSxcblx0fSA9IFNhbGVYcHJlc3NvO1xuXHQvLyBAVE9ETyBoYW5kbGUgR0RQUi5cblx0XG5cdC8qKlxuXHQgKiBBYmFuZG9uIENhcnQuXG5cdCAqIFNhdmUgQ2FydCBEYXRhIGluIGNhc2Ugb2YgdXNlciBkaWRuJ3QgY29tcGxldGUgdGhlIGNoZWNrb3V0LlxuXHQgKiBcblx0ICogQGNsYXNzIFNhbGVYcHJlc3NvQWJhbmRvbkNhcnRcblx0ICovXG5cdGNsYXNzIFNhbGVYcHJlc3NvQWJhbmRvbkNhcnQge1xuXHRcdC8qKlxuXHRcdCAqIENvbnN0cnVjdG9yXG5cdFx0ICovXG5cdFx0Y29uc3RydWN0b3IgKCkge1xuXHRcdFx0dGhpcy5fZ2RwciA9IGdkcHI7XG5cdFx0XHR0aGlzLl90eXBpbmdUaW1lcjtcblx0XHRcdHRoaXMuX2lucHV0X2lkcyA9ICdlbWFpbCxmaXJzdF9uYW1lLGxhc3RfbmFtZSxjb21wYW55LGNvdW50cnksYWRkcmVzc18xLGFkZHJlc3NfMixjaXR5LHN0YXRlLHBvc3Rjb2RlLHBob25lLGNvbW1lbnRzJy5zcGxpdCggJywnICk7XG5cdFx0XHR0aGlzLl9kb25lVHlwaW5nSW50ZXJ2YWwgPSA1MDA7XG5cdFx0XHR0aGlzLl9iaWxsaW5nID0gJ2JpbGxpbmcnO1xuXHRcdFx0Ly8gdGhpcy5fb2xkRGF0YSA9IFwiXCI7XG5cdFx0XHR0aGlzLl9pbml0KCk7XG5cdFx0fVxuXHRcdFxuXHRcdC8qKlxuXHRcdCAqIEluaXRpYWxpemUuXG5cdFx0ICpcblx0XHQgKiBAcHJpdmF0ZVxuXHRcdCAqL1xuXHRcdF9pbml0KCkge1xuXHRcdFx0Y29uc3Qgc2VsZiA9IHRoaXM7XG5cdFx0XHRjb25zdCBzZWxlY3RvcnMgPSAnaW5wdXQsIHNlbGVjdCwgdGV4dGFyZWEnO1xuXHRcdFx0JChkb2N1bWVudCkub24oICdrZXl1cCBrZXlwcmVzcyBjaGFuZ2UgYmx1cicsIHNlbGVjdG9ycywgc2VsZi5fc2F2ZURhdGEuYmluZCggc2VsZiApICk7XG5cdFx0XHQkKGRvY3VtZW50KS5vbiggJ2tleWRvd24nLCBzZWxlY3RvcnMsIHNlbGYuX2NsZWFyVGhlQ291bnREb3duLmJpbmQoIHNlbGYgKSApO1xuXHRcdFx0JChkb2N1bWVudCkub24oICdyZWFkeScsIHNlbGYuX2F1dG9maWxfY2hlY2tvdXQuYmluZCggc2VsZiApICk7XG5cdFx0XHRzZXRUaW1lb3V0KCAoKSA9PiB7XG5cdFx0XHRcdHNlbGYuX3NhdmVEYXRhKCk7XG5cdFx0XHR9LCA3NTAgKTtcblx0XHR9XG5cdFx0XG5cdFx0X2F1dG9maWxfY2hlY2tvdXQoKSB7XG5cdFx0XHRjb25zdCBzZWxmID0gdGhpcztcblx0XHRcdGZvciAoIGNvbnN0IGsgb2Ygc2VsZi5faW5wdXRfaWRzICkge1xuXHRcdFx0XHRpZiAoIGsgKSB7XG5cdFx0XHRcdFx0bGV0IGlkID0gYCR7c2VsZi5fYmlsbGluZ31fJHtrfWA7XG5cdFx0XHRcdFx0aWYgKCAnY29tbWVudHMnID09PSBrICkge1xuXHRcdFx0XHRcdFx0aWQgPSBgb3JkZXJfJHtrfWA7XG5cdFx0XHRcdFx0fVxuXHRcdFx0XHRcdCQoYCMke2lkfWApLnZhbCggQ29va2llcy5nZXQoIGBzeHBfYWNfJHtrfWAgKSApO1xuXHRcdFx0XHR9XG5cdFx0XHR9XG5cdFx0fVxuXHRcdFxuXHRcdC8qKlxuXHRcdCAqIFNhdmUgVXNlciBEYXRhLlxuXHRcdCAqIERlYnVuY2VkIHdpdGggc2V0VGltZW91dFxuXHRcdCAqXG5cdFx0ICogQHBhcmFtIHtFdmVudH0gZXZlbnRcblx0XHQgKlxuXHRcdCAqIEBwcml2YXRlXG5cdFx0ICovXG5cdFx0X3NhdmVEYXRhKCBldmVudCApIHtcblx0XHRcdGNvbnN0IHNlbGYgPSB0aGlzO1xuXHRcdFx0c2VsZi5fY2xlYXJUaGVDb3VudERvd24oKTtcblx0XHRcdHNlbGYuX3R5cGluZ1RpbWVyID0gc2V0VGltZW91dCggKCkgPT4ge1xuXHRcdFx0XHRjb25zdCBlbWFpbCA9ICQoIGAjJHtzZWxmLl9iaWxsaW5nfV9lbWFpbGAgKS52YWwoKSB8fCAnJztcblx0XHRcdFx0aWYgKCBpc0VtYWlsKCBlbWFpbCApICkge1xuXHRcdFx0XHRcdGxldCBkYXRhID0geyBfd3Bub25jZSwgZW1haWwgfTtcblx0XHRcdFx0XHRmb3IgKCBjb25zdCBrIG9mIHRoaXMuX2lucHV0X2lkcyApIHtcblx0XHRcdFx0XHRcdGlmICggayApIHtcblx0XHRcdFx0XHRcdFx0bGV0IGlkID0gYGJpbGxpbmdfJHtrfWA7XG5cdFx0XHRcdFx0XHRcdGlmICggJ2NvbW1lbnRzJyA9PT0gayApIHtcblx0XHRcdFx0XHRcdFx0XHRpZCA9IGBvcmRlcl8ke2t9YDtcblx0XHRcdFx0XHRcdFx0fVxuXHRcdFx0XHRcdFx0XHRjb25zdCBfX1ZBTFVFX18gPSAkKGAjJHtpZH1gKS52YWwoKSB8fCAnJztcblx0XHRcdFx0XHRcdFx0ZGF0YVtrXSA9IF9fVkFMVUVfXztcblx0XHRcdFx0XHRcdFx0Q29va2llcy5zZXQoIGBzeHBfYWNfJHtrfWAsIF9fVkFMVUVfXywgeyBleHBpcmVzOiBwYXJzZUludCggYWNfdGltZW91dCApIH0gKTtcblx0XHRcdFx0XHRcdH1cblx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0Y29uc29sZS5sb2coIHtkYXRhLCBldmVudH0gKTtcblx0XHRcdFx0XHQvLyBjb25zdCBoYXNoID0gSlNPTi5zdHJpbmdpZnkoIGRhdGEgKTtcblx0XHRcdFx0XHQvLyBpZiAoIHNlbGYuX29sZERhdGEgIT09IGhhc2ggKSB7XG5cdFx0XHRcdFx0Ly8gXHRzZWxmLl9vbGREYXRhID0gaGFzaDsgLy8gcmVkdWNlIGJhY2tlbmQgY2FsbC5cblx0XHRcdFx0XHQvLyBcdHdwLmFqYXgucG9zdCggJ3N4cF9zYXZlX2FiYW5kb25fY2FydF9kYXRhJywgZGF0YSApO1xuXHRcdFx0XHRcdC8vIH1cblx0XHRcdFx0XHR3cC5hamF4LnBvc3QoICdzeHBfc2F2ZV9hYmFuZG9uX2NhcnRfZGF0YScsIGRhdGEgKTtcblx0XHRcdFx0fVxuXHRcdFx0fSwgdGhpcy5fZG9uZVR5cGluZ0ludGVydmFsICk7XG5cdFx0fVxuXHRcdFxuXHRcdC8qKlxuXHRcdCAqIENsZWFyIFRpbWVyLlxuXHRcdCAqXG5cdFx0ICogQHByaXZhdGVcblx0XHQgKi9cblx0XHRfY2xlYXJUaGVDb3VudERvd24oKSB7XG5cdFx0XHRpZiAoIHRoaXMuX3R5cGluZ1RpbWVyICkge1xuXHRcdFx0XHRjbGVhclRpbWVvdXQoIHRoaXMuX3R5cGluZ1RpbWVyICk7XG5cdFx0XHR9XG5cdFx0fVxuXHR9XG5cdFxuXHRuZXcgU2FsZVhwcmVzc29BYmFuZG9uQ2FydCgpO1xuXHRcblx0JCggZG9jdW1lbnQgKS5vbiggJ3JlYWR5JywgZnVuY3Rpb24oKSB7XG5cdFx0JCggZG9jdW1lbnQgKVxuXHRcdFx0Ly8gQWRkIHRvIGNhdCBvbiBzaW5nbGUgcHJvZHVjdCBwYWdlLlxuXHRcdFx0Lm9uKCAnY2xpY2snLCAnLnNpbmdsZV9hZGRfdG9fY2FydF9idXR0b24nLCBmdW5jdGlvbiAoKSB7XG5cdFx0XHRcdGNvbnN0IGVsID0gJCggdGhpcyApO1xuXHRcdFx0XHRjb25zdCBmb3JtID0gJCggdGhpcyApLmNsb3Nlc3QoICdmb3JtLmNhcnQnICk7XG5cdFx0XHRcdGNvbnN0IHF0eUVsID0gJCggJ1tuYW1lPVwicXVhbnRpdHlcIl0nICk7XG5cdFx0XHRcdGxldCBxdHkgPSAxO1xuXHRcdFx0XHRpZiAoIHF0eUVsLmxlbmd0aCApIHtcblx0XHRcdFx0XHRxdHkgPSBxdHlFbC52YWwoKTtcblx0XHRcdFx0fVxuXHRcdFx0XHRsZXQgZGF0YSA9IFsgeyBsYWJlbDogJ3F1YW50aXR5JywgdmFsdWU6IHF0eSB9IF07XG5cdFx0XHRcdGlmICggZm9ybS5oYXNDbGFzcyggJ3ZhcmlhdGlvbnNfZm9ybScgKSApIHtcblx0XHRcdFx0XHRkYXRhLnB1c2goIHsgbGFiZWw6ICdwcm9kdWN0X2lkJywgdmFsdWU6IGVsVmFsKCBmb3JtLCAnW25hbWU9XCJwcm9kdWN0X2lkXCJdJyApIH0gKTtcblx0XHRcdFx0XHRkYXRhLnB1c2goIHsgbGFiZWw6ICd2YXJpYXRpb25faWQnLCB2YWx1ZTogZWxWYWwoIGZvcm0sICdbbmFtZT1cInZhcmlhdGlvbl9pZFwiXScgKSB9ICk7XG5cdFx0XHRcdH0gZWxzZSB7XG5cdFx0XHRcdFx0ZGF0YS5wdXNoKCB7IGxhYmVsOiAncHJvZHVjdF9pZCcsIHZhbHVlOiBlbFZhbCggZWwgKSB9ICk7XG5cdFx0XHRcdH1cblx0XHRcdFx0XG5cdFx0XHRcdHN4cEV2ZW50KCAnYWRkLXRvLWNhcnQnLCBkYXRhICk7XG5cdFx0XHR9IClcblx0XHRcdC8vIEFkZCB0byBjYXQgb24gcHJvZHVjdCBhcmNoaXZlIHBhZ2UuXG5cdFx0XHQub24oICdjbGljaycsICcuYWRkX3RvX2NhcnRfYnV0dG9uJywgZnVuY3Rpb24gKCkge1xuXHRcdFx0XHRjb25zdCBlbCA9ICQoIHRoaXMgKTtcblx0XHRcdFx0c3hwRXZlbnQoICdhZGQtdG8tY2FydCcsIFtcblx0XHRcdFx0XHR7IGxhYmVsOiAncHJvZHVjdF9pZCcsIHZhbHVlOiBlbERhdGEoIGVsLCAncHJvZHVjdF9pZCcgKSB9LFxuXHRcdFx0XHRcdHsgbGFiZWw6ICdxdWFudGl0eScsIHZhbHVlOiBlbERhdGEoIGVsLCAncXVhbnRpdHkgJykgfSxcblx0XHRcdFx0XSApO1xuXHRcdFx0fSApXG5cdFx0XHQub24oICdjbGljaycsICcud29vY29tbWVyY2UtY2FydC1mb3JtIC5wcm9kdWN0LXJlbW92ZSA+IGEnLCBmdW5jdGlvbiAoKSB7XG5cdFx0XHRcdGNvbnN0IGVsID0gJCggdGhpcyApO1xuXHRcdFx0XHRzeHBFdmVudCggJ3JlbW92ZS1mcm9tLWNhcnQnLCBbIHsgbGFiZWw6ICdwcm9kdWN0X2lkJywgdmFsdWU6IGVsRGF0YSggZWwsICdwcm9kdWN0X2lkJyApIH0gXSApO1xuXHRcdFx0fSApXG5cdFx0XHQub24oICdjbGljaycsICcud29vY29tbWVyY2UtY2FydCAucmVzdG9yZS1pdGVtJywgZnVuY3Rpb24gKCkge1xuXHRcdFx0XHRzeHBFdmVudCggJ3VuZG8tcmVtb3ZlLWZyb20tY2FydCcgKTtcblx0XHRcdH0gKTtcblx0XHQvLyBDYXB0dXJlIHN1Y2Nlc3NmdWxsIGNoZWNrb3V0LlxuXHRcdGNvbnN0IGNoZWNrb3V0Rm9ybSA9ICQoICdmb3JtLmNoZWNrb3V0JyApO1xuXHRcdGNoZWNrb3V0Rm9ybS5vbiggJ2NoZWNrb3V0X3BsYWNlX29yZGVyX3N1Y2Nlc3MnLCBmdW5jdGlvbiAoKSB7XG5cdFx0XHRjb25zdCBkYXRhID0gWyB7XG5cdFx0XHRcdGxhYmVsOiAnZ2F0ZXdheV9pZCcsXG5cdFx0XHRcdHZhbHVlOiBlbFZhbCggJ1tuYW1lPVwicGF5bWVudF9tZXRob2RcIl06Y2hlY2tlZCcgKSxcblx0XHRcdH0sIHtcblx0XHRcdFx0bGFiZWw6ICd0b3RhbCcsXG5cdFx0XHRcdHZhbHVlOiBwYXJzZUZsb2F0KCAkKCcub3JkZXItdG90YWwnKS50ZXh0KCkucmVwbGFjZSggL1teXFxkLl0vZ20sICcnICkgKS50b0ZpeGVkKDIpLFxuXHRcdFx0fSBdO1xuXHRcdFx0c3hwRXZlbnQoICdjaGVja291dC1jb21wbGV0ZWQnLCBkYXRhICk7XG5cdFx0fSApO1xuXHR9ICk7XG59KCBqUXVlcnksIHdpbmRvdywgZG9jdW1lbnQsIFNhbGVYcHJlc3NvLCBDb29raWVzICkgKTtcbiIsImV4cG9ydCBkZWZhdWx0IF9fd2VicGFja19wdWJsaWNfcGF0aF9fICsgXCIuL2Fzc2V0cy9jc3Mvc3R5bGVzLmNzc1wiOyIsIm1vZHVsZS5leHBvcnRzID0galF1ZXJ5OyJdLCJzb3VyY2VSb290IjoiIn0=