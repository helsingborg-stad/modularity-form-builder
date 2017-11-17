var FormBuilder = FormBuilder || {};

FormBuilder = FormBuilder || {};
FormBuilder.Admin = FormBuilder.Admin || {};

FormBuilder.Admin.various = (function ($) {

    function various() {
        $(function() {
            this.externalUpload();
        }.bind(this));
    }

    // Show/hide external upload field
    various.prototype.externalUpload = function () {
    	if (!formbuilder.mod_form_authorized) {
    		$('[data-name="upload_videos_external"]').hide();
    	}
    };

	return new various();

})(jQuery);
