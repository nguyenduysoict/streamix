window["AFFWP"] = window["AFFWP"] || {}; window["AFFWP"]["portal"] = window["AFFWP"]["portal"] || {}; window["AFFWP"]["portal"]["portalForm"] =
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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/portal-form/portal-form.js");
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

/***/ "./node_modules/@babel/runtime/helpers/defineProperty.js":
/*!***************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/defineProperty.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _defineProperty(obj, key, value) {
  if (key in obj) {
    Object.defineProperty(obj, key, {
      value: value,
      enumerable: true,
      configurable: true,
      writable: true
    });
  } else {
    obj[key] = value;
  }

  return obj;
}

module.exports = _defineProperty, module.exports.__esModule = true, module.exports["default"] = module.exports;

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

/***/ "./src/portal-form/portal-form.js":
/*!****************************************!*\
  !*** ./src/portal-form/portal-form.js ***!
  \****************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/asyncToGenerator */ "./node_modules/@babel/runtime/helpers/asyncToGenerator.js");
/* harmony import */ var _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/defineProperty.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/regenerator */ "@babel/runtime/regenerator");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _affiliatewp_portal_alpine_form__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @affiliatewp-portal/alpine-form */ "@affiliatewp-portal/alpine-form");
/* harmony import */ var _affiliatewp_portal_alpine_form__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_affiliatewp_portal_alpine_form__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _affiliatewp_portal_helpers__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @affiliatewp-portal/helpers */ "./src/helpers/helpers.js");
/* harmony import */ var _affiliatewp_portal_sdk__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @affiliatewp-portal/sdk */ "@affiliatewp-portal/sdk");
/* harmony import */ var _affiliatewp_portal_sdk__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_affiliatewp_portal_sdk__WEBPACK_IMPORTED_MODULE_5__);




function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); enumerableOnly && (symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; })), keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = null != arguments[i] ? arguments[i] : {}; i % 2 ? ownKeys(Object(source), !0).forEach(function (key) { _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1___default()(target, key, source[key]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)) : ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } return target; }

/**
 * Form.
 *
 * Works with forms to handle data validation and other form interactions.
 *
 * @author Alex Standiford
 * @since 1.0.0
 * @global form
 *
 */

/**
 * Internal Dependencies
 */



/**
 * Form handler.
 *
 * Works with forms to handle field validation, and submission.
 *
 * @param {string} sectionId The Section ID from which the fields should be fetched.
 *
 * @since 1.0.0
 * @access private
 * @global form
 *
 * @returns object The form AlpineJS object.
 */

