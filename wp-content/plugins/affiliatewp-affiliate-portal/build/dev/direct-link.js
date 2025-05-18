window["AFFWP"] = window["AFFWP"] || {}; window["AFFWP"]["portal"] = window["AFFWP"]["portal"] || {}; window["AFFWP"]["portal"]["directLink"] =
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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/integrations/direct-link/index.js");
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

/***/ "./src/integrations/direct-link/index.js":
/*!***********************************************!*\
  !*** ./src/integrations/direct-link/index.js ***!
  \***********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/asyncToGenerator */ "./node_modules/@babel/runtime/helpers/asyncToGenerator.js");
/* harmony import */ var _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/regenerator */ "@babel/runtime/regenerator");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _affiliatewp_portal_url_helpers__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @affiliatewp-portal/url-helpers */ "./src/url-helpers/url-helpers.js");



/**
 * Direct Link Tracking view Handler.
 *
 * Works with the Direct Link Tracking screen in the affiliate portal to handle link operations.
 *
 * @since 1.0.0
 *
 */

/**
 * Internal dependencies
 */

/**
* Direct Link Tracking view screen AlpineJS handler.
*
* Works with the Direct Link Tracking screen in the affiliate portal to handle link operations.
*
* @since 1.0.0
* @access public
*
* @returns object The AlpineJS object.
*/

