<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel CORS Configuration (for Laravel 12)
    |--------------------------------------------------------------------------
    |
    | This configuration allows your Vue 3 + Vite frontend running on port 5173
    | to access your Laravel backend running on port 8000.
    |
    */

    'paths' => [
        'api/*',
        'login',
        'register',
        'sanctum/csrf-cookie',
    ],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:5173',
        'http://127.0.0.1:5173',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,
];