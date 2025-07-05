<div class="wrap">
    <h1>تنظیمات SSO</h1>
    <form method="post" action="options.php">
        <?php settings_fields('yekta_sso_options_group'); ?>
        <?php do_settings_sections('yekta_sso_options_group'); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">نوع سرویس</th>
                <td>
                    <select id="yekta_sso_service_type" name="yekta_sso_service_type">
                        <option value="client" <?php selected(get_option('yekta_sso_service_type','client'), 'client'); ?>>سرویس بیرونی</option>
                        <option value="auth" <?php selected(get_option('yekta_sso_service_type','client'), 'auth'); ?>>سرویس احراز هویت</option>
                    </select>
                </td>
            </tr>
            <tbody class="client-fields">
                <tr valign="top">
                    <th scope="row">پارامتر ورود</th>
                    <td><input type="text" name="yekta_sso_login_param" value="<?php echo esc_attr(get_option('yekta_sso_login_param','login')); ?>" style="width:100%;" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">پارامتر کد بازگشت</th>
                    <td><input type="text" name="yekta_sso_code_param" value="<?php echo esc_attr(get_option('yekta_sso_code_param','code')); ?>" style="width:100%;" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">شناسه کلاینت</th>
                    <td><input type="text" name="yekta_sso_token_guard_client_id" value="<?php echo esc_attr(get_option('yekta_sso_token_guard_client_id')); ?>" style="width:100%;" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">آدرس ورود</th>
                    <td><input type="text" name="yekta_sso_token_guard_login_url" value="<?php echo esc_attr(get_option('yekta_sso_token_guard_login_url')); ?>" style="width:100%;" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">آدرس اعتبارسنجی</th>
                    <td><input type="text" name="yekta_sso_token_guard_validate_url" value="<?php echo esc_attr(get_option('yekta_sso_token_guard_validate_url')); ?>" style="width:100%;" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">آدرس بازگشت (پس از ورود)</th>
                    <td><input type="text" name="yekta_sso_token_guard_redirect_url" value="<?php echo esc_attr(get_option('yekta_sso_token_guard_redirect_url')); ?>" style="width:100%;" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">آدرس خروج</th>
                    <td><input type="text" name="yekta_sso_token_guard_logout_url" value="<?php echo esc_attr(get_option('yekta_sso_token_guard_logout_url')); ?>" style="width:100%;" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">کلید عمومی</th>
                    <td><textarea name="yekta_sso_token_guard_public_key" rows="4" style="width:100%;"><?php echo esc_textarea(get_option('yekta_sso_token_guard_public_key')); ?></textarea></td>
                </tr>
                <tr valign="top">
                    <th scope="row">آدرس اطلاعات کاربر</th>
                    <td><input type="text" name="yekta_sso_token_guard_userinfo_url" value="<?php echo esc_attr(get_option('yekta_sso_token_guard_userinfo_url')); ?>" style="width:100%;" /></td>
                </tr>
            </tbody>
        </table>
        <?php submit_button(); ?>
    </form>
    <div class="auth-fields" style="margin-top:20px;">
        <?php
            global $wpdb;
            $table = $wpdb->prefix.'yekta_allowed_origins';
            $origins = $wpdb->get_results("SELECT * FROM {$table}");
        ?>
        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
            <?php wp_nonce_field('yekta_manage_origins'); ?>
            <input type="hidden" name="action" value="yekta_manage_origins">
            <table class="widefat">
                <thead>
                    <tr><th>IP</th><th>Client ID</th><th>Redirect URL</th></tr>
                </thead>
                <tbody id="yekta-origins-table">
                    <?php foreach($origins as $origin): ?>
                        <tr>
                            <td>
                                <input type="hidden" name="id[]" value="<?php echo esc_attr($origin->id); ?>">
                                <input type="text" name="ip[]" value="<?php echo esc_attr($origin->ip); ?>">
                            </td>
                            <td><input type="text" name="client_id[]" value="<?php echo esc_attr($origin->client_id); ?>"></td>
                            <td><input type="text" name="redirect_url[]" value="<?php echo esc_attr($origin->redirect_url); ?>" style="width:100%;"></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="button" id="add-origin" class="button">افزودن</button>
            <?php submit_button('ذخیره مبدأها'); ?>
        </form>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded',function(){
        const select=document.getElementById('yekta_sso_service_type');
        function toggle(){
            const isAuth=select.value==='auth';
            document.querySelector('.client-fields').style.display=isAuth?'none':'';
            document.querySelector('.auth-fields').style.display=isAuth?'':'none';
        }
        if(select){select.addEventListener('change',toggle);toggle();}
        const addBtn=document.getElementById('add-origin');
        if(addBtn){
            addBtn.addEventListener('click',function(e){
                e.preventDefault();
                const tbody=document.getElementById('yekta-origins-table');
                const tr=document.createElement('tr');
                tr.innerHTML='<td><input type="hidden" name="id[]" value=""><input type="text" name="ip[]"></td><td><input type="text" name="client_id[]"></td><td><input type="text" name="redirect_url[]" style="width:100%;"></td>';
                tbody.appendChild(tr);
            });
        }
    });
    </script>
</div>
