@if(is_array($field['value']) && !empty($field['value']))
    <div class="mod-form-field">
        <p><b><label>
            <?php _e('Attached files', 'modularity-form-builder'); ?>
        </label></b></p>
    </div>
    <ul style="list-style-type:none" id="media-items">
        @foreach ($field['value'] as $file)
            <li>
                <a target="_blank" href="{{ file_exists($file) ? $uploadFolder . basename($file) : $file }}">{{ file_exists($file) ? basename($file) : $file }}</a>
                <span style="display: block;">
                    <a href="#" target="_blank" class="delete-form-file delete" filepath="{{ $file }}" fieldname="{{ $field['name'] }}" formid="{{ $module_id }}" postid="{{ $post_id }}"><?php _e('Delete', 'modularity-form-builder'); ?></a>
                </span>
                <input type="hidden" name="{{ $field['name'] }}[]" value="{{ $file }}">
            </li>
        @endforeach
    </ul>
@endif
<div class="mod-form-field">
    <p><b><label for="{{ $module_id }}-{{ $field['name'] }}">
        {{ $field['label'] }}
    </label></b></p>
    <input type="file" name="{{ $field['name'] }}-upload[]" fieldname="{{ $field['name'] }}" formid="{{ $module_id }}" postid="{{ $post_id }}" {!! $field['filetypes'] && is_array($field['filetypes']) ? 'accept="' . implode(',', $field['filetypes']) . '"' : '' !!} {{ $field['required'] ? 'required' : '' }} multiple>
    <div class="upload-status"></div>
</div>
