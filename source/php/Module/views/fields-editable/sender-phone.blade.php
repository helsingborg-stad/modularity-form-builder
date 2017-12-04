{{-- Phone --}}
<div class="mod-form-field">
    <p><b><label for="{{ $module_id }}-phone">
        {{ $field['label'] }}{!! $field['required'] ? '<span class="text-danger">*</span>' : '' !!}
    </label></b></p>
    <input type="tel" name="mod-form[{{ $field['name'] }}]" class="large-text" id="{{ $module_id }}-phone" value="{{ $field['value'] }}" {{ $field['required'] ? 'required' : '' }}>
</div>