function settings() {
  return {
    /**
     * Is Loading.
     *
     * Determines if the app is loading.
     *
     * @since  1.0.0
     * @access public
     *
     * @type boolean
     */
    isLoading: false,

    /**
     * Is form valid.
     *
     * Determines if the form is valid.
     *
     * @since  1.0.0
     * @access public
     *
     * @type boolean
     */
    valid: false,

    /**
     * Current links Items.
     *
     * Array containing the current affiliate direct links.
     *
     * @since  1.0.0
     * @access public
     *
     * @type array
     */
    links: [],

    /**
     * Max number of links allowed.
     *
     * The max number of links an affiliate can register.
     *
     * @since  1.0.0
     * @access public
     *
     * @type int
     */
    maxLinks: 0,

    /**
     * Rejected domains.
     *
     * HTML string with list of rejected domains to show to the affiliate.
     *
     * @since  1.0.4
     * @access public
     *
     * @type string
     */
    rejected: '',

    /**
     * Showing success message.
     *
     * Shows success message when the form is submitted
     *
     * @since  1.0.0
     * @access public
     *
     * @type boolean
     */
    showingSuccessMessage: false,

    /**
     * Shows update notice.
     *
     * Shows notice to the user when links were updated.
     *
     * @since  1.0.0
     * @access public
     *
     * @type boolean
     */
    showUpdateNotice: false,

    /**
     * Shows invalid submission.
     *
     * Shows to the user when invalid links were submitted.
     *
     * @since  1.0.0
     * @access public
     *
     * @type boolean
     */
    showInvalidSubmission: false,

    /**
     * Is dismissing notice.
     *
     * Determines if the app is dismissing the notice.
     *
     * @since  1.0.0
     * @access public
     *
     * @type boolean
     */
    isDismissingNotice: false,

    /**
     * Init.
     *
     * Initializes the AlpineJS instance.
     *
     * @since      1.0.0
     * @access     public
     *
     * @return void
     */
    init: function init() {
      var _this = this;

      return _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_0___default()( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_1___default.a.mark(function _callee() {
        var response;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_1___default.a.wrap(function _callee$(_context) {
          while (1) {
            switch (_context.prev = _context.next) {
              case 0:
                _context.next = 2;
                return AFFWP.portal.core.fetch({
                  path: 'affwp/v2/portal/integrations/direct-link-tracking/get-links',
                  cacheResult: false
                });

              case 2:
                response = _context.sent;
                // Add some extra flags to each link.
                _this.links = response.links.map(function (link) {
                  link.timer = false;
                  link.isValidatingUrl = false;
                  link.isRemoving = false;
                  return link;
                });
                _this.rejected = response.rejected.join('<br>'); // Add one default domain if no links saved.

                if (_this.links.length === 0) {
                  _this.addDomain();
                }

                _this.checkValid();

                _this.isLoading = false;

              case 8:
              case "end":
                return _context.stop();
            }
          }
        }, _callee);
      }))();
    },

    /**
     * Adds a new direct link domain.
     *
     * Adds a new domain to the list of links.
     *
     * @since  1.0.0
     * @access public
     *
     * @returns void
     */
    addDomain: function addDomain() {
      if (this.links.length + 1 <= this.maxLinks) {
        this.links.push({
          url_id: '',
          url: '',
          errors: {}
        }); // New link is empty so the form should be invalid.

        this.valid = false;
      }
    },

    /**
     * Get Link Object.
     *
     * Attempts to retrieve the Link object from the list of links.
     *
     * @since      1.0.0
     * @access     public
     * 
     * @param index {int} index of link on links array.
     * @return {linkObject|boolean} linkObject instance, if it is set. Otherwise false.
     */
    getLinkObject: function getLinkObject(index) {
      // Bail if the index is not set.
      if (undefined === this.links[index]) {
        return false;
      }

      return this.links[index];
    },

    /**
     * Get Link Param.
     *
     * Attempts to retrieve the param from the specified link object.
     *
     * @since      1.0.0
     * @access     public
     * 
     * @param index {index} Index of link on links array.
     * @param param {string} Param Link object param to retrieve.
     *
     * @return {*} The param value.
     */
    getLinkParam: function getLinkParam(index, param) {
      var object = this.getLinkObject(index);
      /*
      * If the Link index doesn't exist, or the param cannot be found, bail with an empty string
      * Empty string is used here because this method is frequently called in the DOM.
      * Returning false would cause the DOM elements to display "false" in various inputs.
       */

      if (false === object || undefined === object[param]) {
        return '';
      }

      return object[param];
    },

    /**
     * Removes direct link domain.
     *
     * Removes a link from the list of ids by url id.
     *
     * @since  1.0.0
     * @access public
     *
     * @param linkIndex {int} Index of link on links array.
     * @returns void
     */
    removeLink: function removeLink(linkIndex) {
      var _this2 = this;

      return _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_0___default()( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_1___default.a.mark(function _callee2() {
        var linkToDelete, urlId;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_1___default.a.wrap(function _callee2$(_context2) {
          while (1) {
            switch (_context2.prev = _context2.next) {
              case 0:
                linkToDelete = _this2.getLinkObject(linkIndex);
                urlId = linkToDelete.url_id;

                if (!urlId) {
                  _context2.next = 6;
                  break;
                }

                linkToDelete.isRemoving = true;
                _context2.next = 6;
                return AFFWP.portal.core.fetch({
                  path: "affwp/v2/portal/integrations/direct-link-tracking/links/".concat(urlId),
                  method: 'DELETE',
                  data: {}
                });

              case 6:
                _this2.links.splice(linkIndex, 1);

              case 7:
              case "end":
                return _context2.stop();
            }
          }
        }, _callee2);
      }))();
    },

    /**
     * Submit links.
     *
     * Calls the REST API to save the links and get the new list of links and notices.
     *
     * @since  1.0.0
     * @access public
     *
     * @returns void
     */
    submit: function submit() {
      var _this3 = this;

      return _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_0___default()( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_1___default.a.mark(function _callee3() {
        var response;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_1___default.a.wrap(function _callee3$(_context3) {
          while (1) {
            switch (_context3.prev = _context3.next) {
              case 0:
                if (_this3.valid) {
                  _context3.next = 2;
                  break;
                }

                return _context3.abrupt("return");

              case 2:
                _this3.isLoading = true; // Post list of links and links to delete.

                _context3.next = 5;
                return AFFWP.portal.core.fetch({
                  path: 'affwp/v2/portal/integrations/direct-link-tracking/save-links',
                  method: 'POST',
                  data: {
                    links: _this3.links
                  }
                });

              case 5:
                response = _context3.sent;
                _this3.showInvalidSubmission = !response.success;
                _this3.links = response.links;
                _this3.rejected = response.rejected.join('<br>');
                _this3.showUpdateNotice = true;
                _this3.isLoading = false;

              case 11:
              case "end":
                return _context3.stop();
            }
          }
        }, _callee3);
      }))();
    },

    /**
     * Dismiss notice.
     *
     * Calls the REST API to dismiss the notice and get the new list of links and notices.
     *
     * @since  1.0.0
     * @access public
     *
     * @param url_id {int} URL ID.
     * @returns void
     */
    dismiss: function dismiss(url_id) {
      var _this4 = this;

      return _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_0___default()( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_1___default.a.mark(function _callee4() {
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_1___default.a.wrap(function _callee4$(_context4) {
          while (1) {
            switch (_context4.prev = _context4.next) {
              case 0:
                if (!_this4.isDismissingNotice) {
                  _context4.next = 2;
                  break;
                }

                return _context4.abrupt("return");

              case 2:
                _this4.isDismissingNotice = true;
                _this4.isLoading = true; // Call REST API to dismiss the notice for this url id.

                _context4.next = 6;
                return AFFWP.portal.core.fetch({
                  path: 'affwp/v2/portal/integrations/direct-link-tracking/dismiss-notice',
                  method: 'POST',
                  data: {
                    url_id: url_id
                  }
                });

              case 6:
                // reload data.
                _this4.init();

              case 7:
              case "end":
                return _context4.stop();
            }
          }
        }, _callee4);
      }))();
    },

    /**
     * Has Error.
     *
     * Determines if the specified error is set for a certain link.
     *
     * @since  1.0.0
     * @access public
     *
     * @param link {linkObject} Link object.
     * @param error {string} Type of error.
     * @returns {boolean} True if the error is true. Otherwise false.
     */
    hasError: function hasError(link, error) {
      return link.errors && true === link.errors[error];
    },

    /**
     * Has Errors.
     *
     * Determines if the link has any errors.
     *
     * @since  1.0.0
     * @access public
     *
     * @param link {linkObject} Link object.
     * @returns {boolean} True if the error is true. Otherwise false.
     */
    hasErrors: function hasErrors(link) {
      return link.errors && Object.keys(link.errors).length > 0;
    },

    /**
     * Checks if valid.
     *
     * Determines if there are errors on any of the links.
     *
     * @since  1.0.0
     * @access public
     *
     * @returns {boolean} True if the error is true. Otherwise false.
     */
    checkValid: function checkValid() {
      var valid = true;
      var linkInvalid = this.links.find(function (link) {
        return link.errors && Object.keys(link.errors).length > 0;
      });

      if (linkInvalid) {
        valid = false;
      }

      this.valid = valid;
    },

    /**
     * Validates links on the frontend.
     *
     * Determines if a link is valid just using client-side validations.
     *
     * @since  1.0.0
     * @access public
     *
     * @param linkIndex {int} Index of link on links array.
     * @returns void
     */
    validateFrontend: function validateFrontend(linkIndex) {
      var currentLink = this.getLinkObject(linkIndex); // Bail if link not found.

      if (false === currentLink) {
        return;
      }

      var url = currentLink.url; // Clear backend validation timeout, url has changed.

      clearTimeout(currentLink.timer); // Reset errors.

      var foundErrors = false;
      currentLink.errors = []; // Check if empty.

      if ('' === url.trim()) {
        currentLink.errors.empty = true;
        foundErrors = true;
      } else {
        // Check if duplicated.
        var duplicated = this.links.find(function (link, index) {
          return index !== linkIndex && link.url === url;
        });

        if (duplicated) {
          currentLink.errors.duplicated = true;
          foundErrors = true;
        } // Check if valid url (simple url validation).


        if (!Object(_affiliatewp_portal_url_helpers__WEBPACK_IMPORTED_MODULE_2__["validateUrl"])(url)) {
          currentLink.errors.invalid = true;
          foundErrors = true;
        }
      }

      if (foundErrors) {
        this.checkValid();
      } else {
        // No client-side errors, let's check on backend with add-on validation.
        this.valid = false; // Wait 500ms before submitting the url.

        currentLink.timer = setTimeout(this.validateBackend.bind(this, linkIndex), 500);
      }
    },

    /**
     * Validates links on the backend.
     *
     * Determines if a link is valid just using client-side validations.
     *
     * @since  1.0.0
     * @access public
     *
     * @param linkIndex {int} Index of link on links array.
     * @returns void
     */
    validateBackend: function validateBackend(linkIndex) {
      var _this5 = this;

      return _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_0___default()( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_1___default.a.mark(function _callee5() {
        var currentLink, url, response;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_1___default.a.wrap(function _callee5$(_context5) {
          while (1) {
            switch (_context5.prev = _context5.next) {
              case 0:
                currentLink = _this5.getLinkObject(linkIndex); // Bail if link not found.

                if (!(false === currentLink)) {
                  _context5.next = 3;
                  break;
                }

                return _context5.abrupt("return");

              case 3:
                url = currentLink.url;
                currentLink.isValidatingUrl = true;
                _context5.next = 7;
                return AFFWP.portal.core.fetch({
                  path: 'affwp/v2/portal/integrations/direct-link-tracking/validate',
                  method: 'POST',
                  data: {
                    url: url
                  }
                });

              case 7:
                response = _context5.sent;
                currentLink.isValidatingUrl = false; // url has changed, ignore this validation.

                if (!(url !== currentLink.url)) {
                  _context5.next = 11;
                  break;
                }

                return _context5.abrupt("return");

              case 11:
                if (!response.success) {
                  currentLink.errors.addon = true;
                  currentLink.errors.addonReason = response.error;
                }

                _this5.checkValid();

              case 13:
              case "end":
                return _context5.stop();
            }
          }
        }, _callee5);
      }))();
    }
  };
}

