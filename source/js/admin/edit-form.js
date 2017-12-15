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
            }
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
                $('.upload-status', target).html('<div class="spinner spinner-dark is-active" style="float:none;"></div>');
            },
            success: function(response, textStatus, jqXHR) {
                if (response.success) {
                    $(':submit', '#publishing-action,#edit-post').trigger('click');
                }

                $('.upload-status', target).html('<p>' + response.data + '</p>');
                $('input[type=file]', target).show();
            },
            error: function(jqXHR, textStatus) {
                console.log('error: ' + textStatus);
                $('input[type=file]', target).show();
                $('.upload-status', target).hide();
            }
        });
    };

    EditForm.prototype.savePost = function (event) {
        var $form = $(event.target);
        var data = new FormData(event.target);
            data.append('action', 'save_post');

        $form.find('button[type="submit"]').attr('disabled', 'true').append('<span class="spinner"></span>');

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: data,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $form.find('button[type="submit"]').append('<i class="pricon pricon-check"></i>').find('.spinner').hide();
                } else {
                    $('.modal-footer', $form).html('<span class="notice warning"><i class="pricon pricon-notice-warning"></i> ' + response.data + '</span>');
                }
            },
            complete: function () {
                location.hash = '';
                location.reload();
            }
        });

        return false;
    };

    /**
     * Handle events
     * @return {void}
     */
    EditForm.prototype.handleEvents = function () {
        $(document).on('submit', '#edit-post', function (e) {
            e.preventDefault();
            this.savePost(e);
        }.bind(this));

        $(document).on('click', '.delete-form-file', function (e) {
            e.preventDefault();
            if (window.confirm(formbuilder.delete_confirm)) {
                var postId    = $(e.target).attr('postid'),
                    formId    = $(e.target).attr('formid'),
                    filePath  = $(e.target).attr('filepath'),
                    fieldName = $(e.target).attr('fieldname'),
                    $target   = $(e.target).parents('span');
                this.deleteFile(postId, formId, filePath, fieldName, $target);
            }
        }.bind(this));

        $(document).on('change', 'input[type=file]', function(e) {
            var files     = e.target.files,
                postId    = $(e.target).attr('postid'),
                formId    = $(e.target).attr('formid'),
                fieldName = $(e.target).attr('fieldname'),
                $target   = $(e.target).parents('.mod-form-field');

            this.uploadFile(files, postId, formId, fieldName, $target);
        }.bind(this));
    };

    return new EditForm();

})(jQuery);
