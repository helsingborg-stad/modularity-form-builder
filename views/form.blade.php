<div class="{{ $classes }}">
    @if (!$hideTitle && !empty($post_title))
    <h4 class="box-title">{!! apply_filters('the_title', $post_title) !!}</h4>
    @endif

    <form class="box-content" action="">
        <?php wp_nonce_field('submit', 'modularity-form'); ?>

        @foreach ($form_fields as $field)
            @include('fields.' . $field['acf_fc_layout'])
        @endforeach

        <div class="grid">
            <div class="grid-md-12">
                <input type="submit" value="{{ $submit_button_text ? $submit_button_text : 'Send' }}" class="btn btn-primary">
            </div>
        </div>
    </form>
</div>
