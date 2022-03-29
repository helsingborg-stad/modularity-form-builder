export default (function ($) {
    function Submit() {
        $('form').submit(function (event) {
            this.handleEvents();
        }.bind(this));
    }
    // Show spinner icon on submit
    Submit.prototype.handleEvents = function () {
        $('[class*="mod-form"]').submit(function (e) {
            $(e.target).find('button[type="submit"]').html('<i class="spinner"></i> ' + formbuilder.sending);
        }.bind(this));
    };
    return new Submit();
})(jQuery);
