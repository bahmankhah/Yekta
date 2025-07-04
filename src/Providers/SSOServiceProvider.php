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
        $method = get_option('my_sso_method', appConfig('adapters.auth.default', 'token'));
        $guard  = call_user_func([Auth::class, $method]);

        if (strpos($_SERVER['REQUEST_URI'], '?login=true') !== false && !is_user_logged_in()) {
            wp_redirect($guard->getLoginUrl());
            exit;
        }
        if (isset($_GET['code'])) {
            $guard->attempt([
                'code'  => $_GET['code'],
                'state' => $_GET['state'] ?? null,
            ]);
            $this->remove_code_param_redirect();
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
    
    public function remove_code_param_redirect() {
        $current_url = $_SERVER['REQUEST_URI'];

        $url_parts = parse_url($current_url);
        parse_str($url_parts['query'] ?? '', $query_params);

        unset($query_params['code']);

        $new_query_string = http_build_query($query_params);
        $new_url = $url_parts['path'] . ($new_query_string ? '?' . $new_query_string : '');

        wp_redirect($new_url);
        exit;
    }
}
