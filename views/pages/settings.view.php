<div class="wrap">
    <h1>تنظیمات SSO</h1>
    <form method="post" action="options.php">
        <?php settings_fields('yekta_sso_options_group'); ?>
        <?php do_settings_sections('yekta_sso_options_group'); ?>
        <table class="form-table">
            <tr valign="top">
                <td>
                    <select id="yekta_sso_method" name="yekta_sso_method">
                        <option value="token" <?php selected(get_option('yekta_sso_method'), 'token'); ?>>کد مجوز</option>
                        <option value="password" <?php selected(get_option('yekta_sso_method'), 'password'); ?>>رمز عبور</option>
                        <option value="secret" <?php selected(get_option('yekta_sso_method'), 'secret'); ?>>کلید مخفی</option>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">پارامتر ورود</th>
                <td><input type="text" name="yekta_sso_login_param" value="<?php echo esc_attr(get_option('yekta_sso_login_param', 'login')); ?>" style="width: 100%;" /></td>
            </tr>
            <tr valign="top">
                <th scope="row">پارامتر کد بازگشت</th>
                <td><input type="text" name="yekta_sso_code_param" value="<?php echo esc_attr(get_option('yekta_sso_code_param', 'code')); ?>" style="width: 100%;" /></td>
            </tr>
            <tr valign="top" class="token-fields">
                <th scope="row">شناسه کلاینت</th>
                <td><input type="text" name="yekta_sso_token_guard_client_id" value="<?php echo esc_attr(get_option('yekta_sso_token_guard_client_id')); ?>" style="width: 100%;" /></td>
            </tr>
            <tr valign="top" class="token-fields">
                <th scope="row">آدرس ورود</th>
                <td><input type="text" name="yekta_sso_token_guard_login_url" value="<?php echo esc_attr(get_option('yekta_sso_token_guard_login_url')); ?>" style="width: 100%;" /></td>
            </tr>
            <tr valign="top" class="token-fields">
                <th scope="row">آدرس اعتبارسنجی</th>
                <td><input type="text" name="yekta_sso_token_guard_validate_url" value="<?php echo esc_attr(get_option('yekta_sso_token_guard_validate_url')); ?>" style="width: 100%;" /></td>
            </tr>
            <tr valign="top" class="password-fields">
                <th scope="row">آدرس دریافت توکن</th>
                <td><input type="text" name="yekta_sso_password_guard_token_endpoint" value="<?php echo esc_attr(get_option('yekta_sso_password_guard_token_endpoint')); ?>" style="width: 100%;" /></td>
            </tr>
            <tr valign="top" class="password-fields">
                <th scope="row">کلید مخفی</th>
                <td><input type="text" name="yekta_sso_password_guard_secret_key" value="<?php echo esc_attr(get_option('yekta_sso_password_guard_secret_key')); ?>" style="width: 100%;" /></td>
            </tr>
            <tr valign="top" class="secret-fields">
                <th scope="row">آدرس دریافت توکن</th>
                <td><input type="text" name="yekta_sso_secret_guard_token_endpoint" value="<?php echo esc_attr(get_option('yekta_sso_secret_guard_token_endpoint')); ?>" style="width: 100%;" /></td>
            </tr>
            <tr valign="top" class="secret-fields">
                <th scope="row">کلید مخفی</th>
                <td><input type="text" name="yekta_sso_secret_guard_secret_key" value="<?php echo esc_attr(get_option('yekta_sso_secret_guard_secret_key')); ?>" style="width: 100%;" /></td>
            </tr>
            <tr valign="top" class="token-fields">
                <th scope="row">آدرس بازگشت (پس از ورود)</th>
                <td><input type="text" name="yekta_sso_token_guard_redirect_url" value="<?php echo esc_attr(get_option('yekta_sso_token_guard_redirect_url')); ?>" style="width: 100%;" /></td>
            </tr>
            <tr valign="top" class="password-fields">
                <th scope="row">آدرس بازگشت (پس از ورود)</th>
                <td><input type="text" name="yekta_sso_password_guard_redirect_url" value="<?php echo esc_attr(get_option('yekta_sso_password_guard_redirect_url')); ?>" style="width: 100%;" /></td>
            </tr>
            <tr valign="top" class="secret-fields">
                <th scope="row">آدرس بازگشت (پس از ورود)</th>
                <td><input type="text" name="yekta_sso_secret_guard_redirect_url" value="<?php echo esc_attr(get_option('yekta_sso_secret_guard_redirect_url')); ?>" style="width: 100%;" /></td>
            </tr>
            <tr valign="top" class="token-fields">
                <th scope="row">آدرس خروج</th>
                <td><input type="text" name="yekta_sso_token_guard_logout_url" value="<?php echo esc_attr(get_option('yekta_sso_token_guard_logout_url')); ?>" style="width: 100%;" /></td>
            </tr>
            <tr valign="top" class="password-fields">
                <th scope="row">آدرس خروج</th>
                <td><input type="text" name="yekta_sso_password_guard_logout_url" value="<?php echo esc_attr(get_option('yekta_sso_password_guard_logout_url')); ?>" style="width: 100%;" /></td>
            </tr>
            <tr valign="top" class="secret-fields">
                <th scope="row">آدرس خروج</th>
                <td><input type="text" name="yekta_sso_secret_guard_logout_url" value="<?php echo esc_attr(get_option('yekta_sso_secret_guard_logout_url')); ?>" style="width: 100%;" /></td>
            </tr>
            <tr valign="top" class="token-fields">
                <th scope="row">کلید عمومی</th>
                <td><textarea name="yekta_sso_token_guard_public_key" rows="4" style="width: 100%;"><?php echo esc_textarea(get_option('yekta_sso_token_guard_public_key')); ?></textarea></td>
            </tr>
            <tr valign="top" class="password-fields">
                <th scope="row">کلید عمومی</th>
                <td><textarea name="yekta_sso_password_guard_public_key" rows="4" style="width: 100%;"><?php echo esc_textarea(get_option('yekta_sso_password_guard_public_key')); ?></textarea></td>
            </tr>
            <tr valign="top" class="secret-fields">
                <th scope="row">کلید عمومی</th>
                <td><textarea name="yekta_sso_secret_guard_public_key" rows="4" style="width: 100%;"><?php echo esc_textarea(get_option('yekta_sso_secret_guard_public_key')); ?></textarea></td>
            </tr>
            <tr valign="top" class="token-fields">
                <th scope="row">آدرس اطلاعات کاربر</th>
                <td><input type="text" name="yekta_sso_token_guard_userinfo_url" value="<?php echo esc_attr(get_option('yekta_sso_token_guard_userinfo_url')); ?>" style="width: 100%;" /></td>
            </tr>
            <tr valign="top" class="password-fields">
                <th scope="row">آدرس اطلاعات کاربر</th>
                <td><input type="text" name="yekta_sso_password_guard_userinfo_url" value="<?php echo esc_attr(get_option('yekta_sso_password_guard_userinfo_url')); ?>" style="width: 100%;" /></td>
            </tr>
            <tr valign="top" class="secret-fields">
                <th scope="row">آدرس اطلاعات کاربر</th>
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
