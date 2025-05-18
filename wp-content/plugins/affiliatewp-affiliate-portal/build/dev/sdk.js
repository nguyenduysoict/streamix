window["AFFWP"] = window["AFFWP"] || {}; window["AFFWP"]["portal"] = window["AFFWP"]["portal"] || {}; window["AFFWP"]["portal"]["sdk"] =
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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/sdk/sdk.js");
/******/ })
/************************************************************************/
/******/ ({

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

/***/ "./src/sdk/sdk.js":
/*!************************!*\
  !*** ./src/sdk/sdk.js ***!
  \************************/
/*! exports provided: portalSchemaColumns, portalDataset, portalAffiliate, portalSettings, portalSchemaRows, portalView, submitSection, portalControl, portalSection, portalSectionFields, validateControl */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "portalSchemaColumns", function() { return portalSchemaColumns; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "portalDataset", function() { return portalDataset; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "portalAffiliate", function() { return portalAffiliate; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "portalSettings", function() { return portalSettings; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "portalSchemaRows", function() { return portalSchemaRows; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "portalView", function() { return portalView; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "submitSection", function() { return submitSection; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "portalControl", function() { return portalControl; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "portalSection", function() { return portalSection; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "portalSectionFields", function() { return portalSectionFields; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "validateControl", function() { return validateControl; });
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/defineProperty.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_url__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/url */ "@wordpress/url");
/* harmony import */ var _wordpress_url__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_url__WEBPACK_IMPORTED_MODULE_1__);


function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); enumerableOnly && (symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; })), keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = null != arguments[i] ? arguments[i] : {}; i % 2 ? ownKeys(Object(source), !0).forEach(function (key) { _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0___default()(target, key, source[key]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)) : ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } return target; }

/**
 * AffiliateWP Affiliate Portal SDK.
 *
 * Functions for interacting with AffiliateWP Affiliate Portal REST endpoints.
 *
 * @author Alex Standiford
 * @since 1.0.0
 */

/**
 * WordPress dependencies
 */

/**
 * Portal Affiliate Endpoint.
 *
 * Fetches the data for the provided affiliate.
 *
 * @since      1.0.0
 * @access     protected
 *
 * @return {Promise}
 */

function portalAffiliate() {
  var args = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  var affiliate;

  if (undefined === args.affiliate) {
    affiliate = affwp_portal_vars.affiliate_id;
  } else {
    affiliate = args.affiliate;
    delete args.affiliate;
  }

  return AFFWP.portal.core.fetch({
    path: Object(_wordpress_url__WEBPACK_IMPORTED_MODULE_1__["addQueryArgs"])("/affwp/v1/affiliates/".concat(affiliate), args),
    skipAffiliateId: true,
    cacheResult: true
  });
}
/**
 * Portal Settings Endpoint.
 *
 * Fetches the affiliate portal settings data.
 *
 * @since      1.0.0
 * @access     protected
 *
 * @return {Promise}
 */


function portalSettings() {
  return AFFWP.portal.core.fetch({
    path: '/affwp/v2/portal/settings',
    cacheResult: true
  });
}
/**
 * Portal Referrals Endpoint.
 *
 * Fetches referrals.
 *
 * @since      1.0.0
 * @access     protected
 *
 * @return {Promise}
 */


function portalSchemaRows(type) {
  var args = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};

  var requestArgs = _objectSpread(_objectSpread({}, args), {
    rows: true
  }); // Translate page into offset


  if (requestArgs.page) {
    requestArgs.offset = requestArgs.number ? (requestArgs.page - 1) * requestArgs.number : 20;
  }

  return AFFWP.portal.core.fetch({
    path: Object(_wordpress_url__WEBPACK_IMPORTED_MODULE_1__["addQueryArgs"])("/affwp/v2/portal/controls/".concat(type), requestArgs),
    cacheResult: true
  });
}
/**
 * Portal Referrals Endpoint.
 *
 * Fetches referrals.
 *
 * @since      1.0.0
 * @access     protected
 *
 * @return {Promise}
 */


function portalSchemaColumns(type) {
  return AFFWP.portal.core.fetch({
    path: Object(_wordpress_url__WEBPACK_IMPORTED_MODULE_1__["addQueryArgs"])("/affwp/v2/portal/controls/".concat(type), {
      columns: true
    }),
    cacheResult: true
  });
}
/**
 * Portal Datasets Endpoint.
 *
 * Fetches datasets.
 *
 * @since      1.0.0
 * @access     protected
 *
 * @return {Promise}
 */


function portalDataset() {
  var args = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  return AFFWP.portal.core.fetch({
    path: Object(_wordpress_url__WEBPACK_IMPORTED_MODULE_1__["addQueryArgs"])("/affwp/v2/portal/datasets", args),
    cacheResult: true
  });
}
/**
 * Portal View Endpoint.
 *
 * Fetches Portal view.
 *
 * @since      1.0.0
 * @access     protected
 *
 * @return {Promise}
 */


function portalView(view) {
  return AFFWP.portal.core.fetch({
    path: "/affwp/v2/portal/views/".concat(view),
    cacheResult: true
  });
}
/**
 * Portal Section Endpoint.
 *
 * Fetches a section.
 *
 * @since      1.0.0
 * @access     protected
 *
 * @return {Promise}
 */


function portalSection(section) {
  return AFFWP.portal.core.fetch({
    path: "/affwp/v2/portal/sections/".concat(section),
    cacheResult: true
  });
}
/**
 * Portal Section Endpoint.
 *
 * submits a section form.
 *
 * @since      1.0.0
 * @access     protected
 *
 * @return {Promise}
 */


function portalSectionFields(section) {
  return AFFWP.portal.core.fetch({
    path: "/affwp/v2/portal/sections/".concat(section, "/fields")
  });
}
/**

 * Portal Section Endpoint.
 *
 * submits a section form.
 *
 * @since      1.0.0
 * @access     protected
 *
 * @return {Promise}
 */


function submitSection(section, data) {
  return AFFWP.portal.core.fetch({
    method: 'POST',
    path: "/affwp/v2/portal/sections/".concat(section, "/submit"),
    data: data
  });
}
/**
 * Portal Controls Endpoint.
 *
 * Fetches a single control.
 *
 * @since      1.0.0
 * @access     protected
 *
 * @return {Promise}
 */


function portalControl(control) {
  return AFFWP.portal.core.fetch({
    path: "/affwp/v2/portal/controls/".concat(control),
    cacheResult: true
  });
}
/**
 * Validate Control.
 *
 * Runs field validations against a single control.
 *
 * @since 1.0.0
 * @access protected
 *
 * @param {string} control The control ID
 * @param {object} data The data to validate, keyed by the field ID
 * @returns {object} The control API response.
 */


function validateControl(control, data) {
  return AFFWP.portal.core.fetch({
    path: Object(_wordpress_url__WEBPACK_IMPORTED_MODULE_1__["addQueryArgs"])("/affwp/v2/portal/controls/".concat(control), {
      validate: true,
      data: data
    }),
    cacheResult: true
  });
}



/***/ }),

/***/ "@wordpress/url":
/*!*****************************!*\
  !*** external ["wp","url"] ***!
  \*****************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["url"]; }());

/***/ })

/******/ });
//# sourceMappingURL=sdk.js.map