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

        if ($('body').hasClass('logged-in')) {
          return;
        }

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
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vLy4vc3JjL2pzL3NjcmlwdHMuanMiLCJ3ZWJwYWNrOi8vLy4vc3JjL3Njc3Mvc3R5bGVzLnNjc3MiLCJ3ZWJwYWNrOi8vL2V4dGVybmFsIFwialF1ZXJ5XCIiXSwibmFtZXMiOlsiJCIsIndpbmRvdyIsImRvY3VtZW50IiwiU2FsZVhwcmVzc28iLCJDb29raWVzIiwiaXNFbWFpbCIsImVtYWlsIiwidGVzdCIsImZpbmRFbCIsImVsIiwic2VsZWN0b3IiLCJmaW5kIiwiZ2V0RWwiLCJjb25zdHJ1Y3RvciIsIm5hbWUiLCJsZW5ndGgiLCJjbG9zZXN0RWwiLCJjbG9zZXN0IiwiZWxWYWwiLCJ2YWwiLCJlbERhdGEiLCJkYXRhIiwiX3dwbm9uY2UiLCJnZHByIiwiYWNfdGltZW91dCIsIm1lc3NhZ2VzIiwiY2FydF9lbWFpbF9nZHByIiwibm9fdGhhbmtzIiwiU2FsZVhwcmVzc29BYmFuZG9uQ2FydCIsIl9nZHByIiwiX3R5cGluZ1RpbWVyIiwiX2lucHV0X2lkcyIsInNwbGl0IiwiX2RvbmVUeXBpbmdJbnRlcnZhbCIsIl9iaWxsaW5nIiwiX2luaXQiLCJzZWxmIiwic2VsZWN0b3JzIiwib24iLCJfc2F2ZURhdGEiLCJiaW5kIiwiX2NsZWFyVGhlQ291bnREb3duIiwiX2F1dG9maWxfY2hlY2tvdXQiLCJzZXRUaW1lb3V0IiwiaGFzQ2xhc3MiLCJrIiwiaWQiLCJnZXQiLCJldmVudCIsIl9fVkFMVUVfXyIsInNldCIsImV4cGlyZXMiLCJwYXJzZUludCIsImNvbnNvbGUiLCJsb2ciLCJ3cCIsImFqYXgiLCJwb3N0IiwiY2xlYXJUaW1lb3V0IiwiZm9ybSIsInF0eUVsIiwicXR5IiwibGFiZWwiLCJ2YWx1ZSIsInB1c2giLCJzeHBFdmVudCIsImNoZWNrb3V0Rm9ybSIsInBhcnNlRmxvYXQiLCJ0ZXh0IiwicmVwbGFjZSIsInRvRml4ZWQiLCJqUXVlcnkiXSwibWFwcGluZ3MiOiI7UUFB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QyxFQUE5QyxDQUFGLENBQVYsQ0FBaUVDLE9BQWpFLENBQXlFLENBQXpFO0FBRkwsT0FIVSxDQUFiO0FBT0FMLGNBQVEsQ0FBRSxvQkFBRixFQUF3QjVDLElBQXhCLENBQVI7QUFDQSxLQVREO0FBVUEsR0FoREQ7QUFpREEsQ0E3T0MsRUE2T0NrRCxNQTdPRCxFQTZPU3RFLE1BN09ULEVBNk9pQkMsUUE3T2pCLEVBNk8yQkMsV0E3TzNCLEVBNk93Q0MsT0E3T3hDLENBQUYsQzs7Ozs7Ozs7Ozs7OztBQ1RBO0FBQWUsb0ZBQXVCLDRCQUE0QixFOzs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7QUNBbEUsd0IiLCJmaWxlIjoiLi9hc3NldHMvanMvc2NyaXB0cy5qcyIsInNvdXJjZXNDb250ZW50IjpbIiBcdC8vIFRoZSBtb2R1bGUgY2FjaGVcbiBcdHZhciBpbnN0YWxsZWRNb2R1bGVzID0ge307XG5cbiBcdC8vIFRoZSByZXF1aXJlIGZ1bmN0aW9uXG4gXHRmdW5jdGlvbiBfX3dlYnBhY2tfcmVxdWlyZV9fKG1vZHVsZUlkKSB7XG5cbiBcdFx0Ly8gQ2hlY2sgaWYgbW9kdWxlIGlzIGluIGNhY2hlXG4gXHRcdGlmKGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdKSB7XG4gXHRcdFx0cmV0dXJuIGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdLmV4cG9ydHM7XG4gXHRcdH1cbiBcdFx0Ly8gQ3JlYXRlIGEgbmV3IG1vZHVsZSAoYW5kIHB1dCBpdCBpbnRvIHRoZSBjYWNoZSlcbiBcdFx0dmFyIG1vZHVsZSA9IGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdID0ge1xuIFx0XHRcdGk6IG1vZHVsZUlkLFxuIFx0XHRcdGw6IGZhbHNlLFxuIFx0XHRcdGV4cG9ydHM6IHt9XG4gXHRcdH07XG5cbiBcdFx0Ly8gRXhlY3V0ZSB0aGUgbW9kdWxlIGZ1bmN0aW9uXG4gXHRcdG1vZHVsZXNbbW9kdWxlSWRdLmNhbGwobW9kdWxlLmV4cG9ydHMsIG1vZHVsZSwgbW9kdWxlLmV4cG9ydHMsIF9fd2VicGFja19yZXF1aXJlX18pO1xuXG4gXHRcdC8vIEZsYWcgdGhlIG1vZHVsZSBhcyBsb2FkZWRcbiBcdFx0bW9kdWxlLmwgPSB0cnVlO1xuXG4gXHRcdC8vIFJldHVybiB0aGUgZXhwb3J0cyBvZiB0aGUgbW9kdWxlXG4gXHRcdHJldHVybiBtb2R1bGUuZXhwb3J0cztcbiBcdH1cblxuXG4gXHQvLyBleHBvc2UgdGhlIG1vZHVsZXMgb2JqZWN0IChfX3dlYnBhY2tfbW9kdWxlc19fKVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5tID0gbW9kdWxlcztcblxuIFx0Ly8gZXhwb3NlIHRoZSBtb2R1bGUgY2FjaGVcbiBcdF9fd2VicGFja19yZXF1aXJlX18uYyA9IGluc3RhbGxlZE1vZHVsZXM7XG5cbiBcdC8vIGRlZmluZSBnZXR0ZXIgZnVuY3Rpb24gZm9yIGhhcm1vbnkgZXhwb3J0c1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kID0gZnVuY3Rpb24oZXhwb3J0cywgbmFtZSwgZ2V0dGVyKSB7XG4gXHRcdGlmKCFfX3dlYnBhY2tfcmVxdWlyZV9fLm8oZXhwb3J0cywgbmFtZSkpIHtcbiBcdFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgbmFtZSwgeyBlbnVtZXJhYmxlOiB0cnVlLCBnZXQ6IGdldHRlciB9KTtcbiBcdFx0fVxuIFx0fTtcblxuIFx0Ly8gZGVmaW5lIF9fZXNNb2R1bGUgb24gZXhwb3J0c1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5yID0gZnVuY3Rpb24oZXhwb3J0cykge1xuIFx0XHRpZih0eXBlb2YgU3ltYm9sICE9PSAndW5kZWZpbmVkJyAmJiBTeW1ib2wudG9TdHJpbmdUYWcpIHtcbiBcdFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgU3ltYm9sLnRvU3RyaW5nVGFnLCB7IHZhbHVlOiAnTW9kdWxlJyB9KTtcbiBcdFx0fVxuIFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgJ19fZXNNb2R1bGUnLCB7IHZhbHVlOiB0cnVlIH0pO1xuIFx0fTtcblxuIFx0Ly8gY3JlYXRlIGEgZmFrZSBuYW1lc3BhY2Ugb2JqZWN0XG4gXHQvLyBtb2RlICYgMTogdmFsdWUgaXMgYSBtb2R1bGUgaWQsIHJlcXVpcmUgaXRcbiBcdC8vIG1vZGUgJiAyOiBtZXJnZSBhbGwgcHJvcGVydGllcyBvZiB2YWx1ZSBpbnRvIHRoZSBuc1xuIFx0Ly8gbW9kZSAmIDQ6IHJldHVybiB2YWx1ZSB3aGVuIGFscmVhZHkgbnMgb2JqZWN0XG4gXHQvLyBtb2RlICYgOHwxOiBiZWhhdmUgbGlrZSByZXF1aXJlXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnQgPSBmdW5jdGlvbih2YWx1ZSwgbW9kZSkge1xuIFx0XHRpZihtb2RlICYgMSkgdmFsdWUgPSBfX3dlYnBhY2tfcmVxdWlyZV9fKHZhbHVlKTtcbiBcdFx0aWYobW9kZSAmIDgpIHJldHVybiB2YWx1ZTtcbiBcdFx0aWYoKG1vZGUgJiA0KSAmJiB0eXBlb2YgdmFsdWUgPT09ICdvYmplY3QnICYmIHZhbHVlICYmIHZhbHVlLl9fZXNNb2R1bGUpIHJldHVybiB2YWx1ZTtcbiBcdFx0dmFyIG5zID0gT2JqZWN0LmNyZWF0ZShudWxsKTtcbiBcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5yKG5zKTtcbiBcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KG5zLCAnZGVmYXVsdCcsIHsgZW51bWVyYWJsZTogdHJ1ZSwgdmFsdWU6IHZhbHVlIH0pO1xuIFx0XHRpZihtb2RlICYgMiAmJiB0eXBlb2YgdmFsdWUgIT0gJ3N0cmluZycpIGZvcih2YXIga2V5IGluIHZhbHVlKSBfX3dlYnBhY2tfcmVxdWlyZV9fLmQobnMsIGtleSwgZnVuY3Rpb24oa2V5KSB7IHJldHVybiB2YWx1ZVtrZXldOyB9LmJpbmQobnVsbCwga2V5KSk7XG4gXHRcdHJldHVybiBucztcbiBcdH07XG5cbiBcdC8vIGdldERlZmF1bHRFeHBvcnQgZnVuY3Rpb24gZm9yIGNvbXBhdGliaWxpdHkgd2l0aCBub24taGFybW9ueSBtb2R1bGVzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm4gPSBmdW5jdGlvbihtb2R1bGUpIHtcbiBcdFx0dmFyIGdldHRlciA9IG1vZHVsZSAmJiBtb2R1bGUuX19lc01vZHVsZSA/XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0RGVmYXVsdCgpIHsgcmV0dXJuIG1vZHVsZVsnZGVmYXVsdCddOyB9IDpcbiBcdFx0XHRmdW5jdGlvbiBnZXRNb2R1bGVFeHBvcnRzKCkgeyByZXR1cm4gbW9kdWxlOyB9O1xuIFx0XHRfX3dlYnBhY2tfcmVxdWlyZV9fLmQoZ2V0dGVyLCAnYScsIGdldHRlcik7XG4gXHRcdHJldHVybiBnZXR0ZXI7XG4gXHR9O1xuXG4gXHQvLyBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGxcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubyA9IGZ1bmN0aW9uKG9iamVjdCwgcHJvcGVydHkpIHsgcmV0dXJuIE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbChvYmplY3QsIHByb3BlcnR5KTsgfTtcblxuIFx0Ly8gX193ZWJwYWNrX3B1YmxpY19wYXRoX19cbiBcdF9fd2VicGFja19yZXF1aXJlX18ucCA9IFwiXCI7XG5cblxuIFx0Ly8gTG9hZCBlbnRyeSBtb2R1bGUgYW5kIHJldHVybiBleHBvcnRzXG4gXHRyZXR1cm4gX193ZWJwYWNrX3JlcXVpcmVfXyhfX3dlYnBhY2tfcmVxdWlyZV9fLnMgPSAwKTtcbiIsIi8qKiFcbiAqIFNhbGVYcHJlc3NvIFB1YmxpYyBTY3JpcHRzXG4gKlxuICogQGF1dGhvciBTYWxlWHByZXNzbyA8c3VwcG9ydEBzYWxleHByZXNzby5jb20+XG4gKiBAcGFja2FnZSBTYWxlWHByZXNzb1xuICogQHZlcnNpb24gMS4wLjBcbiAqIEBzaW5jZSAxLjAuMFxuICovXG4vLyBpbXBvcnQgXyBmcm9tICdsb2Rhc2gnO1xuKCBmdW5jdGlvbiggJCwgd2luZG93LCBkb2N1bWVudCwgU2FsZVhwcmVzc28sIENvb2tpZXMgKSB7XG5cdFwidXNlIHN0cmljdFwiO1xuXHRcblx0Ly8gSGVscGVyIEZ1bmN0aW9ucy5cblx0XG5cdC8qKlxuXHQgKiBDaGVja3MgaWYgaW5wdXQgaXMgdmFsaWQgZW1haWwuXG5cdCAqXG5cdCAqIEBwYXJhbSBlbWFpbFxuXHQgKiBAcmV0dXJuIHtib29sZWFufVxuXHQgKi9cblx0Y29uc3QgaXNFbWFpbCA9IGVtYWlsID0+IC9eKFthLXpBLVowLTlfListXSkrXFxAKChbYS16QS1aMC05LV0pK1xcLikrKFthLXpBLVowLTldezIsNH0pKyQvLnRlc3QoIGVtYWlsICk7XG5cdFxuXHQvKipcblx0ICogRmluZCBOZXN0ZWQgRWxlbWVudC5cblx0ICogU2FtZSBhcyAkKGVsKWZpbmQoKS5cblx0ICpcblx0ICogQHBhcmFtIHtqUXVlcnl8SFRNTEVsZW1lbnR8U3RyaW5nfSBlbFxuXHQgKiBAcGFyYW0ge2pRdWVyeXxIVE1MRWxlbWVudHxTdHJpbmd9IHNlbGVjdG9yXG5cdCAqIEByZXR1cm4geyp8alF1ZXJ5fEhUTUxFbGVtZW50fVxuXHQgKi9cblx0Y29uc3QgZmluZEVsID0gKCBlbCwgc2VsZWN0b3IgKSA9PiBlbC5maW5kKCBzZWxlY3RvciApO1xuXHRcblx0LyoqXG5cdCAqIEdldCBFbGVtZW50LlxuXHQgKiBBbHRlcm5hdGl2ZSB0byAkKHNlbGVjdG9yKSB3aXRoIHN1cHBvcnQgZm9yIGZpbmQgbmVzdGVkIGVsZW1lbnQuXG5cdCAqXG5cdCAqIEBwYXJhbSB7alF1ZXJ5fEhUTUxFbGVtZW50fEFycmF5fFN0cmluZ30gZWxcblx0ICogQHBhcmFtIHtqUXVlcnl8SFRNTEVsZW1lbnR8U3RyaW5nfSBzZWxlY3RvclxuXHQgKiBAcmV0dXJuIHsqfGpRdWVyeXxIVE1MRWxlbWVudH1cblx0ICovXG5cdGNvbnN0IGdldEVsID0gKCBlbCwgc2VsZWN0b3IgKSA9PiB7XG5cdFx0aWYgKCAnQXJyYXknID09PSBlbC5jb25zdHJ1Y3Rvci5uYW1lICYmIDIgPT09IGVsLmxlbmd0aCApIHtcblx0XHRcdHNlbGVjdG9yID0gZWxbMV07XG5cdFx0XHRlbCA9IGVsWzBdO1xuXHRcdH1cblx0XHRpZiAoICdzdHJpbmcnID09PSB0eXBlb2YgZWwgKSB7XG5cdFx0XHRlbCA9ICQoIGVsICk7XG5cdFx0fVxuXHRcdGlmICggc2VsZWN0b3IgJiYgJ3N0cmluZycgPT09IHR5cGVvZiBzZWxlY3RvciApIHtcblx0XHRcdHJldHVybiBmaW5kRWwoIGVsLCBzZWxlY3RvciApO1xuXHRcdH1cblx0XHRyZXR1cm4gZWw7XG5cdH07XG5cdFxuXHQvKipcblx0ICogR2V0IENsb3Nlc3QgRWxlbWVudC5cblx0ICogU2FtZSBhcyAkKGVsKS5jbG9zZXN0KCkuXG5cdCAqXG5cdCAqIEBwYXJhbSB7alF1ZXJ5fEhUTUxFbGVtZW50fEFycmF5fFN0cmluZ30gZWxcblx0ICogQHBhcmFtIHtqUXVlcnl8SFRNTEVsZW1lbnR8U3RyaW5nfSBzZWxlY3RvclxuXHQgKiBAcmV0dXJuIHsqfGpRdWVyeXxIVE1MRWxlbWVudHxudWxsfEVsZW1lbnR9XG5cdCAqL1xuXHRjb25zdCBjbG9zZXN0RWwgPSAoIGVsLCBzZWxlY3RvciApID0+IHtcblx0XHRyZXR1cm4gZ2V0RWwoIGVsICkuY2xvc2VzdCggc2VsZWN0b3IgKTtcblx0fVxuXHRcblx0LyoqXG5cdCAqIEdldCBFbGVtZW50IFZhbHVlLlxuXHQgKiAkKGVsKS52YWwoKVxuXHQgKiBAcGFyYW0ge2pRdWVyeXxIVE1MRWxlbWVudHxBcnJheXxTdHJpbmd9IGVsXG5cdCAqIEBwYXJhbSB7alF1ZXJ5fEhUTUxFbGVtZW50fFN0cmluZ30gc2VsZWN0b3Jcblx0ICogQHJldHVybiB7Kn1cblx0ICovXG5cdGNvbnN0IGVsVmFsID0gKCBlbCwgc2VsZWN0b3IgKSA9PiBnZXRFbCggZWwsIHNlbGVjdG9yICkudmFsKCk7XG5cdFxuXHQvKipcblx0ICogR2V0IEVsZW1lbnQgRGF0YSBhdHRyaWJ1dGVzLlxuXHQgKiAkKGVsKS5kYXRhKClcblx0ICpcblx0ICogQHBhcmFtIHtqUXVlcnl8SFRNTEVsZW1lbnR8QXJyYXl8U3RyaW5nfSBzZWxlY3RvclxuXHQgKiBAcGFyYW0ge1N0cmluZ30gZGF0YVxuXHQgKiBAcmV0dXJuIHsqfVxuXHQgKi9cblx0Y29uc3QgZWxEYXRhID0gKCBzZWxlY3RvciwgZGF0YSApID0+IGdldEVsKCBzZWxlY3RvciApLmRhdGEoIGRhdGEgKTtcblx0XG5cdC8vIER5YW1pYyBPcHRpb25zLlxuXHRjb25zdCB7XG5cdFx0X3dwbm9uY2UsXG5cdFx0Z2Rwcixcblx0XHRhY190aW1lb3V0LFxuXHRcdG1lc3NhZ2VzOiB7IGNhcnRfZW1haWxfZ2Rwciwgbm9fdGhhbmtzIH0sXG5cdH0gPSBTYWxlWHByZXNzbztcblx0Ly8gQFRPRE8gaGFuZGxlIEdEUFIuXG5cdFxuXHQvKipcblx0ICogQWJhbmRvbiBDYXJ0LlxuXHQgKiBTYXZlIENhcnQgRGF0YSBpbiBjYXNlIG9mIHVzZXIgZGlkbid0IGNvbXBsZXRlIHRoZSBjaGVja291dC5cblx0ICogXG5cdCAqIEBjbGFzcyBTYWxlWHByZXNzb0FiYW5kb25DYXJ0XG5cdCAqL1xuXHRjbGFzcyBTYWxlWHByZXNzb0FiYW5kb25DYXJ0IHtcblx0XHQvKipcblx0XHQgKiBDb25zdHJ1Y3RvclxuXHRcdCAqL1xuXHRcdGNvbnN0cnVjdG9yICgpIHtcblx0XHRcdHRoaXMuX2dkcHIgPSBnZHByO1xuXHRcdFx0dGhpcy5fdHlwaW5nVGltZXI7XG5cdFx0XHR0aGlzLl9pbnB1dF9pZHMgPSAnZW1haWwsZmlyc3RfbmFtZSxsYXN0X25hbWUsY29tcGFueSxjb3VudHJ5LGFkZHJlc3NfMSxhZGRyZXNzXzIsY2l0eSxzdGF0ZSxwb3N0Y29kZSxwaG9uZSxjb21tZW50cycuc3BsaXQoICcsJyApO1xuXHRcdFx0dGhpcy5fZG9uZVR5cGluZ0ludGVydmFsID0gNTAwO1xuXHRcdFx0dGhpcy5fYmlsbGluZyA9ICdiaWxsaW5nJztcblx0XHRcdC8vIHRoaXMuX29sZERhdGEgPSBcIlwiO1xuXHRcdFx0dGhpcy5faW5pdCgpO1xuXHRcdH1cblx0XHRcblx0XHQvKipcblx0XHQgKiBJbml0aWFsaXplLlxuXHRcdCAqXG5cdFx0ICogQHByaXZhdGVcblx0XHQgKi9cblx0XHRfaW5pdCgpIHtcblx0XHRcdGNvbnN0IHNlbGYgPSB0aGlzO1xuXHRcdFx0Y29uc3Qgc2VsZWN0b3JzID0gJ2lucHV0LCBzZWxlY3QsIHRleHRhcmVhJztcblx0XHRcdCQoZG9jdW1lbnQpLm9uKCAna2V5dXAga2V5cHJlc3MgY2hhbmdlIGJsdXInLCBzZWxlY3RvcnMsIHNlbGYuX3NhdmVEYXRhLmJpbmQoIHNlbGYgKSApO1xuXHRcdFx0JChkb2N1bWVudCkub24oICdrZXlkb3duJywgc2VsZWN0b3JzLCBzZWxmLl9jbGVhclRoZUNvdW50RG93bi5iaW5kKCBzZWxmICkgKTtcblx0XHRcdCQoZG9jdW1lbnQpLm9uKCAncmVhZHknLCBzZWxmLl9hdXRvZmlsX2NoZWNrb3V0LmJpbmQoIHNlbGYgKSApO1xuXHRcdFx0c2V0VGltZW91dCggKCkgPT4ge1xuXHRcdFx0XHRzZWxmLl9zYXZlRGF0YSgpO1xuXHRcdFx0fSwgNzUwICk7XG5cdFx0fVxuXHRcdFxuXHRcdF9hdXRvZmlsX2NoZWNrb3V0KCkge1xuXHRcdFx0Y29uc3Qgc2VsZiA9IHRoaXM7XG5cdFx0XHRpZiAoICQoJ2JvZHknKS5oYXNDbGFzcygnbG9nZ2VkLWluJykgKSB7XG5cdFx0XHRcdHJldHVybjtcblx0XHRcdH1cblx0XHRcdGZvciAoIGNvbnN0IGsgb2Ygc2VsZi5faW5wdXRfaWRzICkge1xuXHRcdFx0XHRpZiAoIGsgKSB7XG5cdFx0XHRcdFx0bGV0IGlkID0gYCR7c2VsZi5fYmlsbGluZ31fJHtrfWA7XG5cdFx0XHRcdFx0aWYgKCAnY29tbWVudHMnID09PSBrICkge1xuXHRcdFx0XHRcdFx0aWQgPSBgb3JkZXJfJHtrfWA7XG5cdFx0XHRcdFx0fVxuXHRcdFx0XHRcdCQoYCMke2lkfWApLnZhbCggQ29va2llcy5nZXQoIGBzeHBfYWNfJHtrfWAgKSApO1xuXHRcdFx0XHR9XG5cdFx0XHR9XG5cdFx0fVxuXHRcdFxuXHRcdC8qKlxuXHRcdCAqIFNhdmUgVXNlciBEYXRhLlxuXHRcdCAqIERlYnVuY2VkIHdpdGggc2V0VGltZW91dFxuXHRcdCAqXG5cdFx0ICogQHBhcmFtIHtFdmVudH0gZXZlbnRcblx0XHQgKlxuXHRcdCAqIEBwcml2YXRlXG5cdFx0ICovXG5cdFx0X3NhdmVEYXRhKCBldmVudCApIHtcblx0XHRcdGNvbnN0IHNlbGYgPSB0aGlzO1xuXHRcdFx0c2VsZi5fY2xlYXJUaGVDb3VudERvd24oKTtcblx0XHRcdHNlbGYuX3R5cGluZ1RpbWVyID0gc2V0VGltZW91dCggKCkgPT4ge1xuXHRcdFx0XHRjb25zdCBlbWFpbCA9ICQoIGAjJHtzZWxmLl9iaWxsaW5nfV9lbWFpbGAgKS52YWwoKSB8fCAnJztcblx0XHRcdFx0aWYgKCBpc0VtYWlsKCBlbWFpbCApICkge1xuXHRcdFx0XHRcdGxldCBkYXRhID0geyBfd3Bub25jZSwgZW1haWwgfTtcblx0XHRcdFx0XHRmb3IgKCBjb25zdCBrIG9mIHRoaXMuX2lucHV0X2lkcyApIHtcblx0XHRcdFx0XHRcdGlmICggayApIHtcblx0XHRcdFx0XHRcdFx0bGV0IGlkID0gYGJpbGxpbmdfJHtrfWA7XG5cdFx0XHRcdFx0XHRcdGlmICggJ2NvbW1lbnRzJyA9PT0gayApIHtcblx0XHRcdFx0XHRcdFx0XHRpZCA9IGBvcmRlcl8ke2t9YDtcblx0XHRcdFx0XHRcdFx0fVxuXHRcdFx0XHRcdFx0XHRjb25zdCBfX1ZBTFVFX18gPSAkKGAjJHtpZH1gKS52YWwoKSB8fCAnJztcblx0XHRcdFx0XHRcdFx0ZGF0YVtrXSA9IF9fVkFMVUVfXztcblx0XHRcdFx0XHRcdFx0Q29va2llcy5zZXQoIGBzeHBfYWNfJHtrfWAsIF9fVkFMVUVfXywgeyBleHBpcmVzOiBwYXJzZUludCggYWNfdGltZW91dCApIH0gKTtcblx0XHRcdFx0XHRcdH1cblx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0Y29uc29sZS5sb2coIHtkYXRhLCBldmVudH0gKTtcblx0XHRcdFx0XHQvLyBjb25zdCBoYXNoID0gSlNPTi5zdHJpbmdpZnkoIGRhdGEgKTtcblx0XHRcdFx0XHQvLyBpZiAoIHNlbGYuX29sZERhdGEgIT09IGhhc2ggKSB7XG5cdFx0XHRcdFx0Ly8gXHRzZWxmLl9vbGREYXRhID0gaGFzaDsgLy8gcmVkdWNlIGJhY2tlbmQgY2FsbC5cblx0XHRcdFx0XHQvLyBcdHdwLmFqYXgucG9zdCggJ3N4cF9zYXZlX2FiYW5kb25fY2FydF9kYXRhJywgZGF0YSApO1xuXHRcdFx0XHRcdC8vIH1cblx0XHRcdFx0XHR3cC5hamF4LnBvc3QoICdzeHBfc2F2ZV9hYmFuZG9uX2NhcnRfZGF0YScsIGRhdGEgKTtcblx0XHRcdFx0fVxuXHRcdFx0fSwgdGhpcy5fZG9uZVR5cGluZ0ludGVydmFsICk7XG5cdFx0fVxuXHRcdFxuXHRcdC8qKlxuXHRcdCAqIENsZWFyIFRpbWVyLlxuXHRcdCAqXG5cdFx0ICogQHByaXZhdGVcblx0XHQgKi9cblx0XHRfY2xlYXJUaGVDb3VudERvd24oKSB7XG5cdFx0XHRpZiAoIHRoaXMuX3R5cGluZ1RpbWVyICkge1xuXHRcdFx0XHRjbGVhclRpbWVvdXQoIHRoaXMuX3R5cGluZ1RpbWVyICk7XG5cdFx0XHR9XG5cdFx0fVxuXHR9XG5cdFxuXHRuZXcgU2FsZVhwcmVzc29BYmFuZG9uQ2FydCgpO1xuXHRcblx0JCggZG9jdW1lbnQgKS5vbiggJ3JlYWR5JywgZnVuY3Rpb24oKSB7XG5cdFx0JCggZG9jdW1lbnQgKVxuXHRcdFx0Ly8gQWRkIHRvIGNhdCBvbiBzaW5nbGUgcHJvZHVjdCBwYWdlLlxuXHRcdFx0Lm9uKCAnY2xpY2snLCAnLnNpbmdsZV9hZGRfdG9fY2FydF9idXR0b24nLCBmdW5jdGlvbiAoKSB7XG5cdFx0XHRcdGNvbnN0IGVsID0gJCggdGhpcyApO1xuXHRcdFx0XHRjb25zdCBmb3JtID0gJCggdGhpcyApLmNsb3Nlc3QoICdmb3JtLmNhcnQnICk7XG5cdFx0XHRcdGNvbnN0IHF0eUVsID0gJCggJ1tuYW1lPVwicXVhbnRpdHlcIl0nICk7XG5cdFx0XHRcdGxldCBxdHkgPSAxO1xuXHRcdFx0XHRpZiAoIHF0eUVsLmxlbmd0aCApIHtcblx0XHRcdFx0XHRxdHkgPSBxdHlFbC52YWwoKTtcblx0XHRcdFx0fVxuXHRcdFx0XHRsZXQgZGF0YSA9IFsgeyBsYWJlbDogJ3F1YW50aXR5JywgdmFsdWU6IHF0eSB9IF07XG5cdFx0XHRcdGlmICggZm9ybS5oYXNDbGFzcyggJ3ZhcmlhdGlvbnNfZm9ybScgKSApIHtcblx0XHRcdFx0XHRkYXRhLnB1c2goIHsgbGFiZWw6ICdwcm9kdWN0X2lkJywgdmFsdWU6IGVsVmFsKCBmb3JtLCAnW25hbWU9XCJwcm9kdWN0X2lkXCJdJyApIH0gKTtcblx0XHRcdFx0XHRkYXRhLnB1c2goIHsgbGFiZWw6ICd2YXJpYXRpb25faWQnLCB2YWx1ZTogZWxWYWwoIGZvcm0sICdbbmFtZT1cInZhcmlhdGlvbl9pZFwiXScgKSB9ICk7XG5cdFx0XHRcdH0gZWxzZSB7XG5cdFx0XHRcdFx0ZGF0YS5wdXNoKCB7IGxhYmVsOiAncHJvZHVjdF9pZCcsIHZhbHVlOiBlbFZhbCggZWwgKSB9ICk7XG5cdFx0XHRcdH1cblx0XHRcdFx0XG5cdFx0XHRcdHN4cEV2ZW50KCAnYWRkLXRvLWNhcnQnLCBkYXRhICk7XG5cdFx0XHR9IClcblx0XHRcdC8vIEFkZCB0byBjYXQgb24gcHJvZHVjdCBhcmNoaXZlIHBhZ2UuXG5cdFx0XHQub24oICdjbGljaycsICcuYWRkX3RvX2NhcnRfYnV0dG9uJywgZnVuY3Rpb24gKCkge1xuXHRcdFx0XHRjb25zdCBlbCA9ICQoIHRoaXMgKTtcblx0XHRcdFx0c3hwRXZlbnQoICdhZGQtdG8tY2FydCcsIFtcblx0XHRcdFx0XHR7IGxhYmVsOiAncHJvZHVjdF9pZCcsIHZhbHVlOiBlbERhdGEoIGVsLCAncHJvZHVjdF9pZCcgKSB9LFxuXHRcdFx0XHRcdHsgbGFiZWw6ICdxdWFudGl0eScsIHZhbHVlOiBlbERhdGEoIGVsLCAncXVhbnRpdHkgJykgfSxcblx0XHRcdFx0XSApO1xuXHRcdFx0fSApXG5cdFx0XHQub24oICdjbGljaycsICcud29vY29tbWVyY2UtY2FydC1mb3JtIC5wcm9kdWN0LXJlbW92ZSA+IGEnLCBmdW5jdGlvbiAoKSB7XG5cdFx0XHRcdGNvbnN0IGVsID0gJCggdGhpcyApO1xuXHRcdFx0XHRzeHBFdmVudCggJ3JlbW92ZS1mcm9tLWNhcnQnLCBbIHsgbGFiZWw6ICdwcm9kdWN0X2lkJywgdmFsdWU6IGVsRGF0YSggZWwsICdwcm9kdWN0X2lkJyApIH0gXSApO1xuXHRcdFx0fSApXG5cdFx0XHQub24oICdjbGljaycsICcud29vY29tbWVyY2UtY2FydCAucmVzdG9yZS1pdGVtJywgZnVuY3Rpb24gKCkge1xuXHRcdFx0XHRzeHBFdmVudCggJ3VuZG8tcmVtb3ZlLWZyb20tY2FydCcgKTtcblx0XHRcdH0gKTtcblx0XHQvLyBDYXB0dXJlIHN1Y2Nlc3NmdWxsIGNoZWNrb3V0LlxuXHRcdGNvbnN0IGNoZWNrb3V0Rm9ybSA9ICQoICdmb3JtLmNoZWNrb3V0JyApO1xuXHRcdGNoZWNrb3V0Rm9ybS5vbiggJ2NoZWNrb3V0X3BsYWNlX29yZGVyX3N1Y2Nlc3MnLCBmdW5jdGlvbiAoKSB7XG5cdFx0XHRjb25zdCBkYXRhID0gWyB7XG5cdFx0XHRcdGxhYmVsOiAnZ2F0ZXdheV9pZCcsXG5cdFx0XHRcdHZhbHVlOiBlbFZhbCggJ1tuYW1lPVwicGF5bWVudF9tZXRob2RcIl06Y2hlY2tlZCcgKSxcblx0XHRcdH0sIHtcblx0XHRcdFx0bGFiZWw6ICd0b3RhbCcsXG5cdFx0XHRcdHZhbHVlOiBwYXJzZUZsb2F0KCAkKCcub3JkZXItdG90YWwnKS50ZXh0KCkucmVwbGFjZSggL1teXFxkLl0vZ20sICcnICkgKS50b0ZpeGVkKDIpLFxuXHRcdFx0fSBdO1xuXHRcdFx0c3hwRXZlbnQoICdjaGVja291dC1jb21wbGV0ZWQnLCBkYXRhICk7XG5cdFx0fSApO1xuXHR9ICk7XG59KCBqUXVlcnksIHdpbmRvdywgZG9jdW1lbnQsIFNhbGVYcHJlc3NvLCBDb29raWVzICkgKTtcbiIsImV4cG9ydCBkZWZhdWx0IF9fd2VicGFja19wdWJsaWNfcGF0aF9fICsgXCIuL2Fzc2V0cy9jc3Mvc3R5bGVzLmNzc1wiOyIsIm1vZHVsZS5leHBvcnRzID0galF1ZXJ5OyJdLCJzb3VyY2VSb290IjoiIn0=