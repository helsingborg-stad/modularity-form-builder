<?php

namespace ModularityFormBuilder;

class App
{
    public $postType = 'mod-form';

    public function __construct()
    {
        new PostType();
        new Submission();
        new Options();

        // Register Form module
        add_action('plugins_loaded', function () {
            if (function_exists('modularity_register_module')) {
                modularity_register_module(
                    FORM_BUILDER_MODULE_PATH . 'source/php/Module',
                    'Form'
                );
            }
        });

        add_action('acf/render_field', array($this, 'addHiddenFields'), 10, 1);
        add_action('acf/save_post', array($this, 'updateFieldKeys'), 9);

        add_filter('Municipio/blade/view_paths', array($this, 'addTemplatePaths'));

        add_action('wp_ajax_delete_file', array($this, 'deleteFile'));
        add_action('wp_ajax_upload_files', array($this, 'uploadFiles'));



        add_action('wp_ajax_save_post', array($this, 'frontEndSavePost'));
        //add_action('save_post', array($this, 'userRestriction'), 1);
        //add_filter('wp_insert_post_empty_content', array($this, 'userRestriction'), 999999, 2);
        //add_filter('acf/save_post', array($this, 'userRestriction'), 999999, 2);
        add_action('current_screen', array($this, 'restrictUserPages'));
    }


    /**
     * Add hidden field markup to form input fields
     * @param array $field Field being rendered
     * @return void
     */
    public function addHiddenFields($field)
    {
        if (get_post_type() != $this->postType || ($field['parent'] != 'field_58eb302883a68' && $field['parent'] != 'field_5a0abd4a4342a')) {
            return;
        }

        $val = is_string($field['value']) ? $field['value'] : '';
        echo '<input type="hidden" name="current-' . $field['name'] . '" value="' . $val . '">';
    }

    /**
     * When a form label is changed, we must update the keys in all form submissions.
     * @param  int $postId The posts ID
     * @return void
     */
    public function updateFieldKeys($postId)
    {
        // Bail early if no ACF data
        if (empty($_POST['acf']) || get_post_type($postId) != $this->postType) {
            return;
        }

        // New and old field values
        $newValues = $_POST['acf']['field_58eb302883a68'];
        $oldValues = $_POST['current-acf']['field_58eb302883a68'];
        $defaultLabels = PostType::getSenderLabels();
        $updatedValues = array();

        // Gather updated field labels (used as keys)
        foreach ($newValues as $key => $fieldGroup) {
            if (is_array($fieldGroup) && !empty($fieldGroup)) {
                foreach ($fieldGroup as $fieldKey => $field) {
                    // Get ACF field object
                    $fieldObject = get_field_object($fieldKey);

                    // Loop through custom sender labels
                    if ($fieldKey == 'field_5a0abd4a4342a') {
                        foreach ($field as $senderKey => $senderField) {
                            // Get ACF field object
                            $senderObject = get_field_object($senderKey);
                            $defaultLabel = isset($defaultLabels[$senderObject['_name']]) ? $defaultLabels[$senderObject['_name']] : null;
                            $oldVal = !empty($oldValues[$key][$fieldKey][$senderKey]) ? $oldValues[$key][$fieldKey][$senderKey] : $defaultLabel;
                            $newVal = !empty($senderField) ? $senderField : $defaultLabel;

                            if ($oldVal != $newVal) {
                                $updatedValues[] = array(
                                    'old' => sanitize_title($oldVal),
                                    'new' => sanitize_title($newVal)
                                );
                            }
                        }
                    }

                    // Loop through all other field labels
                    if ($fieldObject['_name'] == 'label' && !empty($oldValues[$key][$fieldKey]) && $newValues[$key][$fieldKey] != $oldValues[$key][$fieldKey]) {
                        $updatedValues[] = array(
                            'old' => sanitize_title($oldValues[$key][$fieldKey]),
                            'new' => sanitize_title($newValues[$key][$fieldKey])
                        );
                    }
                }
            }
        }

        if (!empty($updatedValues)) {
            $this->updateFormData($postId, $updatedValues);
        }
    }

