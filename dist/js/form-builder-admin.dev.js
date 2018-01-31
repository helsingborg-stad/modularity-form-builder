FormBuilder = FormBuilder || {};
FormBuilder.Admin = FormBuilder.Admin || {};

FormBuilder.Admin.Conditional = (function ($) {

    function Conditional() {
        $(document).ready(function () {
        	this.populateSelectFields();
        	this.handleEvents();

        }.bind(this));
    }

    /**
     * Populate conditional field selectors
     * @return {void}
     */
    Conditional.prototype.populateSelectFields = function() {
        $('[data-name="conditonal_field"] select').each(function (index, element) {

        	// Check if selected value is set or exist in db
			var selected  = '',
				$selected = $(':selected', element);

			if ($selected.val()) {
				selected = $selected.val();
			} else {
				// Get selected from database
				var fieldName = $(element).attr('name');
				$.ajax({
		            url: ajaxurl,
		            type: 'post',
		            data: {
		                action    : 'get_selected_field',
		                moduleId  : modularity_current_post_id,
		                fieldName : fieldName
		            },
		            success: function(response) {
		            	if (response != 'error') {
		            		$("option[value='" + response + "']", element).prop('selected', true);
		            	}
		            }
		        });
			}

			// Reset select options
            $('optgroup, option', element).remove();

            // Populate select options
            var options = this.getOptions();
            if (typeof options !== 'undefined' && options.length > 0) {
            	$('.condition-missing').remove();
	            $.each(options, function(key, value) {
	            	var selectvalues = '';

	            	$.each(value.selectvalues, function(key, option) {
	            		var groubLabel = this.conditionalString(value.groupLabel),
	            			optionValue = this.conditionalString(option),
	            			optionObj = {
	            				label: groubLabel,
	            				value: optionValue
	            			};
	            		selectvalues += "<option value='" + JSON.stringify(optionObj) + "'>" + option + "</option>";
		            }.bind(this));

		            var optgroup = '<optgroup label="' + value.groupLabel + '">' + selectvalues + '</optgroup>';
		            $(element).append(optgroup);

		            if (selected) {
		            	$("option[value='" + selected + "']", element).prop('selected', true);
		            }

				}.bind(this));
	        } else {
	        	$('.condition-missing').remove();
	        	$('<p class="condition-missing">' + formbuilder.selections_missing + '</p>').insertBefore(element);
	        }

        }.bind(this));
    };

    Conditional.prototype.getOptions = function(argument) {
    	var selectOptions = [];

    	$('[data-layout="radio"]:not(.acf-clone)').each(function (index, item) {
    		var $item = $(item);
            var value = $item.find('[data-name="label"] input').val();
            var optionGroup = {
            	groupLabel : value,
            	selectvalues : []
            };

	        $('[data-key="field_58eb670d39fef"] table tbody', item).children('tr.acf-row:not(.acf-clone)').each(function (index, item) {
	            var $item = $(item);
	            var value = $item.find('[data-name="value"] input').val();
	            optionGroup.selectvalues.push(value);
	        });

	       	selectOptions.push(optionGroup);
    	});

        return selectOptions;
    };

    Conditional.prototype.conditionalString = function(string) {
    	string = string.toLowerCase();
    	string = string.replace(/\s+/g, '_');
    	string = string.replace(/[^a-z0-9_]+/ig, '');
    	string = string.replace(/_+$/, '');

    	return string;
    };

    Conditional.prototype.handleEvents = function () {
    	$(document).on('click', '[data-name="conditional_logic"] [type="checkbox"], [data-layout="radio"] .acf-row-handle > a', function () {
        	this.populateSelectFields();
        }.bind(this));
    };

	return new Conditional();

})(jQuery);

var FormBuilder = FormBuilder || {};

FormBuilder = FormBuilder || {};
FormBuilder.Admin = FormBuilder.Admin || {};

