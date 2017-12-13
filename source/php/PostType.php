<?php

namespace ModularityFormBuilder;

class PostType
{
    public $postTypeSlug = 'form-submissions';

    public function __construct()
    {
        add_action('init', array($this, 'register'));
        add_action('admin_menu', array($this, 'removePublishBox'));
        add_action('add_meta_boxes', array($this, 'formdata'), 10, 2);
        add_action('restrict_manage_posts', array($this, 'formFilter'));
        add_action('edit_form_after_title', array($this, 'displayFeedbackId'), 10, 1);
        add_action('wp_enqueue_scripts', array($this, 'enqueueScript'));

        add_action('pre_get_posts', array($this, 'queryFilter'));

        add_filter('the_content', array($this, 'appendFormdata'));
        add_filter('manage_edit-' . $this->postTypeSlug . '_columns', array($this, 'tableColumns'));
        add_action('manage_' . $this->postTypeSlug . '_posts_custom_column', array($this, 'tableColumnsContent'), 10, 2);
        add_filter('manage_edit-' . $this->postTypeSlug . '_sortable_columns', array($this, 'listColumnsSorting'));

        add_filter('acf/load_field/name=submission_post_type', array($this, 'submissionPostTypes'));

        add_action('save_post_' . $this->postTypeSlug, array($this, 'updateForm'));

        add_action('wp_ajax_delete_file', array($this, 'deleteFile'));
        add_action('wp_ajax_upload_files', array($this, 'uploadFiles'));
        add_action('wp_ajax_save_post', array($this, 'frontEndSavePost'));
    }

    /**
     * Enqueue scripts for front ui
     * @return void
     */
    public function enqueueScript()
    {
        global $post;

        if (is_object($post) && $post->post_type == $this->postTypeSlug && self::editableFrontend($post)) {
            wp_register_script('form-builder', FORM_BUILDER_MODULE_URL . '/dist/js/form-builder-admin.min.js', array('jQuery'), '', true);
            wp_localize_script('form-builder', 'formbuilder', array(
                'delete_confirm'        => __('Are you sure you want to delete this file?', 'modularity-form-builder'),
            ));
            wp_enqueue_script('form-builder');
        }
    }

