var FormBuilder = FormBuilder || {};

FormBuilder = FormBuilder || {};
FormBuilder.Front = FormBuilder.Front || {};

FormBuilder.Front.handleConditions = (function ($) {

    function handleConditions() {
        this.handleEvents();
    }

    handleConditions.prototype.handleEvents = function () {
        $('input[type=radio]', '.modularity-mod-form').change(function() {
            var conditional = this.getAttribute('conditional');
            var conditions = conditional.split('[#]');
            console.log(conditions[0]);
            console.log(conditions[1]);

            $('div[conditional-target^="' + conditions[0] + '"]').hide();
            $('div[conditional-target^="' + conditions[0] + '"][conditional-target$="' + conditions[1] + '"]').show();

        });
    };

	return new handleConditions();

})(jQuery);
