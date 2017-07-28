<div class="grid">
    <div class="grid-md-12">
        <div class="form-group">
            <label for="{{ $module_id }}-input-{{ sanitize_title($field['label']) }}">{{ $field['label'] }}{!!  $field['required'] ? '<span class="text-danger">*</span>' : '' !!}</label>
            {!! !empty($field['description']) ? '<div class="text-sm text-dark-gray">' . $field['description'] . '</div>' : '' !!}

            @if (in_array($field['value_type'], array('number', 'range')))
                <input type="{{ $field['value_type'] }}" id="{{ $module_id }}-input-{{ sanitize_title($field['label']) }}" name="{{ sanitize_title($field['label']) }}" {{  $field['required'] ? 'required' : '' }} {!! $field['min_value'] ? 'min="' . $field['min_value'] . '"' : '' !!} {!! $field['max_value'] ? 'max="' . $field['max_value'] . '"' : '' !!} {!! $field['step'] ? 'step="' . $field['step'] . '"' : '' !!}>
            @elseif ($field['value_type'] === 'date')
                <input type="{{ $field['value_type'] }}" id="{{ $module_id }}-input-{{ sanitize_title($field['label']) }}" name="{{ sanitize_title($field['label']) }}" {{  $field['required'] ? 'required' : '' }} {!! $field['min_value'] ? 'min="' . $field['min_value'] . '"' : '' !!} {!! $field['max_value'] ? 'max="' . $field['max_value'] . '"' : '' !!}>
            @else
                <input type="{{ $field['value_type'] }}" id="{{ $module_id }}-input-{{ sanitize_title($field['label']) }}" name="{{ sanitize_title($field['label']) }}" {{  $field['required'] ? 'required' : '' }}>
            @endif
        </div>
    </div>
</div>
