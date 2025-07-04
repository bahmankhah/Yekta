<?php

return [

    'auth' => [
        'default' => 'token',
        'contexts' => [
            'token' => [
                'context'       => Kernel\Auth\Guards\TokenSSOGuard::class,
                'login_url'     => get_option('yekta_sso_token_guard_login_url'),
                'client_id'     => get_option('yekta_sso_token_guard_client_id'),
                'validate_url'  => get_option('yekta_sso_token_guard_validate_url'),
                'redirect_url'  => get_option('yekta_sso_token_guard_redirect_url'),
                'public_key'    => get_option('yekta_sso_token_guard_public_key'),
                'logout_url'    => get_option('yekta_sso_token_guard_logout_url'),
                'user_info_url' => get_option('yekta_sso_token_guard_userinfo_url'),
            ],
            'password' => [
                'context'        => Kernel\Auth\Guards\PasswordSSOGuard::class,
                'token_endpoint' => get_option('yekta_sso_password_guard_token_endpoint'),
                'secret_key'     => get_option('yekta_sso_password_guard_secret_key'),
                'redirect_url'   => get_option('yekta_sso_password_guard_redirect_url'),
                'public_key'     => get_option('yekta_sso_password_guard_public_key'),
                'logout_url'     => get_option('yekta_sso_password_guard_logout_url'),
                'user_info_url'  => get_option('yekta_sso_password_guard_userinfo_url'),
            ],
            'secret' => [
                'context'        => Kernel\Auth\Guards\SecretKeySSOGuard::class,
                'token_endpoint' => get_option('yekta_sso_secret_guard_token_endpoint'),
                'secret_key'     => get_option('yekta_sso_secret_guard_secret_key'),
                'redirect_url'   => get_option('yekta_sso_secret_guard_redirect_url'),
                'public_key'     => get_option('yekta_sso_secret_guard_public_key'),
                'logout_url'     => get_option('yekta_sso_secret_guard_logout_url'),
                'user_info_url'  => get_option('yekta_sso_secret_guard_userinfo_url'),
            ]
        ]
    ],

];
