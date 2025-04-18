@card([
    'classList' => [$classes ]
])
    @if (!$hideTitle && !empty($post_title))
        <div class="c-card__header">
            <h4>{!! apply_filters('the_title', $post_title) !!}</h4>
        </div>
    @endif

    <div class="c-card__body">
        @if(!$formWasSent)
            @form([
                'errorMessage' => $lang['errorMessage'],
                'validateMessage' => $lang['validateMessage'],
                'classList' => [
                    'box-content', 'modularity-validation', 'mod-form', 'js-form-validation'
                ],
                'attributeList' => [
                    'enctype' => $hasFileUpload ? 'multipart/form-data' : '',
                ]
            ])
            <?php wp_nonce_field('submit', 'modularity-form'); ?>
            <input type="hidden" class="js-no-validation" name="modularity-form-id" value="{{ $ID }}">
            <input type="hidden" class="js-no-validation" name="modularity-form-post-type" value="{{ $submissionPostType }}">
            <input type="hidden" class="js-no-validation" id="modularity-form-{{ $ID }}-history" name="modularity-form-history" value="">
            <input type="hidden" class="js-no-validation" id="modularity-form-{{ $ID }}-url" name="modularity-form-url" value="">
            <input type="hidden" class="js-no-validation" id="modularity-gdpr-{{ $ID }}-data" name="modularity-gdpr-data" value="{{$dataStorage}}">

            <?php /* These field is not allowed to change. Honeypot function. */ ?>
            <input aria-hidden="true" autocomplete="off" class="modularity-v-field js-no-validation" type="text" id="modularity-{{ $ID }}-v-field" name="modularity-v-field" value="7y0dwakjbdwabclsglcaw" style="overflow: hidden; width: 1px; height: 1px; opacity: .001; position: absolute; padding: 0; margin: 0; border: none;" tabindex="-1">
            <input aria-hidden="true" autocomplete="off" class="modularity-e-field js-no-validation" type="text" id="modularity-{{ $ID }}-e-field" name="modularity-e-field" value="" style="overflow: hidden; width: 1px; height: 1px; opacity: .001; position: absolute; padding: 0; margin: 0; border: none;" tabindex="-1">
            <input aria-hidden="true" autocomplete="off" class="modularity-t-field js-no-validation" type="text" id="modularity-{{ $ID }}-t-field" name="modularity-t-field" value="89dwaohdwa9y8"  style="overflow: hidden; width: 1px; height: 1px; opacity: .001; position: absolute; padding: 0; margin: 0; border: none;" tabindex="-1">

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

            @if ($submissionResult === 'failed')
                <div class="o-grid">
                    <div class="o-grid-12@md">
                        
                        @notice([
                            'type' => 'warning',
                            'message' => [
                                'text' =>  strip_tags($reason),
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
            
 
                <div class="o-grid">
                    <div class="o-grid-12@md">
                        @includeWhen($submission_public_act || $gdpr_complience_notice, 'partials.policy')
                    
                        <button type="submit" class="c-button c-button__filled c-button__filled--primary c-button--md">{{ $submit_button_text ? $submit_button_text : 'Send' }}</button>
                        
                    </div>
                </div>
                @endform
                @else
            <div class="o-grid">
                <div class="o-grid-12@md">
                    @notice([
                        'type' => 'success',
                        'message' => [
                            'text' => strip_tags($subimission_notice) ? strip_tags($subimission_notice) :  __('The for was submitted, thank you!', 'modularity-form-builder'),
                            'size' => 'sm'
                        ],
                        'classList' => [
                            'u-margin__bottom--3'
                        ],
                        'icon' => [
                            'name' => 'report',
                            'size' => 'md',
                            'color' => 'white'
                        ]
                    ])
                    @endnotice
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
    </div>
@endcard