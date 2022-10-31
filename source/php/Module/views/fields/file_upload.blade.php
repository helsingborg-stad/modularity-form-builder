<div class="o-grid mod-form-field" {!! $field['conditional_hidden'] !!}>
    <div class="o-grid-12@md">
        <div class="form-group">

            @fileinput([
                'name' => sanitize_title($field['label']),
                'description' => (!empty($field['description'])) ? ModularityFormBuilder\Helper\SanitizeData::convertLinks($field['description']) : '',
                'display' => 'area',
                'multiple' => ($field['type'] === 'multiple') ? true : false,
                'label' => $field['label'],
                'accept' => $field['filetypes'] && is_array($field['filetypes']) ? $field['attributeList']['accept'] : '',
                'filesMax' => ($field['type'] === 'multiple') ? $field['files_max'] : 1
            ])
            @endfileinput

            @if(isset($field['files_max']))
                @typography(['element' => 'p', 'variant' => 'meta'])
                    {{__('Max number of files', 'modularity-form-builder')}}: 
                    {{($field['type'] === 'multiple' ? $field['files_max'] : '1')}}
                @endtypography        
            @endif

            <!-- <span class="input-file-selected"><?php _e('No file selected', 'modularity-form-builder'); ?></span> -->

        </div>
    </div>
</div>
