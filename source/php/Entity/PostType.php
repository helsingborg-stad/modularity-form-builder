<?php

namespace ModularityFormBuilder\Entity;

class PostType
{
    public $postTypeSlug;
    public $nameSingular;
    public $namePlural;
    public $args;

    public function __construct($postTypeSlug, $nameSingular, $namePlural, $args = array())
    {
        $this->postTypeSlug = $postTypeSlug;
        $this->nameSingular = $nameSingular;
        $this->namePlural = $namePlural;
        $this->args = $args;

        add_action('init', array($this, 'register'), 12);
        add_action('admin_menu', array($this, 'removePublishBox'));
        add_action('add_meta_boxes', array($this, 'formdata'), 10, 2);
        add_action('edit_form_after_title', array($this, 'displayFeedbackId'), 10, 1);
        add_action('wp_enqueue_scripts', array($this, 'enqueue'));
        add_action('pre_get_posts', array($this, 'queryFilter'));
        add_action('save_post_' . $this->postTypeSlug, array($this, 'updateForm'));
        add_action('manage_' . $this->postTypeSlug . '_posts_custom_column', array($this, 'tableColumnsContent'), 10, 2);

        add_filter('Municipio/blog/post_settings', array($this, 'addEditButton'), 10, 2);
        add_filter('the_content', array($this, 'appendFormdata'));
        add_filter('manage_edit-' . $this->postTypeSlug . '_columns', array($this, 'tableColumns'));
        add_filter('manage_edit-' . $this->postTypeSlug . '_sortable_columns', array($this, 'listColumnsSorting'));

        //Force download of encrypted files
        add_action('admin_init', array($this, 'forceEcryptedFileDownload')); 
    
    }

    public function addEditButton($items, $post)
    {
        if (is_object($post) && self::editableFrontend($post) && $post->post_type == $this->postTypeSlug) {
            $items[] = '<a href="#modal-edit-post" class="settings-item"><i class="pricon pricon-space-right pricon-pen"></i> ' . __('Edit', 'modularity-form-builder') . '</a>';
        }

        return $items;
    }

    /**
     * Enqueue scripts and styles for front ui
     * @return void
     */
    public function enqueue()
    {
        global $post;

        if (is_object($post) && $post->post_type == $this->postTypeSlug) {
            wp_enqueue_style('form-builder', FORM_BUILDER_MODULE_URL . '/dist/css/modularity-form-builder.min.css');

            if (self::editableFrontend($post)) {
                wp_register_script('form-builder', FORM_BUILDER_MODULE_URL . '/dist/js/form-builder-admin.min.js',
                    array('jQuery'), '', true);
                wp_localize_script('form-builder', 'formbuilder', array(
                    'delete_confirm' => __('Are you sure you want to delete this file?', 'modularity-form-builder'),
                ));
                wp_enqueue_script('form-builder');
            }
        }
    }

