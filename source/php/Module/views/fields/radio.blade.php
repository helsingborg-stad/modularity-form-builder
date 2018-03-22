<div class="grid mod-form-field" {!! $field['conditional_hidden'] !!}>
    <div class="grid-md-12">
        <div class="form-group">
            <label for="{{ $module_id }}-{{ sanitize_title($field['label']) }}">{{ $field['label'] }}{!!  $field['required'] ? '<span class="text-danger">*</span>' : '' !!}</label>
            {!! !empty($field['description']) ? '<div class="text-sm text-dark-gray">' . ModularityFormBuilder\Helper\SanitizeData::convertLinks($field['description']) . '</div>' : '' !!}

            @foreach ($field['values'] as $value)
            <label class="checkbox">
                <input type="radio" name="{{ sanitize_title($field['label']) }}" value="{{ $value['value'] }}" {{ $field['required'] ? 'required' : '' }} conditional='{{ $value['conditional_value'] }}'> {{ $value['value'] }}
            </label>
            @endforeach
        </div>
    </div>
</div>
