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
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/tracker.js":
/*!***************************!*\
  !*** ./src/js/tracker.js ***!
  \***************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

/* eslint-env browser */
(function (window, document, sxpOptions, onPageData) {
  if (!window) {
    return;
  } /////////////////////
  // PREDEFINED VARIABLES FOR BETTER MINIFICATION
  //
  // This seems like a lot of repetition, but it makes our script available for
  // multiple destination which prevents us to need multiple scripts. The minified
  // version stays small.
  // Collection TYPES


  var TYPE_ERROR = 'error';
  var TYPE_EVENT = 'event';
  var TYPE_PAGE_VIEW = 'page-view';
  var TYPE_PAGE_LEAVE = 'page-leave';
  var version = '{{VERSION}}';
  var sxpGlobal = 'sxpEvent';
  var con = window.console;
  var doNotTrack = 'doNotTrack';
  var slash = '/';
  var nav = window.navigator;
  var loc = window.location;
  var SITE_URL = '{{SITE_URL}}';
  var path_replace = SITE_URL.replace(new RegExp('.*' + location.hostname + '\/?'), '');
  var PATH_NAME = loc.pathname.replace(path_replace, '').replace(/^\/\//, '/');
  var userAgent = nav.userAgent;
  var notSending = 'Not sending request ';
  var encodeURIComponentFunc = encodeURIComponent;
  var decodeURIComponentFunc = decodeURIComponent;
  var stringify = JSON.stringify;
  var thousand = 1000;
  var addEventListenerFunc = window.addEventListener;
  var undefinedVar = undefined;
  var documentElement = document.documentElement || {};
  var language = 'language';
  var Height = 'Height';
  var Width = 'Width';
  var scroll = 'scroll';
  var scrollHeight = scroll + Height;
  var offsetHeight = 'offset' + Height;
  var clientHeight = 'client' + Height;
  var clientWidth = 'client' + Width;
  var screen = window.screen;
  var sendBeaconText = 'sendBeacon';

  var uuid = function uuid() {
    var cryptoObject = window.crypto || window.msCrypto;
    var emptyUUID = [1e7] + -1e3 + -4e3 + -8e3 + -1e11;
    var uuidRegex = /[018]/g;

    try {
      return emptyUUID.replace(uuidRegex, function (c) {
        return (c ^ cryptoObject.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4 // eslint-disable-line no-bitwise
        ).toString(16);
      });
    } catch (error) {
      return emptyUUID.replace(uuidRegex, function (c) {
        var r = Math.random() * 16 | 0,
            // eslint-disable-line no-bitwise
        v = c < 2 ? r : r & 0x3 | 0x8; // eslint-disable-line no-bitwise

        return v.toString(16);
      });
    }
  };

  var assign = function assign() {
    var to = {};

    for (var _len = arguments.length, objects = new Array(_len), _key = 0; _key < _len; _key++) {
      objects[_key] = arguments[_key];
    }

    var arg = objects;

    for (var index = 0; index < arg.length; index++) {
      var nextSource = arg[index];

      if (nextSource) {
        for (var nextKey in nextSource) {
          if (Object.prototype.hasOwnProperty.call(nextSource, nextKey)) {
            to[nextKey] = nextSource[nextKey];
          }
        }
      }
    }

    return to;
  };

  var getParams = function getParams(regex) {
    // From the search we grab the utm_source and ref and save only that
    var matches = loc.search.match(new RegExp('[?&](' + regex + ')=([^?&]+)', 'gi'));
    var match = matches ? matches.map(function (m) {
      return m.split('=')[1];
    }) : [];

    if (match && match[0]) {
      return match[0];
    }
  }; /////////////////////
  // GET SETTINGS
  //
  // const isBoolean = value => !! value === value;
  // Find the script element where options can be set on
  // const attr = ( el, attribute ) => el && el.getAttribute( 'data-' + attribute );


  var api = '{{api_url}}'; // api url used as pixel too.

  var tracker = '{{tracker}}'; // tracker file name used in script src attribute.

  var _assign = assign({
    mode: '',
    // Script mode, this can be hash mode for example
    ignorePages: [],
    // Customers can ignore certain pages
    autoCollect: true,
    // Some customers want to collect page views manually
    recordDnt: false // Should we record Do Not Track visits?

  }, sxpOptions),
      mode = _assign.mode,
      ignorePages = _assign.ignorePages,
      autoCollect = _assign.autoCollect,
      recordDnt = _assign.recordDnt; /////////////////////
  // HELPER FUNCTIONS
  //


  var now = Date.now; // Ignore pages specified in data-ignore-pages

  var shouldIgnore = function shouldIgnore(path) {
    for (var i in ignorePages) {
      var ignorePageRaw = ignorePages[i];

      if (!ignorePageRaw) {
        continue;
      } // Prepend a slash when it's missing


      var ignorePage = ignorePageRaw[0] === '/' ? ignorePageRaw : '/' + ignorePageRaw;

      try {
        if (ignorePage === path || new RegExp(ignorePage.replace(/\*/gi, '(.*)'), 'gi').test(path)) {
          return true;
        }
      } catch (error) {
        return false;
      }
    }

    return false;
  }; /////////////////////
  // SEND DATA VIA OUR PIXEL
  //
  // Send data via image (pixel)


  var sendData = function sendData(data, callback) {
    data = assign(payload, data);
    callback = callback && 'function' === typeof callback ? callback : function () {}; // data = assign( append, payload );

    if (!(sendBeaconText in nav)) {
      var image = new Image();

      if (callback && 'function' === typeof callback) {
        image.onerror = callback;
        image.onload = callback;
      }

      image.src = api + (api.indexOf('?') > -1 ? '&' : '?') + 'payload=' + stringify(data);
    } else {
      nav[sendBeaconText](api, stringify(data));
      callback();
    }
  }; /////////////////////
  // ERROR FUNCTIONS
  //
  // Send errors


  var sendError = function sendError(errorOrMessage) {
    errorOrMessage = errorOrMessage.message || errorOrMessage;
    warn(errorOrMessage);
    sendData({
      type: TYPE_ERROR,
      error: errorOrMessage,
      url: SITE_URL + PATH_NAME
    });
  }; // A simple log function so the user knows why a request is not being send


  var warn = function warn(message) {
    // @TODO only execute the next block if dev mode is enabled.
    if (con && con.warn) {
      con.warn('SaleXpresso Analytics:', message);
    }
  }; // Make sure ignore pages is an array


  if (!Array.isArray(ignorePages)) {
    if (typeof ignorePages === 'string' && ignorePages.length) {
      ignorePages = ignorePages.split(/, ?/).map(function (page) {
        return page.trim();
      });
    } else {
      ignorePages = [];
    }
  } // bot detection.


  var bot = nav.webdriver || window.hasOwnProperty('__nightmare') && window.__nightmare || 'callPhantom' in window || '_phantom' in window || 'phantom' in window || /(bot|spider|crawl)/i.test(userAgent) || window.chrome && ('' === nav.languages || !(nav.plugins instanceof PluginArray)); // This code could error on (incomplete) implementations, that's why we use try...catch

  var timezone;

  try {
    timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
  } catch (e) {
    /* Do nothing */
  } /////////////////////
  // PAYLOAD FOR BOTH PAGE VIEWS AND EVENTS
  //


  var payload = {
    version: version,
    bot: bot,
    timezone: timezone,
    meta: {
      object_type: onPageData.wp_object_type,
      object_id: onPageData.wp_object_id
    }
  };

  try {
    // We listen for the error events and only send errors that are
    // from our script (checked by filename) to our server.
    addEventListenerFunc(TYPE_ERROR, function (event) {
      if (event.filename && event.filename.indexOf(tracker) > -1) {
        sendError(event.message);
      }
    }, false);
    var pushState = 'pushState';
    var dispatchEvent = window.dispatchEvent;
    var duration = 'duration';
    var start = now();
    var scrolled = 0; // Warn when no document.doctype is defined (this breaks some documentElement dimensions)

    if (!document.doctype) {
      warn('Add DOCTYPE html for more accurate dimensions');
    } // Don't track when Do Not Track is set to true


    if (!recordDnt && doNotTrack in nav && nav[doNotTrack] === '1') {
      return warn(notSending + 'when ' + doNotTrack + ' is enabled');
    } /////////////////////
    // SETUP INITIAL VARIABLES
    //


    var page = {};
    var lastPageId = uuid();
    var lastSendPath; // We don't want to end up with sensitive data so we clean the referrer URL
    // Eg.

    var referrer = (document.referrer || '').replace(/^https?:\/\/((m|l|w{2,3}([0-9]+)?)\.)?([^?#]+)(.*)$/, '$4').replace(/^([^/]+)$/, '$1') || undefinedVar; // The prefix utm_ is optional

    var utmRegexPrefix = '(utm_)?';
    var source = {
      source: getParams(utmRegexPrefix + 'source|ref'),
      medium: getParams(utmRegexPrefix + 'medium'),
      campaign: getParams(utmRegexPrefix + 'campaign'),
      term: getParams(utmRegexPrefix + 'term'),
      content: getParams(utmRegexPrefix + 'content'),
      referrer: referrer
    }; /////////////////////
    // TIME ON PAGE AND SCROLLED LOGIC
    //
    // We don't put msHidden in if duration block, because it's used outside of that functionality

    var msHidden = 0;
    var hiddenStart;
    window.addEventListener('visibilitychange', function () {
      if (document.hidden) {
        hiddenStart = now();
      } else {
        msHidden += now() - hiddenStart;
      }
    }, false);

    var sendOnLeave = function sendOnLeave(id, push) {
      var append = {
        type: TYPE_PAGE_LEAVE,
        page_id: push ? id : lastPageId,
        duration: Math.round((now() - start + msHidden) / thousand)
      };
      msHidden = 0;
      start = now();
      append.scrolled = Math.max(0, scrolled, position());
      sendData(append);
    };

    addEventListenerFunc('unload', sendOnLeave, false);
    var body = document.body || {};

    var position = function position() {
      try {
        var documentClientHeight = documentElement[clientHeight] || 0;
        var height = Math.max(body[scrollHeight] || 0, body[offsetHeight] || 0, documentElement[clientHeight] || 0, documentElement[scrollHeight] || 0, documentElement[offsetHeight] || 0);
        return Math.min(100, Math.round(100 * ((documentElement.scrollTop || 0) + documentClientHeight) / height / 5) * 5);
      } catch (error) {
        return 0;
      }
    };

    addEventListenerFunc('load', function () {
      scrolled = position();
      addEventListenerFunc(scroll, function () {
        if (scrolled < position()) {
          scrolled = position();
        }
      }, false);
    }); /////////////////////
    // ACTUAL PAGE VIEW LOGIC
    //

    var getPath = function getPath(overwrite) {
      var path = overwrite || decodeURIComponentFunc(PATH_NAME); // Ignore pages specified in data-ignore-pages

      if (shouldIgnore(path)) {
        warn(notSending + 'because ' + path + ' is ignored');
        return;
      } // Add hash to path when script is put in to hash mode


      if (mode === 'hash' && loc.hash) {
        path += loc.hash.split('?')[0];
      }

      return path;
    }; // Send page view and append data to it


    var sendPageView = function sendPageView(isPushState, deleteSourceInfo, sameSite) {
      if (isPushState) {
        sendOnLeave('' + lastPageId, true);
      }

      lastPageId = uuid();
      page.page_id = lastPageId;
      var currentPage = SITE_URL + getPath();
      sendData(assign(page, deleteSourceInfo ? {
        referrer: sameSite ? referrer : null
      } : source, {
        type: TYPE_PAGE_VIEW
      }));
      referrer = currentPage;
    };

    var PageView = function PageView(isPushState, pathOverwrite) {
      // Obfuscate personal data in URL by dropping the search and hash
      var path = getPath(pathOverwrite); // Don't send the last path again (this could happen when pushState is used to change the path hash or search)

      if (!path || lastSendPath === path) {
        return;
      }

      lastSendPath = path;
      var data = {
        path: path,
        viewport_width: Math.max(documentElement[clientWidth] || 0, window.innerWidth || 0) || null,
        viewport_height: Math.max(documentElement[clientHeight] || 0, window.innerHeight || 0) || null,
        screen_width: screen.width,
        screen_height: screen.height
      };

      if (nav[language]) {
        data[language] = nav[language];
      } // If a user does refresh we need to delete the referrer because otherwise it count double


      var perf = window.performance;
      var navigation = 'navigation'; // Check if back, forward or reload buttons are being used in modern browsers

      var userNavigated = perf && perf.getEntriesByType && perf.getEntriesByType(navigation)[0] && perf.getEntriesByType(navigation)[0].type ? ['reload', 'back_forward'].indexOf(perf.getEntriesByType(navigation)[0].type) > -1 : perf && perf[navigation] && [1, 2].indexOf(perf[navigation].type) > -1; // Check if back, forward or reload buttons are being use in older browsers 1: TYPE_RELOAD, 2: TYPE_BACK_FORWARD
      // Check if referrer is the same as current hostname

      var sameSite = referrer ? referrer.split(slash)[0] === SITE_URL : false; // We set unique variable based on pushState or back navigation, if no match we check the referrer

      data.is_unique = isPushState || userNavigated ? false : !sameSite;
      page = data;
      sendPageView(isPushState, isPushState || userNavigated, sameSite);
    }; /////////////////////
    // AUTOMATED PAGE VIEW COLLECTION
    //


    var his = window.history;
    var hisPushState = his ? his.pushState : undefinedVar; // Overwrite history pushState function to
    // allow listening on the pushState event

    if (autoCollect && hisPushState && Event && dispatchEvent) {
      var stateListener = function stateListener(type) {
        var orig = his[type];
        return function () {
          var arg = arguments;
          var rv = orig.apply(this, arg);
          var event;

          if (typeof Event === 'function') {
            event = new Event(type);
          } else {
            // Fix for IE
            event = document.createEvent('Event');
            event.initEvent(type, true, true);
          }

          event.arguments = arg;
          dispatchEvent(event);
          return rv;
        };
      };

      his.pushState = stateListener(pushState);
      addEventListenerFunc(pushState, function () {
        PageView(1);
      }, false);
      addEventListenerFunc('popstate', function () {
        PageView(1);
      }, false);
    } // When in hash mode, we record a PageView based on the onhashchange function


    if (autoCollect && mode === 'hash' && 'onhashchange' in window) {
      addEventListenerFunc('hashchange', function () {
        PageView(1);
      }, false);
    } // This script should be loaded inside <head> tag.
    // Collect page view soons as it loaded (if autoCollect enabled).


    if (autoCollect) {
      PageView();
    } else {
      window.sxpPageView = function (path) {
        PageView(0, path);
      };
    } /////////////////////
    // EVENTS
    //
    // replace this session if from cookie session id.


    var validTypes = ['string', 'number'];

    var sendEvent = function sendEvent(event, meta, callbackRaw) {
      var isFunction = event instanceof Function;
      var callback = callbackRaw instanceof Function ? callbackRaw : function () {};

      if (validTypes.indexOf(_typeof(event)) < 0 && !isFunction) {
        warn('event is not a string: ' + event);
        return callback();
      }

      try {
        if (isFunction) {
          if (validTypes.indexOf(_typeof(event)) < 0) {
            warn('event function output is not a string: ' + event);
            return callback();
          }
        }
      } catch (error) {
        warn('Error in your event function: ' + error.message);
        return callback();
      }

      event = ('' + event).replace(/[^a-z0-9]+/gi, '-').replace(/(^-|-$)/g, '').toLowerCase();

      if (event) {
        sendData(assign(source, bot, {
          meta: {
            event: meta
          }
        }, {
          type: TYPE_EVENT,
          event: event,
          // Event Category.
          page_id: page.page_id
        }), callback);
      }
    };

    var defaultEventFunc = function defaultEventFunc(event, data, callback) {
      sendEvent(event, data, callback);
    }; // Set default function if user didn't define a function


    if (!window[sxpGlobal]) {
      window[sxpGlobal] = defaultEventFunc;
    }

    var eventFunc = window[sxpGlobal]; // Read queue of the user defined function

    var queue = eventFunc && eventFunc.q ? eventFunc.q : []; // Overwrite user defined function

    window[sxpGlobal] = defaultEventFunc; // Post events from the queue of the user defined function

    for (var event in queue) {
      sendEvent(queue[event]);
    }
  } catch (e) {
    sendError(e);
  }
})(window, document, '{{tracker_options}}', sxpSessionOnPageData);

/***/ }),

/***/ 2:
/*!*********************************!*\
  !*** multi ./src/js/tracker.js ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! ./src/js/tracker.js */"./src/js/tracker.js");


/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vLy4vc3JjL2pzL3RyYWNrZXIuanMiXSwibmFtZXMiOlsid2luZG93IiwiZG9jdW1lbnQiLCJzeHBPcHRpb25zIiwib25QYWdlRGF0YSIsIlRZUEVfRVJST1IiLCJUWVBFX0VWRU5UIiwiVFlQRV9QQUdFX1ZJRVciLCJUWVBFX1BBR0VfTEVBVkUiLCJ2ZXJzaW9uIiwic3hwR2xvYmFsIiwiY29uIiwiY29uc29sZSIsImRvTm90VHJhY2siLCJzbGFzaCIsIm5hdiIsIm5hdmlnYXRvciIsImxvYyIsImxvY2F0aW9uIiwiU0lURV9VUkwiLCJwYXRoX3JlcGxhY2UiLCJyZXBsYWNlIiwiUmVnRXhwIiwiaG9zdG5hbWUiLCJQQVRIX05BTUUiLCJwYXRobmFtZSIsInVzZXJBZ2VudCIsIm5vdFNlbmRpbmciLCJlbmNvZGVVUklDb21wb25lbnRGdW5jIiwiZW5jb2RlVVJJQ29tcG9uZW50IiwiZGVjb2RlVVJJQ29tcG9uZW50RnVuYyIsImRlY29kZVVSSUNvbXBvbmVudCIsInN0cmluZ2lmeSIsIkpTT04iLCJ0aG91c2FuZCIsImFkZEV2ZW50TGlzdGVuZXJGdW5jIiwiYWRkRXZlbnRMaXN0ZW5lciIsInVuZGVmaW5lZFZhciIsInVuZGVmaW5lZCIsImRvY3VtZW50RWxlbWVudCIsImxhbmd1YWdlIiwiSGVpZ2h0IiwiV2lkdGgiLCJzY3JvbGwiLCJzY3JvbGxIZWlnaHQiLCJvZmZzZXRIZWlnaHQiLCJjbGllbnRIZWlnaHQiLCJjbGllbnRXaWR0aCIsInNjcmVlbiIsInNlbmRCZWFjb25UZXh0IiwidXVpZCIsImNyeXB0b09iamVjdCIsImNyeXB0byIsIm1zQ3J5cHRvIiwiZW1wdHlVVUlEIiwidXVpZFJlZ2V4IiwiYyIsImdldFJhbmRvbVZhbHVlcyIsIlVpbnQ4QXJyYXkiLCJ0b1N0cmluZyIsImVycm9yIiwiciIsIk1hdGgiLCJyYW5kb20iLCJ2IiwiYXNzaWduIiwidG8iLCJvYmplY3RzIiwiYXJnIiwiaW5kZXgiLCJsZW5ndGgiLCJuZXh0U291cmNlIiwibmV4dEtleSIsIk9iamVjdCIsInByb3RvdHlwZSIsImhhc093blByb3BlcnR5IiwiY2FsbCIsImdldFBhcmFtcyIsInJlZ2V4IiwibWF0Y2hlcyIsInNlYXJjaCIsIm1hdGNoIiwibWFwIiwibSIsInNwbGl0IiwiYXBpIiwidHJhY2tlciIsIm1vZGUiLCJpZ25vcmVQYWdlcyIsImF1dG9Db2xsZWN0IiwicmVjb3JkRG50Iiwibm93IiwiRGF0ZSIsInNob3VsZElnbm9yZSIsInBhdGgiLCJpIiwiaWdub3JlUGFnZVJhdyIsImlnbm9yZVBhZ2UiLCJ0ZXN0Iiwic2VuZERhdGEiLCJkYXRhIiwiY2FsbGJhY2siLCJwYXlsb2FkIiwiaW1hZ2UiLCJJbWFnZSIsIm9uZXJyb3IiLCJvbmxvYWQiLCJzcmMiLCJpbmRleE9mIiwic2VuZEVycm9yIiwiZXJyb3JPck1lc3NhZ2UiLCJtZXNzYWdlIiwid2FybiIsInR5cGUiLCJ1cmwiLCJBcnJheSIsImlzQXJyYXkiLCJwYWdlIiwidHJpbSIsImJvdCIsIndlYmRyaXZlciIsIl9fbmlnaHRtYXJlIiwiY2hyb21lIiwibGFuZ3VhZ2VzIiwicGx1Z2lucyIsIlBsdWdpbkFycmF5IiwidGltZXpvbmUiLCJJbnRsIiwiRGF0ZVRpbWVGb3JtYXQiLCJyZXNvbHZlZE9wdGlvbnMiLCJ0aW1lWm9uZSIsImUiLCJtZXRhIiwib2JqZWN0X3R5cGUiLCJ3cF9vYmplY3RfdHlwZSIsIm9iamVjdF9pZCIsIndwX29iamVjdF9pZCIsImV2ZW50IiwiZmlsZW5hbWUiLCJwdXNoU3RhdGUiLCJkaXNwYXRjaEV2ZW50IiwiZHVyYXRpb24iLCJzdGFydCIsInNjcm9sbGVkIiwiZG9jdHlwZSIsImxhc3RQYWdlSWQiLCJsYXN0U2VuZFBhdGgiLCJyZWZlcnJlciIsInV0bVJlZ2V4UHJlZml4Iiwic291cmNlIiwibWVkaXVtIiwiY2FtcGFpZ24iLCJ0ZXJtIiwiY29udGVudCIsIm1zSGlkZGVuIiwiaGlkZGVuU3RhcnQiLCJoaWRkZW4iLCJzZW5kT25MZWF2ZSIsImlkIiwicHVzaCIsImFwcGVuZCIsInBhZ2VfaWQiLCJyb3VuZCIsIm1heCIsInBvc2l0aW9uIiwiYm9keSIsImRvY3VtZW50Q2xpZW50SGVpZ2h0IiwiaGVpZ2h0IiwibWluIiwic2Nyb2xsVG9wIiwiZ2V0UGF0aCIsIm92ZXJ3cml0ZSIsImhhc2giLCJzZW5kUGFnZVZpZXciLCJpc1B1c2hTdGF0ZSIsImRlbGV0ZVNvdXJjZUluZm8iLCJzYW1lU2l0ZSIsImN1cnJlbnRQYWdlIiwiUGFnZVZpZXciLCJwYXRoT3ZlcndyaXRlIiwidmlld3BvcnRfd2lkdGgiLCJpbm5lcldpZHRoIiwidmlld3BvcnRfaGVpZ2h0IiwiaW5uZXJIZWlnaHQiLCJzY3JlZW5fd2lkdGgiLCJ3aWR0aCIsInNjcmVlbl9oZWlnaHQiLCJwZXJmIiwicGVyZm9ybWFuY2UiLCJuYXZpZ2F0aW9uIiwidXNlck5hdmlnYXRlZCIsImdldEVudHJpZXNCeVR5cGUiLCJpc191bmlxdWUiLCJoaXMiLCJoaXN0b3J5IiwiaGlzUHVzaFN0YXRlIiwiRXZlbnQiLCJzdGF0ZUxpc3RlbmVyIiwib3JpZyIsImFyZ3VtZW50cyIsInJ2IiwiYXBwbHkiLCJjcmVhdGVFdmVudCIsImluaXRFdmVudCIsInN4cFBhZ2VWaWV3IiwidmFsaWRUeXBlcyIsInNlbmRFdmVudCIsImNhbGxiYWNrUmF3IiwiaXNGdW5jdGlvbiIsIkZ1bmN0aW9uIiwidG9Mb3dlckNhc2UiLCJkZWZhdWx0RXZlbnRGdW5jIiwiZXZlbnRGdW5jIiwicXVldWUiLCJxIiwic3hwU2Vzc2lvbk9uUGFnZURhdGEiXSwibWFwcGluZ3MiOiI7UUFBQTtRQUNBOztRQUVBO1FBQ0E7O1FBRUE7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7O1FBRUE7UUFDQTs7UUFFQTtRQUNBOztRQUVBO1FBQ0E7UUFDQTs7O1FBR0E7UUFDQTs7UUFFQTtRQUNBOztRQUVBO1FBQ0E7UUFDQTtRQUNBLDBDQUEwQyxnQ0FBZ0M7UUFDMUU7UUFDQTs7UUFFQTtRQUNBO1FBQ0E7UUFDQSx3REFBd0Qsa0JBQWtCO1FBQzFFO1FBQ0EsaURBQWlELGNBQWM7UUFDL0Q7O1FBRUE7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBLHlDQUF5QyxpQ0FBaUM7UUFDMUUsZ0hBQWdILG1CQUFtQixFQUFFO1FBQ3JJO1FBQ0E7O1FBRUE7UUFDQTtRQUNBO1FBQ0EsMkJBQTJCLDBCQUEwQixFQUFFO1FBQ3ZELGlDQUFpQyxlQUFlO1FBQ2hEO1FBQ0E7UUFDQTs7UUFFQTtRQUNBLHNEQUFzRCwrREFBK0Q7O1FBRXJIO1FBQ0E7OztRQUdBO1FBQ0E7Ozs7Ozs7Ozs7Ozs7O0FDbEZBO0FBRUUsV0FBVUEsTUFBVixFQUFrQkMsUUFBbEIsRUFBNEJDLFVBQTVCLEVBQXdDQyxVQUF4QyxFQUFxRDtBQUN0RCxNQUFLLENBQUVILE1BQVAsRUFBZ0I7QUFDZjtBQUNBLEdBSHFELENBS3REO0FBQ0E7QUFDQTtBQUVBO0FBQ0E7QUFDQTtBQUVBOzs7QUFDQSxNQUFNSSxVQUFVLEdBQUcsT0FBbkI7QUFDQSxNQUFNQyxVQUFVLEdBQUcsT0FBbkI7QUFDQSxNQUFNQyxjQUFjLEdBQUcsV0FBdkI7QUFDQSxNQUFNQyxlQUFlLEdBQUcsWUFBeEI7QUFFQSxNQUFNQyxPQUFPLEdBQUcsYUFBaEI7QUFDQSxNQUFNQyxTQUFTLEdBQUcsVUFBbEI7QUFDQSxNQUFNQyxHQUFHLEdBQUdWLE1BQU0sQ0FBQ1csT0FBbkI7QUFDQSxNQUFNQyxVQUFVLEdBQUcsWUFBbkI7QUFDQSxNQUFNQyxLQUFLLEdBQUcsR0FBZDtBQUNBLE1BQU1DLEdBQUcsR0FBR2QsTUFBTSxDQUFDZSxTQUFuQjtBQUNBLE1BQU1DLEdBQUcsR0FBR2hCLE1BQU0sQ0FBQ2lCLFFBQW5CO0FBQ0EsTUFBTUMsUUFBUSxHQUFHLGNBQWpCO0FBQ0EsTUFBTUMsWUFBWSxHQUFHRCxRQUFRLENBQUNFLE9BQVQsQ0FBa0IsSUFBSUMsTUFBSixDQUFZLE9BQU9KLFFBQVEsQ0FBQ0ssUUFBaEIsR0FBMkIsS0FBdkMsQ0FBbEIsRUFBa0UsRUFBbEUsQ0FBckI7QUFDQSxNQUFNQyxTQUFTLEdBQUdQLEdBQUcsQ0FBQ1EsUUFBSixDQUFhSixPQUFiLENBQXNCRCxZQUF0QixFQUFvQyxFQUFwQyxFQUF5Q0MsT0FBekMsQ0FBa0QsT0FBbEQsRUFBMkQsR0FBM0QsQ0FBbEI7QUFDQSxNQUFNSyxTQUFTLEdBQUdYLEdBQUcsQ0FBQ1csU0FBdEI7QUFDQSxNQUFNQyxVQUFVLEdBQUcsc0JBQW5CO0FBQ0EsTUFBTUMsc0JBQXNCLEdBQUdDLGtCQUEvQjtBQUNBLE1BQU1DLHNCQUFzQixHQUFHQyxrQkFBL0I7QUFDQSxNQUFNQyxTQUFTLEdBQUdDLElBQUksQ0FBQ0QsU0FBdkI7QUFDQSxNQUFNRSxRQUFRLEdBQUcsSUFBakI7QUFDQSxNQUFNQyxvQkFBb0IsR0FBR2xDLE1BQU0sQ0FBQ21DLGdCQUFwQztBQUNBLE1BQU1DLFlBQVksR0FBR0MsU0FBckI7QUFDQSxNQUFNQyxlQUFlLEdBQUdyQyxRQUFRLENBQUNxQyxlQUFULElBQTRCLEVBQXBEO0FBQ0EsTUFBTUMsUUFBUSxHQUFHLFVBQWpCO0FBQ0EsTUFBTUMsTUFBTSxHQUFHLFFBQWY7QUFDQSxNQUFNQyxLQUFLLEdBQUcsT0FBZDtBQUNBLE1BQU1DLE1BQU0sR0FBRyxRQUFmO0FBQ0EsTUFBTUMsWUFBWSxHQUFHRCxNQUFNLEdBQUdGLE1BQTlCO0FBQ0EsTUFBTUksWUFBWSxHQUFHLFdBQVdKLE1BQWhDO0FBQ0EsTUFBTUssWUFBWSxHQUFHLFdBQVdMLE1BQWhDO0FBQ0EsTUFBTU0sV0FBVyxHQUFHLFdBQVdMLEtBQS9CO0FBQ0EsTUFBTU0sTUFBTSxHQUFHL0MsTUFBTSxDQUFDK0MsTUFBdEI7QUFDQSxNQUFNQyxjQUFjLEdBQUcsWUFBdkI7O0FBRUEsTUFBTUMsSUFBSSxHQUFHLFNBQVBBLElBQU8sR0FBTTtBQUNsQixRQUFNQyxZQUFZLEdBQUdsRCxNQUFNLENBQUNtRCxNQUFQLElBQWlCbkQsTUFBTSxDQUFDb0QsUUFBN0M7QUFDQSxRQUFNQyxTQUFTLEdBQUcsQ0FBRSxHQUFGLElBQVUsQ0FBQyxHQUFYLEdBQWlCLENBQUMsR0FBbEIsR0FBd0IsQ0FBQyxHQUF6QixHQUErQixDQUFDLElBQWxEO0FBQ0EsUUFBTUMsU0FBUyxHQUFHLFFBQWxCOztBQUNBLFFBQUk7QUFDSCxhQUFPRCxTQUFTLENBQUNqQyxPQUFWLENBQW1Ca0MsU0FBbkIsRUFBOEIsVUFBQUMsQ0FBQztBQUFBLGVBQUksQ0FDekNBLENBQUMsR0FBS0wsWUFBWSxDQUFDTSxlQUFiLENBQThCLElBQUlDLFVBQUosQ0FBZ0IsQ0FBaEIsQ0FBOUIsRUFBcUQsQ0FBckQsSUFBNkQsTUFBUUYsQ0FBQyxHQUFHLENBRHRDLENBQzhDO0FBRDlDLFVBRXhDRyxRQUZ3QyxDQUU5QixFQUY4QixDQUFKO0FBQUEsT0FBL0IsQ0FBUDtBQUdBLEtBSkQsQ0FJRSxPQUFRQyxLQUFSLEVBQWdCO0FBQ2pCLGFBQU9OLFNBQVMsQ0FBQ2pDLE9BQVYsQ0FBbUJrQyxTQUFuQixFQUE4QixVQUFBQyxDQUFDLEVBQUk7QUFDeEMsWUFBTUssQ0FBQyxHQUFLQyxJQUFJLENBQUNDLE1BQUwsS0FBZ0IsRUFBbEIsR0FBeUIsQ0FBbkM7QUFBQSxZQUFzQztBQUNyQ0MsU0FBQyxHQUFHUixDQUFDLEdBQUcsQ0FBSixHQUFRSyxDQUFSLEdBQWNBLENBQUMsR0FBRyxHQUFOLEdBQWMsR0FEL0IsQ0FEd0MsQ0FFSjs7QUFDcEMsZUFBT0csQ0FBQyxDQUFDTCxRQUFGLENBQVksRUFBWixDQUFQO0FBQ0EsT0FKSyxDQUFQO0FBTUE7QUFDRCxHQWhCRDs7QUFpQkEsTUFBTU0sTUFBTSxHQUFHLFNBQVRBLE1BQVMsR0FBa0I7QUFDaEMsUUFBTUMsRUFBRSxHQUFHLEVBQVg7O0FBRGdDLHNDQUFiQyxPQUFhO0FBQWJBLGFBQWE7QUFBQTs7QUFFaEMsUUFBTUMsR0FBRyxHQUFHRCxPQUFaOztBQUNBLFNBQU0sSUFBSUUsS0FBSyxHQUFHLENBQWxCLEVBQXFCQSxLQUFLLEdBQUdELEdBQUcsQ0FBQ0UsTUFBakMsRUFBeUNELEtBQUssRUFBOUMsRUFBbUQ7QUFDbEQsVUFBTUUsVUFBVSxHQUFHSCxHQUFHLENBQUVDLEtBQUYsQ0FBdEI7O0FBQ0EsVUFBS0UsVUFBTCxFQUFrQjtBQUNqQixhQUFNLElBQU1DLE9BQVosSUFBdUJELFVBQXZCLEVBQW9DO0FBQ25DLGNBQUtFLE1BQU0sQ0FBQ0MsU0FBUCxDQUFpQkMsY0FBakIsQ0FBZ0NDLElBQWhDLENBQXNDTCxVQUF0QyxFQUFrREMsT0FBbEQsQ0FBTCxFQUFtRTtBQUNsRU4sY0FBRSxDQUFFTSxPQUFGLENBQUYsR0FBZ0JELFVBQVUsQ0FBRUMsT0FBRixDQUExQjtBQUNBO0FBQ0Q7QUFDRDtBQUNEOztBQUNELFdBQU9OLEVBQVA7QUFDQSxHQWREOztBQWdCQSxNQUFNVyxTQUFTLEdBQUcsU0FBWkEsU0FBWSxDQUFVQyxLQUFWLEVBQWtCO0FBQ25DO0FBQ0EsUUFBTUMsT0FBTyxHQUFHOUQsR0FBRyxDQUFDK0QsTUFBSixDQUFXQyxLQUFYLENBQWtCLElBQUkzRCxNQUFKLENBQVksVUFBVXdELEtBQVYsR0FBa0IsWUFBOUIsRUFBNEMsSUFBNUMsQ0FBbEIsQ0FBaEI7QUFDQSxRQUFNRyxLQUFLLEdBQUdGLE9BQU8sR0FBR0EsT0FBTyxDQUFDRyxHQUFSLENBQWEsVUFBQUMsQ0FBQztBQUFBLGFBQUlBLENBQUMsQ0FBQ0MsS0FBRixDQUFTLEdBQVQsRUFBZ0IsQ0FBaEIsQ0FBSjtBQUFBLEtBQWQsQ0FBSCxHQUE2QyxFQUFsRTs7QUFDQSxRQUFLSCxLQUFLLElBQUlBLEtBQUssQ0FBRSxDQUFGLENBQW5CLEVBQTJCO0FBQzFCLGFBQU9BLEtBQUssQ0FBRSxDQUFGLENBQVo7QUFDQTtBQUNELEdBUEQsQ0FsRnNELENBMkZ0RDtBQUNBO0FBQ0E7QUFFQTtBQUNBO0FBQ0E7OztBQUNBLE1BQU1JLEdBQUcsR0FBRyxhQUFaLENBbEdzRCxDQWtHM0I7O0FBQzNCLE1BQU1DLE9BQU8sR0FBRyxhQUFoQixDQW5Hc0QsQ0FtR3ZCOztBQW5HdUIsZ0JBeUdsRHJCLE1BQU0sQ0FDVDtBQUNDc0IsUUFBSSxFQUFFLEVBRFA7QUFDVztBQUNWQyxlQUFXLEVBQUUsRUFGZDtBQUVrQjtBQUNqQkMsZUFBVyxFQUFFLElBSGQ7QUFHb0I7QUFDbkJDLGFBQVMsRUFBRSxLQUpaLENBSW1COztBQUpuQixHQURTLEVBT1R2RixVQVBTLENBekc0QztBQUFBLE1BcUdyRG9GLElBckdxRCxXQXFHckRBLElBckdxRDtBQUFBLE1Bc0dyREMsV0F0R3FELFdBc0dyREEsV0F0R3FEO0FBQUEsTUF1R3JEQyxXQXZHcUQsV0F1R3JEQSxXQXZHcUQ7QUFBQSxNQXdHckRDLFNBeEdxRCxXQXdHckRBLFNBeEdxRCxFQXFIdEQ7QUFDQTtBQUNBOzs7QUFDQSxNQUFNQyxHQUFHLEdBQUdDLElBQUksQ0FBQ0QsR0FBakIsQ0F4SHNELENBMEh0RDs7QUFDQSxNQUFNRSxZQUFZLEdBQUcsU0FBZkEsWUFBZSxDQUFVQyxJQUFWLEVBQWlCO0FBQ3JDLFNBQU0sSUFBTUMsQ0FBWixJQUFpQlAsV0FBakIsRUFBK0I7QUFDOUIsVUFBTVEsYUFBYSxHQUFHUixXQUFXLENBQUVPLENBQUYsQ0FBakM7O0FBQ0EsVUFBSyxDQUFFQyxhQUFQLEVBQXVCO0FBQ3RCO0FBQ0EsT0FKNkIsQ0FLOUI7OztBQUNBLFVBQU1DLFVBQVUsR0FBR0QsYUFBYSxDQUFFLENBQUYsQ0FBYixLQUF1QixHQUF2QixHQUE2QkEsYUFBN0IsR0FBNkMsTUFBTUEsYUFBdEU7O0FBQ0EsVUFBSTtBQUNILFlBQUtDLFVBQVUsS0FBS0gsSUFBZixJQUF1QixJQUFJeEUsTUFBSixDQUFZMkUsVUFBVSxDQUFDNUUsT0FBWCxDQUFvQixNQUFwQixFQUE0QixNQUE1QixDQUFaLEVBQWtELElBQWxELEVBQXlENkUsSUFBekQsQ0FBK0RKLElBQS9ELENBQTVCLEVBQW9HO0FBQ25HLGlCQUFPLElBQVA7QUFDQTtBQUNELE9BSkQsQ0FJRSxPQUFRbEMsS0FBUixFQUFnQjtBQUNqQixlQUFPLEtBQVA7QUFDQTtBQUNEOztBQUNELFdBQU8sS0FBUDtBQUNBLEdBakJELENBM0hzRCxDQThJdEQ7QUFDQTtBQUNBO0FBRUE7OztBQUNBLE1BQU11QyxRQUFRLEdBQUcsU0FBWEEsUUFBVyxDQUFVQyxJQUFWLEVBQWdCQyxRQUFoQixFQUEyQjtBQUMzQ0QsUUFBSSxHQUFHbkMsTUFBTSxDQUFFcUMsT0FBRixFQUFXRixJQUFYLENBQWI7QUFFQUMsWUFBUSxHQUFLQSxRQUFRLElBQUksZUFBZSxPQUFPQSxRQUFwQyxHQUFpREEsUUFBakQsR0FBNEQsWUFBTSxDQUFFLENBQS9FLENBSDJDLENBSzNDOztBQUNBLFFBQUssRUFBSXBELGNBQWMsSUFBSWxDLEdBQXRCLENBQUwsRUFBbUM7QUFDbEMsVUFBTXdGLEtBQUssR0FBRyxJQUFJQyxLQUFKLEVBQWQ7O0FBQ0EsVUFBS0gsUUFBUSxJQUFJLGVBQWUsT0FBT0EsUUFBdkMsRUFBa0Q7QUFDakRFLGFBQUssQ0FBQ0UsT0FBTixHQUFnQkosUUFBaEI7QUFDQUUsYUFBSyxDQUFDRyxNQUFOLEdBQWVMLFFBQWY7QUFDQTs7QUFDREUsV0FBSyxDQUFDSSxHQUFOLEdBQVl0QixHQUFHLElBQUtBLEdBQUcsQ0FBQ3VCLE9BQUosQ0FBYSxHQUFiLElBQXFCLENBQUMsQ0FBdEIsR0FBMEIsR0FBMUIsR0FBZ0MsR0FBckMsQ0FBSCxHQUFnRCxVQUFoRCxHQUE2RDVFLFNBQVMsQ0FBRW9FLElBQUYsQ0FBbEY7QUFDQSxLQVBELE1BT087QUFDTnJGLFNBQUcsQ0FBRWtDLGNBQUYsQ0FBSCxDQUF1Qm9DLEdBQXZCLEVBQTRCckQsU0FBUyxDQUFFb0UsSUFBRixDQUFyQztBQUNBQyxjQUFRO0FBQ1I7QUFDRCxHQWpCRCxDQW5Kc0QsQ0FzS3REO0FBQ0E7QUFDQTtBQUVBOzs7QUFDQSxNQUFNUSxTQUFTLEdBQUcsU0FBWkEsU0FBWSxDQUFVQyxjQUFWLEVBQTJCO0FBQzVDQSxrQkFBYyxHQUFHQSxjQUFjLENBQUNDLE9BQWYsSUFBMEJELGNBQTNDO0FBQ0FFLFFBQUksQ0FBRUYsY0FBRixDQUFKO0FBQ0FYLFlBQVEsQ0FBRTtBQUNUYyxVQUFJLEVBQUU1RyxVQURHO0FBRVR1RCxXQUFLLEVBQUVrRCxjQUZFO0FBR1RJLFNBQUcsRUFBRS9GLFFBQVEsR0FBR0s7QUFIUCxLQUFGLENBQVI7QUFLQSxHQVJELENBM0tzRCxDQXFMdEQ7OztBQUNBLE1BQU13RixJQUFJLEdBQUcsU0FBUEEsSUFBTyxDQUFBRCxPQUFPLEVBQUk7QUFDdkI7QUFDQSxRQUFLcEcsR0FBRyxJQUFJQSxHQUFHLENBQUNxRyxJQUFoQixFQUF1QjtBQUN0QnJHLFNBQUcsQ0FBQ3FHLElBQUosQ0FBVSx3QkFBVixFQUFvQ0QsT0FBcEM7QUFDQTtBQUNELEdBTEQsQ0F0THNELENBNkx0RDs7O0FBQ0EsTUFBSyxDQUFFSSxLQUFLLENBQUNDLE9BQU4sQ0FBZTVCLFdBQWYsQ0FBUCxFQUFzQztBQUNyQyxRQUFLLE9BQU9BLFdBQVAsS0FBdUIsUUFBdkIsSUFBbUNBLFdBQVcsQ0FBQ2xCLE1BQXBELEVBQTZEO0FBQzVEa0IsaUJBQVcsR0FBR0EsV0FBVyxDQUFDSixLQUFaLENBQW1CLEtBQW5CLEVBQTJCRixHQUEzQixDQUFnQyxVQUFBbUMsSUFBSTtBQUFBLGVBQUlBLElBQUksQ0FBQ0MsSUFBTCxFQUFKO0FBQUEsT0FBcEMsQ0FBZDtBQUNBLEtBRkQsTUFFTztBQUNOOUIsaUJBQVcsR0FBRyxFQUFkO0FBQ0E7QUFDRCxHQXBNcUQsQ0FzTXREOzs7QUFDQSxNQUFNK0IsR0FBRyxHQUNSeEcsR0FBRyxDQUFDeUcsU0FBSixJQUNFdkgsTUFBTSxDQUFDMEUsY0FBUCxDQUF1QixhQUF2QixLQUEwQzFFLE1BQU0sQ0FBQ3dILFdBRG5ELElBRUEsaUJBQWlCeEgsTUFGakIsSUFFMkIsY0FBY0EsTUFGekMsSUFFbUQsYUFBYUEsTUFGaEUsSUFHQSxzQkFBc0JpRyxJQUF0QixDQUE0QnhFLFNBQTVCLENBSEEsSUFJQ3pCLE1BQU0sQ0FBQ3lILE1BQVAsS0FDQyxPQUFPM0csR0FBRyxDQUFDNEcsU0FBWCxJQUNBLEVBQUk1RyxHQUFHLENBQUM2RyxPQUFKLFlBQXVCQyxXQUEzQixDQUZELENBTEYsQ0F2TXNELENBa050RDs7QUFDQSxNQUFJQyxRQUFKOztBQUNBLE1BQUk7QUFDSEEsWUFBUSxHQUFHQyxJQUFJLENBQUNDLGNBQUwsR0FBc0JDLGVBQXRCLEdBQXdDQyxRQUFuRDtBQUNBLEdBRkQsQ0FFRSxPQUFRQyxDQUFSLEVBQVk7QUFDYjtBQUNBLEdBeE5xRCxDQTBOdEQ7QUFDQTtBQUNBOzs7QUFDQSxNQUFNN0IsT0FBTyxHQUFHO0FBQ2Y3RixXQUFPLEVBQVBBLE9BRGU7QUFFZjhHLE9BQUcsRUFBSEEsR0FGZTtBQUdmTyxZQUFRLEVBQVJBLFFBSGU7QUFJZk0sUUFBSSxFQUFFO0FBQ0xDLGlCQUFXLEVBQUVqSSxVQUFVLENBQUNrSSxjQURuQjtBQUVMQyxlQUFTLEVBQUVuSSxVQUFVLENBQUNvSTtBQUZqQjtBQUpTLEdBQWhCOztBQVVBLE1BQUk7QUFFSDtBQUNBO0FBQ0FyRyx3QkFBb0IsQ0FBRTlCLFVBQUYsRUFBYyxVQUFVb0ksS0FBVixFQUFrQjtBQUNuRCxVQUFLQSxLQUFLLENBQUNDLFFBQU4sSUFBa0JELEtBQUssQ0FBQ0MsUUFBTixDQUFlOUIsT0FBZixDQUF3QnRCLE9BQXhCLElBQW9DLENBQUMsQ0FBNUQsRUFBZ0U7QUFDL0R1QixpQkFBUyxDQUFFNEIsS0FBSyxDQUFDMUIsT0FBUixDQUFUO0FBQ0E7QUFDRCxLQUptQixFQUlqQixLQUppQixDQUFwQjtBQU1BLFFBQU00QixTQUFTLEdBQUcsV0FBbEI7QUFDQSxRQUFNQyxhQUFhLEdBQUczSSxNQUFNLENBQUMySSxhQUE3QjtBQUNBLFFBQU1DLFFBQVEsR0FBRyxVQUFqQjtBQUNBLFFBQUlDLEtBQUssR0FBR25ELEdBQUcsRUFBZjtBQUNBLFFBQUlvRCxRQUFRLEdBQUcsQ0FBZixDQWRHLENBZ0JIOztBQUNBLFFBQUssQ0FBRTdJLFFBQVEsQ0FBQzhJLE9BQWhCLEVBQTBCO0FBQ3pCaEMsVUFBSSxDQUFFLCtDQUFGLENBQUo7QUFDQSxLQW5CRSxDQXFCSDs7O0FBQ0EsUUFBSyxDQUFFdEIsU0FBRixJQUFlN0UsVUFBVSxJQUFJRSxHQUE3QixJQUFvQ0EsR0FBRyxDQUFFRixVQUFGLENBQUgsS0FBc0IsR0FBL0QsRUFBcUU7QUFDcEUsYUFBT21HLElBQUksQ0FBRXJGLFVBQVUsR0FBRyxPQUFiLEdBQXVCZCxVQUF2QixHQUFvQyxhQUF0QyxDQUFYO0FBQ0EsS0F4QkUsQ0EwQkg7QUFDQTtBQUNBOzs7QUFFQSxRQUFJd0csSUFBSSxHQUFHLEVBQVg7QUFDQSxRQUFJNEIsVUFBVSxHQUFHL0YsSUFBSSxFQUFyQjtBQUNBLFFBQUlnRyxZQUFKLENBaENHLENBa0NIO0FBQ0E7O0FBQ0EsUUFBSUMsUUFBUSxHQUFHLENBQUVqSixRQUFRLENBQUNpSixRQUFULElBQXFCLEVBQXZCLEVBQ2I5SCxPQURhLENBQ0oscURBREksRUFDbUQsSUFEbkQsRUFFYkEsT0FGYSxDQUVKLFdBRkksRUFFUyxJQUZULEtBRW1CZ0IsWUFGbEMsQ0FwQ0csQ0F3Q0g7O0FBQ0EsUUFBTStHLGNBQWMsR0FBRyxTQUF2QjtBQUNBLFFBQU1DLE1BQU0sR0FBRztBQUNkQSxZQUFNLEVBQUV4RSxTQUFTLENBQUV1RSxjQUFjLEdBQUcsWUFBbkIsQ0FESDtBQUVkRSxZQUFNLEVBQUV6RSxTQUFTLENBQUV1RSxjQUFjLEdBQUcsUUFBbkIsQ0FGSDtBQUdkRyxjQUFRLEVBQUUxRSxTQUFTLENBQUV1RSxjQUFjLEdBQUcsVUFBbkIsQ0FITDtBQUlkSSxVQUFJLEVBQUUzRSxTQUFTLENBQUV1RSxjQUFjLEdBQUcsTUFBbkIsQ0FKRDtBQUtkSyxhQUFPLEVBQUU1RSxTQUFTLENBQUV1RSxjQUFjLEdBQUcsU0FBbkIsQ0FMSjtBQU1kRCxjQUFRLEVBQVJBO0FBTmMsS0FBZixDQTFDRyxDQW1ESDtBQUNBO0FBQ0E7QUFFQTs7QUFDQSxRQUFJTyxRQUFRLEdBQUcsQ0FBZjtBQUVBLFFBQUlDLFdBQUo7QUFDQTFKLFVBQU0sQ0FBQ21DLGdCQUFQLENBQXlCLGtCQUF6QixFQUE2QyxZQUFXO0FBQ3ZELFVBQUtsQyxRQUFRLENBQUMwSixNQUFkLEVBQXVCO0FBQ3RCRCxtQkFBVyxHQUFHaEUsR0FBRyxFQUFqQjtBQUNBLE9BRkQsTUFFTztBQUNOK0QsZ0JBQVEsSUFBSS9ELEdBQUcsS0FBS2dFLFdBQXBCO0FBQ0E7QUFDRCxLQU5ELEVBTUcsS0FOSDs7QUFRQSxRQUFNRSxXQUFXLEdBQUcsU0FBZEEsV0FBYyxDQUFVQyxFQUFWLEVBQWNDLElBQWQsRUFBcUI7QUFDeEMsVUFBTUMsTUFBTSxHQUFHO0FBQ2QvQyxZQUFJLEVBQUV6RyxlQURRO0FBRWR5SixlQUFPLEVBQUVGLElBQUksR0FBR0QsRUFBSCxHQUFRYixVQUZQO0FBR2RKLGdCQUFRLEVBQUUvRSxJQUFJLENBQUNvRyxLQUFMLENBQVksQ0FBRXZFLEdBQUcsS0FBS21ELEtBQVIsR0FBZ0JZLFFBQWxCLElBQStCeEgsUUFBM0M7QUFISSxPQUFmO0FBTUF3SCxjQUFRLEdBQUcsQ0FBWDtBQUNBWixXQUFLLEdBQUduRCxHQUFHLEVBQVg7QUFDQXFFLFlBQU0sQ0FBQ2pCLFFBQVAsR0FBa0JqRixJQUFJLENBQUNxRyxHQUFMLENBQVUsQ0FBVixFQUFhcEIsUUFBYixFQUF1QnFCLFFBQVEsRUFBL0IsQ0FBbEI7QUFDQWpFLGNBQVEsQ0FBRTZELE1BQUYsQ0FBUjtBQUNBLEtBWEQ7O0FBYUE3SCx3QkFBb0IsQ0FBRSxRQUFGLEVBQVkwSCxXQUFaLEVBQXlCLEtBQXpCLENBQXBCO0FBRUEsUUFBTVEsSUFBSSxHQUFHbkssUUFBUSxDQUFDbUssSUFBVCxJQUFpQixFQUE5Qjs7QUFFQSxRQUFNRCxRQUFRLEdBQUcsU0FBWEEsUUFBVyxHQUFNO0FBQ3RCLFVBQUk7QUFDSCxZQUFNRSxvQkFBb0IsR0FBRy9ILGVBQWUsQ0FBRU8sWUFBRixDQUFmLElBQW1DLENBQWhFO0FBQ0EsWUFBTXlILE1BQU0sR0FBR3pHLElBQUksQ0FBQ3FHLEdBQUwsQ0FBVUUsSUFBSSxDQUFFekgsWUFBRixDQUFKLElBQXdCLENBQWxDLEVBQXFDeUgsSUFBSSxDQUFFeEgsWUFBRixDQUFKLElBQXdCLENBQTdELEVBQWdFTixlQUFlLENBQUVPLFlBQUYsQ0FBZixJQUFtQyxDQUFuRyxFQUFzR1AsZUFBZSxDQUFFSyxZQUFGLENBQWYsSUFBbUMsQ0FBekksRUFBNElMLGVBQWUsQ0FBRU0sWUFBRixDQUFmLElBQW1DLENBQS9LLENBQWY7QUFDQSxlQUFPaUIsSUFBSSxDQUFDMEcsR0FBTCxDQUFVLEdBQVYsRUFBZTFHLElBQUksQ0FBQ29HLEtBQUwsQ0FBYyxPQUFRLENBQUUzSCxlQUFlLENBQUNrSSxTQUFoQixJQUE2QixDQUEvQixJQUFxQ0gsb0JBQTdDLENBQUYsR0FBMEVDLE1BQTFFLEdBQW1GLENBQS9GLElBQXFHLENBQXBILENBQVA7QUFDQSxPQUpELENBSUUsT0FBUTNHLEtBQVIsRUFBZ0I7QUFDakIsZUFBTyxDQUFQO0FBQ0E7QUFDRCxLQVJEOztBQVVBekIsd0JBQW9CLENBQUUsTUFBRixFQUFVLFlBQVc7QUFDeEM0RyxjQUFRLEdBQUdxQixRQUFRLEVBQW5CO0FBQ0FqSSwwQkFBb0IsQ0FBRVEsTUFBRixFQUFVLFlBQVc7QUFDeEMsWUFBS29HLFFBQVEsR0FBR3FCLFFBQVEsRUFBeEIsRUFBNkI7QUFDNUJyQixrQkFBUSxHQUFHcUIsUUFBUSxFQUFuQjtBQUNBO0FBQ0QsT0FKbUIsRUFJakIsS0FKaUIsQ0FBcEI7QUFLQSxLQVBtQixDQUFwQixDQTlGRyxDQXVHSDtBQUNBO0FBQ0E7O0FBRUEsUUFBTU0sT0FBTyxHQUFHLFNBQVZBLE9BQVUsQ0FBVUMsU0FBVixFQUFzQjtBQUNyQyxVQUFJN0UsSUFBSSxHQUFHNkUsU0FBUyxJQUFJN0ksc0JBQXNCLENBQUVOLFNBQUYsQ0FBOUMsQ0FEcUMsQ0FFckM7O0FBQ0EsVUFBS3FFLFlBQVksQ0FBRUMsSUFBRixDQUFqQixFQUE0QjtBQUMzQmtCLFlBQUksQ0FBRXJGLFVBQVUsR0FBRyxVQUFiLEdBQTBCbUUsSUFBMUIsR0FBaUMsYUFBbkMsQ0FBSjtBQUNBO0FBQ0EsT0FOb0MsQ0FPckM7OztBQUNBLFVBQUtQLElBQUksS0FBSyxNQUFULElBQW1CdEUsR0FBRyxDQUFDMkosSUFBNUIsRUFBbUM7QUFDbEM5RSxZQUFJLElBQUk3RSxHQUFHLENBQUMySixJQUFKLENBQVN4RixLQUFULENBQWdCLEdBQWhCLEVBQXVCLENBQXZCLENBQVI7QUFDQTs7QUFFRCxhQUFPVSxJQUFQO0FBQ0EsS0FiRCxDQTNHRyxDQTBISDs7O0FBQ0EsUUFBTStFLFlBQVksR0FBRyxTQUFmQSxZQUFlLENBQVVDLFdBQVYsRUFBdUJDLGdCQUF2QixFQUF5Q0MsUUFBekMsRUFBb0Q7QUFFeEUsVUFBS0YsV0FBTCxFQUFtQjtBQUNsQmpCLG1CQUFXLENBQUUsS0FBS1osVUFBUCxFQUFtQixJQUFuQixDQUFYO0FBQ0E7O0FBRURBLGdCQUFVLEdBQUcvRixJQUFJLEVBQWpCO0FBQ0FtRSxVQUFJLENBQUM0QyxPQUFMLEdBQWVoQixVQUFmO0FBRUEsVUFBTWdDLFdBQVcsR0FBRzlKLFFBQVEsR0FBR3VKLE9BQU8sRUFBdEM7QUFFQXZFLGNBQVEsQ0FDUGxDLE1BQU0sQ0FDTG9ELElBREssRUFFTDBELGdCQUFnQixHQUFHO0FBQUU1QixnQkFBUSxFQUFFNkIsUUFBUSxHQUFHN0IsUUFBSCxHQUFjO0FBQWxDLE9BQUgsR0FBOENFLE1BRnpELEVBR0w7QUFBRXBDLFlBQUksRUFBRTFHO0FBQVIsT0FISyxDQURDLENBQVI7QUFRQTRJLGNBQVEsR0FBRzhCLFdBQVg7QUFDQSxLQXBCRDs7QUFzQkEsUUFBTUMsUUFBUSxHQUFHLFNBQVhBLFFBQVcsQ0FBVUosV0FBVixFQUF1QkssYUFBdkIsRUFBdUM7QUFDdkQ7QUFDQSxVQUFNckYsSUFBSSxHQUFHNEUsT0FBTyxDQUFFUyxhQUFGLENBQXBCLENBRnVELENBSXZEOztBQUNBLFVBQUssQ0FBRXJGLElBQUYsSUFBVW9ELFlBQVksS0FBS3BELElBQWhDLEVBQXVDO0FBQ3RDO0FBQ0E7O0FBRURvRCxrQkFBWSxHQUFHcEQsSUFBZjtBQUVBLFVBQU1NLElBQUksR0FBRztBQUNaTixZQUFJLEVBQUpBLElBRFk7QUFFWnNGLHNCQUFjLEVBQUV0SCxJQUFJLENBQUNxRyxHQUFMLENBQVU1SCxlQUFlLENBQUVRLFdBQUYsQ0FBZixJQUFrQyxDQUE1QyxFQUErQzlDLE1BQU0sQ0FBQ29MLFVBQVAsSUFBcUIsQ0FBcEUsS0FBMkUsSUFGL0U7QUFHWkMsdUJBQWUsRUFBRXhILElBQUksQ0FBQ3FHLEdBQUwsQ0FBVTVILGVBQWUsQ0FBRU8sWUFBRixDQUFmLElBQW1DLENBQTdDLEVBQWdEN0MsTUFBTSxDQUFDc0wsV0FBUCxJQUFzQixDQUF0RSxLQUE4RSxJQUhuRjtBQUlaQyxvQkFBWSxFQUFFeEksTUFBTSxDQUFDeUksS0FKVDtBQUtaQyxxQkFBYSxFQUFFMUksTUFBTSxDQUFDdUg7QUFMVixPQUFiOztBQVFBLFVBQUt4SixHQUFHLENBQUV5QixRQUFGLENBQVIsRUFBdUI7QUFDdEI0RCxZQUFJLENBQUU1RCxRQUFGLENBQUosR0FBbUJ6QixHQUFHLENBQUV5QixRQUFGLENBQXRCO0FBQ0EsT0FyQnNELENBdUJ2RDs7O0FBQ0EsVUFBTW1KLElBQUksR0FBRzFMLE1BQU0sQ0FBQzJMLFdBQXBCO0FBQ0EsVUFBTUMsVUFBVSxHQUFHLFlBQW5CLENBekJ1RCxDQTJCdkQ7O0FBQ0EsVUFBTUMsYUFBYSxHQUNsQkgsSUFBSSxJQUNKQSxJQUFJLENBQUNJLGdCQURMLElBRUFKLElBQUksQ0FBQ0ksZ0JBQUwsQ0FBdUJGLFVBQXZCLEVBQXFDLENBQXJDLENBRkEsSUFHQUYsSUFBSSxDQUFDSSxnQkFBTCxDQUF1QkYsVUFBdkIsRUFBcUMsQ0FBckMsRUFBeUM1RSxJQUh6QyxHQUlHLENBQUUsUUFBRixFQUFZLGNBQVosRUFBNkJMLE9BQTdCLENBQXNDK0UsSUFBSSxDQUFDSSxnQkFBTCxDQUF1QkYsVUFBdkIsRUFBcUMsQ0FBckMsRUFBeUM1RSxJQUEvRSxJQUF3RixDQUFDLENBSjVGLEdBS0cwRSxJQUFJLElBQUlBLElBQUksQ0FBRUUsVUFBRixDQUFaLElBQThCLENBQUUsQ0FBRixFQUFLLENBQUwsRUFBU2pGLE9BQVQsQ0FBa0IrRSxJQUFJLENBQUVFLFVBQUYsQ0FBSixDQUFtQjVFLElBQXJDLElBQThDLENBQUMsQ0FOakYsQ0E1QnVELENBa0M2QjtBQUVwRjs7QUFDQSxVQUFNK0QsUUFBUSxHQUFHN0IsUUFBUSxHQUFHQSxRQUFRLENBQUMvRCxLQUFULENBQWdCdEUsS0FBaEIsRUFBeUIsQ0FBekIsTUFBaUNLLFFBQXBDLEdBQStDLEtBQXhFLENBckN1RCxDQXVDdkQ7O0FBQ0FpRixVQUFJLENBQUM0RixTQUFMLEdBQWlCbEIsV0FBVyxJQUFJZ0IsYUFBZixHQUErQixLQUEvQixHQUF1QyxDQUFFZCxRQUExRDtBQUVBM0QsVUFBSSxHQUFHakIsSUFBUDtBQUVBeUUsa0JBQVksQ0FBRUMsV0FBRixFQUFlQSxXQUFXLElBQUlnQixhQUE5QixFQUE2Q2QsUUFBN0MsQ0FBWjtBQUNBLEtBN0NELENBakpHLENBZ01IO0FBQ0E7QUFDQTs7O0FBRUEsUUFBTWlCLEdBQUcsR0FBR2hNLE1BQU0sQ0FBQ2lNLE9BQW5CO0FBQ0EsUUFBTUMsWUFBWSxHQUFHRixHQUFHLEdBQUdBLEdBQUcsQ0FBQ3RELFNBQVAsR0FBbUJ0RyxZQUEzQyxDQXJNRyxDQXVNSDtBQUNBOztBQUNBLFFBQUtvRCxXQUFXLElBQUkwRyxZQUFmLElBQStCQyxLQUEvQixJQUF3Q3hELGFBQTdDLEVBQTZEO0FBQzVELFVBQU15RCxhQUFhLEdBQUcsU0FBaEJBLGFBQWdCLENBQVVwRixJQUFWLEVBQWlCO0FBQ3RDLFlBQU1xRixJQUFJLEdBQUdMLEdBQUcsQ0FBRWhGLElBQUYsQ0FBaEI7QUFDQSxlQUFPLFlBQVc7QUFDakIsY0FBTTdDLEdBQUcsR0FBR21JLFNBQVo7QUFDQSxjQUFNQyxFQUFFLEdBQUdGLElBQUksQ0FBQ0csS0FBTCxDQUFZLElBQVosRUFBa0JySSxHQUFsQixDQUFYO0FBQ0EsY0FBSXFFLEtBQUo7O0FBQ0EsY0FBSyxPQUFPMkQsS0FBUCxLQUFpQixVQUF0QixFQUFtQztBQUNsQzNELGlCQUFLLEdBQUcsSUFBSTJELEtBQUosQ0FBV25GLElBQVgsQ0FBUjtBQUNBLFdBRkQsTUFFTztBQUNOO0FBQ0F3QixpQkFBSyxHQUFHdkksUUFBUSxDQUFDd00sV0FBVCxDQUFzQixPQUF0QixDQUFSO0FBQ0FqRSxpQkFBSyxDQUFDa0UsU0FBTixDQUFpQjFGLElBQWpCLEVBQXVCLElBQXZCLEVBQTZCLElBQTdCO0FBQ0E7O0FBQ0R3QixlQUFLLENBQUM4RCxTQUFOLEdBQWtCbkksR0FBbEI7QUFDQXdFLHVCQUFhLENBQUVILEtBQUYsQ0FBYjtBQUNBLGlCQUFPK0QsRUFBUDtBQUNBLFNBZEQ7QUFlQSxPQWpCRDs7QUFtQkFQLFNBQUcsQ0FBQ3RELFNBQUosR0FBZ0IwRCxhQUFhLENBQUUxRCxTQUFGLENBQTdCO0FBRUF4RywwQkFBb0IsQ0FBRXdHLFNBQUYsRUFBYSxZQUFXO0FBQzNDdUMsZ0JBQVEsQ0FBRSxDQUFGLENBQVI7QUFDQSxPQUZtQixFQUVqQixLQUZpQixDQUFwQjtBQUlBL0ksMEJBQW9CLENBQUUsVUFBRixFQUFjLFlBQVc7QUFDNUMrSSxnQkFBUSxDQUFFLENBQUYsQ0FBUjtBQUNBLE9BRm1CLEVBRWpCLEtBRmlCLENBQXBCO0FBR0EsS0F0T0UsQ0F3T0g7OztBQUNBLFFBQUt6RixXQUFXLElBQUlGLElBQUksS0FBSyxNQUF4QixJQUFrQyxrQkFBa0J0RixNQUF6RCxFQUFrRTtBQUNqRWtDLDBCQUFvQixDQUFFLFlBQUYsRUFBZ0IsWUFBVztBQUM5QytJLGdCQUFRLENBQUUsQ0FBRixDQUFSO0FBQ0EsT0FGbUIsRUFFakIsS0FGaUIsQ0FBcEI7QUFHQSxLQTdPRSxDQStPSDtBQUNBOzs7QUFDQSxRQUFLekYsV0FBTCxFQUFtQjtBQUNsQnlGLGNBQVE7QUFDUixLQUZELE1BRU87QUFDTmpMLFlBQU0sQ0FBQzJNLFdBQVAsR0FBcUIsVUFBVTlHLElBQVYsRUFBaUI7QUFDckNvRixnQkFBUSxDQUFFLENBQUYsRUFBS3BGLElBQUwsQ0FBUjtBQUNBLE9BRkQ7QUFHQSxLQXZQRSxDQXlQSDtBQUNBO0FBQ0E7QUFDQTs7O0FBRUEsUUFBTStHLFVBQVUsR0FBRyxDQUFFLFFBQUYsRUFBWSxRQUFaLENBQW5COztBQUVBLFFBQU1DLFNBQVMsR0FBRyxTQUFaQSxTQUFZLENBQVVyRSxLQUFWLEVBQWlCTCxJQUFqQixFQUF1QjJFLFdBQXZCLEVBQXFDO0FBQ3RELFVBQU1DLFVBQVUsR0FBR3ZFLEtBQUssWUFBWXdFLFFBQXBDO0FBQ0EsVUFBTTVHLFFBQVEsR0FBRzBHLFdBQVcsWUFBWUUsUUFBdkIsR0FBa0NGLFdBQWxDLEdBQWdELFlBQU0sQ0FBRSxDQUF6RTs7QUFFQSxVQUFLRixVQUFVLENBQUNqRyxPQUFYLFNBQTJCNkIsS0FBM0IsS0FBcUMsQ0FBckMsSUFBMEMsQ0FBRXVFLFVBQWpELEVBQThEO0FBQzdEaEcsWUFBSSxDQUFFLDRCQUE0QnlCLEtBQTlCLENBQUo7QUFDQSxlQUFPcEMsUUFBUSxFQUFmO0FBQ0E7O0FBRUQsVUFBSTtBQUNILFlBQUsyRyxVQUFMLEVBQWtCO0FBQ2pCLGNBQUtILFVBQVUsQ0FBQ2pHLE9BQVgsU0FBMkI2QixLQUEzQixLQUFxQyxDQUExQyxFQUE4QztBQUM3Q3pCLGdCQUFJLENBQUUsNENBQTRDeUIsS0FBOUMsQ0FBSjtBQUNBLG1CQUFPcEMsUUFBUSxFQUFmO0FBQ0E7QUFDRDtBQUNELE9BUEQsQ0FPRSxPQUFRekMsS0FBUixFQUFnQjtBQUNqQm9ELFlBQUksQ0FBRSxtQ0FBbUNwRCxLQUFLLENBQUNtRCxPQUEzQyxDQUFKO0FBQ0EsZUFBT1YsUUFBUSxFQUFmO0FBQ0E7O0FBRURvQyxXQUFLLEdBQUcsQ0FBRSxLQUFLQSxLQUFQLEVBQ05wSCxPQURNLENBQ0csY0FESCxFQUNtQixHQURuQixFQUVOQSxPQUZNLENBRUcsVUFGSCxFQUVlLEVBRmYsRUFHTjZMLFdBSE0sRUFBUjs7QUFLQSxVQUFLekUsS0FBTCxFQUFhO0FBQ1p0QyxnQkFBUSxDQUNQbEMsTUFBTSxDQUNMb0YsTUFESyxFQUVMOUIsR0FGSyxFQUdMO0FBQ0NhLGNBQUksRUFBRTtBQUFFSyxpQkFBSyxFQUFFTDtBQUFUO0FBRFAsU0FISyxFQU1MO0FBQ0NuQixjQUFJLEVBQUUzRyxVQURQO0FBRUNtSSxlQUFLLEVBQUxBLEtBRkQ7QUFFUTtBQUNQd0IsaUJBQU8sRUFBRTVDLElBQUksQ0FBQzRDO0FBSGYsU0FOSyxDQURDLEVBYVA1RCxRQWJPLENBQVI7QUFlQTtBQUNELEtBM0NEOztBQTZDQSxRQUFNOEcsZ0JBQWdCLEdBQUcsU0FBbkJBLGdCQUFtQixDQUFVMUUsS0FBVixFQUFpQnJDLElBQWpCLEVBQXVCQyxRQUF2QixFQUFrQztBQUMxRHlHLGVBQVMsQ0FBRXJFLEtBQUYsRUFBU3JDLElBQVQsRUFBZUMsUUFBZixDQUFUO0FBQ0EsS0FGRCxDQTdTRyxDQWlUSDs7O0FBQ0EsUUFBSyxDQUFFcEcsTUFBTSxDQUFFUyxTQUFGLENBQWIsRUFBNkI7QUFDNUJULFlBQU0sQ0FBRVMsU0FBRixDQUFOLEdBQXNCeU0sZ0JBQXRCO0FBQ0E7O0FBRUQsUUFBTUMsU0FBUyxHQUFHbk4sTUFBTSxDQUFFUyxTQUFGLENBQXhCLENBdFRHLENBd1RIOztBQUNBLFFBQU0yTSxLQUFLLEdBQUdELFNBQVMsSUFBSUEsU0FBUyxDQUFDRSxDQUF2QixHQUEyQkYsU0FBUyxDQUFDRSxDQUFyQyxHQUF5QyxFQUF2RCxDQXpURyxDQTJUSDs7QUFDQXJOLFVBQU0sQ0FBRVMsU0FBRixDQUFOLEdBQXNCeU0sZ0JBQXRCLENBNVRHLENBOFRIOztBQUNBLFNBQU0sSUFBTTFFLEtBQVosSUFBcUI0RSxLQUFyQixFQUE2QjtBQUM1QlAsZUFBUyxDQUFFTyxLQUFLLENBQUU1RSxLQUFGLENBQVAsQ0FBVDtBQUNBO0FBQ0QsR0FsVUQsQ0FrVUUsT0FBUU4sQ0FBUixFQUFZO0FBQ2J0QixhQUFTLENBQUVzQixDQUFGLENBQVQ7QUFDQTtBQUNELENBNWlCQyxFQTRpQkNsSSxNQTVpQkQsRUE0aUJTQyxRQTVpQlQsRUE0aUJtQixxQkE1aUJuQixFQTRpQjBDcU4sb0JBNWlCMUMsQ0FBRixDIiwiZmlsZSI6Ii4vYXNzZXRzL2pzL3RyYWNrZXIuanMiLCJzb3VyY2VzQ29udGVudCI6WyIgXHQvLyBUaGUgbW9kdWxlIGNhY2hlXG4gXHR2YXIgaW5zdGFsbGVkTW9kdWxlcyA9IHt9O1xuXG4gXHQvLyBUaGUgcmVxdWlyZSBmdW5jdGlvblxuIFx0ZnVuY3Rpb24gX193ZWJwYWNrX3JlcXVpcmVfXyhtb2R1bGVJZCkge1xuXG4gXHRcdC8vIENoZWNrIGlmIG1vZHVsZSBpcyBpbiBjYWNoZVxuIFx0XHRpZihpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSkge1xuIFx0XHRcdHJldHVybiBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXS5leHBvcnRzO1xuIFx0XHR9XG4gXHRcdC8vIENyZWF0ZSBhIG5ldyBtb2R1bGUgKGFuZCBwdXQgaXQgaW50byB0aGUgY2FjaGUpXG4gXHRcdHZhciBtb2R1bGUgPSBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSA9IHtcbiBcdFx0XHRpOiBtb2R1bGVJZCxcbiBcdFx0XHRsOiBmYWxzZSxcbiBcdFx0XHRleHBvcnRzOiB7fVxuIFx0XHR9O1xuXG4gXHRcdC8vIEV4ZWN1dGUgdGhlIG1vZHVsZSBmdW5jdGlvblxuIFx0XHRtb2R1bGVzW21vZHVsZUlkXS5jYWxsKG1vZHVsZS5leHBvcnRzLCBtb2R1bGUsIG1vZHVsZS5leHBvcnRzLCBfX3dlYnBhY2tfcmVxdWlyZV9fKTtcblxuIFx0XHQvLyBGbGFnIHRoZSBtb2R1bGUgYXMgbG9hZGVkXG4gXHRcdG1vZHVsZS5sID0gdHJ1ZTtcblxuIFx0XHQvLyBSZXR1cm4gdGhlIGV4cG9ydHMgb2YgdGhlIG1vZHVsZVxuIFx0XHRyZXR1cm4gbW9kdWxlLmV4cG9ydHM7XG4gXHR9XG5cblxuIFx0Ly8gZXhwb3NlIHRoZSBtb2R1bGVzIG9iamVjdCAoX193ZWJwYWNrX21vZHVsZXNfXylcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubSA9IG1vZHVsZXM7XG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlIGNhY2hlXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmMgPSBpbnN0YWxsZWRNb2R1bGVzO1xuXG4gXHQvLyBkZWZpbmUgZ2V0dGVyIGZ1bmN0aW9uIGZvciBoYXJtb255IGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uZCA9IGZ1bmN0aW9uKGV4cG9ydHMsIG5hbWUsIGdldHRlcikge1xuIFx0XHRpZighX193ZWJwYWNrX3JlcXVpcmVfXy5vKGV4cG9ydHMsIG5hbWUpKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIG5hbWUsIHsgZW51bWVyYWJsZTogdHJ1ZSwgZ2V0OiBnZXR0ZXIgfSk7XG4gXHRcdH1cbiBcdH07XG5cbiBcdC8vIGRlZmluZSBfX2VzTW9kdWxlIG9uIGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uciA9IGZ1bmN0aW9uKGV4cG9ydHMpIHtcbiBcdFx0aWYodHlwZW9mIFN5bWJvbCAhPT0gJ3VuZGVmaW5lZCcgJiYgU3ltYm9sLnRvU3RyaW5nVGFnKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIFN5bWJvbC50b1N0cmluZ1RhZywgeyB2YWx1ZTogJ01vZHVsZScgfSk7XG4gXHRcdH1cbiBcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsICdfX2VzTW9kdWxlJywgeyB2YWx1ZTogdHJ1ZSB9KTtcbiBcdH07XG5cbiBcdC8vIGNyZWF0ZSBhIGZha2UgbmFtZXNwYWNlIG9iamVjdFxuIFx0Ly8gbW9kZSAmIDE6IHZhbHVlIGlzIGEgbW9kdWxlIGlkLCByZXF1aXJlIGl0XG4gXHQvLyBtb2RlICYgMjogbWVyZ2UgYWxsIHByb3BlcnRpZXMgb2YgdmFsdWUgaW50byB0aGUgbnNcbiBcdC8vIG1vZGUgJiA0OiByZXR1cm4gdmFsdWUgd2hlbiBhbHJlYWR5IG5zIG9iamVjdFxuIFx0Ly8gbW9kZSAmIDh8MTogYmVoYXZlIGxpa2UgcmVxdWlyZVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy50ID0gZnVuY3Rpb24odmFsdWUsIG1vZGUpIHtcbiBcdFx0aWYobW9kZSAmIDEpIHZhbHVlID0gX193ZWJwYWNrX3JlcXVpcmVfXyh2YWx1ZSk7XG4gXHRcdGlmKG1vZGUgJiA4KSByZXR1cm4gdmFsdWU7XG4gXHRcdGlmKChtb2RlICYgNCkgJiYgdHlwZW9mIHZhbHVlID09PSAnb2JqZWN0JyAmJiB2YWx1ZSAmJiB2YWx1ZS5fX2VzTW9kdWxlKSByZXR1cm4gdmFsdWU7XG4gXHRcdHZhciBucyA9IE9iamVjdC5jcmVhdGUobnVsbCk7XG4gXHRcdF9fd2VicGFja19yZXF1aXJlX18ucihucyk7XG4gXHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShucywgJ2RlZmF1bHQnLCB7IGVudW1lcmFibGU6IHRydWUsIHZhbHVlOiB2YWx1ZSB9KTtcbiBcdFx0aWYobW9kZSAmIDIgJiYgdHlwZW9mIHZhbHVlICE9ICdzdHJpbmcnKSBmb3IodmFyIGtleSBpbiB2YWx1ZSkgX193ZWJwYWNrX3JlcXVpcmVfXy5kKG5zLCBrZXksIGZ1bmN0aW9uKGtleSkgeyByZXR1cm4gdmFsdWVba2V5XTsgfS5iaW5kKG51bGwsIGtleSkpO1xuIFx0XHRyZXR1cm4gbnM7XG4gXHR9O1xuXG4gXHQvLyBnZXREZWZhdWx0RXhwb3J0IGZ1bmN0aW9uIGZvciBjb21wYXRpYmlsaXR5IHdpdGggbm9uLWhhcm1vbnkgbW9kdWxlc1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5uID0gZnVuY3Rpb24obW9kdWxlKSB7XG4gXHRcdHZhciBnZXR0ZXIgPSBtb2R1bGUgJiYgbW9kdWxlLl9fZXNNb2R1bGUgP1xuIFx0XHRcdGZ1bmN0aW9uIGdldERlZmF1bHQoKSB7IHJldHVybiBtb2R1bGVbJ2RlZmF1bHQnXTsgfSA6XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0TW9kdWxlRXhwb3J0cygpIHsgcmV0dXJuIG1vZHVsZTsgfTtcbiBcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kKGdldHRlciwgJ2EnLCBnZXR0ZXIpO1xuIFx0XHRyZXR1cm4gZ2V0dGVyO1xuIFx0fTtcblxuIFx0Ly8gT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm8gPSBmdW5jdGlvbihvYmplY3QsIHByb3BlcnR5KSB7IHJldHVybiBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGwob2JqZWN0LCBwcm9wZXJ0eSk7IH07XG5cbiBcdC8vIF9fd2VicGFja19wdWJsaWNfcGF0aF9fXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnAgPSBcIlwiO1xuXG5cbiBcdC8vIExvYWQgZW50cnkgbW9kdWxlIGFuZCByZXR1cm4gZXhwb3J0c1xuIFx0cmV0dXJuIF9fd2VicGFja19yZXF1aXJlX18oX193ZWJwYWNrX3JlcXVpcmVfXy5zID0gMik7XG4iLCIvKiBlc2xpbnQtZW52IGJyb3dzZXIgKi9cblxuKCBmdW5jdGlvbiggd2luZG93LCBkb2N1bWVudCwgc3hwT3B0aW9ucywgb25QYWdlRGF0YSApIHtcblx0aWYgKCAhIHdpbmRvdyApIHtcblx0XHRyZXR1cm47XG5cdH1cblx0XG5cdC8vLy8vLy8vLy8vLy8vLy8vLy8vL1xuXHQvLyBQUkVERUZJTkVEIFZBUklBQkxFUyBGT1IgQkVUVEVSIE1JTklGSUNBVElPTlxuXHQvL1xuXHRcblx0Ly8gVGhpcyBzZWVtcyBsaWtlIGEgbG90IG9mIHJlcGV0aXRpb24sIGJ1dCBpdCBtYWtlcyBvdXIgc2NyaXB0IGF2YWlsYWJsZSBmb3Jcblx0Ly8gbXVsdGlwbGUgZGVzdGluYXRpb24gd2hpY2ggcHJldmVudHMgdXMgdG8gbmVlZCBtdWx0aXBsZSBzY3JpcHRzLiBUaGUgbWluaWZpZWRcblx0Ly8gdmVyc2lvbiBzdGF5cyBzbWFsbC5cblx0XG5cdC8vIENvbGxlY3Rpb24gVFlQRVNcblx0Y29uc3QgVFlQRV9FUlJPUiA9ICdlcnJvcic7XG5cdGNvbnN0IFRZUEVfRVZFTlQgPSAnZXZlbnQnO1xuXHRjb25zdCBUWVBFX1BBR0VfVklFVyA9ICdwYWdlLXZpZXcnO1xuXHRjb25zdCBUWVBFX1BBR0VfTEVBVkUgPSAncGFnZS1sZWF2ZSc7XG5cdFxuXHRjb25zdCB2ZXJzaW9uID0gJ3t7VkVSU0lPTn19Jztcblx0Y29uc3Qgc3hwR2xvYmFsID0gJ3N4cEV2ZW50Jztcblx0Y29uc3QgY29uID0gd2luZG93LmNvbnNvbGU7XG5cdGNvbnN0IGRvTm90VHJhY2sgPSAnZG9Ob3RUcmFjayc7XG5cdGNvbnN0IHNsYXNoID0gJy8nO1xuXHRjb25zdCBuYXYgPSB3aW5kb3cubmF2aWdhdG9yO1xuXHRjb25zdCBsb2MgPSB3aW5kb3cubG9jYXRpb247XG5cdGNvbnN0IFNJVEVfVVJMID0gJ3t7U0lURV9VUkx9fSc7XG5cdGNvbnN0IHBhdGhfcmVwbGFjZSA9IFNJVEVfVVJMLnJlcGxhY2UoIG5ldyBSZWdFeHAoICcuKicgKyBsb2NhdGlvbi5ob3N0bmFtZSArICdcXC8/JyApLCAnJyApO1xuXHRjb25zdCBQQVRIX05BTUUgPSBsb2MucGF0aG5hbWUucmVwbGFjZSggcGF0aF9yZXBsYWNlLCAnJyApLnJlcGxhY2UoIC9eXFwvXFwvLywgJy8nICk7XG5cdGNvbnN0IHVzZXJBZ2VudCA9IG5hdi51c2VyQWdlbnQ7XG5cdGNvbnN0IG5vdFNlbmRpbmcgPSAnTm90IHNlbmRpbmcgcmVxdWVzdCAnO1xuXHRjb25zdCBlbmNvZGVVUklDb21wb25lbnRGdW5jID0gZW5jb2RlVVJJQ29tcG9uZW50O1xuXHRjb25zdCBkZWNvZGVVUklDb21wb25lbnRGdW5jID0gZGVjb2RlVVJJQ29tcG9uZW50O1xuXHRjb25zdCBzdHJpbmdpZnkgPSBKU09OLnN0cmluZ2lmeTtcblx0Y29uc3QgdGhvdXNhbmQgPSAxMDAwO1xuXHRjb25zdCBhZGRFdmVudExpc3RlbmVyRnVuYyA9IHdpbmRvdy5hZGRFdmVudExpc3RlbmVyO1xuXHRjb25zdCB1bmRlZmluZWRWYXIgPSB1bmRlZmluZWQ7XG5cdGNvbnN0IGRvY3VtZW50RWxlbWVudCA9IGRvY3VtZW50LmRvY3VtZW50RWxlbWVudCB8fCB7fTtcblx0Y29uc3QgbGFuZ3VhZ2UgPSAnbGFuZ3VhZ2UnO1xuXHRjb25zdCBIZWlnaHQgPSAnSGVpZ2h0Jztcblx0Y29uc3QgV2lkdGggPSAnV2lkdGgnO1xuXHRjb25zdCBzY3JvbGwgPSAnc2Nyb2xsJztcblx0Y29uc3Qgc2Nyb2xsSGVpZ2h0ID0gc2Nyb2xsICsgSGVpZ2h0O1xuXHRjb25zdCBvZmZzZXRIZWlnaHQgPSAnb2Zmc2V0JyArIEhlaWdodDtcblx0Y29uc3QgY2xpZW50SGVpZ2h0ID0gJ2NsaWVudCcgKyBIZWlnaHQ7XG5cdGNvbnN0IGNsaWVudFdpZHRoID0gJ2NsaWVudCcgKyBXaWR0aDtcblx0Y29uc3Qgc2NyZWVuID0gd2luZG93LnNjcmVlbjtcblx0Y29uc3Qgc2VuZEJlYWNvblRleHQgPSAnc2VuZEJlYWNvbic7XG5cdFxuXHRjb25zdCB1dWlkID0gKCkgPT4ge1xuXHRcdGNvbnN0IGNyeXB0b09iamVjdCA9IHdpbmRvdy5jcnlwdG8gfHwgd2luZG93Lm1zQ3J5cHRvO1xuXHRcdGNvbnN0IGVtcHR5VVVJRCA9IFsgMWU3IF0gKyAtMWUzICsgLTRlMyArIC04ZTMgKyAtMWUxMTtcblx0XHRjb25zdCB1dWlkUmVnZXggPSAvWzAxOF0vZztcblx0XHR0cnkge1xuXHRcdFx0cmV0dXJuIGVtcHR5VVVJRC5yZXBsYWNlKCB1dWlkUmVnZXgsIGMgPT4gKFxuXHRcdFx0XHRjIF4gKCBjcnlwdG9PYmplY3QuZ2V0UmFuZG9tVmFsdWVzKCBuZXcgVWludDhBcnJheSggMSApIClbIDAgXSAmICggMTUgPj4gKCBjIC8gNCApICkgKSAvLyBlc2xpbnQtZGlzYWJsZS1saW5lIG5vLWJpdHdpc2Vcblx0XHRcdCkudG9TdHJpbmcoIDE2ICkgKTtcblx0XHR9IGNhdGNoICggZXJyb3IgKSB7XG5cdFx0XHRyZXR1cm4gZW1wdHlVVUlELnJlcGxhY2UoIHV1aWRSZWdleCwgYyA9PiB7XG5cdFx0XHRcdFx0Y29uc3QgciA9ICggTWF0aC5yYW5kb20oKSAqIDE2ICkgfCAwLCAvLyBlc2xpbnQtZGlzYWJsZS1saW5lIG5vLWJpdHdpc2Vcblx0XHRcdFx0XHRcdHYgPSBjIDwgMiA/IHIgOiAoIHIgJiAweDMgKSB8IDB4ODsgLy8gZXNsaW50LWRpc2FibGUtbGluZSBuby1iaXR3aXNlXG5cdFx0XHRcdFx0cmV0dXJuIHYudG9TdHJpbmcoIDE2ICk7XG5cdFx0XHRcdH0sXG5cdFx0XHQpO1xuXHRcdH1cblx0fTtcblx0Y29uc3QgYXNzaWduID0gKCAuLi5vYmplY3RzICkgPT4ge1xuXHRcdGNvbnN0IHRvID0ge307XG5cdFx0Y29uc3QgYXJnID0gb2JqZWN0cztcblx0XHRmb3IgKCBsZXQgaW5kZXggPSAwOyBpbmRleCA8IGFyZy5sZW5ndGg7IGluZGV4KysgKSB7XG5cdFx0XHRjb25zdCBuZXh0U291cmNlID0gYXJnWyBpbmRleCBdO1xuXHRcdFx0aWYgKCBuZXh0U291cmNlICkge1xuXHRcdFx0XHRmb3IgKCBjb25zdCBuZXh0S2V5IGluIG5leHRTb3VyY2UgKSB7XG5cdFx0XHRcdFx0aWYgKCBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGwoIG5leHRTb3VyY2UsIG5leHRLZXkgKSApIHtcblx0XHRcdFx0XHRcdHRvWyBuZXh0S2V5IF0gPSBuZXh0U291cmNlWyBuZXh0S2V5IF07XG5cdFx0XHRcdFx0fVxuXHRcdFx0XHR9XG5cdFx0XHR9XG5cdFx0fVxuXHRcdHJldHVybiB0bztcblx0fTtcblx0XG5cdGNvbnN0IGdldFBhcmFtcyA9IGZ1bmN0aW9uKCByZWdleCApIHtcblx0XHQvLyBGcm9tIHRoZSBzZWFyY2ggd2UgZ3JhYiB0aGUgdXRtX3NvdXJjZSBhbmQgcmVmIGFuZCBzYXZlIG9ubHkgdGhhdFxuXHRcdGNvbnN0IG1hdGNoZXMgPSBsb2Muc2VhcmNoLm1hdGNoKCBuZXcgUmVnRXhwKCAnWz8mXSgnICsgcmVnZXggKyAnKT0oW14/Jl0rKScsICdnaScgKSApO1xuXHRcdGNvbnN0IG1hdGNoID0gbWF0Y2hlcyA/IG1hdGNoZXMubWFwKCBtID0+IG0uc3BsaXQoICc9JyApWyAxIF0gKSA6IFtdO1xuXHRcdGlmICggbWF0Y2ggJiYgbWF0Y2hbIDAgXSApIHtcblx0XHRcdHJldHVybiBtYXRjaFsgMCBdO1xuXHRcdH1cblx0fTtcblx0XG5cdC8vLy8vLy8vLy8vLy8vLy8vLy8vL1xuXHQvLyBHRVQgU0VUVElOR1Ncblx0Ly9cblx0XG5cdC8vIGNvbnN0IGlzQm9vbGVhbiA9IHZhbHVlID0+ICEhIHZhbHVlID09PSB2YWx1ZTtcblx0Ly8gRmluZCB0aGUgc2NyaXB0IGVsZW1lbnQgd2hlcmUgb3B0aW9ucyBjYW4gYmUgc2V0IG9uXG5cdC8vIGNvbnN0IGF0dHIgPSAoIGVsLCBhdHRyaWJ1dGUgKSA9PiBlbCAmJiBlbC5nZXRBdHRyaWJ1dGUoICdkYXRhLScgKyBhdHRyaWJ1dGUgKTtcblx0Y29uc3QgYXBpID0gJ3t7YXBpX3VybH19JzsgLy8gYXBpIHVybCB1c2VkIGFzIHBpeGVsIHRvby5cblx0Y29uc3QgdHJhY2tlciA9ICd7e3RyYWNrZXJ9fSc7IC8vIHRyYWNrZXIgZmlsZSBuYW1lIHVzZWQgaW4gc2NyaXB0IHNyYyBhdHRyaWJ1dGUuXG5cdGxldCB7XG5cdFx0bW9kZSxcblx0XHRpZ25vcmVQYWdlcyxcblx0XHRhdXRvQ29sbGVjdCxcblx0XHRyZWNvcmREbnQsXG5cdH0gPSBhc3NpZ24oXG5cdFx0e1xuXHRcdFx0bW9kZTogJycsIC8vIFNjcmlwdCBtb2RlLCB0aGlzIGNhbiBiZSBoYXNoIG1vZGUgZm9yIGV4YW1wbGVcblx0XHRcdGlnbm9yZVBhZ2VzOiBbXSwgLy8gQ3VzdG9tZXJzIGNhbiBpZ25vcmUgY2VydGFpbiBwYWdlc1xuXHRcdFx0YXV0b0NvbGxlY3Q6IHRydWUsIC8vIFNvbWUgY3VzdG9tZXJzIHdhbnQgdG8gY29sbGVjdCBwYWdlIHZpZXdzIG1hbnVhbGx5XG5cdFx0XHRyZWNvcmREbnQ6IGZhbHNlLCAvLyBTaG91bGQgd2UgcmVjb3JkIERvIE5vdCBUcmFjayB2aXNpdHM/XG5cdFx0fSxcblx0XHRzeHBPcHRpb25zXG5cdCk7XG5cdFxuXHRcblx0XG5cdC8vLy8vLy8vLy8vLy8vLy8vLy8vL1xuXHQvLyBIRUxQRVIgRlVOQ1RJT05TXG5cdC8vXG5cdGNvbnN0IG5vdyA9IERhdGUubm93O1xuXHRcblx0Ly8gSWdub3JlIHBhZ2VzIHNwZWNpZmllZCBpbiBkYXRhLWlnbm9yZS1wYWdlc1xuXHRjb25zdCBzaG91bGRJZ25vcmUgPSBmdW5jdGlvbiggcGF0aCApIHtcblx0XHRmb3IgKCBjb25zdCBpIGluIGlnbm9yZVBhZ2VzICkge1xuXHRcdFx0Y29uc3QgaWdub3JlUGFnZVJhdyA9IGlnbm9yZVBhZ2VzWyBpIF07XG5cdFx0XHRpZiAoICEgaWdub3JlUGFnZVJhdyApIHtcblx0XHRcdFx0Y29udGludWU7XG5cdFx0XHR9XG5cdFx0XHQvLyBQcmVwZW5kIGEgc2xhc2ggd2hlbiBpdCdzIG1pc3Npbmdcblx0XHRcdGNvbnN0IGlnbm9yZVBhZ2UgPSBpZ25vcmVQYWdlUmF3WyAwIF0gPT09ICcvJyA/IGlnbm9yZVBhZ2VSYXcgOiAnLycgKyBpZ25vcmVQYWdlUmF3O1xuXHRcdFx0dHJ5IHtcblx0XHRcdFx0aWYgKCBpZ25vcmVQYWdlID09PSBwYXRoIHx8IG5ldyBSZWdFeHAoIGlnbm9yZVBhZ2UucmVwbGFjZSggL1xcKi9naSwgJyguKiknICksICdnaScgKS50ZXN0KCBwYXRoICkgKSB7XG5cdFx0XHRcdFx0cmV0dXJuIHRydWU7XG5cdFx0XHRcdH1cblx0XHRcdH0gY2F0Y2ggKCBlcnJvciApIHtcblx0XHRcdFx0cmV0dXJuIGZhbHNlO1xuXHRcdFx0fVxuXHRcdH1cblx0XHRyZXR1cm4gZmFsc2U7XG5cdH07XG5cdFxuXHQvLy8vLy8vLy8vLy8vLy8vLy8vLy9cblx0Ly8gU0VORCBEQVRBIFZJQSBPVVIgUElYRUxcblx0Ly9cblx0XG5cdC8vIFNlbmQgZGF0YSB2aWEgaW1hZ2UgKHBpeGVsKVxuXHRjb25zdCBzZW5kRGF0YSA9IGZ1bmN0aW9uKCBkYXRhLCBjYWxsYmFjayApIHtcblx0XHRkYXRhID0gYXNzaWduKCBwYXlsb2FkLCBkYXRhICk7XG5cdFx0XG5cdFx0Y2FsbGJhY2sgPSAoIGNhbGxiYWNrICYmICdmdW5jdGlvbicgPT09IHR5cGVvZiBjYWxsYmFjayApID8gY2FsbGJhY2sgOiAoKSA9PiB7fTtcblx0XHRcblx0XHQvLyBkYXRhID0gYXNzaWduKCBhcHBlbmQsIHBheWxvYWQgKTtcblx0XHRpZiAoICEgKCBzZW5kQmVhY29uVGV4dCBpbiBuYXYgKSApIHtcblx0XHRcdGNvbnN0IGltYWdlID0gbmV3IEltYWdlKCk7XG5cdFx0XHRpZiAoIGNhbGxiYWNrICYmICdmdW5jdGlvbicgPT09IHR5cGVvZiBjYWxsYmFjayApIHtcblx0XHRcdFx0aW1hZ2Uub25lcnJvciA9IGNhbGxiYWNrO1xuXHRcdFx0XHRpbWFnZS5vbmxvYWQgPSBjYWxsYmFjaztcblx0XHRcdH1cblx0XHRcdGltYWdlLnNyYyA9IGFwaSArICggYXBpLmluZGV4T2YoICc/JyApID4gLTEgPyAnJicgOiAnPycgKSArICdwYXlsb2FkPScgKyBzdHJpbmdpZnkoIGRhdGEgKTtcblx0XHR9IGVsc2Uge1xuXHRcdFx0bmF2WyBzZW5kQmVhY29uVGV4dCBdKCBhcGksIHN0cmluZ2lmeSggZGF0YSApICk7XG5cdFx0XHRjYWxsYmFjaygpO1xuXHRcdH1cblx0fTtcblx0XG5cdC8vLy8vLy8vLy8vLy8vLy8vLy8vL1xuXHQvLyBFUlJPUiBGVU5DVElPTlNcblx0Ly9cblx0XG5cdC8vIFNlbmQgZXJyb3JzXG5cdGNvbnN0IHNlbmRFcnJvciA9IGZ1bmN0aW9uKCBlcnJvck9yTWVzc2FnZSApIHtcblx0XHRlcnJvck9yTWVzc2FnZSA9IGVycm9yT3JNZXNzYWdlLm1lc3NhZ2UgfHwgZXJyb3JPck1lc3NhZ2U7XG5cdFx0d2FybiggZXJyb3JPck1lc3NhZ2UgKTtcblx0XHRzZW5kRGF0YSgge1xuXHRcdFx0dHlwZTogVFlQRV9FUlJPUixcblx0XHRcdGVycm9yOiBlcnJvck9yTWVzc2FnZSxcblx0XHRcdHVybDogU0lURV9VUkwgKyBQQVRIX05BTUUsXG5cdFx0fSApO1xuXHR9O1xuXHRcblx0Ly8gQSBzaW1wbGUgbG9nIGZ1bmN0aW9uIHNvIHRoZSB1c2VyIGtub3dzIHdoeSBhIHJlcXVlc3QgaXMgbm90IGJlaW5nIHNlbmRcblx0Y29uc3Qgd2FybiA9IG1lc3NhZ2UgPT4ge1xuXHRcdC8vIEBUT0RPIG9ubHkgZXhlY3V0ZSB0aGUgbmV4dCBibG9jayBpZiBkZXYgbW9kZSBpcyBlbmFibGVkLlxuXHRcdGlmICggY29uICYmIGNvbi53YXJuICkge1xuXHRcdFx0Y29uLndhcm4oICdTYWxlWHByZXNzbyBBbmFseXRpY3M6JywgbWVzc2FnZSApO1xuXHRcdH1cblx0fTtcblx0XG5cdC8vIE1ha2Ugc3VyZSBpZ25vcmUgcGFnZXMgaXMgYW4gYXJyYXlcblx0aWYgKCAhIEFycmF5LmlzQXJyYXkoIGlnbm9yZVBhZ2VzICkgKSB7XG5cdFx0aWYgKCB0eXBlb2YgaWdub3JlUGFnZXMgPT09ICdzdHJpbmcnICYmIGlnbm9yZVBhZ2VzLmxlbmd0aCApIHtcblx0XHRcdGlnbm9yZVBhZ2VzID0gaWdub3JlUGFnZXMuc3BsaXQoIC8sID8vICkubWFwKCBwYWdlID0+IHBhZ2UudHJpbSgpICk7XG5cdFx0fSBlbHNlIHtcblx0XHRcdGlnbm9yZVBhZ2VzID0gW107XG5cdFx0fVxuXHR9XG5cdFxuXHQvLyBib3QgZGV0ZWN0aW9uLlxuXHRjb25zdCBib3QgPVxuXHRcdG5hdi53ZWJkcml2ZXIgfHxcblx0XHQoIHdpbmRvdy5oYXNPd25Qcm9wZXJ0eSggJ19fbmlnaHRtYXJlJyApICYmIHdpbmRvdy5fX25pZ2h0bWFyZSApIHx8XG5cdFx0J2NhbGxQaGFudG9tJyBpbiB3aW5kb3cgfHwgJ19waGFudG9tJyBpbiB3aW5kb3cgfHwgJ3BoYW50b20nIGluIHdpbmRvdyB8fFxuXHRcdC8oYm90fHNwaWRlcnxjcmF3bCkvaS50ZXN0KCB1c2VyQWdlbnQgKSB8fCAoXG5cdFx0XHR3aW5kb3cuY2hyb21lICYmIChcblx0XHRcdFx0JycgPT09IG5hdi5sYW5ndWFnZXMgfHxcblx0XHRcdFx0ISAoIG5hdi5wbHVnaW5zIGluc3RhbmNlb2YgUGx1Z2luQXJyYXkgKVxuXHRcdFx0KVxuXHRcdCk7XG5cdFxuXHQvLyBUaGlzIGNvZGUgY291bGQgZXJyb3Igb24gKGluY29tcGxldGUpIGltcGxlbWVudGF0aW9ucywgdGhhdCdzIHdoeSB3ZSB1c2UgdHJ5Li4uY2F0Y2hcblx0bGV0IHRpbWV6b25lO1xuXHR0cnkge1xuXHRcdHRpbWV6b25lID0gSW50bC5EYXRlVGltZUZvcm1hdCgpLnJlc29sdmVkT3B0aW9ucygpLnRpbWVab25lO1xuXHR9IGNhdGNoICggZSApIHtcblx0XHQvKiBEbyBub3RoaW5nICovXG5cdH1cblx0XG5cdC8vLy8vLy8vLy8vLy8vLy8vLy8vL1xuXHQvLyBQQVlMT0FEIEZPUiBCT1RIIFBBR0UgVklFV1MgQU5EIEVWRU5UU1xuXHQvL1xuXHRjb25zdCBwYXlsb2FkID0ge1xuXHRcdHZlcnNpb24sXG5cdFx0Ym90LFxuXHRcdHRpbWV6b25lLFxuXHRcdG1ldGE6IHtcblx0XHRcdG9iamVjdF90eXBlOiBvblBhZ2VEYXRhLndwX29iamVjdF90eXBlLFxuXHRcdFx0b2JqZWN0X2lkOiBvblBhZ2VEYXRhLndwX29iamVjdF9pZCxcblx0XHR9LFxuXHR9O1xuXHRcblx0dHJ5IHtcblx0XHRcblx0XHQvLyBXZSBsaXN0ZW4gZm9yIHRoZSBlcnJvciBldmVudHMgYW5kIG9ubHkgc2VuZCBlcnJvcnMgdGhhdCBhcmVcblx0XHQvLyBmcm9tIG91ciBzY3JpcHQgKGNoZWNrZWQgYnkgZmlsZW5hbWUpIHRvIG91ciBzZXJ2ZXIuXG5cdFx0YWRkRXZlbnRMaXN0ZW5lckZ1bmMoIFRZUEVfRVJST1IsIGZ1bmN0aW9uKCBldmVudCApIHtcblx0XHRcdGlmICggZXZlbnQuZmlsZW5hbWUgJiYgZXZlbnQuZmlsZW5hbWUuaW5kZXhPZiggdHJhY2tlciApID4gLTEgKSB7XG5cdFx0XHRcdHNlbmRFcnJvciggZXZlbnQubWVzc2FnZSApO1xuXHRcdFx0fVxuXHRcdH0sIGZhbHNlICk7XG5cdFx0XG5cdFx0Y29uc3QgcHVzaFN0YXRlID0gJ3B1c2hTdGF0ZSc7XG5cdFx0Y29uc3QgZGlzcGF0Y2hFdmVudCA9IHdpbmRvdy5kaXNwYXRjaEV2ZW50O1xuXHRcdGNvbnN0IGR1cmF0aW9uID0gJ2R1cmF0aW9uJztcblx0XHRsZXQgc3RhcnQgPSBub3coKTtcblx0XHRsZXQgc2Nyb2xsZWQgPSAwO1xuXHRcdFxuXHRcdC8vIFdhcm4gd2hlbiBubyBkb2N1bWVudC5kb2N0eXBlIGlzIGRlZmluZWQgKHRoaXMgYnJlYWtzIHNvbWUgZG9jdW1lbnRFbGVtZW50IGRpbWVuc2lvbnMpXG5cdFx0aWYgKCAhIGRvY3VtZW50LmRvY3R5cGUgKSB7XG5cdFx0XHR3YXJuKCAnQWRkIERPQ1RZUEUgaHRtbCBmb3IgbW9yZSBhY2N1cmF0ZSBkaW1lbnNpb25zJyApO1xuXHRcdH1cblx0XHRcblx0XHQvLyBEb24ndCB0cmFjayB3aGVuIERvIE5vdCBUcmFjayBpcyBzZXQgdG8gdHJ1ZVxuXHRcdGlmICggISByZWNvcmREbnQgJiYgZG9Ob3RUcmFjayBpbiBuYXYgJiYgbmF2WyBkb05vdFRyYWNrIF0gPT09ICcxJyApIHtcblx0XHRcdHJldHVybiB3YXJuKCBub3RTZW5kaW5nICsgJ3doZW4gJyArIGRvTm90VHJhY2sgKyAnIGlzIGVuYWJsZWQnICk7XG5cdFx0fVxuXHRcdFxuXHRcdC8vLy8vLy8vLy8vLy8vLy8vLy8vL1xuXHRcdC8vIFNFVFVQIElOSVRJQUwgVkFSSUFCTEVTXG5cdFx0Ly9cblx0XHRcblx0XHRsZXQgcGFnZSA9IHt9O1xuXHRcdGxldCBsYXN0UGFnZUlkID0gdXVpZCgpO1xuXHRcdGxldCBsYXN0U2VuZFBhdGg7XG5cdFx0XG5cdFx0Ly8gV2UgZG9uJ3Qgd2FudCB0byBlbmQgdXAgd2l0aCBzZW5zaXRpdmUgZGF0YSBzbyB3ZSBjbGVhbiB0aGUgcmVmZXJyZXIgVVJMXG5cdFx0Ly8gRWcuXG5cdFx0bGV0IHJlZmVycmVyID0gKCBkb2N1bWVudC5yZWZlcnJlciB8fCAnJyApXG5cdFx0XHQucmVwbGFjZSggL15odHRwcz86XFwvXFwvKChtfGx8d3syLDN9KFswLTldKyk/KVxcLik/KFtePyNdKykoLiopJC8sICckNCcgKVxuXHRcdFx0LnJlcGxhY2UoIC9eKFteL10rKSQvLCAnJDEnICkgfHwgdW5kZWZpbmVkVmFyO1xuXHRcdFxuXHRcdC8vIFRoZSBwcmVmaXggdXRtXyBpcyBvcHRpb25hbFxuXHRcdGNvbnN0IHV0bVJlZ2V4UHJlZml4ID0gJyh1dG1fKT8nO1xuXHRcdGNvbnN0IHNvdXJjZSA9IHtcblx0XHRcdHNvdXJjZTogZ2V0UGFyYW1zKCB1dG1SZWdleFByZWZpeCArICdzb3VyY2V8cmVmJyApLFxuXHRcdFx0bWVkaXVtOiBnZXRQYXJhbXMoIHV0bVJlZ2V4UHJlZml4ICsgJ21lZGl1bScgKSxcblx0XHRcdGNhbXBhaWduOiBnZXRQYXJhbXMoIHV0bVJlZ2V4UHJlZml4ICsgJ2NhbXBhaWduJyApLFxuXHRcdFx0dGVybTogZ2V0UGFyYW1zKCB1dG1SZWdleFByZWZpeCArICd0ZXJtJyApLFxuXHRcdFx0Y29udGVudDogZ2V0UGFyYW1zKCB1dG1SZWdleFByZWZpeCArICdjb250ZW50JyApLFxuXHRcdFx0cmVmZXJyZXIsXG5cdFx0fTtcblx0XHRcblx0XHQvLy8vLy8vLy8vLy8vLy8vLy8vLy9cblx0XHQvLyBUSU1FIE9OIFBBR0UgQU5EIFNDUk9MTEVEIExPR0lDXG5cdFx0Ly9cblx0XHRcblx0XHQvLyBXZSBkb24ndCBwdXQgbXNIaWRkZW4gaW4gaWYgZHVyYXRpb24gYmxvY2ssIGJlY2F1c2UgaXQncyB1c2VkIG91dHNpZGUgb2YgdGhhdCBmdW5jdGlvbmFsaXR5XG5cdFx0bGV0IG1zSGlkZGVuID0gMDtcblx0XHRcblx0XHRsZXQgaGlkZGVuU3RhcnQ7XG5cdFx0d2luZG93LmFkZEV2ZW50TGlzdGVuZXIoICd2aXNpYmlsaXR5Y2hhbmdlJywgZnVuY3Rpb24oKSB7XG5cdFx0XHRpZiAoIGRvY3VtZW50LmhpZGRlbiApIHtcblx0XHRcdFx0aGlkZGVuU3RhcnQgPSBub3coKTtcblx0XHRcdH0gZWxzZSB7XG5cdFx0XHRcdG1zSGlkZGVuICs9IG5vdygpIC0gaGlkZGVuU3RhcnQ7XG5cdFx0XHR9XG5cdFx0fSwgZmFsc2UgKTtcblx0XHRcblx0XHRjb25zdCBzZW5kT25MZWF2ZSA9IGZ1bmN0aW9uKCBpZCwgcHVzaCApIHtcblx0XHRcdGNvbnN0IGFwcGVuZCA9IHtcblx0XHRcdFx0dHlwZTogVFlQRV9QQUdFX0xFQVZFLFxuXHRcdFx0XHRwYWdlX2lkOiBwdXNoID8gaWQgOiBsYXN0UGFnZUlkLFxuXHRcdFx0XHRkdXJhdGlvbjogTWF0aC5yb3VuZCggKCBub3coKSAtIHN0YXJ0ICsgbXNIaWRkZW4gKSAvIHRob3VzYW5kIClcblx0XHRcdH07XG5cdFx0XHRcblx0XHRcdG1zSGlkZGVuID0gMDtcblx0XHRcdHN0YXJ0ID0gbm93KCk7XG5cdFx0XHRhcHBlbmQuc2Nyb2xsZWQgPSBNYXRoLm1heCggMCwgc2Nyb2xsZWQsIHBvc2l0aW9uKCkgKTtcblx0XHRcdHNlbmREYXRhKCBhcHBlbmQgKTtcblx0XHR9O1xuXHRcdFxuXHRcdGFkZEV2ZW50TGlzdGVuZXJGdW5jKCAndW5sb2FkJywgc2VuZE9uTGVhdmUsIGZhbHNlICk7XG5cdFx0XG5cdFx0Y29uc3QgYm9keSA9IGRvY3VtZW50LmJvZHkgfHwge307XG5cdFx0XG5cdFx0Y29uc3QgcG9zaXRpb24gPSAoKSA9PiB7XG5cdFx0XHR0cnkge1xuXHRcdFx0XHRjb25zdCBkb2N1bWVudENsaWVudEhlaWdodCA9IGRvY3VtZW50RWxlbWVudFsgY2xpZW50SGVpZ2h0IF0gfHwgMDtcblx0XHRcdFx0Y29uc3QgaGVpZ2h0ID0gTWF0aC5tYXgoIGJvZHlbIHNjcm9sbEhlaWdodCBdIHx8IDAsIGJvZHlbIG9mZnNldEhlaWdodCBdIHx8IDAsIGRvY3VtZW50RWxlbWVudFsgY2xpZW50SGVpZ2h0IF0gfHwgMCwgZG9jdW1lbnRFbGVtZW50WyBzY3JvbGxIZWlnaHQgXSB8fCAwLCBkb2N1bWVudEVsZW1lbnRbIG9mZnNldEhlaWdodCBdIHx8IDAgKTtcblx0XHRcdFx0cmV0dXJuIE1hdGgubWluKCAxMDAsIE1hdGgucm91bmQoICggMTAwICogKCAoIGRvY3VtZW50RWxlbWVudC5zY3JvbGxUb3AgfHwgMCApICsgZG9jdW1lbnRDbGllbnRIZWlnaHQgKSApIC8gaGVpZ2h0IC8gNSApICogNSApO1xuXHRcdFx0fSBjYXRjaCAoIGVycm9yICkge1xuXHRcdFx0XHRyZXR1cm4gMDtcblx0XHRcdH1cblx0XHR9O1xuXHRcdFxuXHRcdGFkZEV2ZW50TGlzdGVuZXJGdW5jKCAnbG9hZCcsIGZ1bmN0aW9uKCkge1xuXHRcdFx0c2Nyb2xsZWQgPSBwb3NpdGlvbigpO1xuXHRcdFx0YWRkRXZlbnRMaXN0ZW5lckZ1bmMoIHNjcm9sbCwgZnVuY3Rpb24oKSB7XG5cdFx0XHRcdGlmICggc2Nyb2xsZWQgPCBwb3NpdGlvbigpICkge1xuXHRcdFx0XHRcdHNjcm9sbGVkID0gcG9zaXRpb24oKTtcblx0XHRcdFx0fVxuXHRcdFx0fSwgZmFsc2UgKTtcblx0XHR9ICk7XG5cdFx0XG5cdFx0Ly8vLy8vLy8vLy8vLy8vLy8vLy8vXG5cdFx0Ly8gQUNUVUFMIFBBR0UgVklFVyBMT0dJQ1xuXHRcdC8vXG5cdFx0XG5cdFx0Y29uc3QgZ2V0UGF0aCA9IGZ1bmN0aW9uKCBvdmVyd3JpdGUgKSB7XG5cdFx0XHRsZXQgcGF0aCA9IG92ZXJ3cml0ZSB8fCBkZWNvZGVVUklDb21wb25lbnRGdW5jKCBQQVRIX05BTUUgKTtcblx0XHRcdC8vIElnbm9yZSBwYWdlcyBzcGVjaWZpZWQgaW4gZGF0YS1pZ25vcmUtcGFnZXNcblx0XHRcdGlmICggc2hvdWxkSWdub3JlKCBwYXRoICkgKSB7XG5cdFx0XHRcdHdhcm4oIG5vdFNlbmRpbmcgKyAnYmVjYXVzZSAnICsgcGF0aCArICcgaXMgaWdub3JlZCcgKTtcblx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0fVxuXHRcdFx0Ly8gQWRkIGhhc2ggdG8gcGF0aCB3aGVuIHNjcmlwdCBpcyBwdXQgaW4gdG8gaGFzaCBtb2RlXG5cdFx0XHRpZiAoIG1vZGUgPT09ICdoYXNoJyAmJiBsb2MuaGFzaCApIHtcblx0XHRcdFx0cGF0aCArPSBsb2MuaGFzaC5zcGxpdCggJz8nIClbIDAgXTtcblx0XHRcdH1cblx0XHRcdFxuXHRcdFx0cmV0dXJuIHBhdGg7XG5cdFx0fTtcblx0XHRcblx0XHQvLyBTZW5kIHBhZ2UgdmlldyBhbmQgYXBwZW5kIGRhdGEgdG8gaXRcblx0XHRjb25zdCBzZW5kUGFnZVZpZXcgPSBmdW5jdGlvbiggaXNQdXNoU3RhdGUsIGRlbGV0ZVNvdXJjZUluZm8sIHNhbWVTaXRlICkge1xuXHRcdFx0XG5cdFx0XHRpZiAoIGlzUHVzaFN0YXRlICkge1xuXHRcdFx0XHRzZW5kT25MZWF2ZSggJycgKyBsYXN0UGFnZUlkLCB0cnVlICk7XG5cdFx0XHR9XG5cdFx0XHRcblx0XHRcdGxhc3RQYWdlSWQgPSB1dWlkKCk7XG5cdFx0XHRwYWdlLnBhZ2VfaWQgPSBsYXN0UGFnZUlkO1xuXHRcdFx0XG5cdFx0XHRjb25zdCBjdXJyZW50UGFnZSA9IFNJVEVfVVJMICsgZ2V0UGF0aCgpO1xuXHRcdFx0XG5cdFx0XHRzZW5kRGF0YShcblx0XHRcdFx0YXNzaWduKFxuXHRcdFx0XHRcdHBhZ2UsXG5cdFx0XHRcdFx0ZGVsZXRlU291cmNlSW5mbyA/IHsgcmVmZXJyZXI6IHNhbWVTaXRlID8gcmVmZXJyZXIgOiBudWxsIH0gOiBzb3VyY2UsXG5cdFx0XHRcdFx0eyB0eXBlOiBUWVBFX1BBR0VfVklFVyB9LFxuXHRcdFx0XHQpLFxuXHRcdFx0KTtcblx0XHRcdFxuXHRcdFx0cmVmZXJyZXIgPSBjdXJyZW50UGFnZTtcblx0XHR9O1xuXHRcdFxuXHRcdGNvbnN0IFBhZ2VWaWV3ID0gZnVuY3Rpb24oIGlzUHVzaFN0YXRlLCBwYXRoT3ZlcndyaXRlICkge1xuXHRcdFx0Ly8gT2JmdXNjYXRlIHBlcnNvbmFsIGRhdGEgaW4gVVJMIGJ5IGRyb3BwaW5nIHRoZSBzZWFyY2ggYW5kIGhhc2hcblx0XHRcdGNvbnN0IHBhdGggPSBnZXRQYXRoKCBwYXRoT3ZlcndyaXRlICk7XG5cdFx0XHRcblx0XHRcdC8vIERvbid0IHNlbmQgdGhlIGxhc3QgcGF0aCBhZ2FpbiAodGhpcyBjb3VsZCBoYXBwZW4gd2hlbiBwdXNoU3RhdGUgaXMgdXNlZCB0byBjaGFuZ2UgdGhlIHBhdGggaGFzaCBvciBzZWFyY2gpXG5cdFx0XHRpZiAoICEgcGF0aCB8fCBsYXN0U2VuZFBhdGggPT09IHBhdGggKSB7XG5cdFx0XHRcdHJldHVybjtcblx0XHRcdH1cblx0XHRcdFxuXHRcdFx0bGFzdFNlbmRQYXRoID0gcGF0aDtcblx0XHRcdFxuXHRcdFx0Y29uc3QgZGF0YSA9IHtcblx0XHRcdFx0cGF0aCxcblx0XHRcdFx0dmlld3BvcnRfd2lkdGg6IE1hdGgubWF4KCBkb2N1bWVudEVsZW1lbnRbIGNsaWVudFdpZHRoIF0gfHwgMCwgd2luZG93LmlubmVyV2lkdGggfHwgMCApIHx8IG51bGwsXG5cdFx0XHRcdHZpZXdwb3J0X2hlaWdodDogTWF0aC5tYXgoIGRvY3VtZW50RWxlbWVudFsgY2xpZW50SGVpZ2h0IF0gfHwgMCwgd2luZG93LmlubmVySGVpZ2h0IHx8IDAsICkgfHwgbnVsbCxcblx0XHRcdFx0c2NyZWVuX3dpZHRoOiBzY3JlZW4ud2lkdGgsXG5cdFx0XHRcdHNjcmVlbl9oZWlnaHQ6IHNjcmVlbi5oZWlnaHQsXG5cdFx0XHR9O1xuXHRcdFx0XG5cdFx0XHRpZiAoIG5hdlsgbGFuZ3VhZ2UgXSApIHtcblx0XHRcdFx0ZGF0YVsgbGFuZ3VhZ2UgXSA9IG5hdlsgbGFuZ3VhZ2UgXTtcblx0XHRcdH1cblx0XHRcdFxuXHRcdFx0Ly8gSWYgYSB1c2VyIGRvZXMgcmVmcmVzaCB3ZSBuZWVkIHRvIGRlbGV0ZSB0aGUgcmVmZXJyZXIgYmVjYXVzZSBvdGhlcndpc2UgaXQgY291bnQgZG91YmxlXG5cdFx0XHRjb25zdCBwZXJmID0gd2luZG93LnBlcmZvcm1hbmNlO1xuXHRcdFx0Y29uc3QgbmF2aWdhdGlvbiA9ICduYXZpZ2F0aW9uJztcblx0XHRcdFxuXHRcdFx0Ly8gQ2hlY2sgaWYgYmFjaywgZm9yd2FyZCBvciByZWxvYWQgYnV0dG9ucyBhcmUgYmVpbmcgdXNlZCBpbiBtb2Rlcm4gYnJvd3NlcnNcblx0XHRcdGNvbnN0IHVzZXJOYXZpZ2F0ZWQgPVxuXHRcdFx0XHRwZXJmICYmXG5cdFx0XHRcdHBlcmYuZ2V0RW50cmllc0J5VHlwZSAmJlxuXHRcdFx0XHRwZXJmLmdldEVudHJpZXNCeVR5cGUoIG5hdmlnYXRpb24gKVsgMCBdICYmXG5cdFx0XHRcdHBlcmYuZ2V0RW50cmllc0J5VHlwZSggbmF2aWdhdGlvbiApWyAwIF0udHlwZVxuXHRcdFx0XHRcdD8gWyAncmVsb2FkJywgJ2JhY2tfZm9yd2FyZCcgXS5pbmRleE9mKCBwZXJmLmdldEVudHJpZXNCeVR5cGUoIG5hdmlnYXRpb24gKVsgMCBdLnR5cGUgKSA+IC0xXG5cdFx0XHRcdFx0OiBwZXJmICYmIHBlcmZbIG5hdmlnYXRpb24gXSAmJiBbIDEsIDIgXS5pbmRleE9mKCBwZXJmWyBuYXZpZ2F0aW9uIF0udHlwZSApID4gLTE7IC8vIENoZWNrIGlmIGJhY2ssIGZvcndhcmQgb3IgcmVsb2FkIGJ1dHRvbnMgYXJlIGJlaW5nIHVzZSBpbiBvbGRlciBicm93c2VycyAxOiBUWVBFX1JFTE9BRCwgMjogVFlQRV9CQUNLX0ZPUldBUkRcblx0XHRcdFxuXHRcdFx0Ly8gQ2hlY2sgaWYgcmVmZXJyZXIgaXMgdGhlIHNhbWUgYXMgY3VycmVudCBob3N0bmFtZVxuXHRcdFx0Y29uc3Qgc2FtZVNpdGUgPSByZWZlcnJlciA/IHJlZmVycmVyLnNwbGl0KCBzbGFzaCApWyAwIF0gPT09IFNJVEVfVVJMIDogZmFsc2U7XG5cdFx0XHRcblx0XHRcdC8vIFdlIHNldCB1bmlxdWUgdmFyaWFibGUgYmFzZWQgb24gcHVzaFN0YXRlIG9yIGJhY2sgbmF2aWdhdGlvbiwgaWYgbm8gbWF0Y2ggd2UgY2hlY2sgdGhlIHJlZmVycmVyXG5cdFx0XHRkYXRhLmlzX3VuaXF1ZSA9IGlzUHVzaFN0YXRlIHx8IHVzZXJOYXZpZ2F0ZWQgPyBmYWxzZSA6ICEgc2FtZVNpdGU7XG5cdFx0XHRcblx0XHRcdHBhZ2UgPSBkYXRhO1xuXHRcdFx0XG5cdFx0XHRzZW5kUGFnZVZpZXcoIGlzUHVzaFN0YXRlLCBpc1B1c2hTdGF0ZSB8fCB1c2VyTmF2aWdhdGVkLCBzYW1lU2l0ZSApO1xuXHRcdH07XG5cdFx0XG5cdFx0Ly8vLy8vLy8vLy8vLy8vLy8vLy8vXG5cdFx0Ly8gQVVUT01BVEVEIFBBR0UgVklFVyBDT0xMRUNUSU9OXG5cdFx0Ly9cblx0XHRcblx0XHRjb25zdCBoaXMgPSB3aW5kb3cuaGlzdG9yeTtcblx0XHRjb25zdCBoaXNQdXNoU3RhdGUgPSBoaXMgPyBoaXMucHVzaFN0YXRlIDogdW5kZWZpbmVkVmFyO1xuXHRcdFxuXHRcdC8vIE92ZXJ3cml0ZSBoaXN0b3J5IHB1c2hTdGF0ZSBmdW5jdGlvbiB0b1xuXHRcdC8vIGFsbG93IGxpc3RlbmluZyBvbiB0aGUgcHVzaFN0YXRlIGV2ZW50XG5cdFx0aWYgKCBhdXRvQ29sbGVjdCAmJiBoaXNQdXNoU3RhdGUgJiYgRXZlbnQgJiYgZGlzcGF0Y2hFdmVudCApIHtcblx0XHRcdGNvbnN0IHN0YXRlTGlzdGVuZXIgPSBmdW5jdGlvbiggdHlwZSApIHtcblx0XHRcdFx0Y29uc3Qgb3JpZyA9IGhpc1sgdHlwZSBdO1xuXHRcdFx0XHRyZXR1cm4gZnVuY3Rpb24oKSB7XG5cdFx0XHRcdFx0Y29uc3QgYXJnID0gYXJndW1lbnRzO1xuXHRcdFx0XHRcdGNvbnN0IHJ2ID0gb3JpZy5hcHBseSggdGhpcywgYXJnICk7XG5cdFx0XHRcdFx0bGV0IGV2ZW50O1xuXHRcdFx0XHRcdGlmICggdHlwZW9mIEV2ZW50ID09PSAnZnVuY3Rpb24nICkge1xuXHRcdFx0XHRcdFx0ZXZlbnQgPSBuZXcgRXZlbnQoIHR5cGUgKTtcblx0XHRcdFx0XHR9IGVsc2Uge1xuXHRcdFx0XHRcdFx0Ly8gRml4IGZvciBJRVxuXHRcdFx0XHRcdFx0ZXZlbnQgPSBkb2N1bWVudC5jcmVhdGVFdmVudCggJ0V2ZW50JyApO1xuXHRcdFx0XHRcdFx0ZXZlbnQuaW5pdEV2ZW50KCB0eXBlLCB0cnVlLCB0cnVlICk7XG5cdFx0XHRcdFx0fVxuXHRcdFx0XHRcdGV2ZW50LmFyZ3VtZW50cyA9IGFyZztcblx0XHRcdFx0XHRkaXNwYXRjaEV2ZW50KCBldmVudCApO1xuXHRcdFx0XHRcdHJldHVybiBydjtcblx0XHRcdFx0fTtcblx0XHRcdH07XG5cdFx0XHRcblx0XHRcdGhpcy5wdXNoU3RhdGUgPSBzdGF0ZUxpc3RlbmVyKCBwdXNoU3RhdGUgKTtcblx0XHRcdFxuXHRcdFx0YWRkRXZlbnRMaXN0ZW5lckZ1bmMoIHB1c2hTdGF0ZSwgZnVuY3Rpb24oKSB7XG5cdFx0XHRcdFBhZ2VWaWV3KCAxICk7XG5cdFx0XHR9LCBmYWxzZSApO1xuXHRcdFx0XG5cdFx0XHRhZGRFdmVudExpc3RlbmVyRnVuYyggJ3BvcHN0YXRlJywgZnVuY3Rpb24oKSB7XG5cdFx0XHRcdFBhZ2VWaWV3KCAxICk7XG5cdFx0XHR9LCBmYWxzZSApO1xuXHRcdH1cblx0XHRcblx0XHQvLyBXaGVuIGluIGhhc2ggbW9kZSwgd2UgcmVjb3JkIGEgUGFnZVZpZXcgYmFzZWQgb24gdGhlIG9uaGFzaGNoYW5nZSBmdW5jdGlvblxuXHRcdGlmICggYXV0b0NvbGxlY3QgJiYgbW9kZSA9PT0gJ2hhc2gnICYmICdvbmhhc2hjaGFuZ2UnIGluIHdpbmRvdyApIHtcblx0XHRcdGFkZEV2ZW50TGlzdGVuZXJGdW5jKCAnaGFzaGNoYW5nZScsIGZ1bmN0aW9uKCkge1xuXHRcdFx0XHRQYWdlVmlldyggMSApO1xuXHRcdFx0fSwgZmFsc2UgKTtcblx0XHR9XG5cdFx0XG5cdFx0Ly8gVGhpcyBzY3JpcHQgc2hvdWxkIGJlIGxvYWRlZCBpbnNpZGUgPGhlYWQ+IHRhZy5cblx0XHQvLyBDb2xsZWN0IHBhZ2UgdmlldyBzb29ucyBhcyBpdCBsb2FkZWQgKGlmIGF1dG9Db2xsZWN0IGVuYWJsZWQpLlxuXHRcdGlmICggYXV0b0NvbGxlY3QgKSB7XG5cdFx0XHRQYWdlVmlldygpO1xuXHRcdH0gZWxzZSB7XG5cdFx0XHR3aW5kb3cuc3hwUGFnZVZpZXcgPSBmdW5jdGlvbiggcGF0aCApIHtcblx0XHRcdFx0UGFnZVZpZXcoIDAsIHBhdGggKTtcblx0XHRcdH07XG5cdFx0fVxuXHRcdFxuXHRcdC8vLy8vLy8vLy8vLy8vLy8vLy8vL1xuXHRcdC8vIEVWRU5UU1xuXHRcdC8vXG5cdFx0Ly8gcmVwbGFjZSB0aGlzIHNlc3Npb24gaWYgZnJvbSBjb29raWUgc2Vzc2lvbiBpZC5cblx0XHRcblx0XHRjb25zdCB2YWxpZFR5cGVzID0gWyAnc3RyaW5nJywgJ251bWJlcicgXTtcblx0XHRcblx0XHRjb25zdCBzZW5kRXZlbnQgPSBmdW5jdGlvbiggZXZlbnQsIG1ldGEsIGNhbGxiYWNrUmF3ICkge1xuXHRcdFx0Y29uc3QgaXNGdW5jdGlvbiA9IGV2ZW50IGluc3RhbmNlb2YgRnVuY3Rpb247XG5cdFx0XHRjb25zdCBjYWxsYmFjayA9IGNhbGxiYWNrUmF3IGluc3RhbmNlb2YgRnVuY3Rpb24gPyBjYWxsYmFja1JhdyA6ICgpID0+IHt9O1xuXHRcdFx0XG5cdFx0XHRpZiAoIHZhbGlkVHlwZXMuaW5kZXhPZiggdHlwZW9mIGV2ZW50ICkgPCAwICYmICEgaXNGdW5jdGlvbiApIHtcblx0XHRcdFx0d2FybiggJ2V2ZW50IGlzIG5vdCBhIHN0cmluZzogJyArIGV2ZW50ICk7XG5cdFx0XHRcdHJldHVybiBjYWxsYmFjaygpO1xuXHRcdFx0fVxuXHRcdFx0XG5cdFx0XHR0cnkge1xuXHRcdFx0XHRpZiAoIGlzRnVuY3Rpb24gKSB7XG5cdFx0XHRcdFx0aWYgKCB2YWxpZFR5cGVzLmluZGV4T2YoIHR5cGVvZiBldmVudCApIDwgMCApIHtcblx0XHRcdFx0XHRcdHdhcm4oICdldmVudCBmdW5jdGlvbiBvdXRwdXQgaXMgbm90IGEgc3RyaW5nOiAnICsgZXZlbnQgKTtcblx0XHRcdFx0XHRcdHJldHVybiBjYWxsYmFjaygpO1xuXHRcdFx0XHRcdH1cblx0XHRcdFx0fVxuXHRcdFx0fSBjYXRjaCAoIGVycm9yICkge1xuXHRcdFx0XHR3YXJuKCAnRXJyb3IgaW4geW91ciBldmVudCBmdW5jdGlvbjogJyArIGVycm9yLm1lc3NhZ2UgKTtcblx0XHRcdFx0cmV0dXJuIGNhbGxiYWNrKCk7XG5cdFx0XHR9XG5cdFx0XHRcblx0XHRcdGV2ZW50ID0gKCAnJyArIGV2ZW50IClcblx0XHRcdFx0LnJlcGxhY2UoIC9bXmEtejAtOV0rL2dpLCAnLScgKVxuXHRcdFx0XHQucmVwbGFjZSggLyheLXwtJCkvZywgJycgKVxuXHRcdFx0XHQudG9Mb3dlckNhc2UoKTtcblx0XHRcdFxuXHRcdFx0aWYgKCBldmVudCApIHtcblx0XHRcdFx0c2VuZERhdGEoXG5cdFx0XHRcdFx0YXNzaWduKFxuXHRcdFx0XHRcdFx0c291cmNlLFxuXHRcdFx0XHRcdFx0Ym90LFxuXHRcdFx0XHRcdFx0e1xuXHRcdFx0XHRcdFx0XHRtZXRhOiB7IGV2ZW50OiBtZXRhIH1cblx0XHRcdFx0XHRcdH0sXG5cdFx0XHRcdFx0XHR7XG5cdFx0XHRcdFx0XHRcdHR5cGU6IFRZUEVfRVZFTlQsXG5cdFx0XHRcdFx0XHRcdGV2ZW50LCAvLyBFdmVudCBDYXRlZ29yeS5cblx0XHRcdFx0XHRcdFx0cGFnZV9pZDogcGFnZS5wYWdlX2lkLFxuXHRcdFx0XHRcdFx0fSxcblx0XHRcdFx0XHQpLFxuXHRcdFx0XHRcdGNhbGxiYWNrLFxuXHRcdFx0XHQpO1xuXHRcdFx0fVxuXHRcdH07XG5cdFx0XG5cdFx0Y29uc3QgZGVmYXVsdEV2ZW50RnVuYyA9IGZ1bmN0aW9uKCBldmVudCwgZGF0YSwgY2FsbGJhY2sgKSB7XG5cdFx0XHRzZW5kRXZlbnQoIGV2ZW50LCBkYXRhLCBjYWxsYmFjayApO1xuXHRcdH07XG5cdFx0XG5cdFx0Ly8gU2V0IGRlZmF1bHQgZnVuY3Rpb24gaWYgdXNlciBkaWRuJ3QgZGVmaW5lIGEgZnVuY3Rpb25cblx0XHRpZiAoICEgd2luZG93WyBzeHBHbG9iYWwgXSApIHtcblx0XHRcdHdpbmRvd1sgc3hwR2xvYmFsIF0gPSBkZWZhdWx0RXZlbnRGdW5jO1xuXHRcdH1cblx0XHRcblx0XHRjb25zdCBldmVudEZ1bmMgPSB3aW5kb3dbIHN4cEdsb2JhbCBdO1xuXHRcdFxuXHRcdC8vIFJlYWQgcXVldWUgb2YgdGhlIHVzZXIgZGVmaW5lZCBmdW5jdGlvblxuXHRcdGNvbnN0IHF1ZXVlID0gZXZlbnRGdW5jICYmIGV2ZW50RnVuYy5xID8gZXZlbnRGdW5jLnEgOiBbXTtcblx0XHRcblx0XHQvLyBPdmVyd3JpdGUgdXNlciBkZWZpbmVkIGZ1bmN0aW9uXG5cdFx0d2luZG93WyBzeHBHbG9iYWwgXSA9IGRlZmF1bHRFdmVudEZ1bmM7XG5cdFx0XG5cdFx0Ly8gUG9zdCBldmVudHMgZnJvbSB0aGUgcXVldWUgb2YgdGhlIHVzZXIgZGVmaW5lZCBmdW5jdGlvblxuXHRcdGZvciAoIGNvbnN0IGV2ZW50IGluIHF1ZXVlICkge1xuXHRcdFx0c2VuZEV2ZW50KCBxdWV1ZVsgZXZlbnQgXSApO1xuXHRcdH1cblx0fSBjYXRjaCAoIGUgKSB7XG5cdFx0c2VuZEVycm9yKCBlICk7XG5cdH1cbn0oIHdpbmRvdywgZG9jdW1lbnQsICd7e3RyYWNrZXJfb3B0aW9uc319Jywgc3hwU2Vzc2lvbk9uUGFnZURhdGEgKSApO1xuIl0sInNvdXJjZVJvb3QiOiIifQ==