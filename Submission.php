<?php

namespace ModularityFormBuilder;

class Submission
{
    public function __construct()
    {

    }

    public function submit()
    {
        wp_insert_post(array(
            'post_type' => 'form-submissions'
        ));
    }
}
