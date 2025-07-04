<?php

return [

    'auth' => [
        'default' => 'token',
        'contexts' => [
            'token' => [
                'context'       => Kernel\Auth\Guards\TokenSSOGuard::class,
                'login_url'     => get_option('my_sso_login_url'),
                'client_id'     => get_option('my_sso_client_id'),
                'validate_url'  => get_option('my_sso_validate_url'),
                'redirect_url'  => get_option('my_sso_redirect_url'),
                'public_key'    => get_option('my_sso_public_key'),
                'logout_url'    => get_option('my_sso_logout_url'),
                'user_info_url' => get_option('my_sso_userinfo_url'),
            ],
            'password' => [
                'context'        => Kernel\Auth\Guards\PasswordSSOGuard::class,
                'token_endpoint' => get_option('my_sso_token_endpoint'),
                'secret_key'     => get_option('my_sso_secret_key'),
                'redirect_url'   => get_option('my_sso_redirect_url'),
                'public_key'     => get_option('my_sso_public_key'),
                'logout_url'     => get_option('my_sso_logout_url'),
                'user_info_url'  => get_option('my_sso_userinfo_url'),
            ],
            'secret' => [
                'context'        => Kernel\Auth\Guards\SecretKeySSOGuard::class,
                'token_endpoint' => get_option('my_sso_token_endpoint'),
                'secret_key'     => get_option('my_sso_secret_key'),
                'redirect_url'   => get_option('my_sso_redirect_url'),
                'public_key'     => get_option('my_sso_public_key'),
                'logout_url'     => get_option('my_sso_logout_url'),
                'user_info_url'  => get_option('my_sso_userinfo_url'),
            ]
        ]
    ],

];
