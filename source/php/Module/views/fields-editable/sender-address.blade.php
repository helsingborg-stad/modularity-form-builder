{{-- Sender Address --}}
<div class="mod-form-field">
    <p><b><label for="{{ $module_id }}-address-street">
        {{ $field['labels']['street_address'] }}{!! $field['required'] ? '<span class="text-danger">*</span>' : '' !!}
    </label></b></p>
    <input type="text" name="mod-form[{{ $field['name'] }}][{{ sanitize_title($field['labels']['street_address']) }}]" class="large-text" id="{{ $module_id }}-address-street" value="{{ $field['value'][sanitize_title($field['labels']['street_address'])] ?? '' }}" {{ $field['required'] ? 'required' : '' }}>
</div>
<div class="mod-form-field">
    <p><b><label for="{{ $module_id }}-address-postal_code">
        {{ $field['labels']['postal_code'] }}{!! $field['required'] ? '<span class="text-danger">*</span>' : '' !!}
    </label></b></p>
    <input type="text" name="mod-form[{{ $field['name'] }}][{{ sanitize_title($field['labels']['postal_code']) }}]" class="large-text" id="{{ $module_id }}-address-postal_code" value="{{ $field['value'][sanitize_title($field['labels']['postal_code'])] ?? '' }}" {{ $field['required'] ? 'required' : '' }}>
</div>
<div class="mod-form-field">
    <p><b><label for="{{ $module_id }}-address-city">
        {{ $field['labels']['city'] }}{!! $field['required'] ? '<span class="text-danger">*</span>' : '' !!}
    </label></b></p>
    <input type="text" name="mod-form[{{ $field['name'] }}][{{ sanitize_title($field['labels']['city']) }}]" class="large-text" id="{{ $module_id }}-address-city" value="{{ $field['value'][sanitize_title($field['labels']['city'])] ?? '' }}" {{ $field['required'] ? 'required' : '' }}>
</div>
