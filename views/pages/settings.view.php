<div class="wrap">
    <h1>SSO Settings</h1>
    <form method="post" action="options.php">
        <?php settings_fields('yekta_sso_options_group'); ?>
        <?php do_settings_sections('yekta_sso_options_group'); ?>
        <table class="form-table">
            <tr valign="top">
                <td>
                    <select id="yekta_sso_method" name="yekta_sso_method">
                        <option value="token" <?php selected(get_option('yekta_sso_method'), 'token'); ?>>Authorization Code</option>
                        <option value="password" <?php selected(get_option('yekta_sso_method'), 'password'); ?>>Password</option>
                        <option value="secret" <?php selected(get_option('yekta_sso_method'), 'secret'); ?>>Secret Key</option>
                    </select>
                </td>
            </tr>
            <tr valign="top" class="token-fields">
                <th scope="row">Client ID</th>
                <td><input type="text" name="yekta_sso_token_guard_client_id" value="<?php echo esc_attr(get_option('yekta_sso_token_guard_client_id')); ?>" style="width: 100%;" /></td>
            </tr>
            <tr valign="top" class="token-fields">
                <th scope="row">Login URL</th>
                <td><input type="text" name="yekta_sso_token_guard_login_url" value="<?php echo esc_attr(get_option('yekta_sso_token_guard_login_url')); ?>" style="width: 100%;" /></td>
            </tr>
            <tr valign="top" class="token-fields">
                <th scope="row">Validate URL</th>
                <td><input type="text" name="yekta_sso_token_guard_validate_url" value="<?php echo esc_attr(get_option('yekta_sso_token_guard_validate_url')); ?>" style="width: 100%;" /></td>
            </tr>
            <tr valign="top" class="password-fields">
                <th scope="row">Token Endpoint URL</th>
                <td><input type="text" name="yekta_sso_password_guard_token_endpoint" value="<?php echo esc_attr(get_option('yekta_sso_password_guard_token_endpoint')); ?>" style="width: 100%;" /></td>
            </tr>
            <tr valign="top" class="password-fields">
                <th scope="row">Secret Key</th>
                <td><input type="text" name="yekta_sso_password_guard_secret_key" value="<?php echo esc_attr(get_option('yekta_sso_password_guard_secret_key')); ?>" style="width: 100%;" /></td>
            </tr>
            <tr valign="top" class="secret-fields">
                <th scope="row">Token Endpoint URL</th>
                <td><input type="text" name="yekta_sso_secret_guard_token_endpoint" value="<?php echo esc_attr(get_option('yekta_sso_secret_guard_token_endpoint')); ?>" style="width: 100%;" /></td>
            </tr>
            <tr valign="top" class="secret-fields">
                <th scope="row">Secret Key</th>
                <td><input type="text" name="yekta_sso_secret_guard_secret_key" value="<?php echo esc_attr(get_option('yekta_sso_secret_guard_secret_key')); ?>" style="width: 100%;" /></td>
            </tr>
            <tr valign="top" class="token-fields">
                <th scope="row">Redirect URL (after login)</th>
                <td><input type="text" name="yekta_sso_token_guard_redirect_url" value="<?php echo esc_attr(get_option('yekta_sso_token_guard_redirect_url')); ?>" style="width: 100%;" /></td>
            </tr>
            <tr valign="top" class="password-fields">
                <th scope="row">Redirect URL (after login)</th>
                <td><input type="text" name="yekta_sso_password_guard_redirect_url" value="<?php echo esc_attr(get_option('yekta_sso_password_guard_redirect_url')); ?>" style="width: 100%;" /></td>
            </tr>
            <tr valign="top" class="secret-fields">
                <th scope="row">Redirect URL (after login)</th>
                <td><input type="text" name="yekta_sso_secret_guard_redirect_url" value="<?php echo esc_attr(get_option('yekta_sso_secret_guard_redirect_url')); ?>" style="width: 100%;" /></td>
            </tr>
            <tr valign="top" class="token-fields">
                <th scope="row">Logout URL</th>
                <td><input type="text" name="yekta_sso_token_guard_logout_url" value="<?php echo esc_attr(get_option('yekta_sso_token_guard_logout_url')); ?>" style="width: 100%;" /></td>
            </tr>
            <tr valign="top" class="password-fields">
                <th scope="row">Logout URL</th>
                <td><input type="text" name="yekta_sso_password_guard_logout_url" value="<?php echo esc_attr(get_option('yekta_sso_password_guard_logout_url')); ?>" style="width: 100%;" /></td>
            </tr>
            <tr valign="top" class="secret-fields">
                <th scope="row">Logout URL</th>
                <td><input type="text" name="yekta_sso_secret_guard_logout_url" value="<?php echo esc_attr(get_option('yekta_sso_secret_guard_logout_url')); ?>" style="width: 100%;" /></td>
            </tr>
            <tr valign="top" class="token-fields">
                <th scope="row">Public Key</th>
                <td><textarea name="yekta_sso_token_guard_public_key" rows="4" style="width: 100%;"><?php echo esc_textarea(get_option('yekta_sso_token_guard_public_key')); ?></textarea></td>
            </tr>
            <tr valign="top" class="password-fields">
                <th scope="row">Public Key</th>
                <td><textarea name="yekta_sso_password_guard_public_key" rows="4" style="width: 100%;"><?php echo esc_textarea(get_option('yekta_sso_password_guard_public_key')); ?></textarea></td>
            </tr>
            <tr valign="top" class="secret-fields">
                <th scope="row">Public Key</th>
                <td><textarea name="yekta_sso_secret_guard_public_key" rows="4" style="width: 100%;"><?php echo esc_textarea(get_option('yekta_sso_secret_guard_public_key')); ?></textarea></td>
            </tr>
            <tr valign="top" class="token-fields">
                <th scope="row">User Info URL</th>
                <td><input type="text" name="yekta_sso_token_guard_userinfo_url" value="<?php echo esc_attr(get_option('yekta_sso_token_guard_userinfo_url')); ?>" style="width: 100%;" /></td>
            </tr>
            <tr valign="top" class="password-fields">
                <th scope="row">User Info URL</th>
                <td><input type="text" name="yekta_sso_password_guard_userinfo_url" value="<?php echo esc_attr(get_option('yekta_sso_password_guard_userinfo_url')); ?>" style="width: 100%;" /></td>
            </tr>
            <tr valign="top" class="secret-fields">
                <th scope="row">User Info URL</th>
                <td><input type="text" name="yekta_sso_secret_guard_userinfo_url" value="<?php echo esc_attr(get_option('yekta_sso_secret_guard_userinfo_url')); ?>" style="width: 100%;" /></td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const select = document.getElementById('yekta_sso_method');
            function toggleFields() {
                const method = select.value;
                document.querySelectorAll('.token-fields').forEach(el => {
                    el.style.display = method === 'token' ? '' : 'none';
                });
                document.querySelectorAll('.password-fields').forEach(el => {
                    el.style.display = method === 'password' ? '' : 'none';
                });
                document.querySelectorAll('.secret-fields').forEach(el => {
                    el.style.display = method === 'secret' ? '' : 'none';
                });
            }
            if (select) {
                select.addEventListener('change', toggleFields);
                toggleFields();
            }
        });
    </script>
</div>
