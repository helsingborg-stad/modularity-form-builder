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
        add_action('Municipio/blog/post_info', array($this, 'addEditButton'));
    }

    public function addEditButton()
    {
        global $post;

        if (PostType::editableFrontend($post)) {
            echo '<li><a href="#modal-edit-post" class="btn btn-sm"><i class="pricon pricon-pen"></i> ' . __('Edit', 'modularity-form-builder') . '</a></li>';
        }
    }

    /**
     * Add hidden field markup to form input fields
     * @param array $field Field being rendered
     * @return void
     */
    public function addHiddenFields($field) {
        if (get_post_type() != $this->postType || ($field['parent'] != 'field_58eb302883a68' && $field['parent'] != 'field_5a0abd4a4342a')) {
            return;
        }

        $val = is_string($field['value']) ? $field['value'] : '';
        echo '<input type="hidden" name="current-' . $field['name'] . '" value="' . $val . '">';
    }

    /**
     * When a form label is changed, we must update the keys in all form submissions.
     * @param  int  $postId The posts ID
     * @return void
     */
    public function updateFieldKeys($postId)
    {
        // Bail early if no ACF data
        if (empty($_POST['acf']) || get_post_type($postId) != $this->postType) {
            return;
        }

        // New and old field values
        $newValues      = $_POST['acf']['field_58eb302883a68'];
        $oldValues      = $_POST['current-acf']['field_58eb302883a68'];
        $defaultLabels  = PostType::getSenderLabels();
        $updatedValues  = array();

        // Gather updated field labels (used as keys)
        foreach ($newValues as $key => $fieldGroup) {
            if (is_array($fieldGroup) && !empty($fieldGroup)) {
                foreach ($fieldGroup as $fieldKey => $field) {
                    // Get ACF field object
                    $fieldObject = get_field_object($fieldKey);

                    // Loop through custom sender labels
                    if ($fieldKey == 'field_5a0abd4a4342a') {
                        foreach($field as $senderKey => $senderField) {
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
     * @param int   $moduleId     The forms Post ID
     * @param array $updatedKeys  Array containing old and new key values
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
                $metaVal = get_post_meta((int) $post['ID'], 'form-data', true);
                if (is_array($metaVal)) {

                    // Loop through address array
                    $addressKey = sanitize_title(__('Address', 'modularity-form-builder'));
                    if (!empty($metaVal[$addressKey])) {
                        foreach($updatedKeys as $changedVal) {
                            $metaVal[$addressKey] = $this->replaceKey($metaVal[$addressKey], $changedVal['old'], $changedVal['new']);
                        }
                    }

                    // Loop through updated keys and replace array with new values
                    foreach($updatedKeys as $changedVal) {
                        $metaVal = $this->replaceKey($metaVal, $changedVal['old'], $changedVal['new']);
                    }
                }
                // Update form field data with new keys
                update_post_meta((int) $post['ID'], 'form-data', $metaVal);
            }
        }
    }

    /**
     * Replaces keys in an arrays
     * @param array     $array     Defualt array
     * @param string    $oldKey    Key to replace
     * @param string    $newKey    Replacement key
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
     * @param array  $array Template paths
     * @return array        Modified template paths
     */
    public function addTemplatePaths($array)
    {
        $array[] = FORM_BUILDER_MODULE_PATH . 'source/php/Module/views';
        return $array;
    }
}
