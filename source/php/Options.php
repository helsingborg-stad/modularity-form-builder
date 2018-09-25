<?php

namespace ModularityFormBuilder;

class Options
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'addOptionsFields'), 9);
        add_filter('acf/load_field/name=submission_post_type', array($this, 'submissionPostTypes'));
        add_filter('acf/load_field/name=form_builder_is_admin', array($this, 'isAdmin'), 10, 3);
    }

    /**
     * Adds a options page for the plugin.
     */
    public function addOptionsFields()
    {
        add_submenu_page(
            'edit.php?post_type=form-submissions',
            __('Form builder options', 'modularity-form-builder'),
            __('Options', 'modularity-form-builder'),
            'manage_options',
            'mod-form-options',
            array($this, 'optionsPage')
        );
    }

    /**
     * Callback method for the options page.
     */
    public function optionsPage()
    {
        // Bail if Google_Client is not loaded
        if (!class_exists('\\Google_Client')) {
            echo '<div class="wrap"><div class="notice error is-dismissible"><p>Install library <strong>Google APIs Client Library for PHP</strong> to continue.</p></div></div>';
            return;
        }

        // Save oauth credentials and encryption credential
        if (isset($_POST['save-oauth-credentials']) && wp_verify_nonce($_POST['save-oauth-credentials'], 'save')) {
            update_option('options_mod_form_client_id', $_POST['client-id']);
            update_option('options_mod_form_secret', $_POST['client-secret']);
            update_option('options_mod_form_crypt', isset($_POST['encrypt']) && $_POST['encrypt'] == true ? 1 : null);
        }


        // Delete oauth credentials
        if (isset($_POST['delete-oauth-credentials']) && wp_verify_nonce($_POST['delete-oauth-credentials'], 'delete')) {
            delete_option('options_mod_form_client_id');
            delete_option('options_mod_form_secret');
            delete_option('options_mod_form_access_token');
        }

        // Try to authenticate if all options are set
        if (!empty(get_option('options_mod_form_client_id'))
            && !empty(get_option('options_mod_form_secret'))
            && get_option('options_mod_form_access_token') != true) {
            $oauthRespons = $this->authenticateWebApp();
        }

        include FORM_BUILDER_MODULE_TEMPLATE_PATH . 'options.php';
    }

    public function authenticateWebApp()
    {
        $oauth2ClientID = get_option('options_mod_form_client_id');
        $oauth2ClientSecret = get_option('options_mod_form_secret');
        $redirect = admin_url() . 'edit.php?post_type=form-submissions&page=mod-form-options';
        $appName = "YouTube uploader";

        try {
            $client = new \Google_Client();
            $client->setClientId($oauth2ClientID);
            $client->setClientSecret($oauth2ClientSecret);
            $client->setScopes('https://www.googleapis.com/auth/youtube');
            $client->setRedirectUri($redirect);
            $client->setApplicationName($appName);
            $client->setAccessType('offline');

            // Define an object that will be used to make all API requests.
            $youtube = new \Google_Service_YouTube($client);

            $markup = '';
            if (isset($_GET['code'])) {
                if (strval(get_option('options_mod_form_state')) !== strval($_GET['state'])) {
                    return 'The session state did not match';
                }

                $client->authenticate($_GET['code']);
            }

            // Check to ensure that the access token was successfully acquired.
            if ($client->getAccessToken()) {
                try {
                    // Test cal channels.list method
                    $channelsResponse = $youtube->channels->listChannels('contentDetails', array('mine' => 'true'));
                    if (is_object($channelsResponse) && !empty($channelsResponse)) {
                        update_option('options_mod_form_access_token', $client->getAccessToken());
                        delete_option('options_mod_form_state');
                    }
                } catch (\Google_Service_Exception $e) {
                    $markup .= sprintf('<p>A service error occurred: <code>%s</code></p>', htmlspecialchars($e->getMessage()));
                } catch (\Google_Exception $e) {
                    $markup .= sprintf('<p>A client error occurred: <code>%s</code></p>', htmlspecialchars($e->getMessage()));
                }
            } elseif (!$client->getAccessToken() && isset($_GET['code'])) {
                $markup = '<h3>Authorization failed</h3><p>Wrong credentials or misconfigured OAuth client. Please update the options and try again.<p>';
            } else {
                $state = mt_rand();
                $client->setState($state);
                update_option('options_mod_form_state', $state);

                $authUrl = $client->createAuthUrl();
                $markup = '<h3>Authorization Required</h3><p>You need to <a href="' . $authUrl . '">authorize access</a> before proceeding.<p>';
            }
        } catch (\InvalidArgumentException $e) {
            $markup .= sprintf('<p>Invalid Argument Exception: <code>%s</code></p>', htmlspecialchars($e->getMessage()));
        }

        return $markup;
    }

    /**
     * Add custom post types to post type list
     * @param  array $field Field data
     * @return array        Modified field data
     */
    public function submissionPostTypes($field)
    {
        if (get_post_type() == 'acf-field-group') {
            return $field;
        }
        // Default post type
        $field['choices']['form-submissions'] = __('Form submissions', 'modularity-form-builder');

        // Get custom post types
        $customPostTypes = get_field('avabile_dynamic_post_types', 'option');
        if (!is_array($customPostTypes) || empty($customPostTypes)) {
            return $field;
        }
        $customPostTypes = array_column($customPostTypes, 'post_type_name');
        // Get all post types objects
        $postTypes = get_post_types(array('_builtin' => false));
        $postTypes = array_map(function ($postType) {
            $postTypeObj = get_post_type_object($postType);
            return $postTypeObj;
        }, $postTypes);
        foreach ($postTypes as $postType) {
            if (in_array($postType->label, $customPostTypes)) {
                $field['choices'][$postType->name] = $postType->label;
            }
        }

        $field['choices'] = apply_filters('ModularityFormBuilder/options/post_types', $field['choices']);
        return $field;
    }

    /**
     * Check if current user is admin
     * @param  array $field Field data
     * @return array        Modified field data
     */
    public function isAdmin($field)
    {
        if (current_user_can('administrator') && get_post_type() != 'acf-field-group') {
            $field['choices'] = array('true' => 'true');
        }

        return $field;
    }
}
