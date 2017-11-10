<div class="{{ $classes }}">
    @if (!$hideTitle && !empty($post_title))
    <h4 class="box-title">{!! apply_filters('the_title', $post_title) !!}</h4>
    @endif

    <form class="box-content" method="post" action="" {!! $hasFileUpload ? 'enctype="multipart/form-data"' : '' !!}>
        <?php wp_nonce_field('submit', 'modularity-form'); ?>
        <input type="hidden" name="modularity-form-id" value="{{ $ID }}">
        <input type="hidden" name="modularity-form-post-type" value="{{ $submissionPostType }}">

        @if (isset($_GET['form']) && $_GET['form'] == 'success')
            <div class="grid">
                <div class="grid-md-12">
                    <div class="notice success"><i class="pricon pricon-check pull-left"></i> <?php echo get_field('subimission_notice', $ID) ? get_field('subimission_notice', $ID) : __('The for was submitted, thank you!', 'modularity-form-builder'); ?></div>
                </div>
            </div>
        @endif

        @foreach ($form_fields as $field)
            @include('fields.' . $field['acf_fc_layout'])
        @endforeach

        @if ($allow_sender_copy)
            @include('fields.sender-copy')
        @endif

        <div class="grid">
            <div class="grid-md-12">
                @if (!empty($submission_public_act))
                    <p class="text-sm gutter gutter-sm gutter-bottom"><?php _e('Note that your comment will become a public act that can be read by others.', 'modularity-form-builder'); ?></p>
                @endif
                <input type="submit" value="{{ $submit_button_text ? $submit_button_text : 'Send' }}" class="btn btn-primary">
            </div>
        </div>
    </form>
</div>