/* harmony default export */ __webpack_exports__["default"] = (settings);

/***/ }),

/***/ "./src/url-helpers/url-helpers.js":
/*!****************************************!*\
  !*** ./src/url-helpers/url-helpers.js ***!
  \****************************************/
/*! exports provided: paginateUrl, getPage, appendUrl, authoritiesMatch, hasValidProtocol, constructUrl, validateUrl, getStablePath */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "paginateUrl", function() { return paginateUrl; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getPage", function() { return getPage; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "appendUrl", function() { return appendUrl; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "authoritiesMatch", function() { return authoritiesMatch; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "hasValidProtocol", function() { return hasValidProtocol; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "constructUrl", function() { return constructUrl; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "validateUrl", function() { return validateUrl; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getStablePath", function() { return getStablePath; });
/* harmony import */ var _wordpress_url__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/url */ "@wordpress/url");
/* harmony import */ var _wordpress_url__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_url__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _affiliatewp_portal_helpers__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @affiliatewp-portal/helpers */ "./src/helpers/helpers.js");
/**
 * URL Helper Functions.
 *
 * Helper functions that extend the @wordpress/url library.
 *
 * @author Alex Standiford
 * @since 1.0.0
 */

/**
 * WordPress dependencies
 */

/**
 * Internal dependencies
 */


