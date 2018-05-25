{!! wp_nonce_field('update', 'update-modularity-form', true, false) !!}

@foreach ($form_fields as $field)
    @includeIf('fields-editable.' . $field['acf_fc_layout'])
@endforeach

<input type="hidden" name="modularity-form-id" value="{{ $module_id }}">
