<div class="grid mod-form-field" {!! $field['conditional_hidden'] !!}>
    <div class="grid-md-12">
        <div class="form-group">
            <label for="{{ $module_id }}-message">{{ $field['label'] ? $field['label'] : 'Message' }}{!!  $field['required'] ? '<span class="text-danger">*</span>' : '' !!}</label>
            {!! !empty($field['description']) ? '<div class="text-sm text-dark-gray">' . ModularityFormBuilder\Helper\SanitizeData::convertLinks($field['description']) . '</div>' : '' !!}

            @textarea([
                'type' => 'text',
                'attributeList' => [
                    'type' => 'textarea',
                    'name' => sanitize_title($field['label']),
                    'rows' => "10",
                    $field['required'] ? 'required' : '' => '',
                    'id' => "{{ $module_id }}-message"
                ],
                'label' => "Normal text field"
            ])
            @endtextarea
            @if (isset($field['custom_post_type_content']) && $field['custom_post_type_content'] == true)
                <input type="hidden" name="post_content" value="{{ sanitize_title($field['label']) }}">
            @endif
        </div>
    </div>
</div>
