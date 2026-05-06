<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    | Google reCAPTCHA v2 Checkbox — opsional untuk form publik (mis. layanan mandiri).
    */
    'recaptcha' => [
        'site_key' => env('RECAPTCHA_SITE_KEY'),
        'secret_key' => env('RECAPTCHA_SECRET_KEY'),
    ],

    'holiday_api' => [
        'url' => env('INDONESIA_HOLIDAY_API_URL', 'https://api-harilibur.vercel.app/api?year={year}'),
        'fallback_urls' => [
            'https://api-hari-libur.vercel.app/api?year={year}',
            'https://api-harilibur.pages.dev/api?year={year}',
            'https://api-harilibur.netlify.app/api?year={year}',
        ],
        'date_key' => env('INDONESIA_HOLIDAY_API_DATE_KEY', 'holiday_date'),
        'name_key' => env('INDONESIA_HOLIDAY_API_NAME_KEY', 'holiday_name'),
        'national_key' => env('INDONESIA_HOLIDAY_API_NATIONAL_KEY', 'is_national_holiday'),
        'cache_ttl' => env('INDONESIA_HOLIDAY_API_CACHE_TTL', 86400),
    ],

];