    /**
     * Gets all posts connected to the form, and replaces the form data Keys
     * @param int $moduleId The forms Post ID
     * @param array $updatedKeys Array containing old and new key values
     * @return void
     */
    public function updateFormData($moduleId, $updatedKeys)
    {
        global $wpdb;

        // Get all post IDs submitted to the form
        $query = "
            SELECT $wpdb->posts.ID
            FROM $wpdb->posts, $wpdb->postmeta
            WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id
            AND $wpdb->postmeta.meta_key = 'modularity-form-id'
            AND $wpdb->postmeta.meta_value = $moduleId
        ";
        $posts = $wpdb->get_results($query, ARRAY_A);

        if (!empty($posts)) {
            foreach ($posts as $key => $post) {
                // Get current form data
                $metaVal = get_post_meta((int)$post['ID'], 'form-data', true);
                if (is_array($metaVal)) {

                    // Loop through address array
                    $addressKey = sanitize_title(__('Address', 'modularity-form-builder'));
                    if (!empty($metaVal[$addressKey])) {
                        foreach ($updatedKeys as $changedVal) {
                            $metaVal[$addressKey] = $this->replaceKey($metaVal[$addressKey], $changedVal['old'],
                                $changedVal['new']);
                        }
                    }

                    // Loop through updated keys and replace array with new values
                    foreach ($updatedKeys as $changedVal) {
                        $metaVal = $this->replaceKey($metaVal, $changedVal['old'], $changedVal['new']);
                    }
                }
                // Update form field data with new keys
                if (!get_option('options_mod_form_crypt')) {
                    update_post_meta((int)$post['ID'], 'form-data', $metaVal);
                } else {
                    update_post_meta((int)$post['ID'], 'form-data',
                        self::encryptDecryptData('encrypt', serialize($metaVal)));

                }

            }
        }
    }

    /**
     * Replaces keys in an arrays
     * @param array $array Defualt array
     * @param string $oldKey Key to replace
     * @param string $newKey Replacement key
     * @return array               Modified array
     */
    public function replaceKey($array, $oldKey, $newKey)
    {
        $keys = array_keys($array);
        if (false === $index = array_search($oldKey, $keys)) {
            error_log(sprintf('Key "%s" does not exist', $oldKey));
            // Return array if the key does'nt exist
            return $array;
        }
        $keys[$index] = $newKey;

        return array_combine($keys, array_values($array));
    }

    /**
     * Add searchable blade template paths
     * @param array $array Template paths
     * @return array        Modified template paths
     */
    public function addTemplatePaths($array)
    {
        $array[] = FORM_BUILDER_MODULE_PATH . 'source/php/Module/views';
        return $array;
    }

