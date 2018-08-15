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
        <input type="hidden" id="modularity-gdpr-data" name="modularity-gdpr-data" value="{{$dataStorage}}">
        @if (isset($_GET['form']) && $_GET['form'] == 'success')
            <div class="grid">
                <div class="grid-md-12">
                    <div class="notice success u-mb-2"><i class="pricon pricon-check pull-left"></i> <?php echo get_field('subimission_notice', $ID) ? get_field('subimission_notice', $ID) : __('The for was submitted, thank you!', 'modularity-form-builder'); ?>
                    </div>
                </div>
            </div>
        @endif

        @if (isset($_GET['form']) && $_GET['form'] == 'failed')
            <div class="grid">
                <div class="grid-md-12">
                    <div class="notice warning"><i
                                class="pricon pricon-notice-warning pull-left"></i> <?php _e('Something went wrong, please try again.', 'modularity-form-builder'); ?>
                    </div>
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
                    <div class="g-recaptcha u-mt-2"></div>
                    <div class="form-notice text-danger captcha-warning text-sm"
                         aria-live="polite"><?php _e('You must confirm you\'re not a robot.', 'modularity-form-builder'); ?></div>
                </div>
            </div>
        @endif

            <div class="grid">
                <div class="grid-md-12">
                    @if($submission_public_act || $gdpr_complience_notice)
                        @if($submission_public_act && !empty($submission_public_act_content))
                            <p class="text-sm gutter gutter-sm gutter-bottom">
                                {{$submission_public_act_content}}
                            </p>
                        @endif
                        @if($gdpr_complience_notice && !empty($gdpr_complience_notice_content))
                            <p class="text-sm gutter gutter-sm gutter-bottom">
                                {{$gdpr_complience_notice_content}}
                            </p>
                        @endif
                    @endif
                    <button type="submit" class="btn btn-primary u-mt-2">{{ $submit_button_text ? $submit_button_text : 'Send' }}</button>
                </div>
            </div>

    </form>
</div>