var paginationRegex = /\/([^\/a-zA-Z-_]+)\/?$/;
/**
 * Append URL.
 *
 * Appends the provided path to the end of the provided URL's path.
 *
 * @since      1.0.0
 * @access     protected
 * @param {string} url The URL to append to.
 * @param {string} append The string to append to the URL.
 *
 * @return {string} URL with path appended.
 */

function appendUrl(url, append) {
  // Remove the slash at the beginning of append, if it was mistakenly added.
  if (append.startsWith('/')) {
    append = append.substr(1);
  } // Define the parts of the URL.


  return constructUrl(url, ['protocol', 'authority', 'path', Object(_affiliatewp_portal_helpers__WEBPACK_IMPORTED_MODULE_1__["trailingslashit"])(append), 'querystring', 'fragment']);
}
/**
 * Construct URL.
 *
 * Constructs a URL from a URL and specified parts.
 *
 * @since      1.0.0
 * @access     protected
 * @param {string} url The url to construct parts from.
 * @param {array} parts List of parts to construct, in the order they should be constructed.
 *                This can be any of the following: 'protocol', 'authority', 'path', 'querystring', 'fragment'
 *                If an arbitrary string is passed, that string will be inserted in the URL.
 *
 * @return {string} constructed URL
 */


function constructUrl(url, parts) {
  var urlObject = {
    /**
     * Get Protocol.
     * Retrieves the protocol from the URL.
     *
     * @since 1.0.0
     * @returns {string}
     */
    getProtocol: function getProtocol() {
      return "".concat(Object(_wordpress_url__WEBPACK_IMPORTED_MODULE_0__["getProtocol"])(url), "//");
    },

    /**
     * Get Authority.
     * Retrieves the authority from the URL.
     *
     * @since 1.0.0
     * @returns {string}
     */
    getAuthority: function getAuthority() {
      return Object(_affiliatewp_portal_helpers__WEBPACK_IMPORTED_MODULE_1__["trailingslashit"])(Object(_wordpress_url__WEBPACK_IMPORTED_MODULE_0__["getAuthority"])(url));
    },

    /**
     * Get Path.
     * Retrieves the path from the URL.
     *
     * @since 1.0.0
     * @returns {string}
     */
    getPath: function getPath() {
      return Object(_affiliatewp_portal_helpers__WEBPACK_IMPORTED_MODULE_1__["trailingslashit"])(Object(_wordpress_url__WEBPACK_IMPORTED_MODULE_0__["getPath"])(url));
    },

    /**
     * Get Query String.
     * Retrieves the querytstring from the URL.
     *
     * @since 1.0.0
     * @returns {string}
     */
    getQuerystring: function getQuerystring() {
      var queryString = Object(_wordpress_url__WEBPACK_IMPORTED_MODULE_0__["getQueryString"])(url);
      return queryString ? "?".concat(Object(_wordpress_url__WEBPACK_IMPORTED_MODULE_0__["getQueryString"])(url)) : '';
    },

    /**
     * Get Fragment.
     * Retrieves the fragment from the URL.
     *
     * @since 1.0.0
     * @returns {string}
     */
    getFragment: function getFragment() {
      return Object(_wordpress_url__WEBPACK_IMPORTED_MODULE_0__["getFragment"])(url);
    }
  };
  return parts.reduce(function (acc, part) {
    var isValidUrlPart = ['protocol', 'authority', 'path', 'querystring', 'fragment'].includes(part.toLowerCase());

    if (!isValidUrlPart && typeof part === 'string') {
      return acc + part;
    } else if (!isValidUrlPart) {
      return acc;
    }

    var callback = urlObject['get' + part.charAt(0).toUpperCase() + part.slice(1).toLowerCase()];
    var urlPart = callback();

    if (undefined === urlPart) {
      return acc;
    }

    return acc + urlPart;
  }, '');
}
/**
 * Authorities Match.
 *
 * Returns true if the provided url matches the specified base authority.
 *
 * @since      1.0.0
 * @access     protected
 * @param url {string} The URL to check.
 * @param baseAuthority {string} The base authority to check against.
 *
 * @return {boolean} true if authorities match, otherwise false.
 */