    /**
     * Registers submissions post type
     * @return void
     */
    public function register()
    {
        $labels = array(
            'name'                => __('Form submissions', 'modularity-form-builder'),
            'singular_name'       => __('Form submission', 'modularity-form-builder'),
            'add_new'             => _x('Add New Form submission', 'modularity-form-builder', 'modularity-form-builder'),
            'add_new_item'        => __('Add New Form submission', 'modularity-form-builder'),
            'edit_item'           => __('Edit Form submission', 'modularity-form-builder'),
            'new_item'            => __('New Form submission', 'modularity-form-builder'),
            'view_item'           => __('View Form submission', 'modularity-form-builder'),
            'search_items'        => __('Search Form submissions', 'modularity-form-builder'),
            'not_found'           => __('No Form submissions found', 'modularity-form-builder'),
            'not_found_in_trash'  => __('No Form submissions found in Trash', 'modularity-form-builder'),
            'parent_item_colon'   => __('Parent Form submission:', 'modularity-form-builder'),
            'menu_name'           => __('Form submissions', 'modularity-form-builder'),
        );

        $args = array(
            'labels'              => $labels,
            'hierarchical'        => false,
            'description'         => 'Modularity Form Builder form submissions',
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_admin_bar'   => false,
            'menu_position'       => 500,
            'menu_icon'           => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPHN2ZyB3aWR0aD0iMzJweCIgaGVpZ2h0PSIyNHB4IiB2aWV3Qm94PSIwIDAgMzIgMjQiIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayI+CiAgICA8IS0tIEdlbmVyYXRvcjogU2tldGNoIDQzICgzODk5OSkgLSBodHRwOi8vd3d3LmJvaGVtaWFuY29kaW5nLmNvbS9za2V0Y2ggLS0+CiAgICA8dGl0bGU+bm91bl81OTgzNzFfY2M8L3RpdGxlPgogICAgPGRlc2M+Q3JlYXRlZCB3aXRoIFNrZXRjaC48L2Rlc2M+CiAgICA8ZGVmcz48L2RlZnM+CiAgICA8ZyBpZD0iUGFnZS0xIiBzdHJva2U9Im5vbmUiIHN0cm9rZS13aWR0aD0iMSIgZmlsbD0ibm9uZSIgZmlsbC1ydWxlPSJldmVub2RkIj4KICAgICAgICA8ZyBpZD0ibm91bl81OTgzNzFfY2MiIGZpbGwtcnVsZT0ibm9uemVybyIgZmlsbD0iIzAwMDAwMCI+CiAgICAgICAgICAgIDxnIGlkPSJHcm91cCI+CiAgICAgICAgICAgICAgICA8cGF0aCBkPSJNMjkuNSwyMCBMMTQsMjAgQzEzLjQ0OCwyMCAxMywxOS41NTMgMTMsMTkgQzEzLDE4LjQ0NyAxMy40NDgsMTggMTQsMTggTDI5LjUsMTggQzI5Ljc3NSwxOCAzMCwxNy43NzUgMzAsMTcuNSBMMzAsNi41IEMzMCw2LjIyNCAyOS43NzUsNiAyOS41LDYgTDE0LDYgQzEzLjQ0OCw2IDEzLDUuNTUyIDEzLDUgQzEzLDQuNDQ4IDEzLjQ0OCw0IDE0LDQgTDI5LjUsNCBDMzAuODc5LDQgMzIsNS4xMjIgMzIsNi41IEwzMiwxNy41IEMzMiwxOC44NzkgMzAuODc5LDIwIDI5LjUsMjAgWiIgaWQ9IlNoYXBlIj48L3BhdGg+CiAgICAgICAgICAgICAgICA8cGF0aCBkPSJNNiwyMCBMMi41LDIwIEMxLjEyMiwyMCAwLDE4Ljg3OSAwLDE3LjUgTDAsNi41IEMwLDUuMTIyIDEuMTIyLDQgMi41LDQgTDYsNCBDNi41NTIsNCA3LDQuNDQ4IDcsNSBDNyw1LjU1MiA2LjU1Miw2IDYsNiBMMi41LDYgQzIuMjI0LDYgMiw2LjIyNCAyLDYuNSBMMiwxNy41IEMyLDE3Ljc3NSAyLjIyNCwxOCAyLjUsMTggTDYsMTggQzYuNTUyLDE4IDcsMTguNDQ3IDcsMTkgQzcsMTkuNTUzIDYuNTUyLDIwIDYsMjAgWiIgaWQ9IlNoYXBlIj48L3BhdGg+CiAgICAgICAgICAgICAgICA8cGF0aCBkPSJNMTAsMjQgQzkuNDQ4LDI0IDksMjMuNTUzIDksMjMgTDksMSBDOSwwLjQ0OCA5LjQ0OCwwIDEwLDAgQzEwLjU1MiwwIDExLDAuNDQ4IDExLDEgTDExLDIzIEMxMSwyMy41NTMgMTAuNTUyLDI0IDEwLDI0IFoiIGlkPSJTaGFwZSI+PC9wYXRoPgogICAgICAgICAgICAgICAgPHBhdGggZD0iTTEzLDIgTDcsMiBDNi40NDgsMiA2LDEuNTUyIDYsMSBDNiwwLjQ0OCA2LjQ0OCwwIDcsMCBMMTMsMCBDMTMuNTUyLDAgMTQsMC40NDggMTQsMSBDMTQsMS41NTIgMTMuNTUyLDIgMTMsMiBaIiBpZD0iU2hhcGUiPjwvcGF0aD4KICAgICAgICAgICAgICAgIDxwYXRoIGQ9Ik0xMywyNCBMNywyNCBDNi40NDgsMjQgNiwyMy41NTMgNiwyMyBDNiwyMi40NDcgNi40NDgsMjIgNywyMiBMMTMsMjIgQzEzLjU1MiwyMiAxNCwyMi40NDcgMTQsMjMgQzE0LDIzLjU1MyAxMy41NTIsMjQgMTMsMjQgWiIgaWQ9IlNoYXBlIj48L3BhdGg+CiAgICAgICAgICAgIDwvZz4KICAgICAgICA8L2c+CiAgICA8L2c+Cjwvc3ZnPg==',
            'show_in_nav_menus'   => false,
            'publicly_queryable'  => false,
            'exclude_from_search' => true,
            'has_archive'         => false,
            'query_var'           => true,
            'can_export'          => true,
            'rewrite'             => false,
            'capability_type'     => 'post',
            'capabilities' => array(
                'create_posts'    => 'do_not_allow',
            ),
            'map_meta_cap'        => true,
            'supports'            => array('title')
        );

        register_post_type($this->postTypeSlug, $args);
    }

