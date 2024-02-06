<?php
return [
    'redirect_home' => env('APP_URL').'/welcome',
    'version' => env('SHOPIFY_VERSION'),
    'client_id' => env('SHOPIFY_CLIENT_ID'),
    'client_secret' => env('SHOPIFY_CLIENT_SECRET'),
    'redirect_url' => env('APP_URL'),
    'scopes' => [
        'read_themes',
    ],
    'webhooks' => [],
];
