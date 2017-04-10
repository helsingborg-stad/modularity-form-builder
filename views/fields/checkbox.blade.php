<div class="grid">
    <div class="grid-md-12">
        <div class="form-group">
            <label for="{{ $module_id }}-{{ sanitize_title($field['label']) }}">{{ $field['label'] }}{!!  $field['required'] ? '<span class="text-danger">*</span>' : '' !!}</label>

            @foreach ($field['values'] as $value)
            <label class="checkbox">
                <input type="checkbox" name="{{ $module_id }}-{{ sanitize_title($field['label']) }}" value="{{ $value['value'] }}"> {{ $value['value'] }}
            </label>
            @endforeach
        </div>
    </div>
</div>
