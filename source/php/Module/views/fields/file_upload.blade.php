<div {!! $field['conditional_hidden'] !!} class="o-grid mod-form-field">
    <div class="o-grid-12@md">
        <div class="form-group">
            <label
                for="{{ $module_id }}-{{ sanitize_title($field['label']) }}">{{ $field['label'] }}{!! $field['required'] ? '<span class="text-danger">*</span>' : '' !!}</label>
            {!! !empty($field['description'])
                ? '<div class="text-sm text-dark-gray">' .
                    ModularityFormBuilder\Helper\SanitizeData::convertLinks($field['description']) .
                    '</div>'
                : '' !!}


            @fileinput([
                'name' => sanitize_title($field['label']),
                'display' => 'area',
                'multiple' => $field['type'] === 'multiple' ? true : false,
                'label' => $selectFileLabel,
                'accept' => $field['filetypes'] && is_array($field['filetypes']) ? $field['attributeList']['accept'] : '',
                'filesMax' => $field['type'] === 'multiple' ? $field['files_max'] : 1
            ])
            @endfileinput

			@typography(['element' => 'p', 'variant' => 'meta'])
				{{ $field['maxFilesNotice'] }}
			@endtypography

            <!-- <span class="input-file-selected"><?php _e('No file selected', 'modularity-form-builder'); ?></span> -->

        </div>
    </div>
</div>
