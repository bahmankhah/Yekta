<?php

namespace App\Providers;

use Kernel\Facades\Wordpress;

class PanelServiceProvider 
{
    public function register() {}

    public function boot()
    {
        Wordpress::action('admin_menu',function(){
                add_options_page(
                    'SSO Settings',
                    'SSO Settings',
                    'manage_options',
                    'my-sso-plugin',
                    function (){
                        return view('pages.settings');
                    }
                );
        });
        Wordpress::action('admin_init',function(){
            register_setting('yekta_sso_options_group', 'yekta_sso_method', ['sanitize_callback' => 'sanitize_text_field']);

            register_setting('yekta_sso_options_group', 'yekta_sso_token_guard_client_id', ['sanitize_callback' => 'sanitize_text_field']);
            register_setting('yekta_sso_options_group', 'yekta_sso_token_guard_login_url', ['sanitize_callback' => 'esc_url_raw']);
            register_setting('yekta_sso_options_group', 'yekta_sso_token_guard_validate_url', ['sanitize_callback' => 'esc_url_raw']);
            register_setting('yekta_sso_options_group', 'yekta_sso_token_guard_redirect_url', ['sanitize_callback' => 'esc_url_raw']);
            register_setting('yekta_sso_options_group', 'yekta_sso_token_guard_logout_url', ['sanitize_callback' => 'esc_url_raw']);
            register_setting('yekta_sso_options_group', 'yekta_sso_token_guard_public_key');
            register_setting('yekta_sso_options_group', 'yekta_sso_token_guard_userinfo_url', ['sanitize_callback' => 'esc_url_raw']);

            register_setting('yekta_sso_options_group', 'yekta_sso_password_guard_token_endpoint', ['sanitize_callback' => 'esc_url_raw']);
            register_setting('yekta_sso_options_group', 'yekta_sso_password_guard_secret_key', ['sanitize_callback' => 'sanitize_text_field']);
            register_setting('yekta_sso_options_group', 'yekta_sso_password_guard_redirect_url', ['sanitize_callback' => 'esc_url_raw']);
            register_setting('yekta_sso_options_group', 'yekta_sso_password_guard_logout_url', ['sanitize_callback' => 'esc_url_raw']);
            register_setting('yekta_sso_options_group', 'yekta_sso_password_guard_public_key');
            register_setting('yekta_sso_options_group', 'yekta_sso_password_guard_userinfo_url', ['sanitize_callback' => 'esc_url_raw']);

            register_setting('yekta_sso_options_group', 'yekta_sso_secret_guard_token_endpoint', ['sanitize_callback' => 'esc_url_raw']);
            register_setting('yekta_sso_options_group', 'yekta_sso_secret_guard_secret_key', ['sanitize_callback' => 'sanitize_text_field']);
            register_setting('yekta_sso_options_group', 'yekta_sso_secret_guard_redirect_url', ['sanitize_callback' => 'esc_url_raw']);
            register_setting('yekta_sso_options_group', 'yekta_sso_secret_guard_logout_url', ['sanitize_callback' => 'esc_url_raw']);
            register_setting('yekta_sso_options_group', 'yekta_sso_secret_guard_public_key');
            register_setting('yekta_sso_options_group', 'yekta_sso_secret_guard_userinfo_url', ['sanitize_callback' => 'esc_url_raw']);
        });
    }
}
