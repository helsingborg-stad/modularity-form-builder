FormBuilder = FormBuilder || {};
FormBuilder.Admin = FormBuilder.Admin || {};

FormBuilder.Admin.Conditional = (function ($) {

    function Conditional() {
        $(document).ready(function () {
        	if (pagenow == 'mod-form') {
        		this.populateSelectFields();
        		this.handleEvents();
        	}

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
            },
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
                $('.upload-status', target).html('<p><div class="spinner is-active" style="float:left;"></div> ' + formbuilder.uploading + '</p>');
            },
            success: function(response, textStatus, jqXHR) {
                if (response.success) {
                    //location.reload();
                    $(':submit', '#publishing-action').trigger('click');
                } else {
                    $('.upload-status', target).html('<p>' + response.data + '</p>');
                    $('input[type=file]', target).show();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('error: ' + textStatus);
                $('input[type=file]', target).show();
                $('.upload-status', target).hide();
            },
        });
    };

    /**
     * Handle events
     * @return {void}
     */
    EditForm.prototype.handleEvents = function () {
        $(document).on('click', '.delete-form-file', function (e) {
            e.preventDefault();
            if (window.confirm(formbuilder.delete_confirm)) {
                var postId    = $(e.target).attr('postid'),
                    formId    = $(e.target).attr('formid'),
                    filePath  = $(e.target).attr('filepath'),
                    fieldName = $(e.target).attr('fieldname'),
                    $target   = $(e.target).parents('li');
                this.deleteFile(postId, formId, filePath, fieldName, $target);
            }
        }.bind(this));

        $(document).on('change', 'input[type=file]', function(e) {
            var files     = e.target.files;
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
