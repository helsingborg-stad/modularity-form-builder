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
