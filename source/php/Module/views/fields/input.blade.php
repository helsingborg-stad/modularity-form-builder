<?php

use ModularityFormBuilder\Helper\SanitizeData;

?>

<div class="grid mod-form-field" {!! $field['conditional_hidden'] !!}>
    <div class="grid-md-12">
        <div class="form-group">

            <label for="{{ $module_id }}-input-{{ sanitize_title($field['label']) }}">
                {{ $field['label'] }}
                @if ($field['required'])
                    <span class="text-danger">*</span>
                @endif
            </label>

            @if (!empty($field['description']))
                <div class="text-sm text-dark-gray">
                    {!! SanitizeData::convertLinks($field['description']) !!}
                </div>
            @endif

            @field([
                'type' => $field['value_type'],
                'value' => '',
                'attributeList' => $field['attributeList'],
                'required' => $field['required'] ? true : false,
              
            ])
            @endfield

            @if (isset($field['custom_post_type_title']) && $field['custom_post_type_title'] == true)
                <input type="hidden" name="post_title" value="{{ sanitize_title($field['label']) }}">
            @endif
        </div>
    </div>
</div>
