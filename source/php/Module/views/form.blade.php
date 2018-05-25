<div class="{{ $classes }}">
    @if (!$hideTitle && !empty($post_title))
    <h4 class="box-title">{!! apply_filters('the_title', $post_title) !!}</h4>
    @endif

    <form class="box-content" method="post" action="" {!! $hasFileUpload ? 'enctype="multipart/form-data"' : '' !!}>
        <?php wp_nonce_field('submit', 'modularity-form'); ?>
        <input type="hidden" name="modularity-form-id" value="{{ $ID }}">
        <input type="hidden" name="modularity-form-post-type" value="{{ $submissionPostType }}">
        <input type="hidden" id="modularity-form-history" name="modularity-form-history" value="">
        <input type="hidden" id="modularity-form-url" name="modularity-form-url" value="">
        @if (isset($_GET['form']) && $_GET['form'] == 'success')
            <div class="grid">
                <div class="grid-md-12">
                    <div class="notice success"><i class="pricon pricon-check pull-left"></i> <?php echo get_field('subimission_notice', $ID) ? get_field('subimission_notice', $ID) : __('The for was submitted, thank you!', 'modularity-form-builder'); ?></div>
                </div>
            </div>
        @endif

        @if (isset($_GET['form']) && $_GET['form'] == 'failed')
            <div class="grid">
                <div class="grid-md-12">
                    <div class="notice warning"><i class="pricon pricon-notice-warning pull-left"></i> <?php _e('Something went wrong, please try again.', 'modularity-form-builder'); ?></div>
                </div>
            </div>
        @endif

        @foreach ($form_fields as $field)
            @includeIf('fields.' . $field['acf_fc_layout'])
        @endforeach

        @if ($allow_sender_copy)
            @include('fields.sender-copy')
        @endif

        @if (!is_user_logged_in())
                <div class="grid">
                    <div class="grid-md-12">
                        <div class="g-recaptcha"></div>
                        <div class="form-notice text-danger captcha-warning text-sm" aria-live="polite"><?php _e('You must confirm your not a robot.', 'modularity-form-builder'); ?></div>
                    </div>
                </div>
        @endif

<div class="grid">
   <div class="grid-md-12">
        {{-- Will be a public act  --}}
        @if (!empty($submission_public_act))
        <p class="text-sm gutter gutter-sm gutter-bottom">
            <?php _e('Note that your comment will become a public act that can be read by others.', 'modularity-form-builder'); ?>
        </p>
        @endif

        {{-- GDPR notice  --}}
        @if (is_null($gdpr_complience_notice) || !empty($gdpr_complience_notice))
        <p class="text-sm gutter gutter-sm gutter-bottom">
            <input type="checkbox" name="gdpr" required="required">
            <?php _e('I agree to GDPR stuff.', 'modularity-form-builder'); ?>
        </p>
        @endif

       <button type="submit" class="btn btn-primary">{{ $submit_button_text ? $submit_button_text : 'Send' }}</button>
   </div>
</div>
</form>
</div>
