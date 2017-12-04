{{-- Message --}}
<div class="mod-form-field">
	<p><b><label for="{{ $module_id }}-message">{{ $field['label'] ? $field['label'] : 'Message' }}{!! $field['required'] ? '<span class="text-danger">*</span>' : '' !!}</label></b></p>
	<textarea name="mod-form[{{ $field['name'] }}]" id="{{ $module_id }}-message" class="large-text" rows="5" {{ $field['required'] ? 'required' : '' }}>{{ $field['value'] }}</textarea>
</div>
