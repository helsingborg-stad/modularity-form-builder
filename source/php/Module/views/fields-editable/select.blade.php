{{-- Radio field --}}
<div class="mod-form-field">
    <p><b><label for="{{ $module_id }}-{{ $field['name'] }}">
        {{ $field['label'] }}{!! $field['required'] ? '<span class="text-danger">*</span>' : '' !!}
    </label></b></p>
</div>
<select name="mod-form[{{ $field['name'] }}]" id="{{ $module_id }}-{{ $field['name'] }}">
    @foreach ($field['values'] as $value)
        <label>
            <option value="{{ $value['value'] }}" {{ $field['value'] == $value['value'] ? 'selected' : '' }}>{{ $value['value'] }}</option>
        </label>
    @endforeach
</select>
