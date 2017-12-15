<div id="modal-edit-post" class="modal modal-backdrop-2 modal-small" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-content material-shadow-lg">
        <form id="edit-post">
            <div class="modal-header">
                <a class="btn btn-close" href="#close"></a>
                <h2 class="modal-title"><?php _e('Edit', 'modularity-form-builder'); ?></h2>
            </div>
            <div class="modal-body gutter">
                    {!! wp_nonce_field('update', 'update-modularity-form') !!}

                    @if ($custom_post_type_title)
                        <div class="mod-form-field">
                            <p><b><label for="title"><?php _e('Title', 'modularity-form-builder'); ?><span class="text-danger">*</span></label></b></p>
                            <input type="text" name="mod-form[post-title]" class="large-text" value="{{ get_the_title() }}" required="">
                        </div>
                    @endif
                    @if ($custom_post_type_content)
                        <div class="mod-form-field">
                            {{ wp_editor(get_the_content(), 'post_content', $editor_settings) }}
                        </div>
                    @endif
                    @foreach ($form_fields as $field)
                        @include('fields-editable.' . $field['acf_fc_layout'])
                    @endforeach
            </div>
            <div class="modal-footer">
                <input type="hidden" name="post_id" value="{{ the_ID() }}">
                <a href="#close" class="btn btn-default"><?php _e('Close', 'modularity-form-builder'); ?></a>
                <button type="submit" class="btn btn-primary"><?php _e('Update', 'modularity-form-builder'); ?> </button>
            </div>
        </form>
    </div>
    <a href="#close" class="backdrop"></a>
</div>
