<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'https://don-onofre-zeta.vercel.app',
        'https://add6-170-245-133-249.ngrok-free.app', // Agrega tu ngrok aquí
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => [
        'Content-Type',
        'X-Requested-With',
        'Authorization',
        'Accept',
        'Origin'
    ],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,
];


