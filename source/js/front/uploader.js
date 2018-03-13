FormBuilder = FormBuilder || {};
FormBuilder.Front = FormBuilder.Front || {};

FormBuilder.Front.submit = (function ($) {

    function Submit() {

        $("form").submit(function(event) {

            if (formbuilder.site_key) {
                var recaptcha = $("#g-recaptcha-response").val();
                if (recaptcha === "") {
                    event.preventDefault();
                    $('.captcha-warning').show();
                }
                else {
                    this.handleEvents();
                }
            }
            else {
                this.handleEvents();
            }
        });
    }

    // Show spinner icon on submit
    Submit.prototype.handleEvents = function () {
        $('[class*="mod-form"]').submit(function(e) {
            $(e.target).find('button[type="submit"]').html('<i class="spinner"></i> ' + formbuilder.sending);
        }.bind(this));
    };


	return new Submit();

})(jQuery);
