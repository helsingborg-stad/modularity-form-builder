<?php

namespace ModularityFormBuilder;

class App
{
    public $postType = 'mod-form';

    public function __construct()
    {
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
        add_action('init', array($this, 'registerPostTypes'), 11);
        add_action('acf/render_field', array($this, 'addHiddenFields'), 10, 1);
        add_action('acf/save_post', array($this, 'updateFieldKeys'), 9);
        add_action('acf/update_value/name=granted_users', array($this, 'updateGrantedUser'), 10, 3);
        add_action('wp_ajax_delete_file', array($this, 'deleteFile'));
        add_action('wp_ajax_upload_files', array($this, 'uploadFiles'));
        add_action('wp_ajax_save_post', array($this, 'frontEndSavePost'));
        add_action('current_screen', array($this, 'restrictUserPages'));
        add_action('restrict_manage_posts', array($this, 'formFilter'));
        add_action('admin_head', array($this, 'jsonSelectedValues'));

        add_filter('Municipio/blade/view_paths', array($this, 'addTemplatePaths'));
    }

    /**
     * Register post types
     */
    public function registerPostTypes()
    {
        // Default form submission post type
        new Entity\PostType(
            'form-submissions',
            __('Form submission', 'modularity-form-builder'),
            __('Form submissions', 'modularity-form-builder')
        );

        global $wpdb;
        $postTypes = $wpdb->get_col(
            "
            SELECT pm1.meta_value FROM $wpdb->posts as p
            LEFT JOIN $wpdb->postmeta pm1 ON p.ID = pm1.post_id
            LEFT JOIN $wpdb->postmeta pm2 ON p.ID = pm2.post_id
            WHERE p.post_status = 'publish'
            AND pm1.meta_key = 'submission_post_type'
            AND pm2.meta_key = 'custom_submission_post_type' && pm2.meta_value = 1
            "
        );

        // Re-register custom form post types
        if (!empty($postTypes)) {
            foreach ($postTypes as $postType) {
                if (!$postTypeObj = get_post_type_object($postType)) {
                    continue;
                }
                unregister_post_type($postType);

                $json = json_encode($postTypeObj);
                $postTypeArr = json_decode($json, true);

                unset($postTypeArr['show_in_rest']);
                unset($postTypeArr['capabilities']);
                unset($postTypeArr['capability_type']);
                unset($postTypeArr['cap']);

                new Entity\PostType(
                    $postTypeArr['name'],
                    $postTypeArr['labels']['singular_name'] ?? $postTypeArr['label'],
                    $postTypeArr['label'],
                    $postTypeArr
                );
            }
        }
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
        $defaultLabels = Helper\SenderLabels::getLabels();
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
     * @param int   $moduleId    The forms Post ID
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
                $metaVal = $this->getDataAsArray($metaVal);

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
                    update_post_meta((int)$post['ID'], 'form-data', self::encryptDecryptData('encrypt', $metaVal));
                }
            }
        }
    }

    /**
     * Decrypt recursively until data is an array or limit is reached
     * @param array|string $data  Data to be returned as array
     * @param int          $limit Limits the recursion
     * @return mixed
     */
    public function getDataAsArray($data, $limit = 0)
    {
        if (is_array($data) || $limit >= 10) {
            return $data;
        } else {
            $data = maybe_unserialize(self::encryptDecryptData('decrypt', $data));
            $limit++;
            $data = $this->getDataAsArray($data, $limit);

            return $data;
        }
    }

    /**
     * Replaces keys in an arrays
     * @param array  $array  Defualt array
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
            update_post_meta($postId, 'form-data', $formData);
        } else {
            update_post_meta($postId, 'form-data', self::encryptDecryptData('encrypt', $formData));
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
                update_post_meta($postId, 'form-data', self::encryptDecryptData('encrypt', $formData));
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
                update_post_meta($postId, 'form-data', self::encryptDecryptData('encrypt', $data));
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
     * @param $method  string encrypt or decrypt
     * @param $data    mixed data to encrypt or decrypt
     * @return string
     */
    static function encryptDecryptData($method, $data)
    {
        if (defined('ENCRYPT_SECRET_VI') && defined('ENCRYPT_SECRET_KEY') && defined('ENCRYPT_METHOD')) {
            switch ($method) {
                case 'encrypt':
                    $data = is_array($data) ? serialize($data) : $data;
                    return base64_encode(openssl_encrypt(json_encode($data), ENCRYPT_METHOD,
                        hash('sha256', ENCRYPT_SECRET_KEY), 0,
                        substr(hash('sha256', ENCRYPT_SECRET_VI), 0, 16)));
                    break;
                case 'decrypt':
                    return json_decode(openssl_decrypt(base64_decode($data), ENCRYPT_METHOD,
                        hash('sha256', ENCRYPT_SECRET_KEY), 0,
                        substr(hash('sha256', ENCRYPT_SECRET_VI), 0, 16)));
                    break;
                default;
                    return $data;
            }
        } else {
            return $data;
        }
    }

    /**
     * Encrypt & decrypt data
     * @param $method  string encrypt or decrypt
     * @param $data    mixed data to encrypt or decrypt
     * @return string
     */
    static function encryptDecryptFile($method, $data)
    {
        if (defined('ENCRYPT_SECRET_VI') && defined('ENCRYPT_SECRET_KEY') && defined('ENCRYPT_METHOD')) {
            switch ($method) {
                case 'encrypt':
                    return openssl_encrypt(
                        $data,
                        ENCRYPT_METHOD,
                        hash('sha256', ENCRYPT_SECRET_KEY),
                        0,
                        substr(hash('sha256', ENCRYPT_SECRET_VI), 0, 16)
                    );
                    break;
                case 'decrypt':
                    return openssl_decrypt(
                        $data,
                        ENCRYPT_METHOD,
                        hash('sha256', ENCRYPT_SECRET_KEY),
                        0,
                        substr(hash('sha256', ENCRYPT_SECRET_VI), 0, 16)
                    );
                    break;
                default;
                    return $data;
            }
        } else {
            return $data;
        }
    }

    /**
     * Update granted user field with author id if author forget to add him/her self as granted user
     */
    public function updateGrantedUser($value, $post_id, $field)
    {
        if (is_array($value) && !in_array(get_current_user_id(), $value)) {
            $value[] = get_current_user_id();
        }
        return $value;
    }


    /**
     * Check if user have permission to edit or view form
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
     * @return true if the user is admin otherwise show message and disable page
     */
    public function checkPermission()
    {

        if (isset($_GET['post'])) {
            $userRestriction = get_field('user_restriction', $_GET['post']);

            if ($userRestriction) {
                if (current_user_can('administrator')) {
                    return true;
                }
                if (get_current_user_id() !== get_post_field('post_author', $_GET['post'])) {
                    $grantedUsers = get_field('granted_users', $_GET['post']);
                    $granted = false;
                    if (isset($grantedUsers) && !empty($grantedUsers)) {
                        foreach ($grantedUsers as $user) {
                            if ($user['ID'] === get_current_user_id()) {
                                $granted = true;
                            }
                        }
                    }
                    if ($granted === false) {
                        wp_die(
                            '<h1>' . __('Hello, you do not have permission to edit this form') . '</h1>' .
                            '<p>' . __('Please ask the creator/author of the form to grant you access.') . '</p>',
                            403
                        );
                    }
                }
            }
        }
        return false;
    }

    /**
     * Filters admin list table
     * @return void
     */
    public function formFilter()
    {
        global $typenow;

        if ($typenow !== 'form-submissions') {
            return;
        }

        $forms = get_posts(array(
            'post_type' => 'mod-form',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'numberposts' => -1
        ));

        echo '<select name="form"><option value="-1">' . __('Select form…', 'modularity-form-builder') . '</option>';

        foreach ($forms as $form) {
            $selected = isset($_GET['form']) && $_GET['form'] == $form->ID ? 'selected' : '';
            echo '<option value="' . $form->ID . '" ' . $selected . '>' . $form->post_title . '</option>';
        }
        echo '</select>';
    }

    public function jsonSelectedValues()
    {
        //Get saved data
        $fieldData = get_field('notify');

        //Declare result
        $result = array();

        //Fill result array
        if (isset($fieldData) && is_array($fieldData) && !empty($fieldData)) {
            foreach ($fieldData as $field) {
                $result[] = array(
                    'conditional_field' => $field['form_conditional_field'],
                    'conditional_field_equals' => $field['form_conditional_field_equals']
                );
            }
        }

        //Print json array
        if (is_array($result) && !empty($result)) {
            echo "<script> var notificationConditions = '" . json_encode($result) . "'; </script>";
        }
    }
}

