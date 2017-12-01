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

        add_action('acf/render_field', array($this, 'action_function_name'), 10, 1);
        add_action('acf/save_post', array($this, 'saveFormPost'), 1);

        add_filter('Municipio/blade/view_paths', array($this, 'addTemplatePaths'));
    }

    public function action_function_name($field) {
        if ($field['parent'] != 'field_58eb302883a68') {
            return;
        }

        $val = is_string($field['value']) ? $field['value'] : '';
        echo '<input type="hidden" name="current-' . $field['name'] . '" value="' . $val . '">';
    }

    public function saveFormPost($postId)
    {
        // Bail early if no ACF data
        if (empty($_POST['acf']) || get_post_type($postId) != $this->postType) {
            return;
        }

        // New and old field values
        $newValues = $_POST['acf']['field_58eb302883a68'];
        $oldValues = $_POST['current-acf']['field_58eb302883a68'];

        $updatedValues = array();
        foreach ($newValues as $key => $fieldGroup) {
            if (is_array($fieldGroup) && !empty($fieldGroup)) {
                foreach ($fieldGroup as $fieldKey => $field) {

                    $fieldObject = get_field_object($fieldKey);

                    if ($fieldObject['_name'] == 'label' && !empty($oldValues[$key][$fieldKey]) && $newValues[$key][$fieldKey] != $oldValues[$key][$fieldKey]) {
                        $updatedValues[] = array(
                            'old' => sanitize_title($oldValues[$key][$fieldKey]),
                            'new' => sanitize_title($newValues[$key][$fieldKey])
                        );
                    }
                }
            }
        }

        if ($updatedValues) {
            $this->updateFieldNames($postId, $updatedValues);
        }
    }

    /**
     * @param $moduleId
     * @param $updatedKeys
     */
    public function updateFieldNames($moduleId, $updatedKeys)
    {
        global $wpdb;

        // Get all post IDs connected to the form
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
                $metaVal = get_post_meta((int) $post['ID'], 'form-data', true);
                if (is_array($metaVal)) {
                    foreach($updatedKeys as $changedKey => $changedVal) {
                        $metaVal = $this->replaceKey($metaVal, $changedVal['old'], $changedVal['new']);
                    }
                }
                update_post_meta((int) $post['ID'], 'form-data', $metaVal);
            }
        }
    }

    public function replaceKey($array, $oldKey, $newKey)
    {
        $keys = array_keys($array);
        if (false === $index = array_search($oldKey, $keys)) {
            error_log(sprintf('Key "%s" does not exist', $oldKey));
            $array[$newKey] = null;
            return $array;
        }
        $keys[$index] = $newKey;

        return array_combine($keys, array_values($array));
    }

    /**
     * Add searchable blade template paths
     * @param array $array Template paths
     * @return array       Modified template paths
     */
    public function addTemplatePaths($array)
    {
        $array[] = FORM_BUILDER_MODULE_PATH . 'source/php/Module/views';
        return $array;
    }
}
