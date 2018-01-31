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
            $('.acf-tab-button').click(function(){

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
