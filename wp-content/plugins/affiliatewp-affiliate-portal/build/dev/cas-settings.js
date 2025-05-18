window["AFFWP"] = window["AFFWP"] || {}; window["AFFWP"]["portal"] = window["AFFWP"]["portal"] || {}; window["AFFWP"]["portal"]["casSettings"] =
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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/integrations/cas-settings/index.js");
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

/***/ "./src/integrations/cas-settings/index.js":
/*!************************************************!*\
  !*** ./src/integrations/cas-settings/index.js ***!
  \************************************************/
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
/* harmony import */ var _affiliatewp_portal_portal_form__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @affiliatewp-portal/portal-form */ "@affiliatewp-portal/portal-form");
/* harmony import */ var _affiliatewp_portal_portal_form__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_affiliatewp_portal_portal_form__WEBPACK_IMPORTED_MODULE_3__);




function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); enumerableOnly && (symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; })), keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = null != arguments[i] ? arguments[i] : {}; i % 2 ? ownKeys(Object(source), !0).forEach(function (key) { _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1___default()(target, key, source[key]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)) : ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } return target; }

/**
 * Custom Affiliate Slugs Settings Handler.
 *
 * Works with the settings page template to handle slug validation.
 *
 * @author Alex Standiford
 * @since 1.0.0
 * @global CASSettings
 *
 */

/**
 * Internal Dependencies
 */

/**
 * Custom Affiliate Slugs Settings screen AlpineJS handler.
 *
 * Works with the settings page template to handle slug validation.
 *
 * @since 1.0.0
 * @access public
 * @global CASSettings
 *
 * @returns object The AlpineJS object.
 */

function settings() {
  var form = _affiliatewp_portal_portal_form__WEBPACK_IMPORTED_MODULE_3___default()();
  return _objectSpread(_objectSpread({}, form), {
    /**
     * Section ID.
     *
     * The section ID that contains the form fields.
     *
     * @since 1.0.0
     *
     * @type {string} The section ID
     */
    sectionId: 'custom-affiliate-slugs-settings',

    /**
     * Original Slug
     *
     * The original slug that was provided on page load.
     *
     * @since  1.0.0
     * @access public
     *
     * @type string
     */
    originalSlug: '',

    /**
     * Show Confirm Field.
     *
     * Returns true if the confirm setting field should be visible.
     *
     * @since      1.0.0
     * @access     public
     *
     * @returns {boolean} true if visible, otherwise false.
     */
    showConfirmField: function showConfirmField() {
      var slug = this.getField('custom-affiliate-slug-setting');

      if (false === slug) {
        return false;
      }

      if (this.originalSlug === slug.value || "" === slug.value) {
        return false;
      }

      return true;
    },

    /**
     * Reset Confirmations.
     *
     * Resets the delete checkbox, the confirm slug, and their validations.
     *
     * @since      1.0.0
     * @access     public
     *
     * @returns {Promise<void>}
     */
    resetConfirmations: function resetConfirmations() {
      var _this = this;

      return _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_0___default()( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_2___default.a.mark(function _callee() {
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_2___default.a.wrap(function _callee$(_context) {
          while (1) {
            switch (_context.prev = _context.next) {
              case 0:
                // Reset confirmation values
                _this.updateFieldValue("custom-affiliate-slug-confirm-delete", false);

                _this.updateFieldValue("custom-affiliate-slug-confirm", '');

                _this.isValidating = true; // Reset confirmations.

                _this.removeErrors(['custom-affiliate-slug-confirm', 'custom-affiliate-slug-confirm-delete']);

                _context.next = 6;
                return Promise.all([_this.validateControl('custom-affiliate-slug-confirm'), _this.validateControl('custom-affiliate-slug-confirm-delete')]);

              case 6:
                _this.isValidating = false;

              case 7:
              case "end":
                return _context.stop();
            }
          }
        }, _callee);
      }))();
    },

    /**
     * Validate Control.
     *
     * Validates a control by the provided ID, and sets the error if so
     *
     * @since 1.0.0
     * @access public
     * @param {String} id Control ID.
     *
     * @returns {Promise<void>}
     */
    validateControl: function validateControl(id) {
      var _this2 = this;

      return _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_0___default()( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_2___default.a.mark(function _callee2() {
        var validateControl;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_2___default.a.wrap(function _callee2$(_context2) {
          while (1) {
            switch (_context2.prev = _context2.next) {
              case 0:
                validateControl = form.validateControl.bind(_this2);

                if (!('custom-affiliate-slug-setting' === id)) {
                  _context2.next = 4;
                  break;
                }

                _context2.next = 4;
                return _this2.resetConfirmations();

              case 4:
                validateControl(id);

              case 5:
              case "end":
                return _context2.stop();
            }
          }
        }, _callee2);
      }))();
    },

    /**
     * Show Confirm Delete Field.
     *
     * Returns true if the confirm delete setting checkbox should be visible.
     *
     * @since      1.0.0
     * @access     public
     *
     * @returns {boolean} true if visible, otherwise false.
     */
    showConfirmDeleteField: function showConfirmDeleteField() {
      if (true === this.isLoading) {
        return false;
      }

      var slug = this.getField('custom-affiliate-slug-setting');

      if (false === slug) {
        return false;
      }

      if ('' === this.originalSlug || "" !== slug.value) {
        return false;
      }

      return true;
    },

    /**
     * Submit Form.
     *
     * Actions that should be taken when the form is submitted.
     *
     * @since      1.0.0
     * @access     public
     *
     * @returns {Promise<void>}
     */
    submitForm: function submitForm() {
      var _this3 = this;

      return _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_0___default()( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_2___default.a.mark(function _callee3() {
        var submitForm;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_2___default.a.wrap(function _callee3$(_context3) {
          while (1) {
            switch (_context3.prev = _context3.next) {
              case 0:
                submitForm = form.submitForm.bind(_this3);
                _context3.next = 3;
                return submitForm();

              case 3:
                _this3.resetConfirmations();

                _this3.resetSlug();

              case 5:
              case "end":
                return _context3.stop();
            }
          }
        }, _callee3);
      }))();
    },

    /**
     * Reset Slug.
     *
     * Resets the original slug value to whatever the current slug setting value is.
     *
     * @since 1.0.0
     *
     * @returns {Promise<void>}
     */
    resetSlug: function resetSlug() {
      // Just after setup is complete, get the field value.
      var slug = this.getField('custom-affiliate-slug-setting');

      if (false !== slug) {
        this.originalSlug = slug.value;
      }
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
      var _this4 = this;

      return _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_0___default()( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_2___default.a.mark(function _callee4() {
        var init;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_2___default.a.wrap(function _callee4$(_context4) {
          while (1) {
            switch (_context4.prev = _context4.next) {
              case 0:
                init = form.init.bind(_this4);
                _context4.next = 3;
                return init();

              case 3:
                _this4.resetSlug();

              case 4:
              case "end":
                return _context4.stop();
            }
          }
        }, _callee4);
      }))();
    }
  });
}

/* harmony default export */ __webpack_exports__["default"] = (settings);

/***/ }),

/***/ "@affiliatewp-portal/portal-form":
/*!************************************************!*\
  !*** external ["AFFWP","portal","portalForm"] ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["AFFWP"]["portal"]["portalForm"]; }());

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
//# sourceMappingURL=cas-settings.js.map