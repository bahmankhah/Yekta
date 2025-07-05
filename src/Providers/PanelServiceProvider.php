<?php

namespace App\Providers;

use Kernel\Facades\Wordpress;

class PanelServiceProvider 
{
    public function register() {}

    public function boot()
    {
        Wordpress::action('admin_menu',function(){
                add_menu_page(
                    'کاربران SSO',
                    'کاربران SSO',
                    'manage_options',
                    'yekta-sso-users',
                    function(){
                        return view('pages.sso-users');
                    }
                );

                add_submenu_page(
                    'yekta-sso-users',
                    'تنظیمات SSO',
                    'تنظیمات SSO',
                    'manage_options',
                    'yekta-sso-settings',
                    function(){
                        return view('pages.settings');
                    }
                );
        });
        Wordpress::action('admin_init',function(){
            register_setting('yekta_sso_options_group', 'yekta_sso_service_type', ['sanitize_callback' => 'sanitize_text_field']);
            register_setting('yekta_sso_options_group', 'yekta_sso_method', ['sanitize_callback' => 'sanitize_text_field']);
            register_setting('yekta_sso_options_group', 'yekta_sso_login_param', ['sanitize_callback' => 'sanitize_text_field']);
            register_setting('yekta_sso_options_group', 'yekta_sso_code_param', ['sanitize_callback' => 'sanitize_text_field']);

            register_setting('yekta_sso_options_group', 'yekta_sso_token_guard_client_id', ['sanitize_callback' => 'sanitize_text_field']);
            register_setting('yekta_sso_options_group', 'yekta_sso_token_guard_login_url', ['sanitize_callback' => 'esc_url_raw']);
            register_setting('yekta_sso_options_group', 'yekta_sso_token_guard_validate_url', ['sanitize_callback' => 'esc_url_raw']);
            register_setting('yekta_sso_options_group', 'yekta_sso_token_guard_redirect_url', ['sanitize_callback' => 'esc_url_raw']);
            register_setting('yekta_sso_options_group', 'yekta_sso_token_guard_logout_url', ['sanitize_callback' => 'esc_url_raw']);
            register_setting('yekta_sso_options_group', 'yekta_sso_token_guard_public_key');
            register_setting('yekta_sso_options_group', 'yekta_sso_token_guard_userinfo_url', ['sanitize_callback' => 'esc_url_raw']);

        });

        Wordpress::action('wp_ajax_yekta_get_user_info', function(){
            if(!current_user_can('manage_options')){
                wp_send_json_error('forbidden');
            }
            $user_id = intval($_GET['user_id'] ?? 0);
            $method = get_option('yekta_sso_method', appConfig('adapters.auth.default', 'token'));
            $guard  = call_user_func([\Kernel\Facades\Auth::class, $method]);
            $user   = get_user_by('id', $user_id);
            if(!$user){
                wp_send_json_error('not found');
            }
            $info = $guard->getUserInfo($user);
            wp_send_json($info);
        });

        Wordpress::action('admin_post_yekta_manage_origins', function(){
            if(!current_user_can('manage_options')){
                wp_die('forbidden');
            }
            check_admin_referer('yekta_manage_origins');
            global $wpdb;
            $table = $wpdb->prefix . 'yekta_allowed_origins';
            $ids = $_POST['id'] ?? [];
            $ips = $_POST['ip'] ?? [];
            $clients = $_POST['client_id'] ?? [];
            $redirects = $_POST['redirect_url'] ?? [];
            foreach($clients as $i => $client){
                $id = intval($ids[$i]);
                $data = [
                    'ip' => sanitize_text_field($ips[$i] ?? ''),
                    'client_id' => sanitize_text_field($client),
                    'redirect_url' => esc_url_raw($redirects[$i] ?? '')
                ];
                if($id){
                    $wpdb->update($table, $data, ['id' => $id]);
                }else if($client){
                    $wpdb->insert($table, array_merge($data,['created_at'=>current_time('mysql')]));
                }
            }
            wp_redirect(admin_url('admin.php?page=yekta-sso-settings&updated=1'));
            exit;
        });
    }
}