/* harmony default export */ __webpack_exports__["default"] = (function (sectionId) {
  return _objectSpread(_objectSpread({}, _affiliatewp_portal_alpine_form__WEBPACK_IMPORTED_MODULE_3___default.a), {
    /**
     * Section ID.
     *
     * The section ID that contains the form fields.
     *
     * @since  1.0.0
     * @access public
     *
     * @type {string} The section ID
     */
    sectionId: sectionId,

    /**
     * Is Loading.
     *
     * Set to true if this item is loading.
     *
     * @since  1.0.0
     * @access public
     *
     * @type {boolean} True if loading, otherwise false.
     */
    isLoading: true,

    /**
     * Is Validating.
     *
     * Set to true if this item is validating fields.
     *
     * @since  1.0.0
     * @access public
     *
     * @type {boolean} True if loading, otherwise false.
     */
    isValidating: false,

    /**
     * Is Submitting.
     *
     * Set to true if this item is submitting the form.
     *
     * @since  1.0.0
     * @access public
     *
     * @type {boolean} True if loading, otherwise false.
     */
    isSubmitting: false,

    /**
     * Showing success message.
     *
     * Whether or not the success message is showing (during submission).
     *
     * @since  1.0.0
     * @access public
     *
     * @type boolean
     */
    showingSuccessMessage: false,

    /**
     * Export Fields.
     *
     * Converts Alpine form fields to key => value pairs for REST submissions & validation.
     *
     * @since  1.0.0
     * @access public
     *
     * @returns object Object of values keyed by the field ID.
     */
    exportFields: function exportFields() {
      return this.fields.reduce(function (acc, field) {
        acc[field.id] = field.value;
        return acc;
      }, {});
    },

    /**
     * Has Validations.
     *
     * Returns true if the specified control has validations.
     *
     * @since  1.0.0
     * @access public
     *
     * @param {String} id Control ID.
     *
     * @returns {boolean} True if the field has validations, otherwise false.
     */
    hasValidations: function hasValidations(id) {
      var field = this.getField(id);

      if (false === field) {
        return false;
      }

      return true === field.hasValidations;
    },

    /**
     * Validate Control.
     *
     * Validates a control by the provided ID, and sets the error if so
     *
     * @since  1.0.0
     * @access public
     *
     * @param {String} id Control ID.
     *
     * @returns {Promise<void>}
     */
    validateControl: function validateControl(id) {
      var _this = this;

      return _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_0___default()( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_2___default.a.mark(function _callee() {
        var response, passed;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_2___default.a.wrap(function _callee$(_context) {
          while (1) {
            switch (_context.prev = _context.next) {
              case 0:
                if (!(false === _this.hasValidations(id))) {
                  _context.next = 2;
                  break;
                }

                return _context.abrupt("return");

              case 2:
                _this.isValidating = true;
                _context.next = 5;
                return Object(_affiliatewp_portal_sdk__WEBPACK_IMPORTED_MODULE_5__["validateControl"])(id, _this.exportFields());

              case 5:
                response = _context.sent;
                // Get the passed IDs.
                passed = response.validations.passed.map(function (validation) {
                  return validation.id;
                }); // Remove all errors that passed this time

                _this.removeErrors(passed); // Add any errors that failed.


                _this.addErrors(response.validations.failed);

                _this.isValidating = false;

              case 10:
              case "end":
                return _context.stop();
            }
          }
        }, _callee);
      }))();
    },

    /**
     * Setup Submit.
     *
     * Sets up the default directives for the submit button. Intended to be called using Alpine's x-spread directive.
     *
     * @since  1.0.0
     * @access public
     *
     * @returns {object} Directives that should be applied to the submit button by default.
     */
    setupSubmit: function setupSubmit() {
      return _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1___default()({}, 'x-bind:disabled', function xBindDisabled() {
        return this.hasErrors() || this.isLoading || this.isValidating || this.isSubmitting;
      });
    },

    /**
     * Default Directives.
     *
     * Sets up the default directives for a field.
     *
     * @since  1.0.0
     * @access public
     *
     * @param {string} id The control ID from which directives should be constructed.
     * @param {string} type The input type, such as text, or checkbox.
     *
     * @returns {object} Directives that should be applied to all inputs by default.
     */
    setupField: function setupField(id) {
      var _additionalDirectives;

      var type = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';
      var value = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : '';
      // Bind the parent function to this instance. this is kind-of like running parent::function() in PHP.
      var setupControl = _affiliatewp_portal_alpine_form__WEBPACK_IMPORTED_MODULE_3___default.a.setupField.bind(this); // Get the default directives

      var parentDirectives = setupControl(id, type, value);
      var parentInput = parentDirectives['x-on:input'].bind(this); // A list of validations that should not have an input delay.

      var hasNoDelay = ['checkbox', 'select', 'radio'].includes(type); // AP-specific directives.

      var additionalDirectives = (_additionalDirectives = {}, _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1___default()(_additionalDirectives, 'x-on:input', function xOnInput(event) {
        var _this2 = this;

        parentInput(event); // Run field validations.

        if (hasNoDelay) {
          this.validateControl(id);
        } else {
          var fieldIndex = this.fields.findIndex(function (field) {
            return field.id === id;
          }); // Maybe reset the timeout, if it is already set.

          if (undefined !== this.fields[fieldIndex].validating) {
            window.clearTimeout(this.fields[fieldIndex].validating);
          }

          this.isLoading = true;
          this.fields[fieldIndex].validating = window.setTimeout(function () {
            _this2.validateControl(id);

            delete _this2.fields[fieldIndex].validating;
            _this2.isLoading = false;
          }, 200);
        }
      }), _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1___default()(_additionalDirectives, 'x-on:blur', function xOnBlur() {
        this.validateControl(id);
        this.isLoading = false;
      }), _additionalDirectives); // Spread (combine) the two objects into a single object.

      return _objectSpread(_objectSpread({}, parentDirectives), additionalDirectives);
    },

    /**
     * Submit Form.
     *
     * Actions that should be taken when the form is submitted.
     *
     * @since  1.0.0
     * @access public
     *
     * @returns {Promise<void>}
     */
    submitForm: function submitForm() {
      var _this3 = this;

      return _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_0___default()( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_2___default.a.mark(function _callee2() {
        var response;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_2___default.a.wrap(function _callee2$(_context2) {
          while (1) {
            switch (_context2.prev = _context2.next) {
              case 0:
                _this3.isSubmitting = true;
                _context2.next = 3;
                return Object(_affiliatewp_portal_sdk__WEBPACK_IMPORTED_MODULE_5__["submitSection"])(_this3.sectionId, _this3.exportFields());

              case 3:
                response = _context2.sent;

                // remove all errors.
                _this3.removeErrors(response.validations.passed);

                _this3.addErrors(response.validations.failed);

                _this3.isSubmitting = false;

                if (!_this3.hasErrors()) {
                  _this3.flashSuccessMessage();
                }

              case 8:
              case "end":
                return _context2.stop();
            }
          }
        }, _callee2);
      }))();
    },

    /**
     * Flash Success Message.
     *
     * Flashes the success message.
     *
     * @since  1.0.0
     * @access public
     *
     * @returns {Promise<void>}
     */
    flashSuccessMessage: function flashSuccessMessage() {
      var _this4 = this;

      return _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_0___default()( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_2___default.a.mark(function _callee3() {
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_2___default.a.wrap(function _callee3$(_context3) {
          while (1) {
            switch (_context3.prev = _context3.next) {
              case 0:
                _this4.showingSuccessMessage = true;
                _context3.next = 3;
                return Object(_affiliatewp_portal_helpers__WEBPACK_IMPORTED_MODULE_4__["pause"])(1000);

              case 3:
                _this4.showingSuccessMessage = false;

              case 4:
              case "end":
                return _context3.stop();
            }
          }
        }, _callee3);
      }))();
    },

    /**
     * Sets up the form.
     *
     * @since  1.0.0
     * @access public
     *
     * @returns {Promise<void>}
     */
    setupForm: function setupForm() {
      return _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1___default()({}, 'x-on:submit', function xOnSubmit(event) {
        var _this5 = this;

        return _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_0___default()( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_2___default.a.mark(function _callee4() {
          return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_2___default.a.wrap(function _callee4$(_context4) {
            while (1) {
              switch (_context4.prev = _context4.next) {
                case 0:
                  event.preventDefault();

                  _this5.submitForm();

                case 2:
                case "end":
                  return _context4.stop();
              }
            }
          }, _callee4);
        }))();
      });
    },

    /**
     * Init.
     *
     * Fires when this object is set up.
     *
     * @since      1.0.0
     * @access     public
     *
     * @returns {Promise<void>}
     */
    init: function init() {
      var _this6 = this;

      return _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_0___default()( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_2___default.a.mark(function _callee5() {
        var response;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_2___default.a.wrap(function _callee5$(_context5) {
          while (1) {
            switch (_context5.prev = _context5.next) {
              case 0:
                _context5.next = 2;
                return Object(_affiliatewp_portal_sdk__WEBPACK_IMPORTED_MODULE_5__["portalSectionFields"])(_this6.sectionId);

              case 2:
                response = _context5.sent;
                _this6.fields = response.fields.map(function (field) {
                  if ('checkbox' === field.type) {
                    if ('on' === field.value) {
                      field.value = true;
                    }

                    if ('off' === field.value) {
                      field.value = false;
                    }
                  }

                  return field;
                });
                _this6.isLoading = false;

              case 5:
              case "end":
                return _context5.stop();
            }
          }
        }, _callee5);
      }))();
    }
  });
});

/***/ }),

/***/ "@affiliatewp-portal/alpine-form":
/*!************************************************!*\
  !*** external ["AFFWP","portal","alpineForm"] ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["AFFWP"]["portal"]["alpineForm"]; }());

/***/ }),

/***/ "@affiliatewp-portal/sdk":
/*!*****************************************!*\
  !*** external ["AFFWP","portal","sdk"] ***!
  \*****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["AFFWP"]["portal"]["sdk"]; }());

/***/ }),

/***/ "@babel/runtime/regenerator":
/*!*************************************!*\
  !*** external "regeneratorRuntime" ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["regeneratorRuntime"]; }());

/***/ })

/******/ });
//# sourceMappingURL=portal-form.js.map