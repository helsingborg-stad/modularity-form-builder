<div class="grid">
    <div class="grid-md-12">
        <div class="form-group">
            <label for="{{ $module_id }}-{{ sanitize_title($field['label']) }}">{{ $field['label'] }}{!!  $field['required'] ? '<span class="text-danger">*</span>' : '' !!}</label>

            @foreach ($field['values'] as $value)
            <label class="checkbox">
                <input type="radio" name="{{ sanitize_title($field['label']) }}" value="{{ $value['value'] }}" {{ $field['required'] ? 'required' : '' }}> {{ $value['value'] }}
            </label>
            @endforeach
        </div>
    </div>
</div>
