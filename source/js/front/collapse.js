FormBuilder = FormBuilder || {};
FormBuilder.Front = FormBuilder.Front || {};

FormBuilder.Front.collapse = (function ($) {

    function Collapse() {
        this.init();
        this.handleEvents();
    }

    Collapse.prototype.init = function() {
        $('.mod-form-collapse').each(function(index) {
            $(this).nextUntil(':not(.mod-form-field)').hide();
        });
    };

    Collapse.prototype.handleEvents = function () {
        $('button', '.mod-form-collapse').click(function(e) {
            e.preventDefault();

            $('.mod-form-collapse__icon > i', e.target).toggleClass('pricon-plus-o pricon-minus-o');

            $(e.target).parents('.mod-form-collapse')
                .nextUntil(':not(.mod-form-field)')
                .each(function() {
                    $(this).fadeToggle('fast');
                });

        }.bind(this));
    };

    return new Collapse();
})(jQuery);
