{{-- Firstname and/or lastname --}}
@if (in_array('firstname', $field['fields']) && in_array('lastname', $field['fields']))
    <div class="grid hidden">
        <div class="grid-md-6">
            <div class="form-group">
                <label for="{{ $module_id }}-firstname"><?php _e('Firstname', 'modularity-form-builder'); ?>{!! in_array('firstname', $field['required_fields']) ? '<span class="text-danger">*</span>' : '' !!}</label>
                <input type="text" name="firstname" id="{{ $module_id }}-firstname" {{ in_array('firstname', $field['required_fields']) ? 'required' : '' }}>
            </div>
        </div>
        <div class="grid-md-6">
            <div class="form-group">
                <label for="{{ $module_id }}-lastname"><?php _e('Lastname', 'modularity-form-builder'); ?>{!! in_array('lastname', $field['required_fields']) ? '<span class="text-danger">*</span>' : '' !!}</label>
                <input type="text" name="lastname" id="{{ $module_id }}-lastname" {{ in_array('lastname', $field['required_fields']) ? 'required' : '' }}>
            </div>
        </div>
    </div>
@elseif (in_array('firstname', $field['fields']) && !in_array('lastname', $field['fields']))
    <div class="grid">
        <div class="grid-md-6">
            <div class="form-group">
                <label for="{{ $module_id }}-firstname"><?php _e('Firstname', 'modularity-form-builder'); ?>{!! in_array('firstname', $field['required_fields']) ? '<span class="text-danger">*</span>' : '' !!}</label>
                <input type="text" name="firstname" id="{{ $module_id }}-firstname" {{ in_array('firstname', $field['required_fields']) ? 'required' : '' }}>
            </div>
        </div>
    </div>
@elseif (!in_array('firstname', $field['fields']) && in_array('lastname', $field['fields']))
    <div class="grid">
        <div class="grid-md-6">
            <div class="form-group">
                <label for="{{ $module_id }}-lastname"><?php _e('Lastname', 'modularity-form-builder'); ?>{!! in_array('lastname', $field['required_fields']) ? '<span class="text-danger">*</span>' : '' !!}</label>
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
                <label for="{{ $module_id }}-email"><?php _e('Email', 'modularity-form-builder'); ?>{!! in_array('email', $field['required_fields']) ? '<span class="text-danger">*</span>' : '' !!}</label>
                <input type="email" name="email" id="{{ $module_id }}-email" {{ in_array('email', $field['required_fields']) ? 'required' : '' }}>
            </div>
        </div>
        <div class="grid-md-6">
            <div class="form-group">
                <label for="{{ $module_id }}-phone"><?php _e('Phone', 'modularity-form-builder'); ?>{!! in_array('phone', $field['required_fields']) ? '<span class="text-danger">*</span>' : '' !!}</label>
                <input type="tel" name="phone" id="{{ $module_id }}-phone" {{ in_array('phone', $field['required_fields']) ? 'required' : '' }}>
            </div>
        </div>
    </div>
@elseif (in_array('email', $field['fields']) && !in_array('phone', $field['fields']))
    <div class="grid">
        <div class="grid-md-6">
            <div class="form-group">
                <label for="{{ $module_id }}-email"><?php _e('Email', 'modularity-form-builder'); ?>{!! in_array('email', $field['required_fields']) ? '<span class="text-danger">*</span>' : '' !!}</label>
                <input type="email" name="email" id="{{ $module_id }}-email" {{ in_array('email', $field['required_fields']) ? 'required' : '' }}>
            </div>
        </div>
    </div>
@elseif (!in_array('email', $field['fields']) && in_array('phone', $field['fields']))
    <div class="grid">
        <div class="grid-md-6">
            <div class="form-group">
                <label for="{{ $module_id }}-phone"><?php _e('Phone', 'modularity-form-builder'); ?>{!! in_array('phone', $field['required_fields']) ? '<span class="text-danger">*</span>' : '' !!}</label>
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
                <label for="{{ $module_id }}-address-street"><?php _e('Street address', 'modularity-form-builder'); ?>{!! in_array('address', $field['required_fields']) ? '<span class="text-danger">*</span>' : '' !!}</label>
                <input type="text" name="address[street]" id="{{ $module_id }}-address-street" {{ in_array('address', $field['required_fields']) ? 'required' : '' }}>
            </div>
        </div>
    </div>
    <div class="grid">
        <div class="grid-md-6">
            <div class="form-group">
                <label for="{{ $module_id }}-address-postalcode"><?php _e('Postal code', 'modularity-form-builder'); ?>{!! in_array('address', $field['required_fields']) ? '<span class="text-danger">*</span>' : '' !!}</label>
                <input type="text" name="address[postalcode]" id="{{ $module_id }}-address-postalcode" {{ in_array('address', $field['required_fields']) ? 'required' : '' }}>
            </div>
        </div>
        <div class="grid-md-6">
            <div class="form-group">
                <label for="{{ $module_id }}-address-city"><?php _e('City', 'modularity-form-builder'); ?>{!! in_array('address', $field['required_fields']) ? '<span class="text-danger">*</span>' : '' !!}</label>
                <input type="text" name="address[city]" id="{{ $module_id }}-address-city" {{ in_array('address', $field['required_fields']) ? 'required' : '' }}>
            </div>
        </div>
    </div>
@endif
