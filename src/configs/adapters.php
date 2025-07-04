<?php

return [

    'auth' => [
        'default' => 'sso',
        'contexts' => [
            'sso' => [
                'context' => Kernel\Auth\Guards\TokenSSOGuard::class,
                'login_url' => 'https://tauth.platform.donap.ir/realms/donap/protocol/openid-connect/auth?client_id={clientId}&response_type=code',
                'client_id' => 'market',
                'validate_url'=>'https://tauth.platform.donap.ir/realms/donap/protocol/openid-connect/token'
            ],
            'password' => [
                'context' => Kernel\Auth\Guards\PasswordSSOGuard::class,
            ],
            'secret' => [
                'context' => Kernel\Auth\Guards\SecretKeySSOGuard::class,
            ]
        ]
    ],

];
