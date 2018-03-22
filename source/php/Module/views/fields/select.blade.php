<div class="grid mod-form-field" {!! $field['conditional_hidden'] !!}>
    <div class="grid-md-12">
        <div class="form-group">
            <label for="{{ $module_id }}-{{ sanitize_title($field['label']) }}">{{ $field['label'] }}{!!  $field['required'] ? '<span class="text-danger">*</span>' : '' !!}</label>
            {!! !empty($field['description']) ? '<div class="text-sm text-dark-gray">' . ModularityFormBuilder\Helper\SanitizeData::convertLinks($field['description']) . '</div>' : '' !!}

            <select name="{{ sanitize_title($field['label']) }}" id="{{ $module_id }}-{{ sanitize_title($field['label']) }}">
            @foreach ($field['values'] as $value)
            <option value="{{ $value['value'] }}">{{ $value['value'] }}</option>
            @endforeach
            </select>
        </div>
    </div>
</div>