function authoritiesMatch(url, baseAuthority) {
  var inputAuthority = Object(_wordpress_url__WEBPACK_IMPORTED_MODULE_0__["getAuthority"])(url); // Return true if the authorities match.


  if (inputAuthority === baseAuthority) {
    return true;
  } // Return true if inputAuthority is a subdomain of baseAuthority.


  var regex = new RegExp("\\w\\." + baseAuthority + "$");
  return regex.test(inputAuthority);
}
/**
 * Has valid protocol.
 *
 * Returns true if the provided URL has a valid URL protocol for a typical web request.
 *
 * @since      1.0.0
 * @access     protected
 * @param url {string} The URL to check.
 *
 * @returns {boolean} true if valid, otherwise false.
 */


function hasValidProtocol(url) {
  var protocol = Object(_wordpress_url__WEBPACK_IMPORTED_MODULE_0__["getProtocol"])(url);

  return ['https:', 'http:'].includes(protocol);
}
/**
 * Get Page.
 *
 * Fetches the page from the provided URL
 *
 * @since     1.0.0
 * @access    protected
 * @param url {string} The URL from which the page number should be retrieved.
 *
 * @returns {string} The page number
 */


function getPage(url) {
  var path = Object(_wordpress_url__WEBPACK_IMPORTED_MODULE_0__["getPath"])(url);

  var search = path.match(paginationRegex); // If no page was found, we are on page 1.

  if (null === search) {
    return '1';
  } // Otherwise, get the page number.


  return search[1];
}
/**
 * Paginate URL.
 *
 * Appends the URL with the provided query args, and formats for pretty pagination.
 *
 * @since     1.0.0
 * @access    protected
 * @param url {string} The URL to paginate.
 * @param args {object} List of query param values keyed by their key.
 *                      If a page is passed, it will be formatted for pagination.
 *
 * @returns {string} The page number
 */