    public function removePublishBox()
    {
        if (isset($_GET['post']) && is_numeric($_GET['post'])) {
            $parent = get_post_meta($_GET['post'], 'modularity-form-id', true);
            if (get_field('editable_back_end', $parent) == false) {
                remove_meta_box('submitdiv', $this->postTypeSlug, 'side');
            }
        }
    }

    /**
     * Adds meta box for viewing submission data
     * @param  string $postType
     * @param  WP_Post $post
     * @return void
     */
    public function formdata($postType, $post)
    {
        if ($postType !== $this->postTypeSlug) {
            return;
        }

        add_meta_box('formdata', 'Submission data', array($this, 'formdataDisplay'), $postType, 'normal', 'default');
    }

    /**
     * Displays the form data
     * @return void
     */
    public function formdataDisplay()
    {
        global $post;

        $indata = get_post_meta($post->ID, 'form-data', true);
        $fields = get_fields($indata['modularity-form-id']);
        $data['form_fields'] = array();
        $data['post_id'] = $post->ID;
        $data['module_id'] = $indata['modularity-form-id'];
        $data['custom_post_type_title'] = false;
        $data['custom_post_type_content'] = false;
        $uploadFolder = wp_upload_dir();
        $data['uploadFolder'] = $uploadFolder['baseurl'] . '/modularity-form-builder/';
        $excludedFields = array(
            'custom_content',
            'collapse'
        );

        // Skip custom content field or fields that are used as post data
        foreach ($fields['form_fields'] as $field) {
            if (in_array($field['acf_fc_layout'], $excludedFields)) {
                continue;
            }

            if (!empty($field['custom_post_type_title'])) {
                $data['custom_post_type_title'] = true;
                continue;
            }

            if (!empty($field['custom_post_type_content'])) {
                $data['custom_post_type_content'] = true;
                continue;
            }

            if ($field['acf_fc_layout'] === 'sender') {

                $field['labels'] = self::getSenderLabels();
                // Merge default and custom labels
                if (!empty($field['custom_sender_labels']['add_sender_labels'])) {
                    $field['labels'] = array_merge($field['labels'], array_filter($field['custom_sender_labels']));
                }

                foreach ($field['fields'] as $subfield) {
                    $data['form_fields'][] = array(
                        'acf_fc_layout' => 'sender-' . $subfield,
                        'label' => $field['labels'][$subfield],
                        'labels' => $field['labels'],
                        'name' => sanitize_title($field['labels'][$subfield]),
                        'required' => in_array($subfield, $field['required_fields']),
                        'value' => (!empty($indata[sanitize_title($field['labels'][$subfield])])) ? $indata[sanitize_title($field['labels'][$subfield])] : '',
                    );
                }

                continue;
            }

            $data['form_fields'][] = array_merge(
                $field,
                array(
                    'name' => sanitize_title($field['label']),
                    'value' => (!empty($indata[sanitize_title($field['label'])])) ? $indata[sanitize_title($field['label'])] : '',
                )
            );
        }

        if (is_admin() && isset($fields['editable_back_end']) && $fields['editable_back_end'] == true) {
            $template = new \Municipio\template;
            $view = \Municipio\Helper\Template::locateTemplate('form-edit.blade.php', array(FORM_BUILDER_MODULE_PATH . 'source/php/Module/views'));
            $view = $template->cleanViewPath($view);
            $template->render($view, $data);
        } elseif (self::editableFrontend($post)) {
            $data['editor_settings'] = array(
                'wpautop' => true, // use wpautop?
                'media_buttons' => false, // show insert/upload button(s)
                'textarea_name' => 'mod-form[post-content]',
                'textarea_rows' => 15,
                'teeny' => true, // output the minimal editor config used in Press This
                'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
            );

            include FORM_BUILDER_MODULE_PATH . 'source/php/Module/views/admin/formdata.php';

            $template = new \Municipio\template;
            $view = \Municipio\Helper\Template::locateTemplate('form-edit-front.blade.php', array(FORM_BUILDER_MODULE_PATH . 'source/php/Module/views'));
            $view = $template->cleanViewPath($view);
            $template->render($view, $data);
        } else {
            include FORM_BUILDER_MODULE_PATH . 'source/php/Module/views/admin/formdata.php';
        }
    }

