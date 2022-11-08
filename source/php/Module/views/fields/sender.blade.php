{{-- Firstname and/or lastname --}}
@if (in_array('firstname', $field['fields']) && in_array('lastname', $field['fields']))
    <div class="o-grid mod-form-field">
        <div class="o-grid-6@md">
            <div class="form-group">
                @field([
                    'label' => $field['labels']['firstname'], 
                    'type' => 'text',
                    'id' =>  $module_id . "-firstname",
                    'value' => $user_details['firstname'],
                    'name' => sanitize_title($field['labels']['firstname']),
                    'required' => in_array('firstname', $field['required_fields'])

                ])
                @endfield
            </div>
        </div>
        <div class="o-grid-6@md">
            <div class="form-group">
                @field([
                    'label' => $field['labels']['lastname'],
                    'type' => 'text',
                    'id' =>  $module_id . "-lastname",
                    'value' => $user_details['lastname'],
                    'name' => sanitize_title($field['labels']['lastname']),
                    'required' => in_array('lastname', $field['required_fields'])

                ])
                @endfield
            </div>
        </div>
    </div>
@elseif (in_array('firstname', $field['fields']) && !in_array('lastname', $field['fields']))
    <div class="o-grid mod-form-field">
        <div class="o-grid-6@md">
            <div class="form-group">
                @field([
                    'label' => $field['labels']['firstname'],
                    'type' => 'text',
                    'id' =>  $module_id . "-firstname",
                    'value' => $user_details['firstname'],
                    'name' => sanitize_title($field['labels']['firstname']),
                    'required' => in_array('firstname', $field['required_fields'])

                ])
                @endfield
            </div>
        </div>
    </div>
@elseif (!in_array('firstname', $field['fields']) && in_array('lastname', $field['fields']))
    <div class="o-grid mod-form-field">
        <div class="o-grid-6@md">
            <div class="form-group">               
                @field([
                    'label' => $field['labels']['lastname'],
                    'type' => 'text',
                    'id' =>  $module_id . "-lastname",
                    'value' => $user_details['lastname'],
                    'name' => sanitize_title($field['labels']['lastname']),
                    'required' => in_array('lastname', $field['required_fields'])

                ])
                @endfield
            </div>
        </div>
    </div>
@endif

{{-- Email and/or phone --}}
@if (in_array('email', $field['fields']) && in_array('phone', $field['fields']))
    <div class="o-grid mod-form-field">
        <div class="o-grid-6@md">
            <div class="form-group">
                @field([
                    'label' => $field['labels']['email'],
                    'type' => 'email',
                    'id' =>  $module_id . "-email",
                    'invalidMessage' => $field['invalidMessages']['email'],
                    'value' => $user_details['email'],
                    'name' => sanitize_title($field['labels']['email']),
                    'required' => in_array('email', $field['required_fields'])

                ])
                @endfield
            </div>
        </div>
        <div class="o-grid-6@md">
            <div class="form-group">
                @field([
                    'label' => $field['labels']['phone'],
                    'type' => 'number',
                    'id' =>  $module_id . "-phone",
                    'invalidMessage' => $field['invalidMessages']['number'],
                    'value' => $user_details['phone'],
                    'name' => sanitize_title($field['labels']['phone']),
                    'required' => in_array('phone', $field['required_fields'])

                ])
                @endfield
            </div>
        </div>
    </div>
@elseif (in_array('email', $field['fields']) && !in_array('phone', $field['fields']))
    <div class="o-grid mod-form-field">
        <div class="o-grid-6@md">
            <div class="form-group">
                @field([
                    'label' => $field['labels']['email'],
                    'type' => 'text',
                    'id' =>  $module_id . "-email",
                    'value' => $user_details['email'],
                    'name' => sanitize_title($field['labels']['email']),
                    'required' => in_array('email', $field['required_fields'])
                ])
                @endfield
            </div>
        </div>
    </div>
@elseif (!in_array('email', $field['fields']) && in_array('phone', $field['fields']))
    <div class="o-grid mod-form-field">
        <div class="o-grid-6@md">
            <div class="form-group">
                @field([
                    'label' => $field['labels']['phone'],
                    'type' => 'number',
                    'id' =>  $module_id . "-phone",
                    'invalidMessage' => $field['invalidMessages']['number'],
                    'value' => $user_details['phone'],
                    'name' => sanitize_title($field['labels']['phone']),
                    'required' => in_array('phone', $field['required_fields'])

                ])
                @endfield
            </div>
        </div>
    </div>
@endif

{{-- Address --}}
 @if (in_array('address', $field['fields']))
    <div class="o-grid mod-form-field">
        <div class="o-grid-12@md">
            <div class="form-group">
                @if ($googleGeocoding)
                    <label for="input_{{ $module_id }}-address-street">{{ $field['labels']['street_address'] }}{!! in_array('address', $field['required_fields']) ? '<span class="text-danger">*</span>' : '' !!}</label>
                    <div class="input-group">
                        <input type="text" name="{{ sanitize_title($field['labels']['address']) }}[{{ sanitize_title($field['labels']['street_address']) }}]" class="form-control" id="input_{{ $module_id }}-address-street" {{ in_array('address', $field['required_fields']) ? 'required' : '' }}>
                        <span class="input-group-addon-btn"><button id="form-get-location" class="btn"><i class="pricon pricon-location-pin"></i> <?php _e('Find my location', 'modularity-form-builder'); ?></button></span>
                    </div>
                @else
                    @field([
                        'label' => $field['labels']['street_address'],
                        'type' => 'text',
                        'id' =>  $module_id . "-address-street",
                        'value' => $user_details['address'],
                        'name' => sanitize_title($field['labels']['address']),
                        'required' => in_array('address', $field['required_fields'])

                    ])
                    @endfield
                @endif
            </div>
        </div>
    </div>
    <div class="o-grid mod-form-field">
        <div class="o-grid-6@md">
            <div class="form-group">               
                @field([
                    'label' => $field['labels']['postal_code'],
                    'type' => 'number',
                    'id' =>  $module_id . "-address-postal-code",
                    'invalidMessage' => $field['invalidMessages']['number'],
                    'value' => $user_details['postal_code'],
                    'name' => sanitize_title($field['labels']['postal_code']),
                    'required' => in_array('postal_code', $field['required_fields'])

                ])
                @endfield
            </div>
        </div>
        <div class="o-grid-6@md">
            <div class="form-group">
                @field([
                    'label' => $field['labels']['city'],
                    'type' => 'text',
                    'id' =>  $module_id . "-address-city",
                    'value' => $user_details['city'],
                    'name' => sanitize_title($field['labels']['city']),
                    'required' => in_array('city', $field['required_fields'])

                ])
                @endfield
            </div>
        </div>
    </div>
@endif
