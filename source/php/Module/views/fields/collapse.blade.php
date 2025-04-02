<div class="mod-form-collapse gutter gutter-top" {!! $field['conditional_hidden'] !!}>
    @button([
        'text' => $field['button_text'],
        'color' => 'primary',
        'icon' => 'expand_content',
        'reversePositions' => true,
        'classList' => [
            'u-text-align--left',
            'u-width--100'
        ],
    ])
    @endbutton
</div>
