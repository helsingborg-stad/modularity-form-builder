export default (function ($) {
    function HandleConditions() {
        this.handleRequired();
        this.handleEvents();
    }

    HandleConditions.prototype.handleRequired = function () {
        const $target = $('[class*="mod-form"]');
        $('[conditional-target]:hidden', $target).find('[required]').prop('required', false).attr('hidden-required', true);
        $('[conditional-target]:visible', $target).find('[hidden-required]').prop('required', true);
    };

    HandleConditions.prototype.handleEvents = function () {
        $('input[conditional]').change(function(e) {
            $target = $(e.target).parents('[class*="mod-form"]');
            var conditional = $(e.target).attr('conditional');
            if (typeof conditional !== 'undefined' && conditional.length > 0) {
                var conditionObj = JSON.parse(conditional);
                $target.find("div[conditional-target^='{\"label\":\"" + conditionObj.label + "\",']").hide();
                $target.find("div[conditional-target='" + conditional + "']").show();
            }

            this.handleRequired();
        }.bind(this));
    };

	return new HandleConditions();

})(jQuery);
