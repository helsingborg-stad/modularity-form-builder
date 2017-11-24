{{-- Email --}}
<div class="mod-form-field">
    <p><b><label for="{{ $module_id }}-email">
        {{ $field['label'] }}{!! $field['required'] ? '<span class="text-danger">*</span>' : '' !!}
    </label></b></p>
    <input type="tel" name="{{ $field['name'] }}" class="large-text" id="{{ $module_id }}-email" value="{{ $field['value'] }}" {{ $field['required'] ? 'required' : '' }}>
</div>
