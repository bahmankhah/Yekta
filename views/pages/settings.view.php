<div class="wrap">
        <h1>SSO Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('my_sso_options_group'); ?>
            <?php do_settings_sections('my_sso_options_group'); ?>
            <table class="form-table">

                <tr valign="top">
                  
                    <td>
                        <select id="my_sso_method" name="my_sso_method">
                            <option value="oauth" <?php selected(get_option('my_sso_method'), 'oauth'); ?>>OAuth 2.0</option>
                            <option value="token" <?php selected(get_option('my_sso_method'), 'token'); ?>>Token Based</option>
                        </select>
                    </td>
                </tr>

                <tr valign="top" class="oauth-fields">
                    <th scope="row">Client ID</th>
                    <td><input type="text" name="my_sso_client_id" value="<?php echo esc_attr(get_option('my_sso_client_id')); ?>" style="width: 100%;"/></td>
                </tr>

                <tr valign="top" class="oauth-fields">
                    <th scope="row">Login URL</th>
                    <td><input type="text" name="my_sso_login_url" value="<?php echo esc_attr(get_option('my_sso_login_url')); ?>" style="width: 100%;"/></td>
                </tr>

                <tr valign="top" class="oauth-fields">
                    <th scope="row">Secret Key</th>
                    <td><input type="text" name="my_sso_secret_key" value="<?php echo esc_attr(get_option('my_sso_secret_key')); ?>" style="width: 100%;"/></td>
                </tr>

                <tr valign="top" class="token-fields">
                    <th scope="row">Token Endpoint URL</th>
                    <td><input type="text" name="my_sso_token_endpoint" value="<?php echo esc_attr(get_option('my_sso_token_endpoint')); ?>" style="width: 100%;"/></td>
                </tr>

                <tr valign="top" class="token-fields">
                    <th scope="row">Secret Key</th>
                    <td><input type="text" name="my_sso_secret_key" value="<?php echo esc_attr(get_option('my_sso_secret_key')); ?>" style="width: 100%;"/></td>
                </tr>

                <tr valign="top" class="token-fields">
                    <th scope="row">Redirect URL (after login)</th>
                    <td><input type="text" name="my_sso_redirect_url" value="<?php echo esc_attr(get_option('my_sso_redirect_url')); ?>" style="width: 100%;"/></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const select = document.getElementById('my_sso_method');
                function toggleFields() {
                    const method = select.value;
                    document.querySelectorAll('.oauth-fields').forEach(el => {
                        el.style.display = method === 'oauth' ? '' : 'none';
                    });
                    document.querySelectorAll('.token-fields').forEach(el => {
                        el.style.display = method === 'token' ? '' : 'none';
                    });
                }
                if (select) {
                    select.addEventListener('change', toggleFields);
                    toggleFields();
                }
            });
        </script>
    </div>