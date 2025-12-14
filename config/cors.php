<?php

return [
    'paths' => ['*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        env('FRONTEND_URL'),
    ],

    'allowed_headers' => [
        'Content-Type',
        'Accept',
        'Authorization',
        'X-Requested-With',
    ],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,
];