    /**
     * Registers submissions post type
     * @return void
     */
    public function register()
    {
        $labels = array(
            'name'                => $this->nameSingular,
            'singular_name'       => $this->nameSingular,
            'add_new'             => sprintf(__('Add new %s', 'modularity-form-builder'), $this->nameSingular),
            'add_new_item'        => sprintf(__('Add new %s', 'modularity-form-builder'), $this->nameSingular),
            'edit_item'           => sprintf(__('Edit %s', 'modularity-form-builder'), $this->nameSingular),
            'new_item'            => sprintf(__('New %s', 'modularity-form-builder'), $this->nameSingular),
            'view_item'           => sprintf(__('View %s', 'modularity-form-builder'), $this->nameSingular),
            'search_items'        => sprintf(__('Search %s', 'modularity-form-builder'), $this->namePlural),
            'not_found'           => sprintf(__('No %s found', 'modularity-form-builder'), $this->namePlural),
            'not_found_in_trash'  => sprintf(__('No %s found in trash', 'modularity-form-builder'), $this->namePlural),
            'parent_item_colon'   => sprintf(__('Parent %s:', 'modularity-form-builder'), $this->nameSingular),
            'menu_name'           => $this->namePlural,
        );

        $args = array(
            'labels' => $labels,
            'hierarchical' => false,
            'description' => 'Modularity Form Builder form submissions',
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_admin_bar' => false,
            'menu_position' => 500,
            'menu_icon' => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPHN2ZyB3aWR0aD0iMzJweCIgaGVpZ2h0PSIyNHB4IiB2aWV3Qm94PSIwIDAgMzIgMjQiIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayI+CiAgICA8IS0tIEdlbmVyYXRvcjogU2tldGNoIDQzICgzODk5OSkgLSBodHRwOi8vd3d3LmJvaGVtaWFuY29kaW5nLmNvbS9za2V0Y2ggLS0+CiAgICA8dGl0bGU+bm91bl81OTgzNzFfY2M8L3RpdGxlPgogICAgPGRlc2M+Q3JlYXRlZCB3aXRoIFNrZXRjaC48L2Rlc2M+CiAgICA8ZGVmcz48L2RlZnM+CiAgICA8ZyBpZD0iUGFnZS0xIiBzdHJva2U9Im5vbmUiIHN0cm9rZS13aWR0aD0iMSIgZmlsbD0ibm9uZSIgZmlsbC1ydWxlPSJldmVub2RkIj4KICAgICAgICA8ZyBpZD0ibm91bl81OTgzNzFfY2MiIGZpbGwtcnVsZT0ibm9uemVybyIgZmlsbD0iIzAwMDAwMCI+CiAgICAgICAgICAgIDxnIGlkPSJHcm91cCI+CiAgICAgICAgICAgICAgICA8cGF0aCBkPSJNMjkuNSwyMCBMMTQsMjAgQzEzLjQ0OCwyMCAxMywxOS41NTMgMTMsMTkgQzEzLDE4LjQ0NyAxMy40NDgsMTggMTQsMTggTDI5LjUsMTggQzI5Ljc3NSwxOCAzMCwxNy43NzUgMzAsMTcuNSBMMzAsNi41IEMzMCw2LjIyNCAyOS43NzUsNiAyOS41LDYgTDE0LDYgQzEzLjQ0OCw2IDEzLDUuNTUyIDEzLDUgQzEzLDQuNDQ4IDEzLjQ0OCw0IDE0LDQgTDI5LjUsNCBDMzAuODc5LDQgMzIsNS4xMjIgMzIsNi41IEwzMiwxNy41IEMzMiwxOC44NzkgMzAuODc5LDIwIDI5LjUsMjAgWiIgaWQ9IlNoYXBlIj48L3BhdGg+CiAgICAgICAgICAgICAgICA8cGF0aCBkPSJNNiwyMCBMMi41LDIwIEMxLjEyMiwyMCAwLDE4Ljg3OSAwLDE3LjUgTDAsNi41IEMwLDUuMTIyIDEuMTIyLDQgMi41LDQgTDYsNCBDNi41NTIsNCA3LDQuNDQ4IDcsNSBDNyw1LjU1MiA2LjU1Miw2IDYsNiBMMi41LDYgQzIuMjI0LDYgMiw2LjIyNCAyLDYuNSBMMiwxNy41IEMyLDE3Ljc3NSAyLjIyNCwxOCAyLjUsMTggTDYsMTggQzYuNTUyLDE4IDcsMTguNDQ3IDcsMTkgQzcsMTkuNTUzIDYuNTUyLDIwIDYsMjAgWiIgaWQ9IlNoYXBlIj48L3BhdGg+CiAgICAgICAgICAgICAgICA8cGF0aCBkPSJNMTAsMjQgQzkuNDQ4LDI0IDksMjMuNTUzIDksMjMgTDksMSBDOSwwLjQ0OCA5LjQ0OCwwIDEwLDAgQzEwLjU1MiwwIDExLDAuNDQ4IDExLDEgTDExLDIzIEMxMSwyMy41NTMgMTAuNTUyLDI0IDEwLDI0IFoiIGlkPSJTaGFwZSI+PC9wYXRoPgogICAgICAgICAgICAgICAgPHBhdGggZD0iTTEzLDIgTDcsMiBDNi40NDgsMiA2LDEuNTUyIDYsMSBDNiwwLjQ0OCA2LjQ0OCwwIDcsMCBMMTMsMCBDMTMuNTUyLDAgMTQsMC40NDggMTQsMSBDMTQsMS41NTIgMTMuNTUyLDIgMTMsMiBaIiBpZD0iU2hhcGUiPjwvcGF0aD4KICAgICAgICAgICAgICAgIDxwYXRoIGQ9Ik0xMywyNCBMNywyNCBDNi40NDgsMjQgNiwyMy41NTMgNiwyMyBDNiwyMi40NDcgNi40NDgsMjIgNywyMiBMMTMsMjIgQzEzLjU1MiwyMiAxNCwyMi40NDcgMTQsMjMgQzE0LDIzLjU1MyAxMy41NTIsMjQgMTMsMjQgWiIgaWQ9IlNoYXBlIj48L3BhdGg+CiAgICAgICAgICAgIDwvZz4KICAgICAgICA8L2c+CiAgICA8L2c+Cjwvc3ZnPg==',
            'show_in_nav_menus' => false,
            'publicly_queryable' => false,
            'exclude_from_search' => true,
            'has_archive' => false,
            'query_var' => true,
            'can_export' => true,
            'rewrite' => false,
            'capability_type' => 'post',
            'capabilities' => array(
                'create_posts' => 'do_not_allow',
            ),
            'map_meta_cap' => true,
            'supports' => array('title'),
            'show_in_rest' => false
        );

        //Append default configuration
        $args = array_merge($args, $this->args);

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
     * @param  string  $postType
     * @param  WP_Post $post
     * @return void
     */
    public function formdata($postType, $post)
    {
        if ($postType !== $this->postTypeSlug) {
            return;
        }

        add_meta_box(
            'formdata', 
            __('Submission data', 'modularity-form-builder'), 
            array($this, 'formdataDisplay'), 
            $postType, 
            'normal', 
            'default'
        );
    }


    /**
     * Checks user access to formdata
     * @param  int $moduleId
     * @return true if user is granted access otherwise void
     */
    public function isGrantedUser($moduleId)
    {
        if (isset($moduleId) || empty($moduleId)) {

            $userRestriction = get_field('user_restriction', $moduleId);

            if ($userRestriction) {
                
                //Always allow administrators
                if (current_user_can('administrator')) {
                    return true;
                }

                //Always allow author of the post 
                if (get_current_user_id() == get_post_field('post_author', $moduleId)) {
                    return true; 
                }
                    
                //Check if granted user
                $grantedUsers = get_field('granted_users', $moduleId);

                if (!empty($grantedUsers) && is_array($grantedUsers)) {
                    foreach ($grantedUsers as $user) {
                        if ($user['ID'] === get_current_user_id()) {
                            return true; // Access granted
                        }
                    }
                }

                return false;
            }

        }

        return true; // No user restriction 
    }

    public function forceEcryptedFileDownload() {
        if(isset($_GET['modFormDownloadEncFile'])) {

            //Verify that there is a module id included
            if(!isset($_GET['modFormModuleId']) || (isset($_GET['modFormModuleId']) && !is_numeric($_GET['modFormModuleId']))) {
                wp_die(
                    __("No reference to a module where defined.", 'modularity-form-builder'),
                    __("Module reference missing", 'modularity-form-builder')
                );
            }

            //Check if granted user
            if(!$this->isGrantedUser($_GET['modFormModuleId'])) {
                wp_die(
                    __("You are not authorized to download this file.", 'modularity-form-builder'),
                    __("Unauthorized request", 'modularity-form-builder')
                );
            }

            //Get uploads folder
            $uploadsFolder = wp_upload_dir();
            $uploadsFolder = $uploadsFolder['basedir'] . '/modularity-form-builder/';

            //Get local path to file 
            $filePath = $uploadsFolder . urldecode($_GET['modFormDownloadEncFile']); 

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
                header("Content-Disposition: attachment; filename=\"" . $_GET['modFormDownloadEncFile'] . "\";" );
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
     * Get the download link to the file
     * @return string
     */
    public function getDownloadLink($filePath, $moduleId = null) {

        //Encrypted file requires granted users 
        if(is_null($moduleId) || !$this->isGrantedUser($moduleId)) {
            return false;
        }
        
        //Check if encrypted
        if (strpos($filePath, sanitize_file_name("-enc-" . ENCRYPT_METHOD)) !== false) {

            if(strpos($_SERVER['REQUEST_URI'], "?") !== false) {
                $sep = "&"; 
            } else {
                $sep = "?";
            }

            return "//" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . $sep . 'modFormDownloadEncFile=' . urlencode(basename($filePath)) . "&modFormModuleId=" . $moduleId ;
        }

        return $filePath; 
    }

    /**
     * Displays the form data, as static data or editable
     * @return void
     */
    public function formdataDisplay()
    {
        global $post;

        //Get form configuration
        $data = self::gatherFormData($post);
        $data['parentClass'] = $this;
        
        //Check if user is granted to view this data
        if(!$this->isGrantedUser($data['module_id'])) {

            //Error message
            $this->renderBlade(
                'unauthorized.blade.php', 
                array(
                    FORM_BUILDER_MODULE_PATH . 'source/php/Module/views/admin'
                ), 
                array(
                    'title' => __("Access denied", 'modularity-form-builder'),
                    'message' => __("You don't have the sufficient permissions to view this post.", 'modularity-form-builder'),
                )
            );
            
            return;
        }

        //Translations 
        $data['translation'] = array(
            'removed_file' => __("File removed", 'modularity-form-builder'),
            'unknown_file' => __("Unknown file", 'modularity-form-builder'),
        ); 
        
        //Get form submission data 
        $fields = get_fields($data['module_id']);

        //Check if this should be exluded from frontend
        $data['excludedFront'] = apply_filters('ModularityFormBuilder/excluded_fields/front',
            array(), 
            $post->post_type,
            $data['module_id']
        );

        if (is_admin() && isset($fields['editable_back_end']) && $fields['editable_back_end'] == true) {

            //Editable
            $this->renderBlade(
                'form-edit.blade.php', 
                array(
                    FORM_BUILDER_MODULE_PATH . 'source/php/Module/views'
                ), 
                $data
            );

        } elseif (self::editableFrontend($post)) {

            //Editor settings 
            $data['editor_settings'] = array(
                'wpautop' => true,
                'media_buttons' => false,
                'textarea_name' => 'mod-form[post-content]',
                'textarea_rows' => 15,
                'tinymce' => array(
                    'plugins' => 'wordpress',
                ),
            );

            //Static
            $this->renderBlade(
                'form-data.blade.php', 
                array(
                    FORM_BUILDER_MODULE_PATH . 'source/php/Module/views/admin'
                ), 
                $data
            );

            //Editable
            $this->renderBlade(
                'form-edit-front.blade.php', 
                array(
                    FORM_BUILDER_MODULE_PATH . 'source/php/Module/views'
                ),
                $data
            );

        } else {

            //Static
            $this->renderBlade(
                'form-data.blade.php', 
                array(
                    FORM_BUILDER_MODULE_PATH . 'source/php/Module/views/admin'
                ), 
                $data
            );

        }

        if (is_admin()) {

            $indata = (is_array(get_post_meta($post->ID, 'form-data', true))) ? get_post_meta($post->ID, 'form-data', true) : unserialize(\ModularityFormBuilder\App::encryptDecryptData('decrypt',
                get_post_meta($post->ID, 'form-data', true)));

            if (isset($indata['modularity-form-history'])) {
                echo "<p><strong>" . __('Previous page', 'modularity-form-builder') . "</strong><br />";

                if (trim($indata['modularity-form-history']) !== 'null' && trim($indata['modularity-form-history']) !== 'http://null' && trim($indata['modularity-form-history']) !== 'https://null') {
                    echo "<a href=\"" . $indata['modularity-form-history'] . "\">" . $indata['modularity-form-history'] . "</a>";
                } else {
                    echo __('No Referrer', 'modularity-form-builder');
                }

                echo "<br /></p>";
            }

            if (isset($indata['modularity-form-url'])) {
                echo "<p><strong>" . __('Form',
                        'modularity-form-builder') . "</strong><br /><a href=\"" . $indata['modularity-form-url'] . "\">" . $indata['modularity-form-url'] . "</a></p>";
            }
        }
    }


    public static function gatherFormData($post)
    {
        $data = array();
        $getData = get_post_meta($post->ID, 'form-data', true);
        $indata = (is_array($getData)) ? $getData : unserialize(\ModularityFormBuilder\App::encryptDecryptData('decrypt', $getData));

        // Get the form id
        if (!empty($indata['modularity-form-id'])) {
            $formId = $indata['modularity-form-id'];
        } else {
            // If form id is missing, check if the post type is connected to any forms
            global $wpdb;
            $postTypes = $wpdb->get_row(
                "SELECT post_id
                FROM $wpdb->postmeta
                WHERE meta_key = 'submission_post_type'
                    AND meta_value = '{$post->post_type}'
                ", ARRAY_N);

            if (!empty($postTypes[0])) {
                $formId = $postTypes[0];
            } else {
                // Bail if no form id can be found
                return false;
            }
        }

        $fields = get_fields($formId);

        $data['form_fields'] = array();
        $data['post_id'] = $post->ID;
        $data['author_id'] = $post->post_author;
        $data['module_id'] = $formId;
        $data['custom_post_type_title'] = false;
        $data['custom_post_type_content'] = false;
        $uploadFolder = wp_upload_dir();
        $data['uploadFolder'] = $uploadFolder['baseurl'] . '/modularity-form-builder/';
        $excludedGlobal = apply_filters('ModularityFormBuilder/excluded_fields/global',
            array('custom_content', 'collapse'), $post->post_type, $formId);

        foreach ($fields['form_fields'] as $field) {
            // Skip layout fields
            if (in_array($field['acf_fc_layout'], $excludedGlobal)) {
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
                $field['labels'] = \ModularityFormBuilder\Helper\SenderLabels::getLabels();
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
                        'required' => (is_array($field['required_fields']) && in_array($subfield,
                                $field['required_fields'])),
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

        return $data;
    }

    /**
     * Render and output blade template
     * @param string $fileName Filename
     * @param array  $path     Array with file paths
     * @param array  $data     Template data
     */
    public function renderBlade($fileName, $path, $data = array())
    {
        $template = new \Municipio\template;
        $view = \Municipio\Helper\Template::locateTemplate($fileName, $path);
        $view = $template->cleanViewPath($view);
        $template->render($view, $data);
    }

    public function appendFormdata($content)
    {
        global $post;

        if (is_object($post) && $post->post_type === $this->postTypeSlug && !is_admin() && !is_archive() && is_main_query()) {
            // Apply if content is the same as the global posts content
            $post_content = $post->post_content;
            if (strpos($post_content, '<!--more-->') !== false) {
                $content_parts = explode('<!--more-->', $post_content);
                $post_content = $content_parts[1];
            }
            $post_content = preg_replace('/[^a-z]/i', '', sanitize_text_field($post_content));
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

        $formId = get_field('modularity-form-id', $post->ID);
        $editable = get_field('editable_front_end', $formId);

        if ($editable && !is_admin() && (current_user_can('administrator') || is_user_logged_in() && $current_user->ID == $post->post_author)) {
            return true;
        }

        return false;
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
     * @param  int    $postId
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
     * @param  array $columns Sortable columns
     * @return array           Modified sortable columns
     */
    public function listColumnsSorting($columns)
    {
        $columns['id'] = 'id';
        return $columns;
    }

    /**
     * Display feedback ID
     * @param  object $post Current post object
     * @return void
     */
    public function displayFeedbackId($post)
    {
        if ($post->post_type == $this->postTypeSlug) {
            echo '<div class="inside"><span><strong>' . __('ID') . ':</strong> ' . $post->ID . '</span></div>';
        }
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

        // Check nonce and if form data exist
        if (!isset($_POST['update-modularity-form'])
            || !wp_verify_nonce($_POST['update-modularity-form'], 'update')
            || !isset($_POST['modularity-form-id'])
            || !isset($_POST['mod-form'])) {
            return;
        }

        $updatedData = $_POST['mod-form'];
        if (!get_option('options_mod_form_crypt')) {
            $currentData = get_post_meta($postId, 'form-data', true);
        } else {
            $currentData = unserialize(\ModularityFormBuilder\App::encryptDecryptData('decrypt', get_post_meta($postId, 'form-data', true)));
        }

        // Merge old values with new ones
        if (is_array($currentData) && !empty($currentData)) {
            $updatedData = array_merge($currentData, $updatedData);
        }

        update_post_meta($postId, 'modularity-form-id', $_POST['modularity-form-id']);
        if (!get_option('options_mod_form_crypt')) {
            update_post_meta($postId, 'form-data', $updatedData);
        } else {
            update_post_meta($postId, 'form-data', \ModularityFormBuilder\App::encryptDecryptData('encrypt', $updatedData));
        }
    }
}
