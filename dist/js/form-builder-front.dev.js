var FormBuilder = FormBuilder || {};

FormBuilder = FormBuilder || {};
FormBuilder.Front = FormBuilder.Front || {};

FormBuilder.Front.handleConditions = (function ($) {

    function handleConditions() {
        this.handleEvents();
    }

    handleConditions.prototype.handleEvents = function () {
        $('input[type=radio]', '.modularity-mod-form').change(function() {
            var conditional = $(this).attr('conditional');
            if (typeof conditional !== 'undefined' && conditional.length > 0) {
                var conditionObj = JSON.parse(conditional);
                $("div[conditional-target^='{\"label\":\"" + conditionObj.label + "\",']").hide();
                $("div[conditional-target='" + conditional + "']").show();
            }
        });
    };

	return new handleConditions();

})(jQuery);
