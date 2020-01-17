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
/******/ 	return __webpack_require__(__webpack_require__.s = "./source/js/modularity-form-builder-front.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/webpack/buildin/harmony-module.js":
/*!*******************************************!*\
  !*** (webpack)/buildin/harmony-module.js ***!
  \*******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = function(originalModule) {\n\tif (!originalModule.webpackPolyfill) {\n\t\tvar module = Object.create(originalModule);\n\t\t// module.parent = undefined by default\n\t\tif (!module.children) module.children = [];\n\t\tObject.defineProperty(module, \"loaded\", {\n\t\t\tenumerable: true,\n\t\t\tget: function() {\n\t\t\t\treturn module.l;\n\t\t\t}\n\t\t});\n\t\tObject.defineProperty(module, \"id\", {\n\t\t\tenumerable: true,\n\t\t\tget: function() {\n\t\t\t\treturn module.i;\n\t\t\t}\n\t\t});\n\t\tObject.defineProperty(module, \"exports\", {\n\t\t\tenumerable: true\n\t\t});\n\t\tmodule.webpackPolyfill = 1;\n\t}\n\treturn module;\n};\n\n\n//# sourceURL=webpack:///(webpack)/buildin/harmony-module.js?");

/***/ }),

/***/ "./source/js/front/collapse.js":
/*!*************************************!*\
  !*** ./source/js/front/collapse.js ***!
  \*************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* WEBPACK VAR INJECTION */(function(module) {(function () {\n  var enterModule = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.enterModule : undefined;\n  enterModule && enterModule(module);\n})();\n\nvar __signature__ = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.default.signature : function (a) {\n  return a;\n};\n\nvar _default = function ($) {\n  function Collapse() {\n    this.init();\n    this.handleEvents();\n  }\n\n  Collapse.prototype.init = function () {\n    $('.mod-form-collapse').each(function (index) {\n      $(this).nextUntil(':not(.mod-form-field)').hide();\n    });\n  };\n\n  Collapse.prototype.handleEvents = function () {\n    $('button', '.mod-form-collapse').click(function (e) {\n      e.preventDefault();\n      $('.mod-form-collapse__icon > i', e.target).toggleClass('pricon-plus-o pricon-minus-o');\n      $(e.target).parents('.mod-form-collapse').nextUntil(':not(.mod-form-field)').each(function () {\n        $(this).fadeToggle('fast');\n      });\n    }.bind(this));\n  };\n\n  return new Collapse();\n}(jQuery);\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (_default);\n;\n\n(function () {\n  var reactHotLoader = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.default : undefined;\n\n  if (!reactHotLoader) {\n    return;\n  }\n\n  reactHotLoader.register(_default, \"default\", \"/Users/jonatanhanson/Sites/vagrant/public/developement.local/wp-content/plugins/modularity-form-builder/source/js/front/collapse.js\");\n})();\n\n;\n\n(function () {\n  var leaveModule = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.leaveModule : undefined;\n  leaveModule && leaveModule(module);\n})();\n/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../../../node_modules/webpack/buildin/harmony-module.js */ \"./node_modules/webpack/buildin/harmony-module.js\")(module)))\n\n//# sourceURL=webpack:///./source/js/front/collapse.js?");

/***/ }),

/***/ "./source/js/front/get-location.js":
/*!*****************************************!*\
  !*** ./source/js/front/get-location.js ***!
  \*****************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* WEBPACK VAR INJECTION */(function(module) {(function () {\n  var enterModule = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.enterModule : undefined;\n  enterModule && enterModule(module);\n})();\n\nvar __signature__ = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.default.signature : function (a) {\n  return a;\n};\n\nvar _default = function ($) {\n  var componentForm = {\n    street_number: {\n      name: 'street',\n      addressType: 'short_name'\n    },\n    route: {\n      name: 'street',\n      addressType: 'short_name'\n    },\n    locality: {\n      name: 'city',\n      addressType: 'long_name'\n    },\n    postal_code: {\n      name: 'postal-code',\n      addressType: 'long_name'\n    }\n  };\n\n  function GetLocation() {\n    var locationButton = document.getElementById('form-get-location');\n\n    if (!navigator.geolocation || locationButton === null) {\n      return;\n    }\n\n    this.handleEvents();\n  }\n\n  GetLocation.prototype.handleEvents = function () {\n    $('#form-get-location').click(function (e) {\n      e.preventDefault();\n      $target = $(e.target).parents('[class*=\"mod-form\"]');\n      $(e.target).find('.pricon').removeClass().addClass('spinner spinner-dark');\n      navigator.geolocation.getCurrentPosition(function (position) {\n        var lat = position.coords.latitude,\n            lng = position.coords.longitude,\n            googleMapsPos = new google.maps.LatLng(lat, lng),\n            googleMapsGeocoder = new google.maps.Geocoder();\n        googleMapsGeocoder.geocode({\n          'latLng': googleMapsPos\n        }, function (results, status) {\n          var fullAddress = [];\n\n          if (status == google.maps.GeocoderStatus.OK && results[0]) {\n            // Get each component of the address from the place details and fill the form\n            for (var i = 0; i < results[0].address_components.length; i++) {\n              var addressType = results[0].address_components[i].types[0];\n\n              if (componentForm[addressType]) {\n                var value = results[0].address_components[i][componentForm[addressType].addressType];\n                $target.find('[id$=\"address-' + componentForm[addressType].name + '\"]').val(value);\n              } // Combine street name and street number\n\n\n              if (addressType == 'route') {\n                fullAddress[0] = value;\n              } else if (addressType == 'street_number') {\n                fullAddress[1] = value;\n              }\n\n              $target.find('[id$=\"address-street\"]').val(fullAddress.join(' '));\n            }\n          }\n        }); // Reset button icon\n\n        $(e.target).find('.spinner').removeClass().addClass('pricon pricon-location-pin');\n      }, function () {\n        // Show message if Geolocate went wrong\n        $(e.target).html('<span><i class=\"pricon pricon-notice-warning\"></i> ' + formbuilder.something_went_wrong + '</span>');\n      });\n    }.bind(this));\n  };\n\n  return new GetLocation();\n}(jQuery);\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (_default);\n;\n\n(function () {\n  var reactHotLoader = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.default : undefined;\n\n  if (!reactHotLoader) {\n    return;\n  }\n\n  reactHotLoader.register(_default, \"default\", \"/Users/jonatanhanson/Sites/vagrant/public/developement.local/wp-content/plugins/modularity-form-builder/source/js/front/get-location.js\");\n})();\n\n;\n\n(function () {\n  var leaveModule = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.leaveModule : undefined;\n  leaveModule && leaveModule(module);\n})();\n/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../../../node_modules/webpack/buildin/harmony-module.js */ \"./node_modules/webpack/buildin/harmony-module.js\")(module)))\n\n//# sourceURL=webpack:///./source/js/front/get-location.js?");

/***/ }),

/***/ "./source/js/front/handle-conditions.js":
/*!**********************************************!*\
  !*** ./source/js/front/handle-conditions.js ***!
  \**********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* WEBPACK VAR INJECTION */(function(module) {(function () {\n  var enterModule = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.enterModule : undefined;\n  enterModule && enterModule(module);\n})();\n\nvar __signature__ = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.default.signature : function (a) {\n  return a;\n};\n\nvar _default = function ($) {\n  function HandleConditions() {\n    this.handleRequired();\n    this.handleEvents();\n  }\n\n  HandleConditions.prototype.handleRequired = function () {\n    var $target = $('[class*=\"mod-form\"]');\n    $('[conditional-target]:hidden', $target).find('[required]').prop('required', false).attr('hidden-required', true);\n    $('[conditional-target]:visible', $target).find('[hidden-required]').prop('required', true);\n  };\n\n  HandleConditions.prototype.handleEvents = function () {\n    $('input[conditional]').change(function (e) {\n      $target = $(e.target).parents('[class*=\"mod-form\"]');\n      var conditional = $(e.target).attr('conditional');\n\n      if (typeof conditional !== 'undefined' && conditional.length > 0) {\n        var conditionObj = JSON.parse(conditional);\n        $target.find(\"div[conditional-target^='{\\\"label\\\":\\\"\" + conditionObj.label + \"\\\",']\").hide();\n        $target.find(\"div[conditional-target='\" + conditional + \"']\").show();\n      }\n\n      this.handleRequired();\n    }.bind(this));\n  };\n\n  return new HandleConditions();\n}(jQuery);\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (_default);\n;\n\n(function () {\n  var reactHotLoader = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.default : undefined;\n\n  if (!reactHotLoader) {\n    return;\n  }\n\n  reactHotLoader.register(_default, \"default\", \"/Users/jonatanhanson/Sites/vagrant/public/developement.local/wp-content/plugins/modularity-form-builder/source/js/front/handle-conditions.js\");\n})();\n\n;\n\n(function () {\n  var leaveModule = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.leaveModule : undefined;\n  leaveModule && leaveModule(module);\n})();\n/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../../../node_modules/webpack/buildin/harmony-module.js */ \"./node_modules/webpack/buildin/harmony-module.js\")(module)))\n\n//# sourceURL=webpack:///./source/js/front/handle-conditions.js?");

/***/ }),

/***/ "./source/js/front/recaptcha-warning.js":
/*!**********************************************!*\
  !*** ./source/js/front/recaptcha-warning.js ***!
  \**********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* WEBPACK VAR INJECTION */(function(module) {(function () {\n  var enterModule = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.enterModule : undefined;\n  enterModule && enterModule(module);\n})();\n\nvar __signature__ = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.default.signature : function (a) {\n  return a;\n};\n\nvar _default = function ($) {\n  $(document).ready(function () {\n    function removeReCaptchaWarning() {\n      if ($('#g-recaptcha-response').val()) {\n        $('.captcha-warning').hide();\n      }\n    }\n\n    window.setInterval(removeReCaptchaWarning, 1000);\n  });\n}(jQuery);\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (_default);\n;\n\n(function () {\n  var reactHotLoader = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.default : undefined;\n\n  if (!reactHotLoader) {\n    return;\n  }\n\n  reactHotLoader.register(_default, \"default\", \"/Users/jonatanhanson/Sites/vagrant/public/developement.local/wp-content/plugins/modularity-form-builder/source/js/front/recaptcha-warning.js\");\n})();\n\n;\n\n(function () {\n  var leaveModule = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.leaveModule : undefined;\n  leaveModule && leaveModule(module);\n})();\n/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../../../node_modules/webpack/buildin/harmony-module.js */ \"./node_modules/webpack/buildin/harmony-module.js\")(module)))\n\n//# sourceURL=webpack:///./source/js/front/recaptcha-warning.js?");

/***/ }),

/***/ "./source/js/front/submit-handler.js":
/*!*******************************************!*\
  !*** ./source/js/front/submit-handler.js ***!
  \*******************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* WEBPACK VAR INJECTION */(function(module) {(function () {\n  var enterModule = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.enterModule : undefined;\n  enterModule && enterModule(module);\n})();\n\nvar __signature__ = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.default.signature : function (a) {\n  return a;\n};\n\nvar _default = function ($) {\n  function Submit() {\n    $('form').submit(function (event) {\n      if (formbuilder.site_key) {\n        var recaptcha = $(event.target).find('.g-recaptcha-response').val();\n\n        if (recaptcha === '') {\n          event.preventDefault();\n          $('.captcha-warning').show();\n        } else {\n          this.handleEvents();\n        }\n      } else {\n        this.handleEvents();\n      }\n    }.bind(this));\n  } // Show spinner icon on submit\n\n\n  Submit.prototype.handleEvents = function () {\n    $('[class*=\"mod-form\"]').submit(function (e) {\n      $(e.target).find('button[type=\"submit\"]').html('<i class=\"spinner\"></i> ' + formbuilder.sending);\n    }.bind(this));\n  };\n\n  return new Submit();\n}(jQuery);\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (_default);\n;\n\n(function () {\n  var reactHotLoader = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.default : undefined;\n\n  if (!reactHotLoader) {\n    return;\n  }\n\n  reactHotLoader.register(_default, \"default\", \"/Users/jonatanhanson/Sites/vagrant/public/developement.local/wp-content/plugins/modularity-form-builder/source/js/front/submit-handler.js\");\n})();\n\n;\n\n(function () {\n  var leaveModule = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.leaveModule : undefined;\n  leaveModule && leaveModule(module);\n})();\n/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../../../node_modules/webpack/buildin/harmony-module.js */ \"./node_modules/webpack/buildin/harmony-module.js\")(module)))\n\n//# sourceURL=webpack:///./source/js/front/submit-handler.js?");

/***/ }),

/***/ "./source/js/front/validation.js":
/*!***************************************!*\
  !*** ./source/js/front/validation.js ***!
  \***************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* WEBPACK VAR INJECTION */(function(module) {(function () {\n  var enterModule = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.enterModule : undefined;\n  enterModule && enterModule(module);\n})();\n\nfunction _typeof(obj) { if (typeof Symbol === \"function\" && typeof Symbol.iterator === \"symbol\") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === \"function\" && obj.constructor === Symbol && obj !== Symbol.prototype ? \"symbol\" : typeof obj; }; } return _typeof(obj); }\n\nvar __signature__ = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.default.signature : function (a) {\n  return a;\n};\n\nvar _default = function () {\n  var getValidity = function getValidity(element, scope) {\n    var checkedCheckboxes = scope.querySelectorAll('.required[type=\"checkbox\"]:checked');\n    var valid = checkedCheckboxes.length > 0;\n    element.setCustomValidity(valid ? '' : formbuilder.checkbox_required);\n    return valid;\n  };\n\n  var setHyperformValidation = function setHyperformValidation(scope) {\n    var requiredCheckboxes = scope.querySelectorAll('[type=\"checkbox\"].required');\n\n    if (_typeof(requiredCheckboxes) === 'object' && requiredCheckboxes.length > 0) {\n      for (var i = 0; i < requiredCheckboxes.length; i++) {\n        var checkbox = requiredCheckboxes[i];\n        hyperform.addValidator(checkbox, function (element) {\n          return getValidity(element, scope);\n        });\n      }\n    }\n  };\n\n  var setCheckboxValidationRules = function setCheckboxValidationRules(modularityForm) {\n    var checkboxGroups = modularityForm.getElementsByClassName('checkbox-group');\n\n    for (var i = 0; i < checkboxGroups.length; i++) {\n      var checkboxGroup = checkboxGroups.item(i);\n      setHyperformValidation(checkboxGroup);\n    }\n  };\n  /**\n   * Loop through forms and set custom validation rules to required checkboxes\n   */\n\n\n  var init = function init() {\n    var forms = document.getElementsByClassName('modularity-validation');\n\n    var _loop = function _loop(i) {\n      var form = forms.item(i);\n      setCheckboxValidationRules(form);\n      /* Whenever a checkbox is clicked, revalidate all other checkboxes */\n\n      var inputElements = form.getElementsByTagName('input');\n      inputElements = Array.from(inputElements);\n      inputElements = inputElements.filter(function (elem) {\n        return elem.type === 'checkbox';\n      });\n\n      for (var j = 0; j < inputElements.length; j++) {\n        inputElements[j].addEventListener('click', function () {\n          for (var k = 0; k < inputElements.length; k++) {\n            inputElements[k].reportValidity();\n          }\n        });\n      }\n    };\n\n    for (var i = 0; i < forms.length; i++) {\n      _loop(i);\n    }\n  };\n\n  return init();\n}();\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (_default);\n;\n\n(function () {\n  var reactHotLoader = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.default : undefined;\n\n  if (!reactHotLoader) {\n    return;\n  }\n\n  reactHotLoader.register(_default, \"default\", \"/Users/jonatanhanson/Sites/vagrant/public/developement.local/wp-content/plugins/modularity-form-builder/source/js/front/validation.js\");\n})();\n\n;\n\n(function () {\n  var leaveModule = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.leaveModule : undefined;\n  leaveModule && leaveModule(module);\n})();\n/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../../../node_modules/webpack/buildin/harmony-module.js */ \"./node_modules/webpack/buildin/harmony-module.js\")(module)))\n\n//# sourceURL=webpack:///./source/js/front/validation.js?");

/***/ }),

/***/ "./source/js/modularity-form-builder-front.js":
/*!****************************************************!*\
  !*** ./source/js/modularity-form-builder-front.js ***!
  \****************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* WEBPACK VAR INJECTION */(function(module) {/* harmony import */ var _front_collapse__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./front/collapse */ \"./source/js/front/collapse.js\");\n/* harmony import */ var _front_get_location__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./front/get-location */ \"./source/js/front/get-location.js\");\n/* harmony import */ var _front_handle_conditions__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./front/handle-conditions */ \"./source/js/front/handle-conditions.js\");\n/* harmony import */ var _front_recaptcha_warning__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./front/recaptcha-warning */ \"./source/js/front/recaptcha-warning.js\");\n/* harmony import */ var _front_submit_handler__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./front/submit-handler */ \"./source/js/front/submit-handler.js\");\n/* harmony import */ var _front_validation__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./front/validation */ \"./source/js/front/validation.js\");\n(function () {\n  var enterModule = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.enterModule : undefined;\n  enterModule && enterModule(module);\n})();\n\nvar __signature__ = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.default.signature : function (a) {\n  return a;\n};\n\n\n\n\n\n\n\nvar FormBuilderFront = {\n  Collapse: _front_collapse__WEBPACK_IMPORTED_MODULE_0__[\"default\"],\n  GetLocation: _front_get_location__WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n  HandleConditions: _front_handle_conditions__WEBPACK_IMPORTED_MODULE_2__[\"default\"],\n  RecaptchaWarning: _front_recaptcha_warning__WEBPACK_IMPORTED_MODULE_3__[\"default\"],\n  SubmitHandler: _front_submit_handler__WEBPACK_IMPORTED_MODULE_4__[\"default\"],\n  Validation: _front_validation__WEBPACK_IMPORTED_MODULE_5__[\"default\"]\n};\n;\n\n(function () {\n  var reactHotLoader = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.default : undefined;\n\n  if (!reactHotLoader) {\n    return;\n  }\n\n  reactHotLoader.register(FormBuilderFront, \"FormBuilderFront\", \"/Users/jonatanhanson/Sites/vagrant/public/developement.local/wp-content/plugins/modularity-form-builder/source/js/modularity-form-builder-front.js\");\n})();\n\n;\n\n(function () {\n  var leaveModule = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.leaveModule : undefined;\n  leaveModule && leaveModule(module);\n})();\n/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../../node_modules/webpack/buildin/harmony-module.js */ \"./node_modules/webpack/buildin/harmony-module.js\")(module)))\n\n//# sourceURL=webpack:///./source/js/modularity-form-builder-front.js?");

/***/ })

/******/ });