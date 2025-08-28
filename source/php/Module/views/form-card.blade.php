@card([
    'classList' => [$classes ]
])
    @if (!$hideTitle && !empty($post_title))
        <div class="c-card__header">
            <h4>{!! apply_filters('the_title', $post_title) !!}</h4>
        </div>
    @endif

    <div class="c-card__body">
        @include('partials.form')
    </div>
@endcard