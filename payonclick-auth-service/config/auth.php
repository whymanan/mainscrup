<?php

return [
    'defaults' => [
        // 'guard' => 'api',
        "guard"     => env("AUTH_GUARD", "api"),

        'passwords' => 'users',
    ],

    'guards' => [
        'api' => [
            'driver' => 'jwt',
            'provider' => 'users',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => \App\User::class
        ]
    ],
    'channels' => [
      'custom' => [
          'driver' => 'custom',
          'via' => \App\Services\Logs\LogMonolog::class,
      ],
    ],
];
