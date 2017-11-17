<div class="wrap">
    <h1><?php _e('Modularity Form Builder options', 'modularity-form-builder' ); ?></h1>
</div>

    <div class="wrap">
        <h1><?php _e('YouTube API authentication', 'modularity-form-builder' ); ?></h1>
    </div>

    <div class="error notice hidden"></div>
    <div class="updated notice hidden"></div>

    <?php if (get_option('options_mod_form_access_token') == false): ?>
        <div class="wrap oauth-request">
        <h3><?php _e('Save credentials', 'modularity-form-builder'); ?></h3>
        <p><?php _e('To upload videos to YouTube from your forms you need to authenticate this web applicaiton. Enter your Google OAuth 2.0 client keys and submit the request.', 'modularity-form-builder' ); ?></p>
        <p><?php _e('When you are setting up your OAuth Web application set "Authorized redirect URIs" to this URL: ', 'modularity-form-builder' ); ?></p>
        <code><?php menu_page_url('mod-form-options', true); ?></code>
            <form method="post" id="oauth-credentials" action="<?php menu_page_url('mod-form-options', true); ?>">
                <?php wp_nonce_field('save', 'save-oauth-credentials'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="client-id"><?php _e( 'Client ID', 'modularity-form-builder' )?></label>
                        </th>
                        <td>
                            <input type="text" class="client-id" name="client-id" id="client-id" value="<?php echo esc_attr(get_option('options_mod_form_client_id')); ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="client-secret"><?php _e( 'Client secret', 'modularity-form-builder' )?></label>
                        </th>
                        <td>
                            <input type="text" class="client-secret" name="client-secret" id="client-secret" value="<?php echo esc_attr(get_option('options_mod_form_secret')); ?>"/>
                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <input name='submit' type='submit' class='button-primary' value='<?php _e('Save', 'modularity-form-builder') ?>' />
                </p>
            </form>
        </div>

        <div class="wrap">
            <?php echo $oauthRespons; ?>
        </div>

    <?php else: ?>

        <div class="wrap oauth-authorized">
        <h3><?php _e('Authorized', 'modularity-form-builder' ); ?></h3>
            <form method="post" id="oauth-delete-credentials">
                <?php wp_nonce_field('delete', 'delete-oauth-credentials'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <?php _e('Client ID', 'modularity-form-builder'); ?>
                        </th>
                        <td>
                            <code>
                                <?php echo get_option('options_mod_form_client_id'); ?>
                            </code>
                        </td>
                    </tr>
                </table>
            <p class="submit">
                <input name='submit' type='submit' class='button' value='<?php _e('Delete credentials', 'modularity-form-builder') ?>' />
            </p>
            </form>
        </div>
    <?php endif ?>
