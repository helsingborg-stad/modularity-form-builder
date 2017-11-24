FormBuilder = FormBuilder || {};
FormBuilder.Admin = FormBuilder.Admin || {};

FormBuilder.Admin.EditForm = (function ($) {

    function EditForm() {
        $(function(){
            this.handleEvents();
        }.bind(this));
    }

    /**
     * Delete file
     * @return {void}
     */
    EditForm.prototype.deleteFile = function(postId, formId, filePath, fieldName, target) {
        $.ajax({
            url: ajaxurl,
            type: 'post',
            data: {
                action    : 'delete_file',
                postId    : postId,
                formId    : formId,
                filePath  : filePath,
                fieldName : fieldName
            },
            beforeSend: function(response) {
                target.remove();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Error: ' + textStatus);
            },
        });
    };

    /**
     * Upload files
     * @return {void}
     */
    EditForm.prototype.uploadFile = function (files, postId, formId, fieldName, target) {
        var data = new FormData();
            data.append('action', 'upload_files');
            data.append('postId', postId);
            data.append('formId', formId);
            data.append('fieldName', fieldName);
        $.each(files, function(key, value)
        {
            data.append(fieldName + '[]', value);
        });

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'json',
            processData: false,
            contentType: false,
            beforeSend: function(response) {
                $('input[type=file]', target).hide();
                $('.upload-status', target).html('<p><div class="spinner is-active" style="float:left;"></div> ' + formbuilder.uploading + '</p>');
            },
            success: function(response, textStatus, jqXHR) {
                if (response.success) {
                    //location.reload();
                    $(':submit', '#publishing-action').trigger('click');
                } else {
                    $('.upload-status', target).html('<p>' + response.data + '</p>');
                    $('input[type=file]', target).show();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('error: ' + textStatus);
                $('input[type=file]', target).show();
                $('.upload-status', target).hide();
            },
        });
    };

    /**
     * Handle events
     * @return {void}
     */
    EditForm.prototype.handleEvents = function () {
        $(document).on('click', '.delete-form-file', function (e) {
            e.preventDefault();
            if (window.confirm(formbuilder.delete_confirm)) {
                var postId    = $(e.target).attr('postid'),
                    formId    = $(e.target).attr('formid'),
                    filePath  = $(e.target).attr('filepath'),
                    fieldName = $(e.target).attr('fieldname'),
                    $target   = $(e.target).parents('li');
                this.deleteFile(postId, formId, filePath, fieldName, $target);
            }
        }.bind(this));

        $(document).on('change', 'input[type=file]', function(e) {
            var files     = e.target.files;
                postId    = $(e.target).attr('postid'),
                formId    = $(e.target).attr('formid'),
                fieldName = $(e.target).attr('fieldname'),
                $target   = $(e.target).parents('.mod-form-field');

            this.uploadFile(files, postId, formId, fieldName, $target);
        }.bind(this));

    };

    return new EditForm();

})(jQuery);
