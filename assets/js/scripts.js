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
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* WEBPACK VAR INJECTION */(function(jQuery) {/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_0__);
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


(function ($, window, document, SaleXpresso) {
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


  var gdpr = SaleXpresso.gdpr,
      _SaleXpresso$messages = SaleXpresso.messages,
      cart_email_gdpr = _SaleXpresso$messages.cart_email_gdpr,
      no_thanks = _SaleXpresso$messages.no_thanks; // @TODO handle GDPR.

  /**
   * Abundant Cart.
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
      this._doneTypingInterval = 500;
      this._oldData = {};

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
            var firstName = $("#billing_first_name").val() || '';
            var lastName = $("#billing_last_name").val() || '';
            var phone = $("#billing_phone").val() || '';
            var data = {
              email: email,
              firstName: firstName,
              lastName: lastName,
              phone: phone
            };

            if (!lodash__WEBPACK_IMPORTED_MODULE_0___default.a.isEqual(data, self._oldData)) {
              self._oldData = data; // reduce backend call.

              wp.ajax.post('sxp_save_checkout', {
                email: email,
                firstName: firstName,
                lastName: lastName
              });
            }
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

    $('form.checkout').on('checkout_place_order_success', function () {
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
})(jQuery, window, document, SaleXpresso);
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

/***/ }),

/***/ "lodash":
/*!*************************!*\
  !*** external "lodash" ***!
  \*************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = lodash;

/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vLy4vc3JjL2pzL3NjcmlwdHMuanMiLCJ3ZWJwYWNrOi8vLy4vc3JjL3Njc3Mvc3R5bGVzLnNjc3MiLCJ3ZWJwYWNrOi8vL2V4dGVybmFsIFwialF1ZXJ5XCIiLCJ3ZWJwYWNrOi8vL2V4dGVybmFsIFwibG9kYXNoXCIiXSwibmFtZXMiOlsiJCIsIndpbmRvdyIsImRvY3VtZW50IiwiU2FsZVhwcmVzc28iLCJpc0VtYWlsIiwiZW1haWwiLCJ0ZXN0IiwiZmluZEVsIiwiZWwiLCJzZWxlY3RvciIsImZpbmQiLCJnZXRFbCIsImNvbnN0cnVjdG9yIiwibmFtZSIsImxlbmd0aCIsImNsb3Nlc3RFbCIsImNsb3Nlc3QiLCJlbFZhbCIsInZhbCIsImVsRGF0YSIsImRhdGEiLCJnZHByIiwibWVzc2FnZXMiLCJjYXJ0X2VtYWlsX2dkcHIiLCJub190aGFua3MiLCJTYWxlWHByZXNzb0NhcHR1cmVVc2VyRGF0YSIsIl9nZHByIiwiX3R5cGluZ1RpbWVyIiwiX2RvbmVUeXBpbmdJbnRlcnZhbCIsIl9vbGREYXRhIiwiX2luaXQiLCJzZWxmIiwib24iLCJfc2F2ZURhdGEiLCJiaW5kIiwiX2NsZWFyVGhlQ291bnREb3duIiwic2V0VGltZW91dCIsImV2ZW50IiwiZmlyc3ROYW1lIiwibGFzdE5hbWUiLCJwaG9uZSIsIl8iLCJpc0VxdWFsIiwid3AiLCJhamF4IiwicG9zdCIsImNsZWFyVGltZW91dCIsImZvcm0iLCJxdHlFbCIsInF0eSIsImxhYmVsIiwidmFsdWUiLCJoYXNDbGFzcyIsInB1c2giLCJzeHBFdmVudCIsInBhcnNlRmxvYXQiLCJ0ZXh0IiwicmVwbGFjZSIsInRvRml4ZWQiLCJqUXVlcnkiXSwibWFwcGluZ3MiOiI7UUFBQTtRQUNBOztRQUVBO1FBQ0E7O1FBRUE7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7O1FBRUE7UUFDQTs7UUFFQTtRQUNBOztRQUVBO1FBQ0E7UUFDQTs7O1FBR0E7UUFDQTs7UUFFQTtRQUNBOztRQUVBO1FBQ0E7UUFDQTtRQUNBLDBDQUEwQyxnQ0FBZ0M7UUFDMUU7UUFDQTs7UUFFQTtRQUNBO1FBQ0E7UUFDQSx3REFBd0Qsa0JBQWtCO1FBQzFFO1FBQ0EsaURBQWlELGNBQWM7UUFDL0Q7O1FBRUE7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBLHlDQUF5QyxpQ0FBaUM7UUFDMUUsZ0hBQWdILG1CQUFtQixFQUFFO1FBQ3JJO1FBQ0E7O1FBRUE7UUFDQTtRQUNBO1FBQ0EsMkJBQTJCLDBCQUEwQixFQUFFO1FBQ3ZELGlDQUFpQyxlQUFlO1FBQ2hEO1FBQ0E7UUFDQTs7UUFFQTtRQUNBLHNEQUFzRCwrREFBK0Q7O1FBRXJIO1FBQ0E7OztRQUdBO1FBQ0E7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7QUNsRkE7Ozs7Ozs7O0FBUUE7O0FBQ0UsV0FBVUEsQ0FBVixFQUFhQyxNQUFiLEVBQXFCQyxRQUFyQixFQUErQkMsV0FBL0IsRUFBNkM7QUFDOUMsZUFEOEMsQ0FHOUM7O0FBRUE7Ozs7Ozs7QUFNQSxNQUFNQyxPQUFPLEdBQUcsU0FBVkEsT0FBVSxDQUFBQyxLQUFLO0FBQUEsV0FBSSxnRUFBZ0VDLElBQWhFLENBQXNFRCxLQUF0RSxDQUFKO0FBQUEsR0FBckI7QUFFQTs7Ozs7Ozs7OztBQVFBLE1BQU1FLE1BQU0sR0FBRyxTQUFUQSxNQUFTLENBQUVDLEVBQUYsRUFBTUMsUUFBTjtBQUFBLFdBQW9CRCxFQUFFLENBQUNFLElBQUgsQ0FBU0QsUUFBVCxDQUFwQjtBQUFBLEdBQWY7QUFFQTs7Ozs7Ozs7OztBQVFBLE1BQU1FLEtBQUssR0FBRyxTQUFSQSxLQUFRLENBQUVILEVBQUYsRUFBTUMsUUFBTixFQUFvQjtBQUNqQyxRQUFLLFlBQVlELEVBQUUsQ0FBQ0ksV0FBSCxDQUFlQyxJQUEzQixJQUFtQyxNQUFNTCxFQUFFLENBQUNNLE1BQWpELEVBQTBEO0FBQ3pETCxjQUFRLEdBQUdELEVBQUUsQ0FBQyxDQUFELENBQWI7QUFDQUEsUUFBRSxHQUFHQSxFQUFFLENBQUMsQ0FBRCxDQUFQO0FBQ0E7O0FBQ0QsUUFBSyxhQUFhLE9BQU9BLEVBQXpCLEVBQThCO0FBQzdCQSxRQUFFLEdBQUdSLENBQUMsQ0FBRVEsRUFBRixDQUFOO0FBQ0E7O0FBQ0QsUUFBS0MsUUFBUSxJQUFJLGFBQWEsT0FBT0EsUUFBckMsRUFBZ0Q7QUFDL0MsYUFBT0YsTUFBTSxDQUFFQyxFQUFGLEVBQU1DLFFBQU4sQ0FBYjtBQUNBOztBQUNELFdBQU9ELEVBQVA7QUFDQSxHQVpEO0FBY0E7Ozs7Ozs7Ozs7QUFRQSxNQUFNTyxTQUFTLEdBQUcsU0FBWkEsU0FBWSxDQUFFUCxFQUFGLEVBQU1DLFFBQU4sRUFBb0I7QUFDckMsV0FBT0UsS0FBSyxDQUFFSCxFQUFGLENBQUwsQ0FBWVEsT0FBWixDQUFxQlAsUUFBckIsQ0FBUDtBQUNBLEdBRkQ7QUFJQTs7Ozs7Ozs7O0FBT0EsTUFBTVEsS0FBSyxHQUFHLFNBQVJBLEtBQVEsQ0FBRVQsRUFBRixFQUFNQyxRQUFOO0FBQUEsV0FBb0JFLEtBQUssQ0FBRUgsRUFBRixFQUFNQyxRQUFOLENBQUwsQ0FBc0JTLEdBQXRCLEVBQXBCO0FBQUEsR0FBZDtBQUVBOzs7Ozs7Ozs7O0FBUUEsTUFBTUMsTUFBTSxHQUFHLFNBQVRBLE1BQVMsQ0FBRVYsUUFBRixFQUFZVyxJQUFaO0FBQUEsV0FBc0JULEtBQUssQ0FBRUYsUUFBRixDQUFMLENBQWtCVyxJQUFsQixDQUF3QkEsSUFBeEIsQ0FBdEI7QUFBQSxHQUFmLENBMUU4QyxDQTRFOUM7OztBQTVFOEMsTUE4RTdDQyxJQTlFNkMsR0FnRjFDbEIsV0FoRjBDLENBOEU3Q2tCLElBOUU2QztBQUFBLDhCQWdGMUNsQixXQWhGMEMsQ0ErRTdDbUIsUUEvRTZDO0FBQUEsTUErRWpDQyxlQS9FaUMseUJBK0VqQ0EsZUEvRWlDO0FBQUEsTUErRWhCQyxTQS9FZ0IseUJBK0VoQkEsU0EvRWdCLEVBaUY5Qzs7QUFFQTs7Ozs7OztBQW5GOEMsTUF5RnhDQywwQkF6RndDO0FBMEY3Qzs7O0FBR0EsMENBQWU7QUFBQTs7QUFDZCxXQUFLQyxLQUFMLEdBQWFMLElBQWI7QUFDQSxXQUFLTSxZQUFMO0FBQ0EsV0FBS0MsbUJBQUwsR0FBMkIsR0FBM0I7QUFDQSxXQUFLQyxRQUFMLEdBQWdCLEVBQWhCOztBQUNBLFdBQUtDLEtBQUw7QUFDQTtBQUVEOzs7Ozs7O0FBckc2QztBQUFBO0FBQUEsOEJBMEdyQztBQUNQLFlBQU1DLElBQUksR0FBRyxJQUFiO0FBQ0EvQixTQUFDLENBQUNFLFFBQUQsQ0FBRCxDQUFZOEIsRUFBWixDQUFnQiw0QkFBaEIsRUFBOEMsT0FBOUMsRUFBdURELElBQUksQ0FBQ0UsU0FBTCxDQUFlQyxJQUFmLENBQXFCSCxJQUFyQixDQUF2RDtBQUNBL0IsU0FBQyxDQUFDRSxRQUFELENBQUQsQ0FBWThCLEVBQVosQ0FBZ0IsU0FBaEIsRUFBMkIsT0FBM0IsRUFBb0NELElBQUksQ0FBQ0ksa0JBQUwsQ0FBd0JELElBQXhCLENBQThCSCxJQUE5QixDQUFwQztBQUNBSyxrQkFBVSxDQUFFLFlBQU07QUFDakJMLGNBQUksQ0FBQ0UsU0FBTDtBQUNBLFNBRlMsRUFFUCxHQUZPLENBQVY7QUFHQTtBQUVEOzs7Ozs7Ozs7QUFuSDZDO0FBQUE7QUFBQSxnQ0EySGxDSSxLQTNIa0MsRUEySDFCO0FBQ2xCLFlBQU1OLElBQUksR0FBRyxJQUFiOztBQUNBQSxZQUFJLENBQUNJLGtCQUFMOztBQUNBSixZQUFJLENBQUNKLFlBQUwsR0FBb0JTLFVBQVUsQ0FBRSxZQUFNO0FBQ3JDLGNBQU0vQixLQUFLLEdBQUdMLENBQUMsQ0FBRSxnQkFBRixDQUFELENBQXNCa0IsR0FBdEIsTUFBK0IsRUFBN0M7O0FBQ0EsY0FBS2QsT0FBTyxDQUFFQyxLQUFGLENBQVosRUFBd0I7QUFDdkIsZ0JBQU1pQyxTQUFTLEdBQUd0QyxDQUFDLENBQUMscUJBQUQsQ0FBRCxDQUF5QmtCLEdBQXpCLE1BQWtDLEVBQXBEO0FBQ0EsZ0JBQU1xQixRQUFRLEdBQUd2QyxDQUFDLENBQUMsb0JBQUQsQ0FBRCxDQUF3QmtCLEdBQXhCLE1BQWlDLEVBQWxEO0FBQ0EsZ0JBQU1zQixLQUFLLEdBQUd4QyxDQUFDLENBQUMsZ0JBQUQsQ0FBRCxDQUFvQmtCLEdBQXBCLE1BQTZCLEVBQTNDO0FBQ0EsZ0JBQU1FLElBQUksR0FBRztBQUFFZixtQkFBSyxFQUFMQSxLQUFGO0FBQVNpQyx1QkFBUyxFQUFUQSxTQUFUO0FBQW9CQyxzQkFBUSxFQUFSQSxRQUFwQjtBQUE4QkMsbUJBQUssRUFBTEE7QUFBOUIsYUFBYjs7QUFDQSxnQkFBSyxDQUFFQyw2Q0FBQyxDQUFDQyxPQUFGLENBQVd0QixJQUFYLEVBQWlCVyxJQUFJLENBQUNGLFFBQXRCLENBQVAsRUFBMEM7QUFDekNFLGtCQUFJLENBQUNGLFFBQUwsR0FBZ0JULElBQWhCLENBRHlDLENBQ25COztBQUN0QnVCLGdCQUFFLENBQUNDLElBQUgsQ0FBUUMsSUFBUixDQUFjLG1CQUFkLEVBQW1DO0FBQUV4QyxxQkFBSyxFQUFMQSxLQUFGO0FBQVNpQyx5QkFBUyxFQUFUQSxTQUFUO0FBQW9CQyx3QkFBUSxFQUFSQTtBQUFwQixlQUFuQztBQUNBO0FBQ0Q7QUFDRCxTQVo2QixFQVkzQixLQUFLWCxtQkFac0IsQ0FBOUI7QUFhQTtBQUVEOzs7Ozs7QUE3STZDO0FBQUE7QUFBQSwyQ0FrSnhCO0FBQ3BCLFlBQUssS0FBS0QsWUFBVixFQUF5QjtBQUN4Qm1CLHNCQUFZLENBQUUsS0FBS25CLFlBQVAsQ0FBWjtBQUNBO0FBQ0Q7QUF0SjRDOztBQUFBO0FBQUE7O0FBeUo5QyxNQUFJRiwwQkFBSjtBQUVBekIsR0FBQyxDQUFFRSxRQUFGLENBQUQsQ0FBYzhCLEVBQWQsQ0FBa0IsT0FBbEIsRUFBMkIsWUFBVztBQUVyQ2hDLEtBQUMsQ0FBRUUsUUFBRixDQUFELENBQ0U7QUFERixLQUVHOEIsRUFGSCxDQUVPLE9BRlAsRUFFZ0IsNEJBRmhCLEVBRThDLFlBQVk7QUFDeEQsVUFBTXhCLEVBQUUsR0FBR1IsQ0FBQyxDQUFFLElBQUYsQ0FBWjtBQUNBLFVBQU0rQyxJQUFJLEdBQUcvQyxDQUFDLENBQUUsSUFBRixDQUFELENBQVVnQixPQUFWLENBQW1CLFdBQW5CLENBQWI7QUFDQSxVQUFNZ0MsS0FBSyxHQUFHaEQsQ0FBQyxDQUFFLG1CQUFGLENBQWY7QUFDQSxVQUFJaUQsR0FBRyxHQUFHLENBQVY7O0FBQ0EsVUFBS0QsS0FBSyxDQUFDbEMsTUFBWCxFQUFvQjtBQUNuQm1DLFdBQUcsR0FBR0QsS0FBSyxDQUFDOUIsR0FBTixFQUFOO0FBQ0E7O0FBQ0QsVUFBSUUsSUFBSSxHQUFHLENBQUU7QUFBRThCLGFBQUssRUFBRSxVQUFUO0FBQXFCQyxhQUFLLEVBQUVGO0FBQTVCLE9BQUYsQ0FBWDs7QUFDQSxVQUFLRixJQUFJLENBQUNLLFFBQUwsQ0FBZSxpQkFBZixDQUFMLEVBQTBDO0FBQ3pDaEMsWUFBSSxDQUFDaUMsSUFBTCxDQUFXO0FBQUVILGVBQUssRUFBRSxZQUFUO0FBQXVCQyxlQUFLLEVBQUVsQyxLQUFLLENBQUU4QixJQUFGLEVBQVEscUJBQVI7QUFBbkMsU0FBWDtBQUNBM0IsWUFBSSxDQUFDaUMsSUFBTCxDQUFXO0FBQUVILGVBQUssRUFBRSxjQUFUO0FBQXlCQyxlQUFLLEVBQUVsQyxLQUFLLENBQUU4QixJQUFGLEVBQVEsdUJBQVI7QUFBckMsU0FBWDtBQUNBLE9BSEQsTUFHTztBQUNOM0IsWUFBSSxDQUFDaUMsSUFBTCxDQUFXO0FBQUVILGVBQUssRUFBRSxZQUFUO0FBQXVCQyxlQUFLLEVBQUVsQyxLQUFLLENBQUVULEVBQUY7QUFBbkMsU0FBWDtBQUNBOztBQUVEOEMsY0FBUSxDQUFFLGFBQUYsRUFBaUJsQyxJQUFqQixDQUFSO0FBQ0EsS0FuQkYsRUFvQkM7QUFwQkQsS0FxQkVZLEVBckJGLENBcUJNLE9BckJOLEVBcUJlLHFCQXJCZixFQXFCc0MsWUFBWTtBQUNoRCxVQUFNeEIsRUFBRSxHQUFHUixDQUFDLENBQUUsSUFBRixDQUFaO0FBQ0FzRCxjQUFRLENBQUUsYUFBRixFQUFpQixDQUN4QjtBQUFFSixhQUFLLEVBQUUsWUFBVDtBQUF1QkMsYUFBSyxFQUFFaEMsTUFBTSxDQUFFWCxFQUFGLEVBQU0sWUFBTjtBQUFwQyxPQUR3QixFQUV4QjtBQUFFMEMsYUFBSyxFQUFFLFVBQVQ7QUFBcUJDLGFBQUssRUFBRWhDLE1BQU0sQ0FBRVgsRUFBRixFQUFNLFdBQU47QUFBbEMsT0FGd0IsQ0FBakIsQ0FBUjtBQUlBLEtBM0JGLEVBNEJFd0IsRUE1QkYsQ0E0Qk0sT0E1Qk4sRUE0QmUsNENBNUJmLEVBNEI2RCxZQUFZO0FBQ3ZFLFVBQU14QixFQUFFLEdBQUdSLENBQUMsQ0FBRSxJQUFGLENBQVo7QUFDQXNELGNBQVEsQ0FBRSxrQkFBRixFQUFzQixDQUFFO0FBQUVKLGFBQUssRUFBRSxZQUFUO0FBQXVCQyxhQUFLLEVBQUVoQyxNQUFNLENBQUVYLEVBQUYsRUFBTSxZQUFOO0FBQXBDLE9BQUYsQ0FBdEIsQ0FBUjtBQUNBLEtBL0JGLEVBZ0NFd0IsRUFoQ0YsQ0FnQ00sT0FoQ04sRUFnQ2UsaUNBaENmLEVBZ0NrRCxZQUFZO0FBQzVEc0IsY0FBUSxDQUFFLHVCQUFGLENBQVI7QUFDQSxLQWxDRixFQUZxQyxDQXFDckM7O0FBQ0F0RCxLQUFDLENBQUUsZUFBRixDQUFELENBQXFCZ0MsRUFBckIsQ0FBeUIsOEJBQXpCLEVBQXlELFlBQVk7QUFDcEUsVUFBTVosSUFBSSxHQUFHLENBQUU7QUFDZDhCLGFBQUssRUFBRSxZQURPO0FBRWRDLGFBQUssRUFBRWxDLEtBQUssQ0FBRSxpQ0FBRjtBQUZFLE9BQUYsRUFHVjtBQUNGaUMsYUFBSyxFQUFFLE9BREw7QUFFRkMsYUFBSyxFQUFFSSxVQUFVLENBQUV2RCxDQUFDLENBQUMsY0FBRCxDQUFELENBQWtCd0QsSUFBbEIsR0FBeUJDLE9BQXpCLENBQWtDLFVBQWxDLEVBQThDLEVBQTlDLENBQUYsQ0FBVixDQUFpRUMsT0FBakUsQ0FBeUUsQ0FBekU7QUFGTCxPQUhVLENBQWI7QUFPQUosY0FBUSxDQUFFLG9CQUFGLEVBQXdCbEMsSUFBeEIsQ0FBUjtBQUNBLEtBVEQ7QUFVQSxHQWhERDtBQWlEQSxDQTVNQyxFQTRNQ3VDLE1BNU1ELEVBNE1TMUQsTUE1TVQsRUE0TWlCQyxRQTVNakIsRUE0TTJCQyxXQTVNM0IsQ0FBRixDOzs7Ozs7Ozs7Ozs7O0FDVEE7QUFBZSxvRkFBdUIsNEJBQTRCLEU7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7OztBQ0FsRSx3Qjs7Ozs7Ozs7Ozs7QUNBQSx3QiIsImZpbGUiOiIuL2Fzc2V0cy9qcy9zY3JpcHRzLmpzIiwic291cmNlc0NvbnRlbnQiOlsiIFx0Ly8gVGhlIG1vZHVsZSBjYWNoZVxuIFx0dmFyIGluc3RhbGxlZE1vZHVsZXMgPSB7fTtcblxuIFx0Ly8gVGhlIHJlcXVpcmUgZnVuY3Rpb25cbiBcdGZ1bmN0aW9uIF9fd2VicGFja19yZXF1aXJlX18obW9kdWxlSWQpIHtcblxuIFx0XHQvLyBDaGVjayBpZiBtb2R1bGUgaXMgaW4gY2FjaGVcbiBcdFx0aWYoaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0pIHtcbiBcdFx0XHRyZXR1cm4gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0uZXhwb3J0cztcbiBcdFx0fVxuIFx0XHQvLyBDcmVhdGUgYSBuZXcgbW9kdWxlIChhbmQgcHV0IGl0IGludG8gdGhlIGNhY2hlKVxuIFx0XHR2YXIgbW9kdWxlID0gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0gPSB7XG4gXHRcdFx0aTogbW9kdWxlSWQsXG4gXHRcdFx0bDogZmFsc2UsXG4gXHRcdFx0ZXhwb3J0czoge31cbiBcdFx0fTtcblxuIFx0XHQvLyBFeGVjdXRlIHRoZSBtb2R1bGUgZnVuY3Rpb25cbiBcdFx0bW9kdWxlc1ttb2R1bGVJZF0uY2FsbChtb2R1bGUuZXhwb3J0cywgbW9kdWxlLCBtb2R1bGUuZXhwb3J0cywgX193ZWJwYWNrX3JlcXVpcmVfXyk7XG5cbiBcdFx0Ly8gRmxhZyB0aGUgbW9kdWxlIGFzIGxvYWRlZFxuIFx0XHRtb2R1bGUubCA9IHRydWU7XG5cbiBcdFx0Ly8gUmV0dXJuIHRoZSBleHBvcnRzIG9mIHRoZSBtb2R1bGVcbiBcdFx0cmV0dXJuIG1vZHVsZS5leHBvcnRzO1xuIFx0fVxuXG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlcyBvYmplY3QgKF9fd2VicGFja19tb2R1bGVzX18pXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm0gPSBtb2R1bGVzO1xuXG4gXHQvLyBleHBvc2UgdGhlIG1vZHVsZSBjYWNoZVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5jID0gaW5zdGFsbGVkTW9kdWxlcztcblxuIFx0Ly8gZGVmaW5lIGdldHRlciBmdW5jdGlvbiBmb3IgaGFybW9ueSBleHBvcnRzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmQgPSBmdW5jdGlvbihleHBvcnRzLCBuYW1lLCBnZXR0ZXIpIHtcbiBcdFx0aWYoIV9fd2VicGFja19yZXF1aXJlX18ubyhleHBvcnRzLCBuYW1lKSkge1xuIFx0XHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBuYW1lLCB7IGVudW1lcmFibGU6IHRydWUsIGdldDogZ2V0dGVyIH0pO1xuIFx0XHR9XG4gXHR9O1xuXG4gXHQvLyBkZWZpbmUgX19lc01vZHVsZSBvbiBleHBvcnRzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnIgPSBmdW5jdGlvbihleHBvcnRzKSB7XG4gXHRcdGlmKHR5cGVvZiBTeW1ib2wgIT09ICd1bmRlZmluZWQnICYmIFN5bWJvbC50b1N0cmluZ1RhZykge1xuIFx0XHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBTeW1ib2wudG9TdHJpbmdUYWcsIHsgdmFsdWU6ICdNb2R1bGUnIH0pO1xuIFx0XHR9XG4gXHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCAnX19lc01vZHVsZScsIHsgdmFsdWU6IHRydWUgfSk7XG4gXHR9O1xuXG4gXHQvLyBjcmVhdGUgYSBmYWtlIG5hbWVzcGFjZSBvYmplY3RcbiBcdC8vIG1vZGUgJiAxOiB2YWx1ZSBpcyBhIG1vZHVsZSBpZCwgcmVxdWlyZSBpdFxuIFx0Ly8gbW9kZSAmIDI6IG1lcmdlIGFsbCBwcm9wZXJ0aWVzIG9mIHZhbHVlIGludG8gdGhlIG5zXG4gXHQvLyBtb2RlICYgNDogcmV0dXJuIHZhbHVlIHdoZW4gYWxyZWFkeSBucyBvYmplY3RcbiBcdC8vIG1vZGUgJiA4fDE6IGJlaGF2ZSBsaWtlIHJlcXVpcmVcbiBcdF9fd2VicGFja19yZXF1aXJlX18udCA9IGZ1bmN0aW9uKHZhbHVlLCBtb2RlKSB7XG4gXHRcdGlmKG1vZGUgJiAxKSB2YWx1ZSA9IF9fd2VicGFja19yZXF1aXJlX18odmFsdWUpO1xuIFx0XHRpZihtb2RlICYgOCkgcmV0dXJuIHZhbHVlO1xuIFx0XHRpZigobW9kZSAmIDQpICYmIHR5cGVvZiB2YWx1ZSA9PT0gJ29iamVjdCcgJiYgdmFsdWUgJiYgdmFsdWUuX19lc01vZHVsZSkgcmV0dXJuIHZhbHVlO1xuIFx0XHR2YXIgbnMgPSBPYmplY3QuY3JlYXRlKG51bGwpO1xuIFx0XHRfX3dlYnBhY2tfcmVxdWlyZV9fLnIobnMpO1xuIFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkobnMsICdkZWZhdWx0JywgeyBlbnVtZXJhYmxlOiB0cnVlLCB2YWx1ZTogdmFsdWUgfSk7XG4gXHRcdGlmKG1vZGUgJiAyICYmIHR5cGVvZiB2YWx1ZSAhPSAnc3RyaW5nJykgZm9yKHZhciBrZXkgaW4gdmFsdWUpIF9fd2VicGFja19yZXF1aXJlX18uZChucywga2V5LCBmdW5jdGlvbihrZXkpIHsgcmV0dXJuIHZhbHVlW2tleV07IH0uYmluZChudWxsLCBrZXkpKTtcbiBcdFx0cmV0dXJuIG5zO1xuIFx0fTtcblxuIFx0Ly8gZ2V0RGVmYXVsdEV4cG9ydCBmdW5jdGlvbiBmb3IgY29tcGF0aWJpbGl0eSB3aXRoIG5vbi1oYXJtb255IG1vZHVsZXNcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubiA9IGZ1bmN0aW9uKG1vZHVsZSkge1xuIFx0XHR2YXIgZ2V0dGVyID0gbW9kdWxlICYmIG1vZHVsZS5fX2VzTW9kdWxlID9cbiBcdFx0XHRmdW5jdGlvbiBnZXREZWZhdWx0KCkgeyByZXR1cm4gbW9kdWxlWydkZWZhdWx0J107IH0gOlxuIFx0XHRcdGZ1bmN0aW9uIGdldE1vZHVsZUV4cG9ydHMoKSB7IHJldHVybiBtb2R1bGU7IH07XG4gXHRcdF9fd2VicGFja19yZXF1aXJlX18uZChnZXR0ZXIsICdhJywgZ2V0dGVyKTtcbiBcdFx0cmV0dXJuIGdldHRlcjtcbiBcdH07XG5cbiBcdC8vIE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbFxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5vID0gZnVuY3Rpb24ob2JqZWN0LCBwcm9wZXJ0eSkgeyByZXR1cm4gT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsKG9iamVjdCwgcHJvcGVydHkpOyB9O1xuXG4gXHQvLyBfX3dlYnBhY2tfcHVibGljX3BhdGhfX1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5wID0gXCJcIjtcblxuXG4gXHQvLyBMb2FkIGVudHJ5IG1vZHVsZSBhbmQgcmV0dXJuIGV4cG9ydHNcbiBcdHJldHVybiBfX3dlYnBhY2tfcmVxdWlyZV9fKF9fd2VicGFja19yZXF1aXJlX18ucyA9IDApO1xuIiwiLyoqIVxuICogU2FsZVhwcmVzc28gUHVibGljIFNjcmlwdHNcbiAqXG4gKiBAYXV0aG9yIFNhbGVYcHJlc3NvIDxzdXBwb3J0QHNhbGV4cHJlc3NvLmNvbT5cbiAqIEBwYWNrYWdlIFNhbGVYcHJlc3NvXG4gKiBAdmVyc2lvbiAxLjAuMFxuICogQHNpbmNlIDEuMC4wXG4gKi9cbmltcG9ydCBfIGZyb20gJ2xvZGFzaCc7XG4oIGZ1bmN0aW9uKCAkLCB3aW5kb3csIGRvY3VtZW50LCBTYWxlWHByZXNzbyApIHtcblx0XCJ1c2Ugc3RyaWN0XCI7XG5cdFxuXHQvLyBIZWxwZXIgRnVuY3Rpb25zLlxuXHRcblx0LyoqXG5cdCAqIENoZWNrcyBpZiBpbnB1dCBpcyB2YWxpZCBlbWFpbC5cblx0ICpcblx0ICogQHBhcmFtIGVtYWlsXG5cdCAqIEByZXR1cm4ge2Jvb2xlYW59XG5cdCAqL1xuXHRjb25zdCBpc0VtYWlsID0gZW1haWwgPT4gL14oW2EtekEtWjAtOV8uKy1dKStcXEAoKFthLXpBLVowLTktXSkrXFwuKSsoW2EtekEtWjAtOV17Miw0fSkrJC8udGVzdCggZW1haWwgKTtcblx0XG5cdC8qKlxuXHQgKiBGaW5kIE5lc3RlZCBFbGVtZW50LlxuXHQgKiBTYW1lIGFzICQoZWwpZmluZCgpLlxuXHQgKlxuXHQgKiBAcGFyYW0ge2pRdWVyeXxIVE1MRWxlbWVudHxTdHJpbmd9IGVsXG5cdCAqIEBwYXJhbSB7alF1ZXJ5fEhUTUxFbGVtZW50fFN0cmluZ30gc2VsZWN0b3Jcblx0ICogQHJldHVybiB7KnxqUXVlcnl8SFRNTEVsZW1lbnR9XG5cdCAqL1xuXHRjb25zdCBmaW5kRWwgPSAoIGVsLCBzZWxlY3RvciApID0+IGVsLmZpbmQoIHNlbGVjdG9yICk7XG5cdFxuXHQvKipcblx0ICogR2V0IEVsZW1lbnQuXG5cdCAqIEFsdGVybmF0aXZlIHRvICQoc2VsZWN0b3IpIHdpdGggc3VwcG9ydCBmb3IgZmluZCBuZXN0ZWQgZWxlbWVudC5cblx0ICpcblx0ICogQHBhcmFtIHtqUXVlcnl8SFRNTEVsZW1lbnR8QXJyYXl8U3RyaW5nfSBlbFxuXHQgKiBAcGFyYW0ge2pRdWVyeXxIVE1MRWxlbWVudHxTdHJpbmd9IHNlbGVjdG9yXG5cdCAqIEByZXR1cm4geyp8alF1ZXJ5fEhUTUxFbGVtZW50fVxuXHQgKi9cblx0Y29uc3QgZ2V0RWwgPSAoIGVsLCBzZWxlY3RvciApID0+IHtcblx0XHRpZiAoICdBcnJheScgPT09IGVsLmNvbnN0cnVjdG9yLm5hbWUgJiYgMiA9PT0gZWwubGVuZ3RoICkge1xuXHRcdFx0c2VsZWN0b3IgPSBlbFsxXTtcblx0XHRcdGVsID0gZWxbMF07XG5cdFx0fVxuXHRcdGlmICggJ3N0cmluZycgPT09IHR5cGVvZiBlbCApIHtcblx0XHRcdGVsID0gJCggZWwgKTtcblx0XHR9XG5cdFx0aWYgKCBzZWxlY3RvciAmJiAnc3RyaW5nJyA9PT0gdHlwZW9mIHNlbGVjdG9yICkge1xuXHRcdFx0cmV0dXJuIGZpbmRFbCggZWwsIHNlbGVjdG9yICk7XG5cdFx0fVxuXHRcdHJldHVybiBlbDtcblx0fTtcblx0XG5cdC8qKlxuXHQgKiBHZXQgQ2xvc2VzdCBFbGVtZW50LlxuXHQgKiBTYW1lIGFzICQoZWwpLmNsb3Nlc3QoKS5cblx0ICpcblx0ICogQHBhcmFtIHtqUXVlcnl8SFRNTEVsZW1lbnR8QXJyYXl8U3RyaW5nfSBlbFxuXHQgKiBAcGFyYW0ge2pRdWVyeXxIVE1MRWxlbWVudHxTdHJpbmd9IHNlbGVjdG9yXG5cdCAqIEByZXR1cm4geyp8alF1ZXJ5fEhUTUxFbGVtZW50fG51bGx8RWxlbWVudH1cblx0ICovXG5cdGNvbnN0IGNsb3Nlc3RFbCA9ICggZWwsIHNlbGVjdG9yICkgPT4ge1xuXHRcdHJldHVybiBnZXRFbCggZWwgKS5jbG9zZXN0KCBzZWxlY3RvciApO1xuXHR9XG5cdFxuXHQvKipcblx0ICogR2V0IEVsZW1lbnQgVmFsdWUuXG5cdCAqICQoZWwpLnZhbCgpXG5cdCAqIEBwYXJhbSB7alF1ZXJ5fEhUTUxFbGVtZW50fEFycmF5fFN0cmluZ30gZWxcblx0ICogQHBhcmFtIHtqUXVlcnl8SFRNTEVsZW1lbnR8U3RyaW5nfSBzZWxlY3RvclxuXHQgKiBAcmV0dXJuIHsqfVxuXHQgKi9cblx0Y29uc3QgZWxWYWwgPSAoIGVsLCBzZWxlY3RvciApID0+IGdldEVsKCBlbCwgc2VsZWN0b3IgKS52YWwoKTtcblx0XG5cdC8qKlxuXHQgKiBHZXQgRWxlbWVudCBEYXRhIGF0dHJpYnV0ZXMuXG5cdCAqICQoZWwpLmRhdGEoKVxuXHQgKlxuXHQgKiBAcGFyYW0ge2pRdWVyeXxIVE1MRWxlbWVudHxBcnJheXxTdHJpbmd9IHNlbGVjdG9yXG5cdCAqIEBwYXJhbSB7U3RyaW5nfSBkYXRhXG5cdCAqIEByZXR1cm4geyp9XG5cdCAqL1xuXHRjb25zdCBlbERhdGEgPSAoIHNlbGVjdG9yLCBkYXRhICkgPT4gZ2V0RWwoIHNlbGVjdG9yICkuZGF0YSggZGF0YSApO1xuXHRcblx0Ly8gRHlhbWljIE9wdGlvbnMuXG5cdGNvbnN0IHtcblx0XHRnZHByLFxuXHRcdG1lc3NhZ2VzOiB7IGNhcnRfZW1haWxfZ2Rwciwgbm9fdGhhbmtzIH0sXG5cdH0gPSBTYWxlWHByZXNzbztcblx0Ly8gQFRPRE8gaGFuZGxlIEdEUFIuXG5cdFxuXHQvKipcblx0ICogQWJ1bmRhbnQgQ2FydC5cblx0ICogU2F2ZSBDYXJ0IERhdGEgaW4gY2FzZSBvZiB1c2VyIGRpZG4ndCBjb21wbGV0ZSB0aGUgY2hlY2tvdXQuXG5cdCAqIFxuXHQgKiBAY2xhc3MgU2FsZVhwcmVzc29DYXB0dXJlVXNlckRhdGFcblx0ICovXG5cdGNsYXNzIFNhbGVYcHJlc3NvQ2FwdHVyZVVzZXJEYXRhIHtcblx0XHQvKipcblx0XHQgKiBDb25zdHJ1Y3RvclxuXHRcdCAqL1xuXHRcdGNvbnN0cnVjdG9yICgpIHtcblx0XHRcdHRoaXMuX2dkcHIgPSBnZHByO1xuXHRcdFx0dGhpcy5fdHlwaW5nVGltZXI7XG5cdFx0XHR0aGlzLl9kb25lVHlwaW5nSW50ZXJ2YWwgPSA1MDA7XG5cdFx0XHR0aGlzLl9vbGREYXRhID0ge307XG5cdFx0XHR0aGlzLl9pbml0KCk7XG5cdFx0fVxuXHRcdFxuXHRcdC8qKlxuXHRcdCAqIEluaXRpYWxpemUuXG5cdFx0ICpcblx0XHQgKiBAcHJpdmF0ZVxuXHRcdCAqL1xuXHRcdF9pbml0KCkge1xuXHRcdFx0Y29uc3Qgc2VsZiA9IHRoaXM7XG5cdFx0XHQkKGRvY3VtZW50KS5vbiggJ2tleXVwIGtleXByZXNzIGNoYW5nZSBibHVyJywgJ2lucHV0Jywgc2VsZi5fc2F2ZURhdGEuYmluZCggc2VsZiApICk7XG5cdFx0XHQkKGRvY3VtZW50KS5vbiggJ2tleWRvd24nLCAnaW5wdXQnLCBzZWxmLl9jbGVhclRoZUNvdW50RG93bi5iaW5kKCBzZWxmICkgKTtcblx0XHRcdHNldFRpbWVvdXQoICgpID0+IHtcblx0XHRcdFx0c2VsZi5fc2F2ZURhdGEoKTtcblx0XHRcdH0sIDc1MCApO1xuXHRcdH1cblx0XHRcblx0XHQvKipcblx0XHQgKiBTYXZlIFVzZXIgRGF0YS5cblx0XHQgKiBEZWJ1bmNlZCB3aXRoIHNldFRpbWVvdXRcblx0XHQgKlxuXHRcdCAqIEBwYXJhbSB7RXZlbnR9IGV2ZW50XG5cdFx0ICpcblx0XHQgKiBAcHJpdmF0ZVxuXHRcdCAqL1xuXHRcdF9zYXZlRGF0YSggZXZlbnQgKSB7XG5cdFx0XHRjb25zdCBzZWxmID0gdGhpcztcblx0XHRcdHNlbGYuX2NsZWFyVGhlQ291bnREb3duKCk7XG5cdFx0XHRzZWxmLl90eXBpbmdUaW1lciA9IHNldFRpbWVvdXQoICgpID0+IHtcblx0XHRcdFx0Y29uc3QgZW1haWwgPSAkKCAnI2JpbGxpbmdfZW1haWwnICkudmFsKCkgfHwgJyc7XG5cdFx0XHRcdGlmICggaXNFbWFpbCggZW1haWwgKSApIHtcblx0XHRcdFx0XHRjb25zdCBmaXJzdE5hbWUgPSAkKFwiI2JpbGxpbmdfZmlyc3RfbmFtZVwiKS52YWwoKSB8fCAnJztcblx0XHRcdFx0XHRjb25zdCBsYXN0TmFtZSA9ICQoXCIjYmlsbGluZ19sYXN0X25hbWVcIikudmFsKCkgfHwgJyc7XG5cdFx0XHRcdFx0Y29uc3QgcGhvbmUgPSAkKFwiI2JpbGxpbmdfcGhvbmVcIikudmFsKCkgfHwgJyc7XG5cdFx0XHRcdFx0Y29uc3QgZGF0YSA9IHsgZW1haWwsIGZpcnN0TmFtZSwgbGFzdE5hbWUsIHBob25lIH07XG5cdFx0XHRcdFx0aWYgKCAhIF8uaXNFcXVhbCggZGF0YSwgc2VsZi5fb2xkRGF0YSApICkge1xuXHRcdFx0XHRcdFx0c2VsZi5fb2xkRGF0YSA9IGRhdGE7IC8vIHJlZHVjZSBiYWNrZW5kIGNhbGwuXG5cdFx0XHRcdFx0XHR3cC5hamF4LnBvc3QoICdzeHBfc2F2ZV9jaGVja291dCcsIHsgZW1haWwsIGZpcnN0TmFtZSwgbGFzdE5hbWUgfSApO1xuXHRcdFx0XHRcdH1cblx0XHRcdFx0fVxuXHRcdFx0fSwgdGhpcy5fZG9uZVR5cGluZ0ludGVydmFsICk7XG5cdFx0fVxuXHRcdFxuXHRcdC8qKlxuXHRcdCAqIENsZWFyIFRpbWVyLlxuXHRcdCAqXG5cdFx0ICogQHByaXZhdGVcblx0XHQgKi9cblx0XHRfY2xlYXJUaGVDb3VudERvd24oKSB7XG5cdFx0XHRpZiAoIHRoaXMuX3R5cGluZ1RpbWVyICkge1xuXHRcdFx0XHRjbGVhclRpbWVvdXQoIHRoaXMuX3R5cGluZ1RpbWVyICk7XG5cdFx0XHR9XG5cdFx0fVxuXHR9XG5cdFxuXHRuZXcgU2FsZVhwcmVzc29DYXB0dXJlVXNlckRhdGEoKTtcblx0XG5cdCQoIGRvY3VtZW50ICkub24oICdyZWFkeScsIGZ1bmN0aW9uKCkge1xuXHRcdFxuXHRcdCQoIGRvY3VtZW50IClcblx0XHRcdFx0Ly8gQWRkIHRvIGNhdCBvbiBzaW5nbGUgcHJvZHVjdCBwYWdlLlxuXHRcdFx0XHQub24oICdjbGljaycsICcuc2luZ2xlX2FkZF90b19jYXJ0X2J1dHRvbicsIGZ1bmN0aW9uICgpIHtcblx0XHRcdFx0Y29uc3QgZWwgPSAkKCB0aGlzICk7XG5cdFx0XHRcdGNvbnN0IGZvcm0gPSAkKCB0aGlzICkuY2xvc2VzdCggJ2Zvcm0uY2FydCcgKTtcblx0XHRcdFx0Y29uc3QgcXR5RWwgPSAkKCAnW25hbWU9XCJxdWFudGl0eVwiXScgKTtcblx0XHRcdFx0bGV0IHF0eSA9IDE7XG5cdFx0XHRcdGlmICggcXR5RWwubGVuZ3RoICkge1xuXHRcdFx0XHRcdHF0eSA9IHF0eUVsLnZhbCgpO1xuXHRcdFx0XHR9XG5cdFx0XHRcdGxldCBkYXRhID0gWyB7IGxhYmVsOiAncXVhbnRpdHknLCB2YWx1ZTogcXR5IH0gXTtcblx0XHRcdFx0aWYgKCBmb3JtLmhhc0NsYXNzKCAndmFyaWF0aW9uc19mb3JtJyApICkge1xuXHRcdFx0XHRcdGRhdGEucHVzaCggeyBsYWJlbDogJ3Byb2R1Y3RfaWQnLCB2YWx1ZTogZWxWYWwoIGZvcm0sICdbbmFtZT1cInByb2R1Y3RfaWRcIl0nICkgfSApO1xuXHRcdFx0XHRcdGRhdGEucHVzaCggeyBsYWJlbDogJ3ZhcmlhdGlvbl9pZCcsIHZhbHVlOiBlbFZhbCggZm9ybSwgJ1tuYW1lPVwidmFyaWF0aW9uX2lkXCJdJyApIH0gKTtcblx0XHRcdFx0fSBlbHNlIHtcblx0XHRcdFx0XHRkYXRhLnB1c2goIHsgbGFiZWw6ICdwcm9kdWN0X2lkJywgdmFsdWU6IGVsVmFsKCBlbCApIH0gKTtcblx0XHRcdFx0fVxuXHRcdFx0XHRcblx0XHRcdFx0c3hwRXZlbnQoICdhZGQtdG8tY2FydCcsIGRhdGEgKTtcblx0XHRcdH0gKVxuXHRcdFx0Ly8gQWRkIHRvIGNhdCBvbiBwcm9kdWN0IGFyY2hpdmUgcGFnZS5cblx0XHRcdC5vbiggJ2NsaWNrJywgJy5hZGRfdG9fY2FydF9idXR0b24nLCBmdW5jdGlvbiAoKSB7XG5cdFx0XHRcdGNvbnN0IGVsID0gJCggdGhpcyApO1xuXHRcdFx0XHRzeHBFdmVudCggJ2FkZC10by1jYXJ0JywgW1xuXHRcdFx0XHRcdHsgbGFiZWw6ICdwcm9kdWN0X2lkJywgdmFsdWU6IGVsRGF0YSggZWwsICdwcm9kdWN0X2lkJyApIH0sXG5cdFx0XHRcdFx0eyBsYWJlbDogJ3F1YW50aXR5JywgdmFsdWU6IGVsRGF0YSggZWwsICdxdWFudGl0eSAnKSB9LFxuXHRcdFx0XHRdICk7XG5cdFx0XHR9IClcblx0XHRcdC5vbiggJ2NsaWNrJywgJy53b29jb21tZXJjZS1jYXJ0LWZvcm0gLnByb2R1Y3QtcmVtb3ZlID4gYScsIGZ1bmN0aW9uICgpIHtcblx0XHRcdFx0Y29uc3QgZWwgPSAkKCB0aGlzICk7XG5cdFx0XHRcdHN4cEV2ZW50KCAncmVtb3ZlLWZyb20tY2FydCcsIFsgeyBsYWJlbDogJ3Byb2R1Y3RfaWQnLCB2YWx1ZTogZWxEYXRhKCBlbCwgJ3Byb2R1Y3RfaWQnICkgfSBdICk7XG5cdFx0XHR9IClcblx0XHRcdC5vbiggJ2NsaWNrJywgJy53b29jb21tZXJjZS1jYXJ0IC5yZXN0b3JlLWl0ZW0nLCBmdW5jdGlvbiAoKSB7XG5cdFx0XHRcdHN4cEV2ZW50KCAndW5kby1yZW1vdmUtZnJvbS1jYXJ0JyApO1xuXHRcdFx0fSApO1xuXHRcdC8vIENhcHR1cmUgc3VjY2Vzc2Z1bGwgY2hlY2tvdXQuXG5cdFx0JCggJ2Zvcm0uY2hlY2tvdXQnICkub24oICdjaGVja291dF9wbGFjZV9vcmRlcl9zdWNjZXNzJywgZnVuY3Rpb24gKCkge1xuXHRcdFx0Y29uc3QgZGF0YSA9IFsge1xuXHRcdFx0XHRsYWJlbDogJ2dhdGV3YXlfaWQnLFxuXHRcdFx0XHR2YWx1ZTogZWxWYWwoICdbbmFtZT1cInBheW1lbnRfbWV0aG9kXCJdOmNoZWNrZWQnICksXG5cdFx0XHR9LCB7XG5cdFx0XHRcdGxhYmVsOiAndG90YWwnLFxuXHRcdFx0XHR2YWx1ZTogcGFyc2VGbG9hdCggJCgnLm9yZGVyLXRvdGFsJykudGV4dCgpLnJlcGxhY2UoIC9bXlxcZC5dL2dtLCAnJyApICkudG9GaXhlZCgyKSxcblx0XHRcdH0gXTtcblx0XHRcdHN4cEV2ZW50KCAnY2hlY2tvdXQtY29tcGxldGVkJywgZGF0YSApO1xuXHRcdH0gKTtcblx0fSApO1xufSggalF1ZXJ5LCB3aW5kb3csIGRvY3VtZW50LCBTYWxlWHByZXNzbyApICk7XG4iLCJleHBvcnQgZGVmYXVsdCBfX3dlYnBhY2tfcHVibGljX3BhdGhfXyArIFwiLi9hc3NldHMvY3NzL3N0eWxlcy5jc3NcIjsiLCJtb2R1bGUuZXhwb3J0cyA9IGpRdWVyeTsiLCJtb2R1bGUuZXhwb3J0cyA9IGxvZGFzaDsiXSwic291cmNlUm9vdCI6IiJ9