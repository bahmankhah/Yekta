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
            register_setting('my_sso_options_group', 'my_sso_method', ['sanitize_callback' => 'sanitize_text_field']);
            register_setting('my_sso_options_group', 'my_sso_client_id', ['sanitize_callback' => 'sanitize_text_field']);
            register_setting('my_sso_options_group', 'my_sso_login_url', ['sanitize_callback' => 'esc_url_raw']);
            register_setting('my_sso_options_group', 'my_sso_secret_key', ['sanitize_callback' => 'sanitize_text_field']);
            register_setting('my_sso_options_group', 'my_sso_token_endpoint', ['sanitize_callback' => 'esc_url_raw']);
            register_setting('my_sso_options_group', 'my_sso_redirect_url', ['sanitize_callback' => 'esc_url_raw']);
            register_setting('my_sso_options_group', 'my_sso_validate_url', ['sanitize_callback' => 'esc_url_raw']);
            register_setting('my_sso_options_group', 'my_sso_logout_url', ['sanitize_callback' => 'esc_url_raw']);
            register_setting('my_sso_options_group', 'my_sso_public_key');
            register_setting('my_sso_options_group', 'my_sso_userinfo_url', ['sanitize_callback' => 'esc_url_raw']);
        });
    }
}