    public function appendFormdata($content)
    {
        global $post;

        if (is_object($post) && $post->post_type === $this->postTypeSlug && !is_admin() && is_single() && is_main_query() && in_the_loop()) {
            // Apply if content is the same as the global posts content
            $post_content = $post->post_content;
            if (strpos($post_content,  '<!--more-->') !== false) {
                $content_parts = explode('<!--more-->', $post_content);
                $post_content  = $content_parts[1];
            }
            $post_content 	   = preg_replace('/[^a-z]/i', '', sanitize_text_field($post_content));
            $sanitized_content = preg_replace('/[^a-z]/i', '', sanitize_text_field($content));

            if ($post_content == $sanitized_content) {
                ob_start();
                $this->formdataDisplay();
                $output = ob_get_clean();

                $content .= '<br>' . $output;
            }
        }

        return $content;
    }

    public static function editableFrontend($post)
    {
        global $current_user;

        $formId   = get_field('modularity-form-id', $post->ID);
        $editable = get_field('editable_front_end', $formId);

        if ($editable && !is_admin() && (current_user_can('administrator') || is_user_logged_in() && $current_user->ID == $post->post_author)) {
            return true;
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
            'post_type'      => 'mod-form',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'numberposts'    => -1
        ));

        echo '<select name="form"><option value="-1">' . __('Select form…', 'modularity-form-builder') . '</option>';

