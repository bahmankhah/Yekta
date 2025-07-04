<?php

return [

    'auth' => [
        'default' => 'sso',
        'contexts' => [
            'sso' => [
                'context' => Kernel\Auth\Guards\SSOGuard::class,
                'login_url' => 'https://tauth.platform.donap.ir/realms/donap/protocol/openid-connect/auth?client_id={clientId}&response_type=code',
                'client_id' => 'market',
                'validate_url'=>'https://tauth.platform.donap.ir/realms/donap/protocol/openid-connect/token'
            ]
        ]
    ],

];
