@group([
    'direction' => 'vertical',
    'classList' => [$classes]
])
    @if (!$hideTitle && !empty($post_title))
        @typography([
            'element' => 'h2',
            'variant' => 'h2',
        ])
            {!! apply_filters('the_title', $post_title) !!}
        @endtypography
    @endif

    <div class="o-container o-container--remove-spacing">
        @include('partials.form')
    </div>
@endgroup