<div class="o-grid mod-form-field" {!! $field['conditional_hidden'] !!}>
    <div class="o-grid-12@md">
        <div class="form-group c-field checkbox-group {{$field['required'] ? 'checkbox-group-required' : ''}}">
        
            @if($field['required'])
                <input class="js-checkbox-valid u-display--none" aria-label="For validating checkbox group" {{$field['required'] ? 'js-required' : ''}} type="checkbox"/>
            @endif

            <label class="c-field__label" for="{{ $module_id }}-{{ sanitize_title($field['label']) }}" id="{{uniqid()}}">{{ $field['label'] }}{!!  $field['required'] ? '<span class="u-color__text--danger">*</span>' : '' !!}</label>

            {!! !empty($field['description']) ? '<div class="text-sm text-dark-gray">' . ModularityFormBuilder\Helper\SanitizeData::convertLinks($field['description']) . '</div>' : '' !!}

            <div class="errors"></div>

            @foreach ($field['values'] as $value)
                <label class="checkbox">
                    @option([
                        'type' => 'checkbox',
                        'value' => $value['value'],
                        'label' => $value['value'],
                        'attributeList' => [
                            'conditional' => $value['conditional_value'],
                            'name' => sanitize_title($field['label']) . '[]',
                        ]
                    ])
                    @endoption
                </label>
            @endforeach
        </div>
    </div>
</div>
