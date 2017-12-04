{{-- Radio field --}}
<div class="mod-form-field">
    <p><b><label for="{{ $module_id }}-{{ $field['name'] }}">
        {{ $field['label'] }}{!! $field['required'] ? '<span class="text-danger">*</span>' : '' !!}
    </label></b></p>
</div>
<ul style="list-style-type:none">
    @foreach ($field['values'] as $value)
        <li>
            <label class="checkbox">
                <input type="radio" name="mod-form[{{ $field['name'] }}]" value="{{ $value['value'] }}" {{ $field['required'] ? 'required' : '' }} {{ $field['value'] == $value['value'] ? 'checked' : '' }}> {{ $value['value'] }}
            </label>
        </li>
    @endforeach
</ul>
