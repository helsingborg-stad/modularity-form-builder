<div {!! $field['conditional_hidden'] !!} class="o-grid mod-form-field">
    <div class="o-grid-12@md">
        <div class="form-group">
            @fileinput([
                'name' => sanitize_title($field['name']),
                'description' => !empty($field['description'])
                    ? ModularityFormBuilder\Helper\SanitizeData::convertLinks($field['description'])
                    : '',
                'buttonLabel' => $field['button_label'],
                'buttonRemoveLabel' => $field['button_remove_label'] ?? '';
                'required' => $field['required'] ? true : false,
                'multiple' => $field['type'] === 'multiple' ? true : false,
                'label' => $field['label'],
                'accept' => $field['filetypes'] && is_array($field['filetypes']) ? $field['attributeList']['accept'] : '',
                'filesMax' => $field['type'] === 'multiple' ? $field['files_max'] : 1,
                'classList' => [$field['required'] ? 'data-js-required' : ''],
            ])
            @endfileinput

            @typography(['element' => 'p', 'variant' => 'meta'])
                {{ $field['maxFilesNotice'] }}
            @endtypography

        </div>
    </div>
</div>
