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
/******/ 	return __webpack_require__(__webpack_require__.s = "./source/js/modularity-form-builder-admin.js");
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

/***/ "./source/js/admin/conditional-admin.js":
/*!**********************************************!*\
  !*** ./source/js/admin/conditional-admin.js ***!
  \**********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* WEBPACK VAR INJECTION */(function(module) {(function () {\n  var enterModule = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.enterModule : undefined;\n  enterModule && enterModule(module);\n})();\n\nvar __signature__ = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.default.signature : function (a) {\n  return a;\n};\n\nvar _default = function ($) {\n  function Conditional() {\n    $(document).ready(function () {\n      this.populateSelectFields();\n      this.handleEvents();\n    }.bind(this));\n  }\n  /**\n   * Populate conditional field selectors\n   * @return {void}\n   */\n\n\n  Conditional.prototype.populateSelectFields = function () {\n    $('[data-name=\"conditonal_field\"] select').each(function (index, element) {\n      // Check if selected value is set or exist in db\n      var selected = '',\n          $selected = $(':selected', element);\n\n      if ($selected.val()) {\n        selected = $selected.val();\n      } else {\n        // Get selected from database\n        var fieldName = $(element).attr('name');\n        $.ajax({\n          url: ajaxurl,\n          type: 'post',\n          data: {\n            action: 'get_selected_field',\n            moduleId: modularity_current_post_id,\n            fieldName: fieldName\n          },\n          success: function success(response) {\n            if (response != 'error') {\n              $(\"option[value='\" + response + \"']\", element).prop('selected', true);\n            }\n          }\n        });\n      } // Reset select options\n\n\n      $('optgroup, option', element).remove(); // Populate select options\n\n      var options = this.getOptions();\n\n      if (typeof options !== 'undefined' && options.length > 0) {\n        $('.condition-missing').remove();\n        $.each(options, function (key, value) {\n          var selectvalues = '';\n          $.each(value.selectvalues, function (key, option) {\n            var groubLabel = this.conditionalString(value.groupLabel),\n                optionValue = this.conditionalString(option),\n                optionObj = {\n              label: groubLabel,\n              value: optionValue\n            };\n            selectvalues += \"<option value='\" + JSON.stringify(optionObj) + \"'>\" + option + \"</option>\";\n          }.bind(this));\n          var optgroup = '<optgroup label=\"' + value.groupLabel + '\">' + selectvalues + '</optgroup>';\n          $(element).append(optgroup);\n\n          if (selected) {\n            $(\"option[value='\" + selected + \"']\", element).prop('selected', true);\n          }\n        }.bind(this));\n      } else {\n        $('.condition-missing').remove();\n        $('<p class=\"condition-missing\">' + formbuilder.selections_missing + '</p>').insertBefore(element);\n      }\n    }.bind(this));\n  };\n\n  Conditional.prototype.getOptions = function (argument) {\n    var selectOptions = [];\n    $('[data-layout=\"radio\"]:not(.acf-clone)').each(function (index, item) {\n      var $item = $(item);\n      var value = $item.find('[data-name=\"label\"] input').val();\n      var optionGroup = {\n        groupLabel: value,\n        selectvalues: []\n      };\n      $('[data-key=\"field_58eb670d39fef\"] table tbody', item).children('tr.acf-row:not(.acf-clone)').each(function (index, item) {\n        var $item = $(item);\n        var value = $item.find('[data-name=\"value\"] input').val();\n        optionGroup.selectvalues.push(value);\n      });\n      selectOptions.push(optionGroup);\n    });\n    return selectOptions;\n  };\n\n  Conditional.prototype.conditionalString = function (string) {\n    string = string.toLowerCase();\n    string = string.replace(/\\s+/g, '_');\n    string = string.replace(/[^a-z0-9_]+/ig, '');\n    string = string.replace(/_+$/, '');\n    return string;\n  };\n\n  Conditional.prototype.handleEvents = function () {\n    $(document).on('click', '[data-name=\"conditional_logic\"] [type=\"checkbox\"], [data-layout=\"radio\"] .acf-row-handle > a', function () {\n      this.populateSelectFields();\n    }.bind(this));\n  };\n\n  return new Conditional();\n}(jQuery);\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (_default);\n;\n\n(function () {\n  var reactHotLoader = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.default : undefined;\n\n  if (!reactHotLoader) {\n    return;\n  }\n\n  reactHotLoader.register(_default, \"default\", \"/Users/jonatanhanson/Sites/vagrant/public/developement.local/wp-content/plugins/modularity-form-builder/source/js/admin/conditional-admin.js\");\n})();\n\n;\n\n(function () {\n  var leaveModule = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.leaveModule : undefined;\n  leaveModule && leaveModule(module);\n})();\n/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../../../node_modules/webpack/buildin/harmony-module.js */ \"./node_modules/webpack/buildin/harmony-module.js\")(module)))\n\n//# sourceURL=webpack:///./source/js/admin/conditional-admin.js?");

/***/ }),

/***/ "./source/js/admin/conditional-notification.js":
/*!*****************************************************!*\
  !*** ./source/js/admin/conditional-notification.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("var __signature__ = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.default.signature : function (a) {\n  return a;\n};\n\n(function ($) {\n  var selectionsArray = [];\n\n  function Notification() {\n    $(function () {\n      //Init from saved data\n      this.init(); //Get some fresh data (from form)\n\n      this.dataGathering(); //Update selects\n\n      this.updateMainSelect();\n      this.updateSubSelect(); //Reload datas where an update might have happend\n\n      $(document).on('click', '.post-type-mod-form input[type=\"checkbox\"], .post-type-mod-form .acf-tab-button', function (event) {\n        //Get some fresh data (init)\n        this.dataGathering(); //Update selects\n\n        this.updateMainSelect();\n        this.updateSubSelect();\n      }.bind(this)); //Update subselect when main select is changed\n\n      $(\"[data-name='form_conditional_field'] .acf-input select\").change(function (event) {\n        this.updateSubSelect();\n      }.bind(this));\n    }.bind(this));\n  }\n\n  Notification.prototype.init = function () {\n    if (typeof notificationConditions != 'undefined' && notificationConditions !== null) {\n      notificationConditions = JSON.parse(notificationConditions);\n      [\"conditional_field\", \"conditional_field_equals\"].forEach(function (fieldType) {\n        $(\"[data-name='notify'] .acf-row:not(.acf-clone)\").each(function (row_index, row) {\n          if (notificationConditions[row_index] !== null) {\n            var currentSelect = $(\"[data-name='form_\" + fieldType + \"'] .acf-input select\", row);\n            currentSelect.empty();\n            currentSelect.append($(\"<option></option>\").attr(\"value\", notificationConditions[row_index][fieldType]).text(notificationConditions[row_index][fieldType]).attr('selected', 'selected'));\n          }\n        });\n      });\n    }\n  };\n\n  Notification.prototype.updateMainSelect = function () {\n    $(\"[data-name='notify'] .acf-row:not(.acf-clone)\").each(function (row_index, row) {\n      //Get conditional field\n      var conditionalField = $(\"[data-name='form_conditional_field'] .acf-input select\", row); //Get previous value\n\n      var previousValue = $(conditionalField).val(); //Empty field(s)\n\n      $(conditionalField).empty(); //Add selectable values\n\n      $.each(selectionsArray, function (value_index, value) {\n        if (previousValue == value_index) {\n          $(conditionalField).append($(\"<option></option>\").attr(\"value\", value_index).text(value_index).attr('selected', 'selected'));\n        } else {\n          $(conditionalField).append($(\"<option></option>\").attr(\"value\", value_index).text(value_index));\n        }\n      });\n    }.bind(this));\n  };\n\n  Notification.prototype.updateSubSelect = function () {\n    $(\"[data-name='notify'] .acf-row:not(.acf-clone)\").each(function (row_index, row) {\n      //Get conditional field\n      var conditionalFieldEquals = $(\"[data-name='form_conditional_field_equals'] .acf-input select\", row);\n      var conditionalField = $(\"[data-name='form_conditional_field'] .acf-input select\", row); //Get previous value\n\n      var previousValueEquals = $(conditionalFieldEquals).val();\n      var previousValue = $(conditionalField).val(); //Empty field(s)\n\n      $(conditionalFieldEquals).empty(); //Add selectable values\n\n      $.each(selectionsArray, function (value_index, value) {\n        if (conditionalField.val() == value_index) {\n          //Fill avabile selects\n          $.each(selectionsArray[value_index], function (i, v) {\n            if (previousValueEquals == v) {\n              $(conditionalFieldEquals).append($(\"<option></option>\").attr(\"value\", v).text(v).attr('selected', 'selected'));\n            } else {\n              $(conditionalFieldEquals).append($(\"<option></option>\").attr(\"value\", v).text(v));\n            }\n          });\n        }\n      });\n    }.bind(this));\n  };\n\n  Notification.prototype.dataGathering = function () {\n    //Reset\n    selectionsArray = {};\n    $(\"[data-name='form_fields'] .layout\").each(function (layout_index, layout) {\n      //Get current layout\n      var currentLayout = $(layout).attr('data-layout');\n      var currentNameKey = $(\".acf-fields  [data-name='label'] .acf-input .acf-input-wrap input\", layout).val(); //Check i valid\n\n      if (['select', 'radio'].indexOf(currentLayout) != -1) {\n        var isRequired = $(\"[data-name='required'] .acf-input label input\", layout).prop('checked'); // Check if field is required\n\n        var hasCondition = $(\"[data-name='conditional_logic'] .acf-input label input\", layout).prop('checked'); // Check is field is conditional\n\n        if (isRequired && !hasCondition) {\n          //Define where to store values\n          selectionsArray[currentNameKey] = []; //Get & filter data vars\n\n          var dataField = $(\"[data-name='values'] tbody [data-name='value'] .acf-input .acf-input-wrap input[type='text']\", layout); //Check that field isen't clone\n\n          $(dataField).filter(function (item_key, item_value) {\n            // Not a clone field?\n            if ($(item_value).attr('name').includes(\"acfclonefield\")) {\n              return false;\n            }\n\n            return true;\n          }).each(function (data_index, data_value) {\n            if ($(data_value).val() && currentNameKey) {\n              selectionsArray[currentNameKey].push($(data_value).val());\n            }\n          }.bind(this));\n        }\n      }\n    });\n  };\n\n  return new Notification();\n})(jQuery);\n\n//# sourceURL=webpack:///./source/js/admin/conditional-notification.js?");

/***/ }),

/***/ "./source/js/admin/edit-form.js":
/*!**************************************!*\
  !*** ./source/js/admin/edit-form.js ***!
  \**************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* WEBPACK VAR INJECTION */(function(module) {(function () {\n  var enterModule = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.enterModule : undefined;\n  enterModule && enterModule(module);\n})();\n\nvar __signature__ = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.default.signature : function (a) {\n  return a;\n};\n\nvar _default = function ($) {\n  function EditForm() {\n    $(function () {\n      this.handleEvents();\n    }.bind(this));\n  }\n  /**\n   * Delete file\n   * @return {void}\n   */\n\n\n  EditForm.prototype.deleteFile = function (postId, formId, filePath, fieldName, target) {\n    $.ajax({\n      url: ajaxurl,\n      type: 'post',\n      data: {\n        action: 'delete_file',\n        postId: postId,\n        formId: formId,\n        filePath: filePath,\n        fieldName: fieldName\n      },\n      beforeSend: function beforeSend(response) {\n        target.remove();\n      },\n      error: function error(jqXHR, textStatus, errorThrown) {\n        console.log('Error: ' + textStatus);\n      }\n    });\n  };\n  /**\n   * Upload files\n   * @return {void}\n   */\n\n\n  EditForm.prototype.uploadFile = function (files, postId, formId, fieldName, target) {\n    var data = new FormData();\n    data.append('action', 'upload_files');\n    data.append('postId', postId);\n    data.append('formId', formId);\n    data.append('fieldName', fieldName);\n    $.each(files, function (key, value) {\n      data.append(fieldName + '[]', value);\n    });\n    $.ajax({\n      url: ajaxurl,\n      type: 'POST',\n      data: data,\n      cache: false,\n      dataType: 'json',\n      processData: false,\n      contentType: false,\n      beforeSend: function beforeSend(response) {\n        $('input[type=file]', target).hide();\n        $('.upload-status', target).html('<div class=\"spinner spinner-dark is-active\" style=\"float:none;\"></div>');\n      },\n      success: function success(response, textStatus, jqXHR) {\n        if (response.success) {\n          $(':submit', '#publishing-action,#edit-post').trigger('click');\n        }\n\n        $('.upload-status', target).html('<p>' + response.data + '</p>');\n        $('input[type=file]', target).show();\n      },\n      error: function error(jqXHR, textStatus) {\n        console.log('error: ' + textStatus);\n        $('input[type=file]', target).show();\n        $('.upload-status', target).hide();\n      }\n    });\n  };\n\n  EditForm.prototype.savePost = function (event) {\n    var $form = $(event.target);\n    var data = new FormData(event.target);\n    data.append('action', 'save_post');\n    $form.find('button[type=\"submit\"]').attr('disabled', 'true').append('<span class=\"spinner\"></span>');\n    $.ajax({\n      url: ajaxurl,\n      type: 'POST',\n      data: data,\n      dataType: 'json',\n      processData: false,\n      contentType: false,\n      success: function success(response) {\n        if (response.success) {\n          $form.find('button[type=\"submit\"]').append('<i class=\"pricon pricon-check\"></i>').find('.spinner').hide();\n        } else {\n          $('.modal-footer', $form).html('<span class=\"notice warning\"><i class=\"pricon pricon-notice-warning\"></i> ' + response.data + '</span>');\n        }\n      },\n      complete: function complete() {\n        location.hash = '';\n        location.reload();\n      }\n    });\n    return false;\n  };\n  /**\n   * Handle events\n   * @return {void}\n   */\n\n\n  EditForm.prototype.handleEvents = function () {\n    $(document).on('submit', '#edit-post', function (e) {\n      e.preventDefault();\n      this.savePost(e);\n    }.bind(this));\n    $(document).on('click', '.delete-form-file', function (e) {\n      e.preventDefault();\n\n      if (window.confirm(formbuilder.delete_confirm)) {\n        var postId = $(e.target).attr('postid'),\n            formId = $(e.target).attr('formid'),\n            filePath = $(e.target).attr('filepath'),\n            fieldName = $(e.target).attr('fieldname'),\n            $target = $(e.target).parents('span');\n        this.deleteFile(postId, formId, filePath, fieldName, $target);\n      }\n    }.bind(this));\n    $(document).on('change', 'input[type=file]', function (e) {\n      var files = e.target.files,\n          postId = $(e.target).attr('postid'),\n          formId = $(e.target).attr('formid'),\n          fieldName = $(e.target).attr('fieldname'),\n          $target = $(e.target).parents('.mod-form-field');\n      this.uploadFile(files, postId, formId, fieldName, $target);\n    }.bind(this));\n  };\n\n  return new EditForm();\n}(jQuery);\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (_default);\n;\n\n(function () {\n  var reactHotLoader = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.default : undefined;\n\n  if (!reactHotLoader) {\n    return;\n  }\n\n  reactHotLoader.register(_default, \"default\", \"/Users/jonatanhanson/Sites/vagrant/public/developement.local/wp-content/plugins/modularity-form-builder/source/js/admin/edit-form.js\");\n})();\n\n;\n\n(function () {\n  var leaveModule = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.leaveModule : undefined;\n  leaveModule && leaveModule(module);\n})();\n/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../../../node_modules/webpack/buildin/harmony-module.js */ \"./node_modules/webpack/buildin/harmony-module.js\")(module)))\n\n//# sourceURL=webpack:///./source/js/admin/edit-form.js?");

/***/ }),

/***/ "./source/js/admin/external-upload.js":
/*!********************************************!*\
  !*** ./source/js/admin/external-upload.js ***!
  \********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* WEBPACK VAR INJECTION */(function(module) {(function () {\n  var enterModule = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.enterModule : undefined;\n  enterModule && enterModule(module);\n})();\n\nvar __signature__ = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.default.signature : function (a) {\n  return a;\n};\n\nvar _default = function ($) {\n  function externalUpload() {\n    $(function () {\n      if (!formbuilder.mod_form_authorized) {\n        $('[data-name=\"upload_videos_external\"]').hide();\n      }\n    }.bind(this));\n  }\n\n  return new externalUpload();\n}(jQuery);\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (_default);\n;\n\n(function () {\n  var reactHotLoader = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.default : undefined;\n\n  if (!reactHotLoader) {\n    return;\n  }\n\n  reactHotLoader.register(_default, \"default\", \"/Users/jonatanhanson/Sites/vagrant/public/developement.local/wp-content/plugins/modularity-form-builder/source/js/admin/external-upload.js\");\n})();\n\n;\n\n(function () {\n  var leaveModule = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.leaveModule : undefined;\n  leaveModule && leaveModule(module);\n})();\n/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../../../node_modules/webpack/buildin/harmony-module.js */ \"./node_modules/webpack/buildin/harmony-module.js\")(module)))\n\n//# sourceURL=webpack:///./source/js/admin/external-upload.js?");

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

/***/ "./source/js/modularity-form-builder-admin.js":
/*!****************************************************!*\
  !*** ./source/js/modularity-form-builder-admin.js ***!
  \****************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* WEBPACK VAR INJECTION */(function(module) {/* harmony import */ var _admin_external_upload__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./admin/external-upload */ \"./source/js/admin/external-upload.js\");\n/* harmony import */ var _admin_edit_form__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./admin/edit-form */ \"./source/js/admin/edit-form.js\");\n/* harmony import */ var _admin_conditional_notification__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./admin/conditional-notification */ \"./source/js/admin/conditional-notification.js\");\n/* harmony import */ var _admin_conditional_notification__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_admin_conditional_notification__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _admin_conditional_admin__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./admin/conditional-admin */ \"./source/js/admin/conditional-admin.js\");\n/* harmony import */ var _front_validation__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./front/validation */ \"./source/js/front/validation.js\");\n(function () {\n  var enterModule = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.enterModule : undefined;\n  enterModule && enterModule(module);\n})();\n\nvar __signature__ = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.default.signature : function (a) {\n  return a;\n};\n\n\n\n\n\n\nvar FormBuilderAdmin = {\n  ExternalUpload: _admin_external_upload__WEBPACK_IMPORTED_MODULE_0__[\"default\"],\n  EditForm: _admin_edit_form__WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n  ConditionalNotification: _admin_conditional_notification__WEBPACK_IMPORTED_MODULE_2___default.a,\n  ConditionalAdmin: _admin_conditional_admin__WEBPACK_IMPORTED_MODULE_3__[\"default\"],\n  Validation: _front_validation__WEBPACK_IMPORTED_MODULE_4__[\"default\"]\n};\n;\n\n(function () {\n  var reactHotLoader = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.default : undefined;\n\n  if (!reactHotLoader) {\n    return;\n  }\n\n  reactHotLoader.register(FormBuilderAdmin, \"FormBuilderAdmin\", \"/Users/jonatanhanson/Sites/vagrant/public/developement.local/wp-content/plugins/modularity-form-builder/source/js/modularity-form-builder-admin.js\");\n})();\n\n;\n\n(function () {\n  var leaveModule = typeof reactHotLoaderGlobal !== 'undefined' ? reactHotLoaderGlobal.leaveModule : undefined;\n  leaveModule && leaveModule(module);\n})();\n/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../../node_modules/webpack/buildin/harmony-module.js */ \"./node_modules/webpack/buildin/harmony-module.js\")(module)))\n\n//# sourceURL=webpack:///./source/js/modularity-form-builder-admin.js?");

/***/ })

/******/ });