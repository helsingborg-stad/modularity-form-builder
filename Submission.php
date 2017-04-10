<?php

namespace ModularityFormBuilder;

class Submission
{
    public function __construct()
    {
        add_action('init', function () {
            if (isset($_POST['modularity-form']) && wp_verify_nonce($_POST['modularity-form'], 'submit')) {
                $this->submit();
            }
        });
    }

    public function submit()
    {
        unset($_POST['modularity-form']);

        $referer = $_POST['_wp_http_referer'];
        unset($_POST['_wp_http_referer']);

        $submission = wp_insert_post(array(
            'post_title' => get_the_title($_POST['modularity-form-id']),
            'post_type' => 'form-submissions',
            'post_status' => 'publish'
        ));

        update_post_meta($submission, 'form-data', $_POST);
        update_post_meta($submission, 'modularity-form-id', $_POST['modularity-form-id']);

        if (strpos($referer, '?') > -1) {
            $referer .= '&form=success';
        } else {
            $referer .= '?form=success';
        }

        wp_redirect($referer);
        exit;
    }
}
