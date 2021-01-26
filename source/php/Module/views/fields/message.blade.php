<div class="o-grid mod-form-field" {!! $field['conditional_hidden'] !!}>
    <div class="o-grid-12@md">
        <div class="form-group">
            <label for="{{ $module_id }}-message">{{ $field['label'] ? $field['label'] : 'Message' }}{!!  $field['required'] ? '<span class="text-danger">*</span>' : '' !!}</label>
            {!! !empty($field['description']) ? '<div class="text-sm text-dark-gray">' . ModularityFormBuilder\Helper\SanitizeData::convertLinks($field['description']) . '</div>' : '' !!}
            <!-- Message -->
            @textarea([
                'type' => 'text',
                'attributeList' => [
                    'name' => $field['attributeList']['name'],
                    'type' => 'textarea',
                    'rows' => "10",
                    'id' => $module_id . "-message"
                ],
                'label' => '',
                'required' => $field['required']
            ])
            @endtextarea
            @if (isset($field['custom_post_type_content']) && $field['custom_post_type_content'] == true)
                <input type="hidden" name="post_content" value="{{ sanitize_title($field['label']) }}">
            @endif
        </div>
    </div>
</div>
