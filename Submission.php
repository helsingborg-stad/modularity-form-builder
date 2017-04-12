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

        // Upload files
        $files = array();
        if (!empty($_FILES)) {
            $files = $this->uploadFiles($_FILES, $_POST['modularity-form-id']);
        }

        $_POST = array_merge($_POST, $files);

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

    /**
     * Upload files
     * @param  array $fileslist
     * @param  int   $formId
     * @return array
     */
    public function uploadFiles($fileslist, $formId)
    {
        $uploadsFolder = wp_upload_dir();
        $uploadsFolder = $uploadsFolder['basedir'] . '/modularity-form-builder';
        $this->maybeCreateFolder($uploadsFolder);
        $fields = $this->getFileFields($formId);
        $uploaded = array();

        foreach ($fileslist as $key => $files) {
            for ($i = 0; $i < count($files['name']); $i++) {
                $targetFile = $uploadsFolder . '/' . uniqid() . '-' . basename($files['name'][$i]);
                $fileext = pathinfo($targetFile, PATHINFO_EXTENSION);

                if (!in_array('.' . $fileext, $fields[$key]['filetypes'])) {
                    trigger_error('Filetype not allowed');
                    exit;
                }

                if (!move_uploaded_file($files['tmp_name'][$i], $targetFile)) {
                    trigger_error('File not uploaded');
                    exit;
                }

                if (!isset($uploaded[$key])) {
                    $uploaded[$key] = array();
                }

                $uploaded[$key][] = $targetFile;
            }
        }

        return $uploaded;
    }

    /**
     * Get file upload fields in form
     * @param  int    $formId
     * @return array
     */
    public function getFileFields(int $formId) : array
    {
        $fields = get_fields($formId);
        $fields = $fields['form_fields'];
        $fileFields = array();

        foreach ($fields as $field) {
            if ($field['acf_fc_layout'] !== 'file_upload') {
                continue;
            }

            $fileFields[sanitize_title($field['label'])] = $field;
        }

        return $fileFields;
    }

    /**
     * Create upload folder if needed
     * @param  string $path
     * @return string
     */
    public function maybeCreateFolder(string $path) : string
    {
        if (file_exists($path)) {
            return $path;
        }

        mkdir($path, 0777, true);
        return $path;
    }

    /**
     * Notify users about new submission
     * @param  string $email
     * @param  int    $formId
     * @param  int    $submissionId
     * @return void
     */
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
