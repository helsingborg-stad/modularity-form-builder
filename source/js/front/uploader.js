FormBuilder = FormBuilder || {};
FormBuilder.Front = FormBuilder.Front || {};

FormBuilder.Front.submit = (function ($) {

    function Submit() {
        $(function() {
            this.handleEvents();
        }.bind(this));
    }

    // Show spinner icon on submit
    Submit.prototype.handleEvents = function () {
        $('[class*="mod-form"]').submit(function(e) {
            $(e.target).find('button[type="submit"]').html('<i class="spinner"></i> ' + formbuilder.sending);
        }.bind(this));
    };

	return new Submit();

})(jQuery);
