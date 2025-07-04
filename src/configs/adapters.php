<?php

return [

    'auth' => [
        'default' => 'sso',
        'contexts' => [
            'sso' => [
                'context' => Kernel\Auth\Guards\TokenSSOGuard::class,
            ]
        ]
    ],

];