        foreach ($forms as $form) {
            $selected = isset($_GET['form']) && $_GET['form'] == $form->ID ? 'selected' : '';
            echo '<option value="' . $form->ID . '" ' . $selected . '>' . $form->post_title . '</option>';
        }
        echo '</select>';
    }

    /**
     * Filter the wp query
     * @param  WP_Query $query
     * @return void
     */
    public function queryFilter($query)
    {
        global $pagenow;

        if (!is_admin() || !$pagenow || $pagenow !== 'edit.php' || !isset($_GET['form']) || !$_GET['form'] || (isset($query->query['post_type']) && $query->query['post_type'] != $this->postTypeSlug) || !$query->is_main_query()) {
            return;
        }

        $query->set('meta_query', array(
            'relation' => 'OR',
            array(
                'key' => 'modularity-form-id',
                'value' => $_GET['form'],
                'compare' => '='
            )
        ));
    }

    /**
     * Table columns
     * @param  array $columns
     * @return array
     */
    public function tableColumns($columns)
    {
        return array(
            'cb' => '',
            'title' => __('Title'),
            'id' => __('ID'),
            'form' => __('Form', 'modularity-form-builder'),
            'referer' => __('Referer', 'modularity-form-builder'),
            'date' => __('Date')
        );
    }

    /**
     * Content for table columns
     * @param  string $column
     * @param  int $postId
     * @return void
     */
    public function tableColumnsContent($column, $postId)
    {
        switch ($column) {
            case 'form':
                $form = get_post_meta($postId, 'modularity-form-id', true);
                $form = get_post($form);
                echo edit_post_link($form->post_title, null, null, $form->ID);
                break;
            case 'id':
                echo $postId;
                break;
            case 'referer':
                $referer = get_post_meta($postId, 'modularity-form-referer', true);
                if ($referer) {
                    echo '<a href="' . $referer . '" target="_blank">' . $referer . '</a>';
                }
                break;
        }
    }

    /**
     * Setup list table sorting
     * @param  array $columns  Sortable columns
     * @return array           Modified sortable columns
     */
    public function listColumnsSorting($columns)
    {
        $columns['id'] = 'id';
        return $columns;
    }

    /**
     * Translated sender labels
     * @return array
     */
    public static function getSenderLabels()
    {
        $labels = array(
            'firstname'      => __('Firstname', 'modularity-form-builder'),
            'lastname'       => __('Lastname', 'modularity-form-builder'),
            'email'          => __('Email', 'modularity-form-builder'),
            'phone'          => __('Phone', 'modularity-form-builder'),
            'address'        => __('Address', 'modularity-form-builder'),
            'street_address' => __('Street address', 'modularity-form-builder'),
            'postal_code'    => __('Postal code', 'modularity-form-builder'),
            'city'           => __('City', 'modularity-form-builder')
        );

        return $labels;
    }

    /**
     * Display feedback ID
     * @param  object $post Current post object
     * @return void
     */
    public function displayFeedbackId($post)
    {
        if ($post->post_type == $this->postTypeSlug) {
            echo '<div class="inside"><span><strong>' . __('Feedback ID') . ':</strong> ' . $post->ID . '</span></div>';
        }
    }

    /**
     * Add custom post types to post type list
     * @param  array $field Field data
     * @return array        Modified field data
     */
    public function submissionPostTypes($field)
    {
        $field['choices'][$this->postTypeSlug] = __('Form submissions', 'modularity-form-builder');

        if (current_user_can('administrator')) {
            $postTypes = get_post_types(array('_builtin' => false, 'public' => true));
            foreach ($postTypes as $postType) {
                $postTypeObj = get_post_type_object($postType);
                $field['choices'][$postTypeObj->name] = $postTypeObj->labels->singular_name;
            }
        }

        return $field;
    }

    /**
     * Update form data
     * @param  int $postId The post ID.
     * @return void
     */
    public function updateForm($postId)
    {
        // Deny for autosave function
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!isset($_POST['update-modularity-form']) || !wp_verify_nonce($_POST['update-modularity-form'], 'update')) {
            return;
        }

        if (isset($_POST['mod-form']) && isset($postId) && get_post_type($postId) == $this->postTypeSlug) {
            $indata = get_post_meta($postId, 'form-data', true);
            // Merge old values with new ones
            $data = array_merge($indata, $_POST['mod-form']);

            update_post_meta($postId, 'form-data', $data);
        }
    }

    /**
     * Delete a file (Ajax)
     * @return void
     */
    public function deleteFile()
    {
        if (!isset($_POST['postId']) ||!isset($_POST['formId']) ||!isset($_POST['filePath']) ||!isset($_POST['fieldName'])) {
            echo _e('Missing arguments', 'modularity-form-builder');
            die();
        }

        $postId     = $_POST['postId'];
        $filePath   = $_POST['filePath'];
        $fieldName  = $_POST['fieldName'];
        $formData   = get_post_meta($postId, 'form-data', true);

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

        update_post_meta($postId, 'form-data', $formData);

        echo 'success';
        die();
    }

    /**
     * Upload files (Ajax)
     * @return void
     */
    public function uploadFiles()
    {
        if (!isset($_POST['postId']) ||!isset($_POST['formId']) ||!isset($_POST['fieldName'])) {
            wp_send_json_error(__('Missing arguments', 'modularity-form-builder'));
        }

        $postId     = (int)$_POST['postId'];
        $formId     = (int)$_POST['formId'];
        $fieldName  = $_POST['fieldName'];
        $formData   = get_post_meta($postId, 'form-data', true);

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

            update_post_meta($postId, 'form-data', $formData);
        }

        wp_send_json_success(__('Upload succeeded', 'modularity-form-builder'));
    }

    public function frontEndSavePost()
    {
        if (empty($_POST['post_id']) || !isset($_POST['update-modularity-form']) || !wp_verify_nonce($_POST['update-modularity-form'], 'update')) {
            wp_send_json_error(__('Something went wrong', 'modularity-form-builder'));
        }

        $postId = $_POST['post_id'];

        // Save form data
        if (!empty($_POST['mod-form'])) {
            $indata = get_post_meta($postId, 'form-data', true);
            $data = array_merge($indata, $_POST['mod-form']);
            update_post_meta($postId, 'form-data', $data);
        }

        // Update post title and content
        $post = array('ID' => $postId);
        if (!empty($_POST['mod-form']['post-title'])) {
            $post['post_title'] = $_POST['mod-form']['post-title'];
        }
        if (!empty($_POST['mod-form']['post-content'])) {
            $post['post_content'] = $_POST['mod-form']['post-content'];
        }
        wp_update_post($post);

        wp_send_json_success(__('Saved', 'modularity-form-builder'));
    }
}
