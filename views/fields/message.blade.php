<div class="grid">
    <div class="grid-md-12">
        <div class="form-group">
            <label for="{{ $module_id }}-message">{{ $field['label'] ? $field['label'] : 'Message' }}{!!  $field['required'] ? '<span class="text-danger">*</span>' : '' !!}</label>
            <textarea name="message" id="{{ $module_id }}-message" rows="10" {{ $field['required'] ? 'required' : '' }}></textarea>
        </div>
    </div>
</div>
