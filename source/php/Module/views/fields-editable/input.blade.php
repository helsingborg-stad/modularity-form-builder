{{-- Input field --}}
<div class="mod-form-field">
    <p><b><label for="{{ $module_id }}-input-{{ $field['name'] }}">
        {{ $field['label'] }}{!! $field['required'] ? '<span class="text-danger">*</span>' : '' !!}
    </label></b></p>
</div>

@if (in_array($field['value_type'], array('number', 'range')))
    <input type="{{ $field['value_type'] }}" id="{{ $module_id }}-input-{{ $field['name'] }}" class="large-text" name="{{ $field['name'] }}" {{  $field['required'] ? 'required' : '' }} {!! $field['min_value'] ? 'min="' . $field['min_value'] . '"' : '' !!} {!! $field['max_value'] ? 'max="' . $field['max_value'] . '"' : '' !!} {!! $field['step'] ? 'step="' . $field['step'] . '"' : '' !!} value="{{ $field['value'] }}">
@elseif ($field['value_type'] === 'date')
    <input type="{{ $field['value_type'] }}" id="{{ $module_id }}-input-{{ $field['name'] }}" class="large-text" name="{{ $field['name'] }}" {{  $field['required'] ? 'required' : '' }} {!! $field['min_value'] ? 'min="' . $field['min_value'] . '"' : '' !!} {!! $field['max_value'] ? 'max="' . $field['max_value'] . '"' : '' !!} value="{{ $field['value'] }}">
@else
    <input type="{{ $field['value_type'] }}" id="{{ $module_id }}-input-{{ $field['name'] }}" class="large-text" name="{{ $field['name'] }}" {{ $field['required'] ? 'required' : '' }} value="{{ $field['value'] }}">
@endif
