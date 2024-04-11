<div class="o-grid mod-form-field" {!! $field['conditional_hidden'] !!}>
    <div class="o-grid-12@md">
        <div role="group" 
            aria-labelledby="label-{{ $module_id }}-{{ sanitize_title($field['label']) }}" 
            @if(!empty($field['description']))
                aria-describedby="description-{{ $module_id }}-{{ sanitize_title($field['label']) }}" 
            @endif
            class="form-group c-field checkbox-group {{$field['required'] ? 'checkbox-group-required' : ''}}">
        
            @if($field['required'])
                <input class="js-checkbox-valid u-display--none" aria-label="For validating checkbox group" {{$field['required'] ? 'data-js-required' : ''}} type="checkbox"/>
            @endif

            <div class="c-field__label" id="label-{{ $module_id }}-{{ sanitize_title($field['label']) }}">{{ $field['label'] }}{!!  $field['required'] ? '<span class="u-color__text--danger">*</span>' : '' !!}</div>

            @if(!empty($field['description']))
                <div class="text-sm text-dark-gray" id="description-{{ $module_id }}-{{ sanitize_title($field['label']) }}">
                    {!! ModularityFormBuilder\Helper\SanitizeData::convertLinks($field['description']) !!}
                </div>
            @endif

            <div class="errors"></div>

            @foreach ($field['values'] as $value)
                <label class="checkbox">
                    @option([
                        'type' => 'checkbox',
                        'value' => $value['value'],
                        'label' => $value['value'],
                        'attributeList' => [
                            'conditional' => !empty($value['conditional_value']) ? $value['conditional_value'] : false,
                            'name' => sanitize_title($field['label']) . '[]',
                        ]
                    ])
                    @endoption
                </label>
            @endforeach
        </div>
    </div>
</div>