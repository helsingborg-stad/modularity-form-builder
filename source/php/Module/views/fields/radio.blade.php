<div class="o-grid mod-form-field" {!! $field['conditional_hidden'] !!}>
    <div class="o-grid-12@md">
        <div role="radiogroup" 
            aria-labelledby="label-{{ $module_id }}-{{ sanitize_title($field['label']) }}-{{ $field['key'] }}" 
            @if(!empty($field['description']))
                aria-describedby="description-{{ $module_id }}-{{ sanitize_title($field['label']) }}-{{ $field['key'] }}" 
            @endif 
            class="form-group c-field"> 
            <div class="c-field__label" id="label-{{ $module_id }}-{{ sanitize_title($field['label']) }}-{{ $field['key'] }}">{{ $field['label'] }}{!!  $field['required'] ? '<span class="u-color__text--danger">*</span>' : '' !!}</div>

            @if(!empty($field['description']))
                <div class="text-sm text-dark-gray" id="description-{{ $module_id }}-{{ sanitize_title($field['label']) }}-{{ $field['key'] }}">
                    {!! ModularityFormBuilder\Helper\SanitizeData::convertLinks($field['description']) !!}
                </div>
            @endif

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
