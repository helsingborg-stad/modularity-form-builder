var FormBuilder = FormBuilder || {};

FormBuilder = FormBuilder || {};
FormBuilder.Front = FormBuilder.Front || {};

FormBuilder.Front.handleConditions = (function ($) {

    function handleConditions() {
        this.handleEvents();
    }

    handleConditions.prototype.handleEvents = function () {
        $('input[conditional]').change(function(e) {
            $target = $(e.target).parents('[class*="mod-form"]');
            var conditional = $(this).attr('conditional');
            if (typeof conditional !== 'undefined' && conditional.length > 0) {
                var conditionObj = JSON.parse(conditional);
                $target.find("div[conditional-target^='{\"label\":\"" + conditionObj.label + "\",']").hide();
                $target.find("div[conditional-target='" + conditional + "']").show();
            }
        });
    };

	return new handleConditions();

})(jQuery);
