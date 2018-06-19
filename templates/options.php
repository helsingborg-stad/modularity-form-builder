<div class="wrap">
    <h1><?php _e('Modularity Form Builder options', 'modularity-form-builder' ); ?></h1>
</div>

    <div class="wrap">
        <h3><?php _e('YouTube API authentication', 'modularity-form-builder' ); ?></h3>
    </div>

    <div class="error notice hidden"></div>
    <div class="updated notice hidden"></div>

    <?php if (get_option('options_mod_form_access_token') == false): ?>
        <div class="wrap oauth-request">
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
                    <tr>
                    </table>

                <h3><?php _e('Encrypt your data', 'modularity-form-builder'); ?></h3>
                <p><?php _e('If you want to encrypt the form data before it saves to database you need to check the following checkbox ', 'modularity-form-builder' ); ?><br />
                <?php _e('You also have to add following lines to your config file and replace ADD-YOUR-KEY-HERE-1 and ADD-YOUR-KEY-HERE-2 with your own keys, 16 character minimum.', 'modularity-form-builder' ); ?></p>

                <code><?php _e('define("ENCRYPT_METHOD", "AES-256-CBC", true);', 'modularity-form-builder' ); ?><br />
                    &nbsp;<?php _e('define("ENCRYPT_SECRET_KEY", "ADD-YOUR-KEY-HERE-1", true);', 'modularity-form-builder' ); ?><br />
                    &nbsp;<?php _e('define("ENCRYPT_SECRET_VI", "ADD-YOUR-KEY-HERE-2", true);', 'modularity-form-builder' ); ?><br />

                </code>
                <p><?php _e('Dont forget to keep the keys in a safe place, cause you will never be able to decrypt the already encrypted data without them.', 'modularity-form-builder' ); ?><br />
                    <table class="form-table">
                    <tr>
                        <th scope="row">
                            <?php _e('Yes I want to encrypt my form data', 'modularity-form-builder'); ?>
                        </th>
                        <td>
                            <input type="checkbox" name="encrypt" id="encrypt" value="1" <?php if (get_option('options_mod_form_crypt')): echo 'checked'; endif;  ?>> <? echo get_option('options_mod_form_crypt'); ?>
                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <input name='submit' type='submit' class='button-primary' value='<?php _e('Save', 'modularity-form-builder') ?>' />
                </p>
            </form>
        </div>

        <div class="wrap">
            <?php   if(isset($oauthRespons)) {
                        echo $oauthRespons;
                    }
            ?>
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
