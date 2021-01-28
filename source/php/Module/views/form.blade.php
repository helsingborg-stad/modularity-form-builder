@card([
    'classList' => [$classes ]
])
    @if (!$hideTitle && !empty($post_title))
        <div class="c-card__header">
            <h4 class="box-title">{!! apply_filters('the_title', $post_title) !!}</h4>
        </div>
    @endif

    <div class="c-card__body">
        <form class="box-content modularity-validation mod-form" method="post" action="" {!! $hasFileUpload ? 'enctype="multipart/form-data"' : '' !!}>
            <?php wp_nonce_field('submit', 'modularity-form'); ?>
            <input type="hidden" name="modularity-form-id" value="{{ $ID }}">
            <input type="hidden" name="modularity-form-post-type" value="{{ $submissionPostType }}">
            <input type="hidden" id="modularity-form-history" name="modularity-form-history" value="">
            <input type="hidden" id="modularity-form-url" name="modularity-form-url" value="">
            <input type="hidden" id="modularity-gdpr-data" name="modularity-gdpr-data" value="{{$dataStorage}}">

            @if (isset($_GET['form']) && $_GET['form'] == 'success')
                <div class="o-grid">
                    <div class="o-grid-12@md">
                        <div class="notice success u-mb-2"><i class="pricon pricon-check pull-left"></i> <?php echo get_field('subimission_notice', $ID) ? get_field('subimission_notice', $ID) : __('The for was submitted, thank you!', 'modularity-form-builder'); ?>
                        </div>
                    </div>
                </div>
            @endif

            @if (isset($_GET['form']) && $_GET['form'] == 'failed')
                <div class="o-grid">
                    <div class="o-grid-12@md">
                        <div class="notice warning"><i
                                    class="pricon pricon-notice-warning pull-left"></i> <?php _e('Something went wrong, please try again.', 'modularity-form-builder'); ?>
                        </div>
                    </div>
                </div>
            @endif

            @if (!isset($_GET['form']) || $_GET['form'] != 'success')
                @foreach ($form_fields as $field)
                    @includeIf('fields.' . $field['acf_fc_layout'])
                @endforeach
            @endif

            @if ($allow_sender_copy)
                @include('fields.sender-copy')
            @endif

            @if (!is_user_logged_in() && !isset($_GET['form']) || $_GET['form'] != 'success' )
                <div class="o-grid">
                    <div class="o-grid-12@md">
                        <div class="g-recaptcha u-mt-2"></div>
                        <div class="form-notice text-danger captcha-warning text-sm"
                            aria-live="polite"><?php _e('You must confirm you\'re not a robot.', 'modularity-form-builder'); ?></div>
                    </div>
                </div>
            @endif

            @if (!isset($_GET['form']) || $_GET['form'] != 'success')
                <div class="o-grid">
                    <div class="o-grid-12@md">
                        @if($submission_public_act || $gdpr_complience_notice)
                            @if($submission_public_act && !empty($submission_public_act_content))
                                @typography([
                                    "variant" => "meta",
                                    "element" => "p"
                                ])
                                    {{$submission_public_act_content}}
                                @endtypography
                            @endif
                            @if($gdpr_complience_notice && !empty($gdpr_complience_notice_content))
                                @typography([
                                    "variant" => "meta",
                                    "element" => "p"
                                ])
                                    {!! $gdpr_complience_notice_content !!}
                                @endtypography
                            @endif
                        @endif
                    
                        <button type="submit" class="c-button c-button__filled c-button__filled--primary c-button--md">{{ $submit_button_text ? $submit_button_text : 'Send' }}</button>
                        
                    </div>
                </div>
            @endif

            @if (isset($_GET['form']) && $_GET['form'] == 'success')
                <div class="o-grid">
                    <div class="o-grid-12@md">
                        @if($submission_public_act || $gdpr_complience_notice)
                            @if($submission_public_act && !empty($submission_public_act_content))
                                <p class="text-sm gutter gutter-sm gutter-bottom">
                                    {{$submission_public_act_content}}
                                </p>
                            @endif
                            @if($gdpr_complience_notice && !empty($gdpr_complience_notice_content))
                                <div class="text-sm gutter gutter-sm gutter-bottom">
                                    {!! $gdpr_complience_notice_content !!}
                                </div>
                            @endif
                        @endif
                        @button([
                            'text' => translate( 'Show form', 'modularity-form-builder' ),
                            'classList' => ['js-return_to_form'],
                            'attributeList' => [
                                'onClick' => 'window.location = window.location.pathname'
                            ]
                        ])
                        @endbutton
                    </div>
                </div>
            @endif

        </form>
    </div>
@endcard