    /**
     * Delete a file (Ajax)
     * @return void
     */
    public function deleteFile()
    {
        if (!isset($_POST['postId']) || !isset($_POST['formId']) || !isset($_POST['filePath']) || !isset($_POST['fieldName'])) {
            echo _e('Missing arguments', 'modularity-form-builder');
            die();
        }

        $postId = $_POST['postId'];
        $filePath = $_POST['filePath'];
        $fieldName = $_POST['fieldName'];
        $formData = get_post_meta($postId, 'form-data', true);

        if (is_array($formData[$fieldName])) {
            foreach ($formData[$fieldName] as $key => $file) {
                if ($filePath == $file) {
                    unset($formData[$fieldName][$key]);

                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }
        }
        if (!get_option('options_mod_form_crypt')) {
            update_post_meta($postId, 'form-data', self::encryptDecryptData('encrypt', serialize($formData)));
        } else {
            update_post_meta($postId, 'form-data', $formData);
        }

        echo 'success';
        die();
    }

    /**
     * Upload files (Ajax)
     * @return void
     */
    public function uploadFiles()
    {
        if (!isset($_POST['postId']) || !isset($_POST['formId']) || !isset($_POST['fieldName'])) {
            wp_send_json_error(__('Missing arguments', 'modularity-form-builder'));
        }

        $postId = (int)$_POST['postId'];
        $formId = (int)$_POST['formId'];
        $fieldName = $_POST['fieldName'];
        $formData = get_post_meta($postId, 'form-data', true);

        if (!empty($_FILES)) {
            $files = Submission::uploadFiles($_FILES, $formId);

            // Return if upload failed
            if (isset($files['error'])) {
                wp_send_json_error(__('Something went wrong, please try again.', 'modularity-form-builder'));
            }

            // Save new file to array or marge with existing
            if (is_array($formData[$fieldName]) && !empty($formData[$fieldName])) {
                $formData[$fieldName] = array_merge($formData[$fieldName], $files[$fieldName]);
            } else {
                $formData[$fieldName] = $files[$fieldName];
            }

            if (!get_option('options_mod_form_crypt')) {
                update_post_meta($postId, 'form-data', $formData);
            } else {
                update_post_meta($postId, 'form-data', self::encryptDecryptData('encrypt', serialize($formData)));
            }


            wp_send_json_success(__('Upload succeeded', 'modularity-form-builder'));
        }

        wp_send_json_error(__('File was missing, please try again.', 'modularity-form-builder'));
    }

    public function frontEndSavePost()
    {
        if (empty($_POST['post_id']) || !isset($_POST['update-modularity-form']) || !wp_verify_nonce($_POST['update-modularity-form'],
                'update')) {
            wp_send_json_error(__('Something went wrong', 'modularity-form-builder'));
        }

        $postId = $_POST['post_id'];

        // Save form data
        if (!empty($_POST['mod-form'])) {
            $indata = get_post_meta($postId, 'form-data', true);
            $data = array_merge($indata, $_POST['mod-form']);
            if (!get_option('options_mod_form_crypt')) {
                update_post_meta($postId, 'form-data', $data);
            } else {
                update_post_meta($postId, 'form-data', self::encryptDecryptData('encrypt', serialize($data)));
            }

        }

        // Update post title and content
        $post = array('ID' => $postId);
        if (!empty($_POST['mod-form']['post-title'])) {
            $post['post_title'] = $_POST['mod-form']['post-title'];
        }
        if (!empty($_POST['mod-form']['post-content'])) {
            if (!get_option('options_mod_form_crypt')) {
                $post['post_content'] = $_POST['mod-form']['post-content'];
            } else {
                $post['post_content'] = self::encryptDecryptData('encrypt', $_POST['mod-form']['post-content']);
            }

        }
        wp_update_post($post);

        wp_send_json_success(__('Saved', 'modularity-form-builder'));
    }

    /**
     * Encrypt & decrypt data
     * @param $type string encrypt or decrypt
     * @param $str string data to encrypt or decrypt
     * @return string
     */
    static function encryptDecryptData($meth, $str)
    {
        if (defined('ENCRYPT_SECRET_VI') && defined('ENCRYPT_SECRET_KEY') && defined('ENCRYPT_METHOD')) {
            switch ($meth) {
                case 'encrypt':
                    return base64_encode(openssl_encrypt($str, ENCRYPT_METHOD, hash('sha256', ENCRYPT_SECRET_KEY), 0,
                        substr(hash('sha256', ENCRYPT_SECRET_VI), 0, 16)));
                    break;
                case 'decrypt':
                    return openssl_decrypt(base64_decode($str), ENCRYPT_METHOD, hash('sha256', ENCRYPT_SECRET_KEY), 0,
                        substr(hash('sha256', ENCRYPT_SECRET_VI), 0, 16));
                    break;
                default;
                    return $str;
            }

        } else {
            return $str;
        }
    }


    /**
     * Check if event admin have permission to edit user
     * @return void
     */
    public function restrictUserPages()
    {
        if (current_user_can('administrator')) {
            return;
        }

        $screen = get_current_screen();
        if ($screen->base == 'post' && !empty(get_current_user_id())) {
            $this->checkPermission();
        }
    }

    /**
     * checkPermission - Checks if user is author of the form or admin
     * @return false if the user is admin other wise show message and kills make the page disabled
     */
    public function checkPermission()
    {
        $userRestriction = get_field('user_restriction', isset($_GET['post']));
        if ($userRestriction) {
            if (current_user_can('administrator')) {
                return true;
            }
            if (get_current_user_id() !== get_post_field('post_author', $_GET['post'])) {
                wp_die(
                    '<h1>' . __('Hello, you are not Superman, with full access?') . '</h1>' .
                    '<p>' . __('Missing permissions') . '</p>',
                    403
                );
            }
        }
    }



}

