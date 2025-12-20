<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Content Security Policy Configuration
    |--------------------------------------------------------------------------
    |
    | This file defines the Content Security Policy directives for the application.
    | These settings help prevent XSS attacks and mixed content issues on HTTPS.
    |
    */

    'enabled' => env('CSP_ENABLED', true),

    'directives' => [
        'default-src' => [
            "'self'",
            'https:',
        ],

        'script-src' => [
            "'self'",
            "'unsafe-inline'", // Required for Vite in development and some inline scripts
            "'unsafe-eval'",   // Required for some frontend frameworks
            'https:',
            'https://cdn.tailwindcss.com',
            'https://js.stripe.com',
            'https://cdnjs.cloudflare.com',
            'https://cdn.jsdelivr.net',
        ],

        'style-src' => [
            "'self'",
            "'unsafe-inline'", // Required for Tailwind and dynamic styles
            'https:',
            'https://fonts.bunny.net',
            'https://cdnjs.cloudflare.com',
            'https://cdn.tailwindcss.com',
        ],

        'font-src' => [
            "'self'",
            'https:',
            'https://fonts.bunny.net',
        ],

        'img-src' => [
            "'self'",
            'data:',
            'https:',
            'https://images.unsplash.com',
        ],

        'connect-src' => [
            "'self'",
            'https:',
        ],

        'frame-src' => [
            "'self'",
            'https:',
        ],

        'media-src' => [
            "'self'",
            'https:',
        ],

        'object-src' => [
            "'none'",
        ],

        'base-uri' => [
            "'self'",
        ],

        'form-action' => [
            "'self'",
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Additional CSP Directives
    |--------------------------------------------------------------------------
    */

    'upgrade_insecure_requests' => env('CSP_UPGRADE_INSECURE_REQUESTS', true),
    'block_all_mixed_content' => env('CSP_BLOCK_ALL_MIXED_CONTENT', false),
    'require_sri_for' => [], // 'script', 'style', or both

    /*
    |--------------------------------------------------------------------------
    | CSP Reporting
    |--------------------------------------------------------------------------
    */

    'report_uri' => env('CSP_REPORT_URI', null),
    'report_to' => env('CSP_REPORT_TO', null),
];