function paginateUrl(url, args) {
  getPage(url);
  var path = Object(_affiliatewp_portal_helpers__WEBPACK_IMPORTED_MODULE_1__["trailingslashit"])(Object(_wordpress_url__WEBPACK_IMPORTED_MODULE_0__["getPath"])(url)).replace(paginationRegex, '/'); // Strip out any existing pagination from the path.

  var urlParts = ['protocol', 'authority', path]; // Append the page number, if we have a page to append.

  if (args.page) {
    if (args.page > 1) {
      urlParts.push(args.page + '/');
    }

    delete args.page;
  } // Construct the URL using the provided URL parts.


  var result = constructUrl(url, urlParts); // Append query args to the resulting URL.

  return Object(_wordpress_url__WEBPACK_IMPORTED_MODULE_0__["addQueryArgs"])(result, args);
}
/**
 * Validates a given URL.
 *
 * Simple validation of an url.
 *
 * @since     1.0.0
 * @access    protected
 * @param url {string} The URL to validate.
 *
 * @returns {bool}
 */


function validateUrl(url) {
  return /\.\w\w.*/.test(url);
}
/**
 * Given a path, returns a normalized path where equal query parameter values
 * will be treated as identical, regardless of order they appear in the original
 * text.
 *
 * @param {string} path Original path.
 *
 * @return {string} Normalized path.
 */


function getStablePath(path) {
  var splitted = path.split('?');
  var query = splitted[1];
  var base = splitted[0];

  if (!query) {
    return base;
  } // 'b=1&c=2&a=5'


  return base + '?' + query // [ 'b=1', 'c=2', 'a=5' ]
  .split('&') // [ [ 'b, '1' ], [ 'c', '2' ], [ 'a', '5' ] ]
  .map(function (entry) {
    return entry.split('=');
  }) // [ [ 'a', '5' ], [ 'b, '1' ], [ 'c', '2' ] ]
  .sort(function (a, b) {
    return a[0].localeCompare(b[0]);
  }) // [ 'a=5', 'b=1', 'c=2' ]
  .map(function (pair) {
    return pair.join('=');
  }) // 'a=5&b=1&c=2'
  .join('&');
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

/***/ "@wordpress/url":
/*!*****************************!*\
  !*** external ["wp","url"] ***!
  \*****************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["url"]; }());

/***/ })

/******/ });
//# sourceMappingURL=direct-link.js.map