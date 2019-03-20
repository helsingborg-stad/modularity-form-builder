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
        }, 13);

        //Force download of encrypted files
        add_action('init', array($this, 'forceEcryptedPublicFileDownload')); 
    }

    /**
     * Handle form submission
     * @return void
     */
    public function submit()
    {
        if (class_exists('\Municipio\Helper\ReCaptcha')) {
            if (defined('G_RECAPTCHA_KEY') && defined('G_RECAPTCHA_SECRET')) {
                if (!is_user_logged_in()) {
                    $response = (isset($_POST['g-recaptcha-response']) && strlen($_POST['g-recaptcha-response']) > 0) ? $_POST['g-recaptcha-response'] : null;
                    $reCaptcha = \Municipio\Helper\ReCaptcha::controlReCaptcha($response);
                    if (!$reCaptcha) {
                        echo 'false';
                        wp_die();
                    }
                }
            }
        }

        unset($_POST['modularity-form']);
        $referer = esc_url(remove_query_arg('form', $_POST['_wp_http_referer']));
        unset($_POST['_wp_http_referer']);

        //Check if should create a sender copy
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
            $files = self::uploadFiles($_FILES, $_POST['modularity-form-id']);
            
            // Return to form if upload failed
            if (isset($files['error'])) {
                if (strpos($referer, '?') > -1) {
                    $referer .= '&form=failed';
                } else {
                    $referer .= '?form=failed';
                }
                wp_redirect($referer);
                exit;
            }
        }
        $_POST = array_merge($_POST, $files);
        
        // Set post title, content, form page and referer
        $postTitle      = !empty($_POST['post_title']) && !empty($_POST[$_POST['post_title']]) ? $_POST[$_POST['post_title']] : get_the_title($_POST['modularity-form-id']);
        $postContent    = !empty($_POST['post_content']) && !empty($_POST[$_POST['post_content']]) ? $_POST[$_POST['post_content']] : '';
        
        // Referer & page url
        $postReferer = esc_url($_POST['modularity-form-history']);
        $postFormPage = esc_url($_POST['modularity-form-url']);
        $checkReferer = url_to_postid($postReferer);
        $checkFormPage = url_to_postid($postFormPage);
        $dbStorage = sanitize_title($_POST['modularity-gdpr-data']);

        if (empty($postReferer) || $checkReferer !== 0 && $checkFormPage !== 0) {
            $formUrl = "\r\n " . __('Form',
                    'modularity-form-builder') . "<a href=\"" . $postFormPage . "\" >" . $postFormPage . "</a>";
            $refHistory = ($postReferer !== null && $postReferer !== 'null') ? "\r\n" . __('Previous page',
                    'modularity-form-builder') . "<a href=\"" . $postReferer . "\" >" . $postReferer . "</a>" : '';
        }

        // Save submission
        $submission = wp_insert_post(array(
            'post_title' => $postTitle,
            'post_content' => $postContent,
            'post_type' => $_POST['modularity-form-post-type'],
            'post_status' => 'publish'
        ));

        //Encrypt form meta
        if (!get_option('options_mod_form_crypt')) {
            update_post_meta($submission, 'form-data', $_POST);
        } else {
            update_post_meta($submission, 'form-data', \ModularityFormBuilder\App::encryptDecryptData('encrypt', $_POST));
        }

        update_post_meta($submission, 'modularity-form-id', $_POST['modularity-form-id']);
        update_post_meta($submission, 'modularity-form-referer', strtok($referer, '?'));

        //The form url
        if (isset($formUrl)) {
            update_post_meta($submission, 'modularity-form-url', $formUrl);
        }

        //Reference url
        if (isset($refHistory)) {
            update_post_meta($submission, 'modularity-form-history', $refHistory);
        }

        // Get emails to send notification to
        $notify = get_field('notify', $_POST['modularity-form-id']);
        // Get correct labels
        $labels = Helper\SenderLabels::getLabels();
        $fields = get_fields($_POST['modularity-form-id']);
        $fields = $fields['form_fields'];
        foreach ($fields as $key => $field) {
            if ($field['acf_fc_layout'] == 'sender') {
                if (!empty($field['custom_sender_labels']['add_sender_labels'])) {
                    $labels = array_merge($labels, array_filter($field['custom_sender_labels']));
                }
            }
        }
        // Get sender data
        $fromEmail = !empty($_POST[sanitize_title($labels['email'])]) ? $_POST[sanitize_title($labels['email'])] : null;
        $fromFirstName = !empty($_POST[sanitize_title($labels['firstname'])]) ? $_POST[sanitize_title($labels['firstname'])] : null;
        $fromLastName = !empty($_POST[sanitize_title($labels['lastname'])]) ? $_POST[sanitize_title($labels['lastname'])] : null;
        $from = ($fromEmail || $fromFirstName || $fromLastName) ? $fromFirstName . ' ' . $fromLastName . ' <' . $fromEmail . '>' : $fromEmail;
        if (!$fromEmail) {
            $from = ($fromEmail || $fromFirstName || $fromLastName) ? $fromFirstName . ' ' . $fromLastName . ' <' . 'no-reply@' . preg_replace('/www\./i',
                    '', $_SERVER['SERVER_NAME']) . '>' : 'no-reply@' . preg_replace('/www\./i', '',
                    $_SERVER['SERVER_NAME']);
        }
        // Send notifications
        if ($notify) {
            foreach ($notify as $email) {
                $sendMail = true;
                if ($email['condition']) {
                    $conditionalFieldKey = sanitize_title($email['form_conditional_field']);
                    $sendMail = false;
                    if (array_key_exists($conditionalFieldKey, $_POST)) {
                        if ($_POST[$conditionalFieldKey] == $email['form_conditional_field_equals']) {
                            $sendMail = true;
                        }
                    }
                }
                if ($sendMail) {
                    $this->notify($email['email'], $_POST['modularity-form-id'], $submission, $from);
                }
            }
        }
        // Send user copy
        if ($senderCopy && $fromEmail) {
            $this->sendCopy($fromEmail, $_POST['modularity-form-id'], $submission, $from);
        }
        // Send auto reply
        if (get_field('autoreply', $_POST['modularity-form-id'])) {
            $this->autoreply($fromEmail, $submission, $fromEmail);
        }
        $referer = parse_url($referer, PHP_URL_PATH);
        // Redirect
        if (strpos($referer, '?') > -1) {
            $referer .= '&form=success';
        } else {
            $referer .= '?form=success';
        }
        if (empty($postReferer) || $checkReferer !== 0 || $checkReferer !== null) {
            $referer .= '&modularityReferrer=' . urlencode($postReferer);
        }
        if (empty($postFormPage) || $postFormPage !== 0 || $postFormPage !== null) {
            $referer .= '&modularityForm=' . urlencode($postFormPage);
        }

        if ($dbStorage === "1") {
            wp_delete_post($submission, true);
        }

        wp_redirect($referer);
        exit;
    }

    /**
     * Upload files
     * @param  array $fileslist
     * @param  int $formId
     * @return array
     */
    public static function uploadFiles($fileslist, $formId)
    {

        //Create & get uploads folder 
        $uploadsFolder = wp_upload_dir();
        $uploadsFolder = $uploadsFolder['basedir'] . '/modularity-form-builder';
        self::maybeCreateFolder($uploadsFolder);

        //Get fields for file
        $fields = self::getFileFields($formId);

        //Declation of allowed filetypes. 
        $allowedImageTypes = array('.jpeg', '.jpg', '.png', '.gif', '.svg');
        $allowedVideoTypes = array('.mov', '.mpeg4', '.mp4', '.avi', '.wmv', '.mpegps', '.flv', '.3gpp', '.webm');
        
        // Data to be returned
        $uploaded = array();
        foreach ($fileslist as $key => $files) {
            for ($i = 0; $i < count($files['name']); $i++) {
                if (empty($files['name'][$i])) {
                    continue;
                }

                //Get file details 
                $fileName = pathinfo($files['name'][$i], PATHINFO_FILENAME);
                $fileext = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
                
                //Validate that image is in correct format
                if (in_array('image/*', $fields[$key]['filetypes'])) {
                    $fields[$key]['filetypes'] = array_unique(array_merge($fields[$key]['filetypes'], $allowedImageTypes));
                }

                //Validate that video is in correct format 
                if (in_array('video/*', $fields[$key]['filetypes'])) {
                    $fields[$key]['filetypes'] = array_unique(array_merge($fields[$key]['filetypes'], $allowedVideoTypes));
                }

                //Not a valid filetype at all
                if (!in_array('.' . $fileext, $fields[$key]['filetypes'])) {
                    error_log('Filetype not allowed');
                    $uploaded['error'] = true;
                    continue;
                }

                //Encrypt file if encryption is enabled
                if (get_option('options_mod_form_crypt') && empty($fields[$key]['upload_videos_external'])) {

                    if (defined('ENCRYPT_SECRET_VI') && defined('ENCRYPT_SECRET_KEY') && defined('ENCRYPT_METHOD')) {

                        $encrypted = file_put_contents(
                            $files['tmp_name'][$i],
                            \ModularityFormBuilder\App::encryptDecryptFile(
                                'encrypt', 
                                file_get_contents($files['tmp_name'][$i])
                            )
                        ); 

                        if ($encrypted !== false) {
                            $targetFile = $uploadsFolder . '/' . uniqid() . '-' . sanitize_file_name($fileName . "-enc-" . ENCRYPT_METHOD) . '.' . $fileext;
                        }

                    }

                } else {
                    $targetFile = $uploadsFolder . '/' . uniqid() . '-' . sanitize_file_name($fileName) . '.' . $fileext;
                }

                // Upload the file to server
                if (move_uploaded_file($files['tmp_name'][$i], $targetFile)) {
                    
                    // Upload video to YouTube
                    if (!empty($fields[$key]['upload_videos_external']) && in_array('.' . $fileext, $allowedVideoTypes)) {
                        $uploadVideo = \ModularityFormBuilder\Helper\YoutubeUploader::uploadVideo($targetFile, ucwords($fileName), '', '22');
                        $targetFile  = ($uploadVideo) ? $uploadVideo : $targetFile;
                    }

                } else {
                    error_log('File not uploaded');
                    $uploaded['error'] = true;
                    continue;
                }
                
                if (!isset($uploaded[$key])) {
                    $uploaded[$key] = array();
                }
                $uploaded[$key][] = $targetFile;

                error_log($targetFile); 
            }
        }

        return $uploaded;
    }

    /**
     * Get file upload fields in form
     * @param  int $formId
     * @return array
     */
    public static function getFileFields(int $formId): array
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
    public static function maybeCreateFolder(string $path): string
    {
        if (file_exists($path)) {
            return $path;
        }
        mkdir($path, 0777, true);
        return $path;
    }

    /**
     * Get the download link to the file
     * @return string
     */
    public function getMailDownloadLink($filePath) {
        
        //Check if encrypted
        if (strpos($filePath, sanitize_file_name("-enc-" . ENCRYPT_METHOD)) !== false) {
            return home_url("/") . '?modFormDownloadEncFilePublic=' . urlencode(basename($filePath)) . '&token=' . md5(urlencode(basename($filePath)) . NONCE_KEY);
        }

        return $filePath; 
    }

    public function forceEcryptedPublicFileDownload() {

        if(isset($_GET['modFormDownloadEncFilePublic']) && isset($_GET['token'])) {

            //Check if hash is correct
            if($_GET['token'] !== md5($_GET['modFormDownloadEncFilePublic'] . NONCE_KEY)) {
                wp_die(
                    __("The download token provided was not correct.", 'modularity-form-builder'),
                    __("Unauthorized request", 'modularity-form-builder')
                );
            }

            //Get uploads folder
            $uploadsFolder = wp_upload_dir();
            $uploadsFolder = $uploadsFolder['basedir'] . '/modularity-form-builder/';

            //Get local path to file 
            $filePath = $uploadsFolder . urldecode($_GET['modFormDownloadEncFilePublic']); 

            //Decrypt and return
            if(file_exists($filePath)) {
                if (defined('ENCRYPT_SECRET_VI') && defined('ENCRYPT_SECRET_KEY') && defined('ENCRYPT_METHOD')) {
                    $fileContents = \ModularityFormBuilder\App::encryptDecryptFile(
                        'decrypt', 
                        file_get_contents($filePath)
                    );
                }
            }

            //Return file force download
            if(isset($fileContents) && !empty($fileContents)) {
                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Cache-Control: private", false);
                header("Content-Type: application/octet-stream");
                header("Content-Disposition: attachment; filename=\"" . $_GET['modFormDownloadEncFilePublic'] . "\";" );
                header("Content-Transfer-Encoding: binary");

                echo $fileContents;

                exit;
            }

            //No file found
            wp_die(
                __("The file you requested could not be found. The file might have been deleted or corrupted.", 'modularity-form-builder'),
                __("File not found", 'modularity-form-builder')
            );
            
        }
    }

    /**
     * Get data of a submission
     * @param  int $submissionId
     * @return array
     */
    public static function getSubmissionData($submissionId): array
    {
        $submissionId = (int)$submissionId;
        $formId = get_post_meta($submissionId, 'modularity-form-id', true);
        if (!$formId) {
            return array();
        }
        $fields = get_fields($formId);
        $fields = $fields['form_fields'];
        if (get_option('options_mod_form_crypt')) {
            $data = unserialize(\ModularityFormBuilder\App::encryptDecryptData('decrypt',
                get_post_meta($submissionId, 'form-data', true)));
        } else {
            $data = get_post_meta($submissionId, 'form-data', true);
        }
        $formdata = array();
        $labels = Helper\SenderLabels::getLabels();
        $excludedFields = array(
            'custom_content',
            'collapse'
        );
        foreach ($fields as $field) {
            if ($field['acf_fc_layout'] === 'sender') {
                // Merge default and custom labels
                if (!empty($field['custom_sender_labels']['add_sender_labels'])) {
                    $labels = array_merge($labels, array_filter($field['custom_sender_labels']));
                }
                foreach ($field['fields'] as $subfield) {
                    $formdata[$labels[$subfield]] = $data[sanitize_title($labels[$subfield])] ?? '';
                }
            } elseif (in_array($field['acf_fc_layout'], $excludedFields)) {
                continue;
            } else {
                $formdata[$field['label']] = $data[sanitize_title($field['label'])] ?? '';
            }
        }
        $formdata['modularity-form-history'] = $data['modularity-form-history'] ?? '';
        $formdata['modularity-form-url'] = $data['modularity-form-url'] ?? '';
        return $formdata;
    }

    /**
     * Notify users about new submission
     * @param  string $email
     * @param  int $formId
     * @param  int $submissionId
     * @return void
     */
    public function notify($email, $formId, $submissionId, $from = null)
    {
        $headers = array('Content-Type: text/html; charset=UTF-8');
        if (!is_null($from) && !empty($from)) {
            $headers[] = 'From: ' . $from;
        }
        $data = self::getSubmissionData($submissionId);
        $showData = get_field('submission_notice_data', $formId);
        $messagePrefix = get_field('notification_message', $formId);
        $subject = (get_field('notification_custom_subject', $formId) == true) ? get_field('notification_subject',
            $formId) : __('New form submission', 'modularity-form-builder');
        $uploadFolder = wp_upload_dir();
        $uploadFolder = $uploadFolder['baseurl'] . '/modularity-form-builder/';
        $message = sprintf(
            __('This is a notification about a new form submission to the form "%s".<br><br><a href="%s">Read the full submission here</a>.',
                'modularity-form-builder'),
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
                if (is_array($value) && !empty($value)) {
                    $message .= '<strong>' . $key . '</strong><br>';
                    $last = end($value);
                    foreach ($value as $subvalue) {
                        $lineBreak = ($subvalue == $last) ? '' : '<br>';
                        if (strpos($subvalue, '/modularity-form-builder/') !== false) {
                            $message .= __('Open file', 'modularity-form-builder') . ': <a target="_blank" href="' . $this->getMailDownloadLink($uploadFolder . basename($subvalue)) . '">' . basename($subvalue) . '</a>' . $lineBreak;
                        } else {
                            $message .= (!empty($subvalue)) ? $subvalue . $lineBreak : '';
                        }
                    }
                } else {
                    if ($key === 'modularity-form-history') {
                        $message .= ($value !== null && $value !== 'null') ? '<strong>' . __('Referrer',
                                'modularity-form-builder') . '</strong><br>' . $value : __('No Referrer',
                            'modularity-form-builder');
                    } else {
                        if ($key === 'modularity-form-url') {
                            $message .= '<strong>' . __('Form', 'modularity-form-builder') . '</strong><br>' . $value;
                        } else {
                            $message .= '<strong>' . $key . '</strong><br>' . $value;
                        }
                    }
                }
                $i++;
            }
        }
        if ($messagePrefix) {
            $message = $messagePrefix . '<br><br>' . $message;
        }
        $subject = apply_filters('ModularityFormBuilder/notice/subject', $subject, $email, $formId, $submissionId,
            $showData, $data);
        $message = apply_filters('ModularityFormBuilder/notice/message', $message, $email, $formId, $submissionId,
            $showData, $data);
        if (!$message) {
            $message = $_POST['meddelande'];
        }
        if (!wp_mail($email, $subject, $message, $headers)) {
            error_log("Could not send notification.");
        }
    }

    /**
     * Send submission data copy to sender email
     * @param  string $email Email to send to
     * @param  int $formId Form id
     * @param  int $submissionId Submission id
     * @return void
     */
    public function sendCopy($email, $formId, $submissionId, $from = null)
    {
        $headers = array('Content-Type: text/html; charset=UTF-8');
        if (!is_null($from) && !empty($from)) {
            $headers[] = 'From: ' . $from;
        }
        $data = self::getSubmissionData($submissionId);
        $message = '';
        $subject = (get_field('copy_custom_subject', $formId) == true) ? get_field('copy_subject',
            $formId) : __('Form submission copy', 'modularity-form-builder');
        $uploadFolder = wp_upload_dir();
        $uploadFolder = $uploadFolder['baseurl'] . '/modularity-form-builder/';
        $i = 0;
        foreach ($data as $key => $value) {
            if ($i > 0) {
                $message .= '<br><br>';
            }
            if (is_array($value) && !empty($value)) {
                $message .= '<strong>' . $key . '</strong><br>';
                $last = end($value);
                foreach ($value as $subvalue) {
                    $lineBreak = ($subvalue == $last) ? '' : '<br>';
                    if (strpos($subvalue, '/modularity-form-builder/') !== false) {
                        $message .= __('Open file', 'modularity-form-builder') . ': <a target="_blank" href="' . $this->getMailDownloadLink($uploadFolder . basename($subvalue)) . '">' . basename($subvalue) . '</a>' . $lineBreak;
                    } else {
                        $message .= (!empty($subvalue)) ? $subvalue . $lineBreak : '';
                    }
                }
            } else {
                $message .= '<strong>' . $key . '</strong><br>' . $value;
            }
            $i++;
        }
        if ($prefix = get_field('sender_copy_message', $formId)) {
            $message = $prefix . '<br><br>' . $message;
        }
        $subject = apply_filters('ModularityFormBuilder/sender_copy/subject', $subject, $email, $formId, $submissionId,
            $data);
        $message = apply_filters('ModularityFormBuilder/sender_copy/message', $message, $email, $formId, $submissionId,
            $data);
        if (!wp_mail($email, $subject, $message, $headers)) {
            error_log("Could not send mail copy.");
        }
    }

    /**
     * Send autoreply to sender
     * @param  string $email
     * @param  int $submissionId
     * @return void
     */
    public function autoreply($email, $submissionId, $from = null)
    {
        $headers = array('Content-Type: text/html; charset=UTF-8');
        if (!is_null($from) && !empty($from)) {
            $headers[] = 'From: ' . $from;
        }
        $formId = get_post_meta($submissionId, 'modularity-form-id', true);
        $subject = apply_filters('ModularityFormBuilder/autoreply/subject', get_field('auto_reply_subject', $formId),
            $email, $submissionId);
        $message = apply_filters('ModularityFormBuilder/autoreply/message', get_field('auto_reply_content', $formId),
            $email, $submissionId);
        if (!wp_mail($email, $subject, $message, $headers)) {
            error_log("Could not send autoreply to sender.");
        }
    }
}
