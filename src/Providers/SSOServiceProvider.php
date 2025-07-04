<?php

namespace App\Providers;

use DateTime;
use App\Models\UserCart;
use App\Services\AuthService;
use App\Services\BlogService;
use App\Services\ProductService;
use App\Services\VideoService;
use App\Services\WooService;
use Kernel\Container;
use Kernel\Facades\Auth;
use Kernel\Facades\Wordpress;
use Kernel\PostType;

class SSOServiceProvider
{

    public function register() {}

    public function boot()
    {
        $method     = get_option('yekta_sso_method', appConfig('adapters.auth.default', 'token'));
        $loginParam = get_option('yekta_sso_login_param', 'login');
        $codeParam  = get_option('yekta_sso_code_param', 'code');
        $guard      = call_user_func([Auth::class, $method]);

        if (isset($_GET[$loginParam]) && $_GET[$loginParam] === 'true' && !is_user_logged_in()) {
            if ($method === 'password') {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $guard->attempt([
                        'username' => $_POST['username'] ?? '',
                        'password' => $_POST['password'] ?? '',
                    ]);
                    $this->remove_code_param_redirect($codeParam, $loginParam);
                } else {
                    echo '<form method="post"><input type="text" name="username" placeholder="Username" /><input type="password" name="password" placeholder="Password" /><button type="submit">Login</button></form>';
                    exit;
                }
            } elseif ($method === 'secret') {
                $guard->attempt([]);
                $this->remove_code_param_redirect($codeParam, $loginParam);
            } else {
                wp_redirect($guard->getLoginUrl());
                exit;
            }
        }
        if (isset($_GET[$codeParam])) {
            $guard->attempt([
                'code'  => $_GET[$codeParam],
                'state' => $_GET['state'] ?? null,
            ]);
            $this->remove_code_param_redirect($codeParam, $loginParam);
        }

        Wordpress::filter('login_url', function ($login_url, $redirect, $force_reauth) use ($guard) {
            appLogger('setting login url');
            return $guard->getLoginUrl();
        }, 1, 3);
        Wordpress::action('wp_logout', function() use ($guard){
            $user = $guard->user();
            if($user){
                $guard->revokeToken($user);
            }
            wp_safe_redirect( home_url() );
            exit;
        });
    }
    
    public function remove_code_param_redirect($codeParam = 'code', $loginParam = 'login') {
        $current_url = $_SERVER['REQUEST_URI'];

        $url_parts = parse_url($current_url);
        parse_str($url_parts['query'] ?? '', $query_params);

        unset($query_params[$codeParam], $query_params[$loginParam]);

        $new_query_string = http_build_query($query_params);
        $new_url = $url_parts['path'] . ($new_query_string ? '?' . $new_query_string : '');

        wp_redirect($new_url);
        exit;
    }
}
