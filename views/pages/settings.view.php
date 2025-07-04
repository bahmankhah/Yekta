<div class="wrap">
        <h1>SSO Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('my_sso_options_group'); ?>
            <?php do_settings_sections('my_sso_options_group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Token Endpoint URL</th>
                    <td><input type="text" name="my_sso_token_endpoint" value="<?php echo esc_attr(get_option('my_sso_token_endpoint')); ?>" style="width: 100%;"/></td>
                </tr>

                <tr valign="top">
                    <th scope="row">Secret Key</th>
                    <td><input type="text" name="my_sso_secret_key" value="<?php echo esc_attr(get_option('my_sso_secret_key')); ?>" style="width: 100%;"/></td>
                </tr>

                <tr valign="top">
                    <th scope="row">Redirect URL (after login)</th>
                    <td><input type="text" name="my_sso_redirect_url" value="<?php echo esc_attr(get_option('my_sso_redirect_url')); ?>" style="width: 100%;"/></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>