FormBuilder.Admin.Notification = (function ($) {

    var selectionsArray, selectionsArrayFinal = [];

    function Notification() {

        $(function(){

            //Init from saved data
            this.init();

            //Get some fresh data (from form)
            this.dataGathering();

            //Update selects
            this.updateMainSelect();
            this.updateSubSelect();

            //Reload datas when changin tab (posibble data update in list)
            $('.acf-tab-button, [data-name="condition"]').on('click',function(){

                //Get some fresh data (init)
                this.dataGathering();

                //Update selects
                this.updateMainSelect();
                this.updateSubSelect();

            }.bind(this));

            //Update subselect when main select is changed
            $("[data-name='form_conditional_field'] .acf-input select").change(function(event){
                this.updateSubSelect();
            }.bind(this));

        }.bind(this));
    }

    Notification.prototype.init = function() {
        if(typeof notificationConditions != 'undefined' && notificationConditions !== null) {
            notificationConditions = JSON.parse(notificationConditions);
            ["conditional_field", "conditional_field_equals"].forEach(function(fieldType){
                $("[data-name='notify'] .acf-row:not(.acf-clone)").each(function(row_index, row){
                    if(notificationConditions[row_index] !== null) {
                        var currentSelect = $("[data-name='form_" + fieldType + "'] .acf-input select", row);
                            currentSelect.empty();
                            currentSelect.append($("<option></option>").attr("value",notificationConditions[row_index][fieldType]).text(notificationConditions[row_index][fieldType]).attr('selected', 'selected'));
                    }
                });
            });
        }
    };

    Notification.prototype.updateMainSelect = function() {
        $("[data-name='notify'] .acf-row:not(.acf-clone)").each(function(row_index, row){

            //Get conditional field
            var conditionalField = $("[data-name='form_conditional_field'] .acf-input select", row);

            //Get previous value
            var previousValue       = $(conditionalField).val();

            //Empty field(s)
            $(conditionalField).empty();

            //Add selectable values
            $.each(selectionsArrayFinal, function(value_index, value){
                if(previousValue == value_index) {
                    $(conditionalField).append($("<option></option>").attr("value",value_index).text(value_index).attr('selected', 'selected'));
                } else {
                    $(conditionalField).append($("<option></option>").attr("value",value_index).text(value_index));
                }
            });

        }.bind(this));
    }

    Notification.prototype.updateSubSelect = function() {
        $("[data-name='notify'] .acf-row:not(.acf-clone)").each(function(row_index, row){

            //Get conditional field
            var conditionalFieldEquals = $("[data-name='form_conditional_field_equals'] .acf-input select", row);
            var conditionalField = $("[data-name='form_conditional_field'] .acf-input select", row);

            //Get previous value
            var previousValueEquals = $(conditionalFieldEquals).val();
            var previousValue       = $(conditionalField).val();

            //Empty field(s)
            $(conditionalFieldEquals).empty();

            //Add selectable values
            $.each(selectionsArrayFinal, function(value_index, value){
                if (conditionalField.val() == value_index) {
                    //Fill avabile selects
                    $.each(selectionsArrayFinal[value_index], function(i, v) {
                        if(previousValueEquals == v) {
                            $(conditionalFieldEquals).append($("<option></option>").attr("value",v).text(v).attr('selected', 'selected'));
                        } else {
                            $(conditionalFieldEquals).append($("<option></option>").attr("value",v).text(v));
                        }
                    });
                }
            });

        }.bind(this));
    }

    Notification.prototype.dataGathering = function () {

        //Reset
        selectionsArray = {};

        $("[data-name='form_fields'] .layout").each(function(layout_index, layout){

            //Get current layout
            var currentLayout = $(layout).attr('data-layout');
            var currentNameKey = $(".acf-fields  [data-name='label'] .acf-input .acf-input-wrap input", layout).val();

            //Check i valid
            if($.inArray(currentLayout, ['select', 'radio'])) {

                var isRequired      = $("[data-name='required'] .acf-input label input", layout).prop('checked'); // Check if field is required
                var hasCondition    = $("[data-name='conditional_logic'] .acf-input label input", layout).prop('checked'); // Check is field is conditional

                if(isRequired && !hasCondition) {

                    //Define where to store values
                    selectionsArray[currentNameKey] = [];

                    //Get & filter data vars
                    var dataField = $("[data-name='values'] tbody [data-name='value'] .acf-input .acf-input-wrap input[type='text']", layout);

                    //Check that field isen't clone
                    $(dataField).filter(function(item_key, item_value) { // Not a clone field?
                        if($(item_value).attr('name').includes("acfclonefield")) {
                            return false;
                        }
                        return true;
                    }).each(function(data_index, data_value){
                        if($(data_value).val() && currentNameKey) {
                            selectionsArray[currentNameKey].push($(data_value).val());
                        }
                    }.bind(this));
                }
            }
        });

        //Trigger rebuilding of lists
        //if(this.isEqual(selectionsArray, selectionsArrayFinal)) {
            selectionsArrayFinal = selectionsArray;
        //}
    }

    Notification.prototype.isEqual = function (value, other) {

        // Get the value type
        var type = Object.prototype.toString.call(value);

        // If the two objects are not the same type, return false
        if (type !== Object.prototype.toString.call(other)) return false;

        // If items are not an object or array, return false
        if (['[object Array]', '[object Object]'].indexOf(type) < 0) return false;

        // Compare the length of the length of the two items
        var valueLen = type === '[object Array]' ? value.length : Object.keys(value).length;
        var otherLen = type === '[object Array]' ? other.length : Object.keys(other).length;
        if (valueLen !== otherLen) return false;

        // Compare two items
        var compare = function (item1, item2) {
            // Code will go here...
        };

        // Compare properties
        var match;
        if (type === '[object Array]') {
            for (var i = 0; i < valueLen; i++) {
                compare(value[i], other[i]);
            }
        } else {
            for (var key in value) {
                if (value.hasOwnProperty(key)) {
                    compare(value[key], other[key]);
                }
            }
        }

        // If nothing failed, return true
        return true;

    };

    return new Notification();

})(jQuery);

