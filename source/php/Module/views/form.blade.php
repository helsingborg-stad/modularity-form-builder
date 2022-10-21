@card([
    'classList' => [$classes ]
])
    @if (!$hideTitle && !empty($post_title))
        <div class="c-card__header">
            <h4>{!! apply_filters('the_title', $post_title) !!}</h4>
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

            <?php /* These field is not allowed to change. Honeypot function. */ ?>
            <input autocomplete="off" class="modularity-v-field" type="text" id="modularity-v-field" name="modularity-v-field" value="7y0dwakjbdwabclsglcaw" style="overflow: hidden; width: 1px; height: 1px; opacity: .001; position: absolute; padding: 0; margin: 0; border: none;">
            <input autocomplete="off" class="modularity-e-field" type="text" id="modularity-e-field" name="modularity-e-field" value="" style="overflow: hidden; width: 1px; height: 1px; opacity: .001; position: absolute; padding: 0; margin: 0; border: none;">
            <input autocomplete="off" class="modularity-t-field" type="text" id="modularity-t-field" name="modularity-t-field" value="89dwaohdwa9y8"  style="overflow: hidden; width: 1px; height: 1px; opacity: .001; position: absolute; padding: 0; margin: 0; border: none;">

            <?php /* User must be on page for at least 5 seconds. Honeypot function. */ ?>  
            <script type="text/javascript">
                ["onload"].forEach(function(e){
                    [].forEach.call(document.querySelectorAll(".modularity-t-field"), function(item) {
                        setTimeout(function() {
                            item.value = "5000";
                        }.bind(item), 5000); 
                    });
                });
            </script>

            @if (isset($_GET['form']) && $_GET['form'] == 'success')
                <div class="o-grid">
                    <div class="o-grid-12@md">
    
                        @notice([
                            'type' => 'success',
                            'message' => [
                                'text' =>  $submissionNotice,
                                'size' => 'sm'
                            ],
                            'icon' => [
                                'name' => 'report',
                                'size' => 'md',
                                'color' => 'white'
                            ]
                        ])
                        @endnotice

                    </div>
                </div>
            @endif

            @if ($submissionResult === 'failed')
                <div class="o-grid">
                    <div class="o-grid-12@md">
                        
                        @notice([
                            'type' => 'warning',
                            'message' => [
                                'text' =>  $reason,
                                'size' => 'sm'
                            ],
                            'icon' => [
                                'name' => 'report',
                                'size' => 'md',
                                'color' => 'white'
                            ]
                        ])
                        @endnotice
  
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
                            'text' => $showFormLang,
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
