{{-- Firstname and/or lastname --}}
@if (in_array('firstname', $field['fields']) && in_array('lastname', $field['fields']))
    <div class="grid">
        <div class="grid-md-6">
            <div class="form-group">
                <label for="{{ $module_id }}-firstname">{{ $labels['firstname'] }}{!! in_array('firstname', $field['required_fields']) ? '<span class="text-danger">*</span>' : '' !!}</label>
                <input type="text" name="firstname" id="{{ $module_id }}-firstname" {{ in_array('firstname', $field['required_fields']) ? 'required' : '' }}>
            </div>
        </div>
        <div class="grid-md-6">
            <div class="form-group">
                <label for="{{ $module_id }}-lastname">{{ $labels['lastname'] }}{!! in_array('lastname', $field['required_fields']) ? '<span class="text-danger">*</span>' : '' !!}</label>
                <input type="text" name="lastname" id="{{ $module_id }}-lastname" {{ in_array('lastname', $field['required_fields']) ? 'required' : '' }}>
            </div>
        </div>
    </div>
@elseif (in_array('firstname', $field['fields']) && !in_array('lastname', $field['fields']))
    <div class="grid">
        <div class="grid-md-6">
            <div class="form-group">
                <label for="{{ $module_id }}-firstname">{{ $labels['firstname'] }}{!! in_array('firstname', $field['required_fields']) ? '<span class="text-danger">*</span>' : '' !!}</label>
                <input type="text" name="firstname" id="{{ $module_id }}-firstname" {{ in_array('firstname', $field['required_fields']) ? 'required' : '' }}>
            </div>
        </div>
    </div>
@elseif (!in_array('firstname', $field['fields']) && in_array('lastname', $field['fields']))
    <div class="grid">
        <div class="grid-md-6">
            <div class="form-group">
                <label for="{{ $module_id }}-lastname">{{ $labels['lastname'] }}{!! in_array('lastname', $field['required_fields']) ? '<span class="text-danger">*</span>' : '' !!}</label>
                <input type="text" name="lastname" id="{{ $module_id }}-lastname" {{ in_array('lastname', $field['required_fields']) ? 'required' : '' }}>
            </div>
        </div>
    </div>
@endif

{{-- Email and/or phone --}}
@if (in_array('email', $field['fields']) && in_array('phone', $field['fields']))
    <div class="grid">
        <div class="grid-md-6">
            <div class="form-group">
                <label for="{{ $module_id }}-email">{{ $labels['email'] }}{!! in_array('email', $field['required_fields']) ? '<span class="text-danger">*</span>' : '' !!}</label>
                <input type="email" name="email" id="{{ $module_id }}-email" {{ in_array('email', $field['required_fields']) ? 'required' : '' }}>
            </div>
        </div>
        <div class="grid-md-6">
            <div class="form-group">
                <label for="{{ $module_id }}-phone">{{ $labels['phone'] }}{!! in_array('phone', $field['required_fields']) ? '<span class="text-danger">*</span>' : '' !!}</label>
                <input type="tel" name="phone" id="{{ $module_id }}-phone" {{ in_array('phone', $field['required_fields']) ? 'required' : '' }}>
            </div>
        </div>
    </div>
@elseif (in_array('email', $field['fields']) && !in_array('phone', $field['fields']))
    <div class="grid">
        <div class="grid-md-6">
            <div class="form-group">
                <label for="{{ $module_id }}-email">{{ $labels['email'] }}{!! in_array('email', $field['required_fields']) ? '<span class="text-danger">*</span>' : '' !!}</label>
                <input type="email" name="email" id="{{ $module_id }}-email" {{ in_array('email', $field['required_fields']) ? 'required' : '' }}>
            </div>
        </div>
    </div>
@elseif (!in_array('email', $field['fields']) && in_array('phone', $field['fields']))
    <div class="grid">
        <div class="grid-md-6">
            <div class="form-group">
                <label for="{{ $module_id }}-phone">{{ $labels['phone'] }}{!! in_array('phone', $field['required_fields']) ? '<span class="text-danger">*</span>' : '' !!}</label>
                <input type="tel" name="phone" id="{{ $module_id }}-phone" {{ in_array('phone', $field['required_fields']) ? 'required' : '' }}>
            </div>
        </div>
    </div>
@endif

{{-- Address --}}
 @if (in_array('address', $field['fields']))
    <div class="grid">
        <div class="grid-md-12">
            <div class="form-group">
                <label for="{{ $module_id }}-address-street">{{ $labels['street_address'] }}{!! in_array('address', $field['required_fields']) ? '<span class="text-danger">*</span>' : '' !!}</label>
                @if ($googleGeocoding)
                <div class="input-group">
                    <input type="text" name="address[street-address]" class="form-control" id="{{ $module_id }}-address-street" {{ in_array('address', $field['required_fields']) ? 'required' : '' }}>
                    <span class="input-group-addon-btn"><button id="form-get-location" class="btn"><i class="pricon pricon-location-pin"></i> <?php _e('Find my location', 'modularity-form-builder'); ?></button></span>
                </div>
                @else
                    <input type="text" name="address[street-address]" class="form-control" id="{{ $module_id }}-address-street" {{ in_array('address', $field['required_fields']) ? 'required' : '' }}>
                @endif
            </div>
        </div>
    </div>
    <div class="grid">
        <div class="grid-md-6">
            <div class="form-group">
                <label for="{{ $module_id }}-address-postal_code">{{ $labels['postal_code'] }}{!! in_array('address', $field['required_fields']) ? '<span class="text-danger">*</span>' : '' !!}</label>
                <input type="text" name="address[postal-code]" id="{{ $module_id }}-address-postal_code" {{ in_array('address', $field['required_fields']) ? 'required' : '' }}>
            </div>
        </div>
        <div class="grid-md-6">
            <div class="form-group">
                <label for="{{ $module_id }}-address-city">{{ $labels['city'] }}{!! in_array('address', $field['required_fields']) ? '<span class="text-danger">*</span>' : '' !!}</label>
                <input type="text" name="address[city]" id="{{ $module_id }}-address-city" {{ in_array('address', $field['required_fields']) ? 'required' : '' }}>
            </div>
        </div>
    </div>
@endif
