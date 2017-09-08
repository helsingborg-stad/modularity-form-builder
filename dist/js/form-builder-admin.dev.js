var FormBuilder = FormBuilder || {};

FormBuilder = FormBuilder || {};
FormBuilder.Admin = FormBuilder.Admin || {};

FormBuilder.Admin.Conditional = (function ($) {

    function Conditional() {
        $(document).ready(function () {

// kör bara om radio select finns med i dom
this.populateSelectFields();

        	if (pagenow == 'mod-form') {
        		$(document).on('click', '[data-key="field_59b134beb61b0"] [type="checkbox"]', function () {
// kör på init när fälten laddas, och när conditional button klickas, och när nytt radio skapas
                    this.populateSelectFields();
                }.bind(this));
        	}

        }.bind(this));
    }

    /**
     * Populate conditional field selectors
     * @return {void}
     */
    Conditional.prototype.populateSelectFields = function() {
        $('[data-key="field_59b13579b61b1"] select').each(function (index, element) {
            // Empty select except selcted value
            $('option', element).not(':selected').remove();
            $('optgroup', element).remove();

            var objects = this.getObjects();

// Hitta vilken som är selected eller "current"

            $.each(objects, function(key, value) {
            	var selectvalues = '';
            	$.each(value.selectvalues, function(key, selectValue) {
            		var groubLabel = this.conditionalString(value.groupLabel);
            		var newValue = selectValue.toLowerCase().replace(/[^A-Z0-9]+/ig, "_");
            		selectvalues += '<option value="' + groubLabel + '[#]' + newValue + '">' + selectValue + '</option>'
	            }.bind(this));

	            var optgroup = '<optgroup label="' + value.groupLabel + '">' + selectvalues + '</optgroup>';
	            $(element).append(optgroup);
			}.bind(this));

        }.bind(this));
    };

    Conditional.prototype.conditionalString = function(string) {
    	string = string.toLowerCase();
    	string = string.replace(/[^A-Z0-9]+/ig, '_');
    	string = string.replace(/_+$/, "");

    	return string;
    };

    Conditional.prototype.getObjects = function(argument) {
    	var selectObjects = [];

    	$('[data-layout="radio"]:not(.acf-clone)').each(function (index, item) {
    		var $item = $(item);
            var value = $item.find('[data-name="label"] input').val();

            var objectGroup = {
            	groupLabel : value,
            	selectvalues : []
            };

	        $('[data-key="field_58eb670d39fef"] table tbody', item).children('tr.acf-row:not(.acf-clone)').each(function (index, item) {
	            var $item = $(item);
	            var value = $item.find('[data-name="value"] input').val();
	            objectGroup.selectvalues.push(
	            	value
	            );
	        });

	       	selectObjects.push(
	        	objectGroup
	        );
    	});

        // $('[data-layout="radio"]:not(.acf-clone) [data-key="field_58eb670d39fef"] table tbody').children('tr.acf-row:not(.acf-clone)').each(function (index, item) {
        //     var $item = $(item);
        //     var value = $item.find('[data-name="value"] input').val();

        //     selectObjects.push({
        //     	value: value
        //     });
        // });

        return selectObjects;
    };


	return new Conditional();

})(jQuery);
