<div class="{{ $classes }}">
    @if (!$hideTitle && !empty($post_title))
    <h4 class="box-title">{!! apply_filters('the_title', $post_title) !!}</h4>
    @endif

    <form class="box-content" method="post" action="">
        <?php wp_nonce_field('submit', 'modularity-form'); ?>
        <input type="hidden" name="modularity-form-id" value="{{ $ID }}">

        @if (isset($_GET['form']) && $_GET['form'] == 'success')
            <div class="grid">
                <div class="grid-md-12">
                    <div class="notice success"><i class="pricon pricon-check"></i> <?php echo get_field('subimission_notice', $ID) ? get_field('subimission_notice', $ID) : __('The for was submitted, thank you!', 'modularity-form-builder'); ?></div>
                </div>
            </div>
        @endif

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
