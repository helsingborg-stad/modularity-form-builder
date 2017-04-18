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

    /**
     * Handle form submission
     * @return void
     */
    public function submit()
    {
        unset($_POST['modularity-form']);

        $referer = $_POST['_wp_http_referer'];
        unset($_POST['_wp_http_referer']);

        $senderCopy = false;
        if (isset($_POST['sender-copy'])) {
            if ($_POST['sender-copy'] === 'on') {
                $senderCopy = true;
            }

            unset($_POST['sender-copy']);
        }

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

        // Get emails to send notification to
        $notify = get_field('notify', $_POST['modularity-form-id']);

        // Get from email
        $from = null;
        if (isset($_POST['email']) && !empty($_POST['email'])) {
            $from = $_POST['email'];
        }

        // Send notifications
        foreach ($notify as $email) {
            $this->notify($email['email'], $_POST['modularity-form-id'], $submission, $from);
        }

        // Send user copy
        if ($senderCopy && isset($_POST['email'])) {
            $this->sendCopy($_POST['email'], $_POST['modularity-form-id'], $submission);
        }

        if (get_field('autoreply', $_POST['modularity-form-id'])) {
            $this->autoreply($_POST['email'], $submission);
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
                if (empty($files['name'][$i])) {
                    continue;
                }

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
     * Get data of a submission
     * @param  int    $submissionId
     * @return array
     */
    public static function getSubmissionData(int $submissionId) : array
    {
        $formId = get_post_meta($submissionId, 'modularity-form-id', true);

        if (!$formId) {
            return array();
        }

        $fields = get_fields($formId);
        $fields = $fields['form_fields'];
        $data = get_post_meta($submissionId, 'form-data', true);

        $formdata = array();

        foreach ($fields as $field) {
            if ($field['acf_fc_layout'] === 'sender') {
                foreach ($field['fields'] as $subfield) {
                    $label = \ModularityFormBuilder\PostType::getTranslatedSenderField($subfield);
                    $formdata[$label] = $data[$subfield];
                }
            } else {
                $formdata[$field['label']] = isset($data[sanitize_title($field['label'])]) ? $data[sanitize_title($field['label'])] : '';
            }
        }

        return $formdata;
    }

    /**
     * Notify users about new submission
     * @param  string $email
     * @param  int    $formId
     * @param  int    $submissionId
     * @return void
     */
    public function notify($email, $formId, $submissionId, $from = null)
    {
        $headers = array('Content-Type: text/html; charset=UTF-8');

        if ($from) {
            $headers[] = 'From:' . $from;
        }

        $data = self::getSubmissionData($submissionId);
        $showData = get_field('submission_notice_content', $formId);

        $message = sprintf(
            __('Hi, this is a notification about a new form submission to the form "%s".<br><br><a href="%s">Read the full submission here</a>.', 'modularity-form-builder'),
            get_the_title($formId),
            get_edit_post_link($submissionId)
        );

        if ($showData) {
            $message = '';

            $i = 0;
            foreach ($data as $key => $value) {
                if ($i > 0) {
                    $message .= '<br><br>';
                }

                $message .= '<strong>' . $key . '</strong><br>' . $value;

                $i++;
            }
        }

        $subject = apply_filters('ModularityFormBuilder/notice/subject', __('New form submission', 'modularity-form-builder'), $email, $formId, $submissionId, $showData, $data);
        $message = apply_filters('ModularityFormBuilder/notice/message', $message, $email, $formId, $submissionId, $showData, $data);

        wp_mail(
            $email,
            $subject,
            $message,
            $headers
        );
    }

    /**
     * Send submission data copy to sender email
     * @param  string $email        Email to send to
     * @param  int    $formId       Form id
     * @param  int    $submissionId Submission id
     * @return void
     */
    public function sendCopy($email, $formId, $submissionId)
    {
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $data = self::getSubmissionData($submissionId);
        $message = '';

        $i = 0;
        foreach ($data as $key => $value) {
            if ($i > 0) {
                $message .= '<br><br>';
            }

            $message .= '<strong>' . $key . '</strong><br>' . $value;

            $i++;
        }

        $subject = apply_filters('ModularityFormBuilder/sender_copy/subject', __('Form submission copy', 'modularity-form-builder'), $email, $formId, $submissionId, $showData, $data);
        $message = apply_filters('ModularityFormBuilder/sender_copy/message', $message, $email, $formId, $submissionId, $showData, $data);

        wp_mail(
            $email,
            $subject,
            $message,
            $headers
        );
    }

    /**
     * Send autoreply to sender
     * @param  string $email
     * @param  int    $submissionId
     * @return void
     */
    public function autoreply($email, $submissionId)
    {
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $formId = get_post_meta($submissionId, 'modularity-form-id', true);

        $subject = apply_filters('ModularityFormBuilder/autoreply/subject', get_field('auto_reply_subject', $formId), $email, $submissionId);
        $message = apply_filters('ModularityFormBuilder/autoreply/message', get_field('auto_reply_content', $formId), $email, $submissionId);

        wp_mail(
            $email,
            $subject,
            $message,
            $headers
        );
    }
}
