export default (function($) {
    $(document).ready(function() {
        function removeReCaptchaWarning() {
            if ($('#g-recaptcha-response').val()) {
                $('.captcha-warning').hide();
            }
        }
        window.setInterval(removeReCaptchaWarning, 1000);
    });

})( jQuery );
