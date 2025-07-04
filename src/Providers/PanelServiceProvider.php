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
            register_setting('my_sso_options_group', 'my_sso_token_endpoint');
            register_setting('my_sso_options_group', 'my_sso_secret_key');
            register_setting('my_sso_options_group', 'my_sso_redirect_url');
            register_setting('my_sso_options_group', 'my_sso_method');
        });
    }
}
