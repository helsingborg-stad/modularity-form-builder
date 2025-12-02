<?php ?>
<div class="o-grid mod-form-field" {!! $field['conditional_hidden'] !!}>
    <div class="o-grid-12@md">
        <div class="form-group">
            @field([
                'label' => $field['label'],            
                'type' => $field['value_type'],
                'value' => '',
                'invalidMessage' => !empty($field['invalidMessages'][$field['value_type']]) ? $field['invalidMessages'][$field['value_type']] : 'Invalid value',     
                'name' => sanitize_title($field['name']),
                'description' => (!empty($field['description'])) ? ModularityFormBuilder\Helper\SanitizeData::convertLinks(
                    $field['description']
                ) : '',                
                'id' => $module_id . '-input-' . sanitize_title($field['label']),
                'required' => $field['required'] ? true : false,
                'attributeList' => $field['attributeList']
            ])
            @endfield

            @if (isset($field['custom_post_type_title']) && $field['custom_post_type_title'] == true)
                <input type="hidden" name="post_title" value="{{ sanitize_title($field['label']) }}">
            @endif
        </div>
    </div>
</div>