FormBuilder = FormBuilder || {};
FormBuilder.Admin = FormBuilder.Admin || {};

FormBuilder.Admin.EditForm = (function ($) {

    function EditForm() {
        $(function(){
            this.handleEvents();
        }.bind(this));
    }

    /**
     * Delete file
     * @return {void}
     */
    EditForm.prototype.deleteFile = function(postId, formId, filePath, fieldName, target) {
        $.ajax({
            url: ajaxurl,
            type: 'post',
            data: {
                action    : 'delete_file',
                postId    : postId,
                formId    : formId,
                filePath  : filePath,
                fieldName : fieldName
            },
            beforeSend: function(response) {
                target.remove();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Error: ' + textStatus);
            }
        });
    };

    /**
     * Upload files
     * @return {void}
     */
    EditForm.prototype.uploadFile = function (files, postId, formId, fieldName, target) {
        var data = new FormData();
            data.append('action', 'upload_files');
            data.append('postId', postId);
            data.append('formId', formId);
            data.append('fieldName', fieldName);
        $.each(files, function(key, value)
        {
            data.append(fieldName + '[]', value);
        });

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'json',
            processData: false,
            contentType: false,
            beforeSend: function(response) {
                $('input[type=file]', target).hide();
                $('.upload-status', target).html('<div class="spinner spinner-dark is-active" style="float:none;"></div>');
            },
            success: function(response, textStatus, jqXHR) {
                if (response.success) {
                    $(':submit', '#publishing-action,#edit-post').trigger('click');
                }

                $('.upload-status', target).html('<p>' + response.data + '</p>');
                $('input[type=file]', target).show();
            },
            error: function(jqXHR, textStatus) {
                console.log('error: ' + textStatus);
                $('input[type=file]', target).show();
                $('.upload-status', target).hide();
            }
        });
    };

    EditForm.prototype.savePost = function (event) {
        var $form = $(event.target);
        var data = new FormData(event.target);
            data.append('action', 'save_post');

        $form.find('button[type="submit"]').attr('disabled', 'true').append('<span class="spinner"></span>');

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: data,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $form.find('button[type="submit"]').append('<i class="pricon pricon-check"></i>').find('.spinner').hide();
                } else {
                    $('.modal-footer', $form).html('<span class="notice warning"><i class="pricon pricon-notice-warning"></i> ' + response.data + '</span>');
                }
            },
            complete: function () {
                location.hash = '';
                location.reload();
            }
        });

        return false;
    };

    /**
     * Handle events
     * @return {void}
     */
    EditForm.prototype.handleEvents = function () {
        $(document).on('submit', '#edit-post', function (e) {
            e.preventDefault();
            this.savePost(e);
        }.bind(this));

        $(document).on('click', '.delete-form-file', function (e) {
            e.preventDefault();
            if (window.confirm(formbuilder.delete_confirm)) {
                var postId    = $(e.target).attr('postid'),
                    formId    = $(e.target).attr('formid'),
                    filePath  = $(e.target).attr('filepath'),
                    fieldName = $(e.target).attr('fieldname'),
                    $target   = $(e.target).parents('span');
                this.deleteFile(postId, formId, filePath, fieldName, $target);
            }
        }.bind(this));

        $(document).on('change', 'input[type=file]', function(e) {
            var files     = e.target.files,
                postId    = $(e.target).attr('postid'),
                formId    = $(e.target).attr('formid'),
                fieldName = $(e.target).attr('fieldname'),
                $target   = $(e.target).parents('.mod-form-field');

            this.uploadFile(files, postId, formId, fieldName, $target);
        }.bind(this));
    };

    return new EditForm();

})(jQuery);

var FormBuilder = FormBuilder || {};

FormBuilder = FormBuilder || {};
FormBuilder.Admin = FormBuilder.Admin || {};

FormBuilder.Admin.various = (function ($) {

    function various() {
        $(function() {
            this.externalUpload();
        }.bind(this));
    }

    // Show/hide external upload field
    various.prototype.externalUpload = function () {
    	if (!formbuilder.mod_form_authorized) {
    		$('[data-name="upload_videos_external"]').hide();
    	}
    };

	return new various();

})(jQuery);
