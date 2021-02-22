{{-- Firstname and/or lastname --}}
@if (in_array('firstname', $field['fields']) && in_array('lastname', $field['fields']))
    <div class="o-grid mod-form-field">
        <div class="o-grid-6@md">
            <div class="form-group">
                <label for="{{ $module_id }}-firstname">{{ $field['labels']['firstname'] }}{!! in_array('firstname', $field['required_fields']) ? '<span class="text-danger">*</span>' : '' !!}</label>
                @field([
                    'type' => 'text',
                    'attributeList' => [
                        'type' => 'text',
                        'name' => sanitize_title($field['labels']['firstname'])
                    ],
                    'value' => $user_details['firstname'],
                    'name' => sanitize_title($field['labels']['firstname']),
                    'required' => in_array('firstname', $field['required_fields'])

                ])
                @endfield
            </div>
        </div>
        <div class="o-grid-6@md">
            <div class="form-group">
                <label for="{{ $module_id }}-lastname">{{ $field['labels']['lastname'] }}{!! in_array('lastname', $field['required_fields']) ? '<span class="text-danger">*</span>' : '' !!}</label>
                @field([
                    'type' => 'text',
                    'attributeList' => [
                        'type' => 'text',
                        'name' => sanitize_title($field['labels']['lastname'])
                    ],
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
                <label for="{{ $module_id }}-firstname">{{ $field['labels']['firstname'] }}{!! in_array('firstname', $field['required_fields']) ? '<span class="text-danger">*</span>' : '' !!}</label>
                @field([
                    'type' => 'text',
                    'attributeList' => [
                        'type' => 'text',
                        'name' => sanitize_title($field['labels']['firstname'])
                    ],
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
                <label for="{{ $module_id }}-lastname">{{ $field['labels']['lastname'] }}{!! in_array('lastname', $field['required_fields']) ? '<span class="text-danger">*</span>' : '' !!}</label>
                @field([
                    'type' => 'text',
                    'attributeList' => [
                        'type' => 'text',
                        'name' => sanitize_title($field['labels']['lastname'])
                    ],
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
                <label for="{{ $module_id }}-email">{{ $field['labels']['email'] }}{!! in_array('email', $field['required_fields']) ? '<span class="text-danger">*</span>' : '' !!}</label>
                @field([
                    'type' => 'email',
                    'attributeList' => [
                        'type' => 'email',
                        'name' => sanitize_title($field['labels']['email'])
                    ],
                    'value' => $user_details['email'],
                    'name' => sanitize_title($field['labels']['email']),
                    'required' => in_array('email', $field['required_fields'])

                ])
                @endfield
            </div>
        </div>
        <div class="o-grid-6@md">
            <div class="form-group">
                <label for="{{ $module_id }}-phone">{{ $field['labels']['phone'] }}{!! in_array('phone', $field['required_fields']) ? '<span class="text-danger">*</span>' : '' !!}</label>
                @field([
                    'type' => 'number',
                    'attributeList' => [
                        'type' => 'tel',
                        'name' => sanitize_title($field['labels']['phone'])
                    ],
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
                <label for="{{ $module_id }}-email">{{ $field['labels']['email'] }}{!! in_array('email', $field['required_fields']) ? '<span class="text-danger">*</span>' : '' !!}</label>
                @field([
                    'type' => 'text',
                    'attributeList' => [
                        'type' => 'email',
                        'name' => sanitize_title($field['labels']['email'])
                    ],
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
                <label for="{{ $module_id }}-phone">{{ $field['labels']['phone'] }}{!! in_array('phone', $field['required_fields']) ? '<span class="text-danger">*</span>' : '' !!}</label>
                @field([
                    'type' => 'number',
                    'attributeList' => [
                        'type' => 'tel',
                        'name' => sanitize_title($field['labels']['phone'])
                    ],
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
                <label for="{{ $module_id }}-address-street">{{ $field['labels']['street_address'] }}{!! in_array('address', $field['required_fields']) ? '<span class="text-danger">*</span>' : '' !!}</label>
                @if ($googleGeocoding)
                    <div class="input-group">
                        <input type="text" name="{{ sanitize_title($field['labels']['address']) }}[{{ sanitize_title($field['labels']['street_address']) }}]" class="form-control" id="{{ $module_id }}-address-street" {{ in_array('address', $field['required_fields']) ? 'required' : '' }}>
                        <span class="input-group-addon-btn"><button id="form-get-location" class="btn"><i class="pricon pricon-location-pin"></i> <?php _e('Find my location', 'modularity-form-builder'); ?></button></span>
                    </div>
                @else
                    @field([
                        'type' => 'text',
                        'attributeList' => [
                            'type' => 'text',
                            'name' => sanitize_title($field['labels']['address']) . '[' . sanitize_title($field['labels']['street_address']) . ']'
                        ],
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
                <label for="{{ $module_id }}-address-postal-code">{{ $field['labels']['postal_code'] }}{!! in_array('address', $field['required_fields']) ? '<span class="text-danger">*</span>' : '' !!}</label>
                @field([
                    'type' => 'number',
                    'attributeList' => [
                        'type' => 'number',
                        'name' => sanitize_title($field['labels']['address']) . '[' . sanitize_title($field['labels']['postal_code']) . ']'
                    ],
                    'value' => $user_details['postal_code'],
                    'name' => sanitize_title($field['labels']['postal_code']),
                    'required' => in_array('postal_code', $field['required_fields'])

                ])
                @endfield
            </div>
        </div>
        <div class="o-grid-6@md">
            <div class="form-group">
                <label for="{{ $module_id }}-address-city">{{ $field['labels']['city'] }}{!! in_array('address', $field['required_fields']) ? '<span class="text-danger">*</span>' : '' !!}</label>
                @field([
                    'type' => 'text',
                    'attributeList' => [
                        'type' => 'text',
                        'name' => sanitize_title($field['labels']['address']) . '[' . sanitize_title($field['labels']['city']) . ']'
                    ],
                    'value' => $user_details['city'],
                    'name' => sanitize_title($field['labels']['city']),
                    'required' => in_array('city', $field['required_fields'])

                ])
                @endfield
            </div>
        </div>
    </div>
@endif
