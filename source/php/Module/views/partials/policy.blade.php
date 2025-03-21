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

    @option([
        'type' => 'checkbox',
        'value' =>  $lang['policy'],
        'label' => $lang['policy'] . '<span class="u-color__text--danger">*</span>',
        'required' => true,
        'classList' => ['u-margin__bottom--3']
    ])
    @endoption
    
@endif

