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

        // Save submission
        $submission = wp_insert_post(array(
            'post_title' => get_the_title($_POST['modularity-form-id']),
            'post_type' => 'form-submissions',
            'post_status' => 'publish'
        ));

        update_post_meta($submission, 'form-data', $_POST);
        update_post_meta($submission, 'modularity-form-id', $_POST['modularity-form-id']);

        $notify = get_field('notify', $_POST['modularity-form-id']);

        foreach ($notify as $email) {
            $this->notify($email['email'], $_POST['modularity-form-id'], $submission);
        }

        // Redirect
        if (strpos($referer, '?') > -1) {
            $referer .= '&form=success';
        } else {
            $referer .= '?form=success';
        }

        wp_redirect($referer);
        exit;
    }

    public function notify($email, $formId, $submissionId)
    {
        $headers = array('Content-Type: text/html; charset=UTF-8');

        wp_mail(
            $email,
            __('New form submission', 'modularity-form-builder'),
            sprintf(
                __('Hi, this is a notification about a new form submission to the form "%s".<br><br><a href="%s">Read the full submission here</a>.', 'modularity-form-builder'),
                get_the_title($formId),
                get_edit_post_link($submissionId)
            ),
            $headers
        );
    }
}
