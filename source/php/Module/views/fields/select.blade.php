<?php

?>
<div class="o-grid mod-form-field" {!! $field['conditional_hidden'] !!}>
    <div class="o-grid-12@md">
        <div class="form-group">
            @select([
                'name' => sanitize_title($field['name']),
                'label' => $field['label'],       
                'options' => $field['values'],
                'description' => (!empty($field['description'])) ? ModularityFormBuilder\Helper\SanitizeData::convertLinks(
                    $field['description']
                ) : '',                
                'id' => $module_id . '-input-' . sanitize_title($field['label']),
                'attributeList' => $field['attributeList'],
                'required' => $field['required'] ? true : false,
            ])
            @endselect            
        </div>
    </div>
</div>
