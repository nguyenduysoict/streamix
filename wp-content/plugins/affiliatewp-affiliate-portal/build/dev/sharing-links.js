window["AFFWP"] = window["AFFWP"] || {}; window["AFFWP"]["portal"] = window["AFFWP"]["portal"] || {}; window["AFFWP"]["portal"]["sharingLinks"] =
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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/sharing-links/index.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/sharing-links/index.js":
/*!************************************!*\
  !*** ./src/sharing-links/index.js ***!
  \************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/**
 * Referral Sharing Links.
 *
 * Works with the URLs page to add sharing link functionality.
 *
 * @since 1.0.0
 *
 */

/**
 * Referral Sharing Links handler.
 *
 * Works with the URLs page to add sharing link functionality.
 *
 * @since 1.0.0
 * @global sharingLinks
 *
 * @returns object A sharing links AlpineJS object.
 */
function sharingLinks() {
  return {
    /**
     * Text.
     *
     * Text from settings for Twitter set by twitterInit function.
     *
     * @since  1.0.0
     * @access public
     *
     * @type string
     */
    text: '',

    /**
     * Subject.
     *
     * Subject from settings for email set by emailInit function.
     *
     * @since  1.0.0
     * @access public
     *
     * @type string
     */
    subject: '',

    /**
     * Body.
     *
     * Body from settings for email set by emailInit function.
     *
     * @since  1.0.0
     * @access public
     *
     * @type string
     */
    body: '',

    /**
     * Twitter Init.
     *
     * Adds inline-block to twitter link.
     *
     * @since  1.0.0
     * @access public
     *
     * @return {void}
     */
    twitterInit: function twitterInit() {
      document.getElementById("referral-sharing-twitter").parentElement.classList.add("inline-block");
    },

    /**
     * Twitter Referral Link.
     *
     * Creates link and opens window to share the referral link via Twitter.
     *
     * @since  1.0.0
     * @access public
     *
     * @fires window.open()
     *
     * @return {void}
     */
    twitterReferralLink: function twitterReferralLink() {
      var defaultURL = "https://twitter.com/intent/tweet?url=";
      var referralURL = AFFWP.portal.core.store.get("urlGeneratorUrls").generated.url;
      var twitterText = "&text=" + encodeURIComponent(this.text);
      var shareLink = defaultURL + encodeURIComponent(referralURL) + twitterText;
      window.open(shareLink, "twitterwindow", "left=20,top=20,width=600,height=300,toolbar=0,resizable=1");
      return false;
    },

    /**
     * Facebook Init.
     *
     * Adds inline-block to facebook link.
     *
     * @since  1.0.0
     * @access public
     *
     * @return {void}
     */
    facebookInit: function facebookInit() {
      document.getElementById("referral-sharing-facebook").parentElement.classList.add("inline-block");
    },

    /**
     * Facebook Referral Link.
     *
     * Creates link and opens window to share the referral link via Facebook.
     *
     * @since  1.0.0
     * @access public
     *
     * @fires window.open()
     *
     * @return {void}
     */
    fbReferralLink: function fbReferralLink() {
      var defaultURL = "https://www.facebook.com/sharer/sharer.php?u=";
      var referralURL = AFFWP.portal.core.store.get("urlGeneratorUrls").generated.url;
      var shareLink = defaultURL + encodeURIComponent(referralURL);
      window.open(shareLink, "facebookwindow", "left=20,top=20,width=600,height=700,toolbar=0,resizable=1");
      return false;
    },

    /**
     * Email Init.
     *
     * Adds inline-block to email link.
     *
     * @since  1.0.0
     * @access public
     *
     * @return {void}
     */
    emailInit: function emailInit() {
      document.getElementById("referral-sharing-email").parentElement.classList.add("inline-block");
    },

    /**
     * Email Referral Link.
     *
     * Creates link and opens window to share the referral link via email.
     *
     * @since  1.0.0
     * @access public
     *
     * @fires window.open()
     *
     * @return {void}
     */
    emailReferralLink: function emailReferralLink(event) {
      // Prevent page reload.
      event.preventDefault();
      var defaultURL = "mailto:";
      var emailSubject = "?subject=" + encodeURIComponent(this.subject);
      var emailBody = "&body=" + encodeURIComponent(this.body) + " ";
      var referralURL = AFFWP.portal.core.store.get("urlGeneratorUrls").generated.url;
      var shareLink = document.getElementById("referral-sharing-email");
      var link = defaultURL + emailSubject + emailBody + encodeURIComponent(referralURL); // currently only works if you have an email handler setup

      window.open(link, '_self'); // secondary option to work on Chrome if you don't have an email handler setup

      shareLink.href = link;
      shareLink.click();
      shareLink.href = '';
    }
  };
}

/* harmony default export */ __webpack_exports__["default"] = (sharingLinks);

/***/ })

/******/ });
//# sourceMappingURL=sharing-links.js.map