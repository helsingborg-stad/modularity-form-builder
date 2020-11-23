<div class="grid mod-form-field" {!! $field['conditional_hidden'] !!}>
    <div class="grid-md-12">
        <div class="form-group">
            <label for="{{ $module_id }}-{{ sanitize_title($field['label']) }}">{{ $field['label'] }}{!!  $field['required'] ? '<span class="text-danger">*</span>' : '' !!}</label>
            {!! !empty($field['description']) ? '<div class="text-sm text-dark-gray">' . ModularityFormBuilder\Helper\SanitizeData::convertLinks($field['description']) . '</div>' : '' !!}

            @if ($field['type'] === 'multiple')
            
                <label class="input-file">
                    @fileinput([
                        'classList' => ['unlist'],
                        'name' => sanitize_title($field['label']),
                        'display' => 'area',
                        'multiple' => true,
                        'label' => translate('Select file', 'modularity-form-builder')
                    ])
                    @endfileinput
                </label>

            @else
                <label class="input-file">

                    {{-- <input type="file" name="{{ sanitize_title($field['label']) }}[]" {!! $field['filetypes'] && is_array($field['filetypes']) ? 'accept="' . implode(',', $field['filetypes']) . '"' : '' !!} {{ $field['required'] ? 'required' : '' }}> --}}
                    <span class="btn"><?php _e('Select file', 'modularity-form-builder'); ?></span>
                    @button([
                        'text' => translate('Select file', 'modularity-form-builder'),
                        'color' => 'primary',
                    ])
                    @endbutton
                    <span class="input-file-selected"><?php _e('No file selected', 'modularity-form-builder'); ?></span>
                </label>
            @endif
        </div>
    </div>
</div>
