window["AFFWP"] = window["AFFWP"] || {}; window["AFFWP"]["portal"] = window["AFFWP"]["portal"] || {}; window["AFFWP"]["portal"]["creatives"] =
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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/creatives/index.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/@babel/runtime/helpers/asyncToGenerator.js":
/*!*****************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/asyncToGenerator.js ***!
  \*****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) {
  try {
    var info = gen[key](arg);
    var value = info.value;
  } catch (error) {
    reject(error);
    return;
  }

  if (info.done) {
    resolve(value);
  } else {
    Promise.resolve(value).then(_next, _throw);
  }
}

function _asyncToGenerator(fn) {
  return function () {
    var self = this,
        args = arguments;
    return new Promise(function (resolve, reject) {
      var gen = fn.apply(self, args);

      function _next(value) {
        asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value);
      }

      function _throw(err) {
        asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err);
      }

      _next(undefined);
    });
  };
}

module.exports = _asyncToGenerator, module.exports.__esModule = true, module.exports["default"] = module.exports;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/typeof.js":
/*!*******************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/typeof.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _typeof(obj) {
  "@babel/helpers - typeof";

  return (module.exports = _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) {
    return typeof obj;
  } : function (obj) {
    return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
  }, module.exports.__esModule = true, module.exports["default"] = module.exports), _typeof(obj);
}

module.exports = _typeof, module.exports.__esModule = true, module.exports["default"] = module.exports;

/***/ }),

/***/ "./src/clipboard-helpers/clipboard-helpers.js":
/*!****************************************************!*\
  !*** ./src/clipboard-helpers/clipboard-helpers.js ***!
  \****************************************************/
/*! exports provided: copy, copyNode */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "copy", function() { return copy; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "copyNode", function() { return copyNode; });
/* harmony import */ var _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/typeof */ "./node_modules/@babel/runtime/helpers/typeof.js");
/* harmony import */ var _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__);


/**
 * Copy.
 *
 * Attempts to copy the specified content to the user's clipboard
 *
 * @since      1.0.0
 * @access     protected
 *
 * @return {Promise}
 */
function copy(content) {
  return new Promise(function (res, rej) {
    // Check for clipboard API
    if (undefined === _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0___default()(navigator.clipboard) || undefined === _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0___default()(navigator.clipboard.writeText)) {
      rej('Could not find a valid clipboard library.');
    } else {
      res(navigator.clipboard.writeText(content));
    }
  });
}
/**
 * Copy Node.
 *
 * Attempts to copy the content from the specified node.
 * @since 1.0.0
 * @param {Node} target The DOM Node content to copy.
 * @return {Promise}
 */


function copyNode(target) {
  return new Promise(function (res, rej) {
    if (_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0___default()(target) !== 'object' || typeof target.innerText !== 'string' && typeof target.value !== 'string') {
      rej('Target is not a valid HTML node.');
    }

    var value = ''; // Try to get an input value if it's set first.

    if (typeof target.value === 'string') {
      value = target.value; // Fallback to the innerText
    } else if (typeof target.innerText === 'string') {
      value = target.innerText; // If all-else fails, reject.
    } else {
      rej('Could not find valid text to copy');
    }

    res(copy(value));
  });
}



/***/ }),

/***/ "./src/creatives/index.js":
/*!********************************!*\
  !*** ./src/creatives/index.js ***!
  \********************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/asyncToGenerator */ "./node_modules/@babel/runtime/helpers/asyncToGenerator.js");
/* harmony import */ var _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/regenerator */ "@babel/runtime/regenerator");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _affiliatewp_portal_clipboard_helpers__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @affiliatewp-portal/clipboard-helpers */ "./src/clipboard-helpers/clipboard-helpers.js");
/* harmony import */ var _affiliatewp_portal_helpers__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @affiliatewp-portal/helpers */ "./src/helpers/helpers.js");



/**
 * Creatives.
 *
 * Works with the Creatives page template to handle copying, and modal states.
 *
 * @author Alex Standiford
 * @since 1.0.0
 * @global creatives
 *
 */

/* eslint @wordpress/no-unused-vars-before-return: "off" */

/**
 * WordPress dependencies
 */

/**
 * Internal dependencies
 */



/**
 * Creatives screen AlpineJS handler.
 *
 * Works with the Creatives page template to handle copying, and modal states.
 *
 * @since 1.0.0
 * @access private
 * @global creatives
 *
 * @returns object A creatives AlpineJS object.
 */

