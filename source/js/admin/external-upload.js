export default (function ($) {

    function externalUpload() {
        $(function() {
          if (!formbuilder.mod_form_authorized) {
            $('[data-name="upload_videos_external"]').hide();
          }
        }.bind(this));
    }

  return new externalUpload();

})(jQuery);
