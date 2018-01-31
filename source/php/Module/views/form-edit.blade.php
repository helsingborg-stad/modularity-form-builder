<?php wp_nonce_field('update', 'update-modularity-form'); ?>

@foreach ($form_fields as $field)
    @includeIf('fields-editable.' . $field['acf_fc_layout'])
@endforeach