function creatives() {
  return {
    open: false,
    copying: false,

    /**
     * Copy.
     *
     * Attempts to copy the creative text, and flashes a notification.
     *
     * @since      1.0.0
     * @access     public
     * @param type event. The event this is firing against.
     *
     * @return void
     */
    copy: function copy(event) {
      var _this = this;

      return _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_0___default()( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_1___default.a.mark(function _callee() {
        var originalHTML;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_1___default.a.wrap(function _callee$(_context) {
          while (1) {
            switch (_context.prev = _context.next) {
              case 0:
                // Save the original HTML so we can use it to restore the original state of the button.
                originalHTML = event.target.innerHTML; // Attempt to copy the content to the user's clipboard.

                _context.next = 3;
                return Object(_affiliatewp_portal_clipboard_helpers__WEBPACK_IMPORTED_MODULE_3__["copyNode"])(_this.$refs.creativeCode);

              case 3:
                // Flash the text
                _this.copying = true;
                event.target.innerText = "\uD83C\uDF89 ".concat(Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('Copied!', 'affiliatewp-affiliate-portal'));
                _context.next = 7;
                return Object(_affiliatewp_portal_helpers__WEBPACK_IMPORTED_MODULE_4__["pause"])(2000);

              case 7:
                event.target.innerHTML = originalHTML;
                _this.copying = false;

              case 9:
              case "end":
                return _context.stop();
            }
          }
        }, _callee);
      }))();
    },

    /**
     * Fitler creatives by category.
     *
     * @since  [-NEXT-]
     *
     * @return {void} When we navigate away.
     */
    filter: function filter() {
      return _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_0___default()( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_1___default.a.mark(function _callee2() {
        var _ref;

        var selector;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_1___default.a.wrap(function _callee2$(_context2) {
          while (1) {
            switch (_context2.prev = _context2.next) {
              case 0:
                selector = document.getElementById('filter');

                if (selector.length <= 0) {
                  window.console.error('Unable to find <select> for slug');
                }

                if ((_ref = false === selector.value) !== null && _ref !== void 0 ? _ref : false) {
                  window.console.error('Unable to get slug from selector value.');
                } // All categoriies (no filtering), navigate w/out the slug.


                if (!('' === selector.value)) {
                  _context2.next = 6;
                  break;
                }

                // Load the current page w/out the slug selector.
                window.location.href = "".concat(selector.dataset.baseUrl, "/");
                return _context2.abrupt("return");

              case 6:
                // Navigat to URL where selector.value is the slug for the filter.
                window.location.href = "".concat(selector.dataset.baseUrl, "/").concat(selector.value);

              case 7:
              case "end":
                return _context2.stop();
            }
          }
        }, _callee2);
      }))();
    }
  };
}

/* harmony default export */ __webpack_exports__["default"] = (creatives);

/***/ }),

/***/ "./src/helpers/helpers.js":
/*!********************************!*\
  !*** ./src/helpers/helpers.js ***!
  \********************************/
/*! exports provided: pause, trailingslashit */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "pause", function() { return pause; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "trailingslashit", function() { return trailingslashit; });
/**
 * Helper Functions.
 *
 * Generic helper functions specific to AffilaiteWP Affiliate Portal.
 *
 * @author Alex Standiford
 * @since 1.0.0
 */

/**
 * Pause.
 *
 * Delays script execution for the specified amount of time.
 *
 * @since 1.0.0
 * @param length Amount of time to delay, in milliseconds.
 *
 * @returns {Promise} Resolved promise after specified length
 */
function pause(length) {
  return new Promise(function (resolve) {
    return setTimeout(resolve, length);
  });
}
/**
 * Adds a trailing slash to the input value, if it does not already have one.
 *
 * @since 1.0.0
 * @param input {string} The value to append a slash.
 *
 * @returns {string} The appended string.
 */


function trailingslashit(input) {
  if (typeof input !== 'string' || input.endsWith('/')) {
    return input;
  }

  return "".concat(input, "/");
}



/***/ }),

/***/ "@babel/runtime/regenerator":
/*!*************************************!*\
  !*** external "regeneratorRuntime" ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["regeneratorRuntime"]; }());

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["i18n"]; }());

/***/ })

/******/ });
//# sourceMappingURL=creatives.js.map