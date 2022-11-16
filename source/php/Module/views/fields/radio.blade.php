<div class="o-grid mod-form-field" {!! $field['conditional_hidden'] !!}>
    <div class="o-grid-12@md">
        <div class="form-group c-field">
            <label class="c-field__label" for="{{ $module_id }}-{{ sanitize_title($field['label']) }}">{{ $field['label'] }}{!!  $field['required'] ? '<span class="u-color__text--danger">*</span>' : '' !!}</label>
            {!! !empty($field['description']) ? '<div class="text-sm text-dark-gray">' . ModularityFormBuilder\Helper\SanitizeData::convertLinks($field['description']) . '</div>' : '' !!}

            @foreach ($field['values'] as $value)
                <label class="checkbox">
                    @option([
                        'type' => 'radio',
                        'value' => $value['value'],
                        'label' =>  $value['value'],
                        'required' => $field['required'],
                        'attributeList' => [
                            'conditional' => $value['conditional_value'],
                            'name' => sanitize_title($field['label']),
                        ]
                    ])
                    @endoption
                </label>
            @endforeach
        </div>
    </div>
</div>
