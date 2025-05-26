{{-- Firstname --}}
@if (in_array('firstname', $field['fields']))
    <div class="o-grid mod-form-field mod-form-field--firstname">
        <div class="form-group">
            @field([
                'label' => $field['labels']['firstname'], 
                'type' => 'text',
                'name' => sanitize_title($field['labels']['firstname']),
                'id' =>  $module_id . "-firstname-" . uniqid(),
                'value' => $user_details['firstname'],
                'required' => in_array('firstname', $field['required_fields']),
                'autocomplete' => 'given-name',
                'attributeList' => $field['attributeList']
            ])
            @endfield
        </div>
    </div>
@endif

{{-- Lastname --}}
@if (in_array('lastname', $field['fields']))
    <div class="o-grid mod-form-field mod-form-field--lastname">
        <div class="form-group">               
            @field([
                'label' => $field['labels']['lastname'],
                'type' => 'text',
                'id' =>  $module_id . "-lastname-" . uniqid(),
                'value' => $user_details['lastname'],
                'name' => sanitize_title($field['labels']['lastname']),
                'required' => in_array('lastname', $field['required_fields']),
                'autocomplete' => 'family-name',
                'attributeList' => $field['attributeList']
            ])
            @endfield
        </div>
    </div>
@endif

{{-- Email --}}
@if (in_array('email', $field['fields']))
    <div class="o-grid mod-form-field mod-form-field--email">
        <div class="form-group">
            @field([
                'label' => $field['labels']['email'],
                'type' => 'email',
                'id' =>  $module_id . "-email-" . uniqid(),
                'invalidMessage' => $field['invalidMessages']['email'],
                'value' => $user_details['email'],
                'name' => sanitize_title($field['labels']['email']),
                'required' => in_array('email', $field['required_fields']),
                'autocomplete' => 'email',
                'attributeList' => $field['attributeList']
            ])
            @endfield
        </div>
    </div>
@endif

{{-- Phone --}}
@if (in_array('phone', $field['fields']))
    <div class="o-grid mod-form-field mod-form-field--phone">
        <div class="form-group">
            @field([
                'label' => $field['labels']['phone'],
                'type' => 'number',
                'id' =>  $module_id . "-phone-" . uniqid(),
                'invalidMessage' => $field['invalidMessages']['number'],
                'value' => $user_details['phone'],
                'name' => sanitize_title($field['labels']['phone']),
                'required' => in_array('phone', $field['required_fields']),
                'autocomplete' => 'tel',
                'attributeList' => $field['attributeList']
            ])
            @endfield
        </div>
    </div>
@endif

{{-- Address --}}
 @if (in_array('address', $field['fields']))
    <div class="o-grid mod-form-field mod-form-field--street-address">
        <div class="form-group">
            @if ($googleGeocoding)
                <label for="input_{{ $module_id }}-address-street">{{ $field['labels']['street_address'] }}{!! in_array('address', $field['required_fields']) ? '<span class="text-danger">*</span>' : '' !!}</label>
                <div class="input-group">
                    <input type="text" name="{{ sanitize_title($field['labels']['address']) }}[{{ sanitize_title($field['labels']['street_address']) }}]" class="form-control" id="input_{{ $module_id }}-address-street-"{{uniqid()}} {{ in_array('address', $field['required_fields']) ? 'required' : '' }}>
                    <span class="input-group-addon-btn"><button id="form-get-location" class="btn"><i class="pricon pricon-location-pin"></i> <?php _e('Find my location', 'modularity-form-builder'); ?></button></span>
                </div>
            @else
                @field([
                    'label' => $field['labels']['street_address'],
                    'type' => 'text',
                    'id' =>  $module_id . "-address-street-" . uniqid(),
                    'value' => !empty($user_details['address']) ? $user_details['address'] : false,
                    'name' => sanitize_title($field['labels']['address']) . '[' . sanitize_title($field['labels']['street_address']) . ']',
                    'required' => in_array('address', $field['required_fields']),
                    'autocomplete' => 'street-address',
                    'attributeList' => $field['attributeList']
                ])
                @endfield
            @endif
        </div>
    </div>
    <div class="o-grid mod-form-field mod-form-field--postal-code">
        <div class="form-group">               
            @field([
                'label' => $field['labels']['postal_code'],
                'type' => 'number',
                'id' =>  $module_id . "-address-postal-code-" . uniqid(),
                'invalidMessage' => $field['invalidMessages']['number'],
                'value' => !empty($user_details['postal_code']) ? $user_details['postal_code'] : false,
                'name' => sanitize_title($field['labels']['address']) . '[' . sanitize_title($field['labels']['postal_code']) . ']',
                'required' => in_array('postal_code', $field['required_fields']),
                'autocomplete' => 'postal-code'
            ])
            @endfield
        </div>
    </div>
    <div class="o-grid mod-form-field mod-form-field--city">
        <div class="form-group">
            @field([
                'label' => $field['labels']['city'],
                'type' => 'text',
                'id' =>  $module_id . "-address-city-" . uniqid(),
                'value' => !empty($user_details['city']) ? $user_details['city'] : false,
                'name' => sanitize_title($field['labels']['address']) . '[' . sanitize_title($field['labels']['city']) . ']',
                'required' => in_array('city', $field['required_fields']),
                'autocomplete' => 'on'
            ])
            @endfield
        </div>
    </div>
@endif
