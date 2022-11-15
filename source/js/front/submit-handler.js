export default (function ($) {
    function Submit() {
        $('form').submit(function (event) {
            this.handleEvents();
        }.bind(this));
    }
    return new Submit();
})(jQuery);
