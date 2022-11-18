<div class="o-grid mod-form-field" {!! $field['conditional_hidden'] !!}>
    <div class="o-grid-12@md">
        <div class="form-group">

            @field([
                'label' => $field['label'] ? $field['label'] : 'Message',
                'description' => (!empty($field['description'])) ? ModularityFormBuilder\Helper\SanitizeData::convertLinks(
                    $field['description']
                ) : '',
                'multiline' => 10,
                'value' => '',
                'id' => $module_id . "-message",
                'name' => sanitize_title($field['label']),
                'required' => $field['required'] ? true : false,
                'attributeList' => $field['attributeList'],
                'autocomplete' => 'off'
            ])
            @endfield
             
            @if (isset($field['custom_post_type_content']) && $field['custom_post_type_content'] == true)
                <input type="hidden" name="post_content" value="{{ sanitize_title($field['label']) }}">
            @endif
        </div>
    </div>
</div>
