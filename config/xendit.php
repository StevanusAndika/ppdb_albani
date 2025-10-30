<?php

return [
    'secret_key' => env('XENDIT_SECRET_KEY'),
    'public_key' => env('XENDIT_PUBLIC_KEY'),
    'callback_token' => env('XENDIT_CALLBACK_TOKEN'),
    'api_url' => env('XENDIT_API_URL', 'https://api.xendit.co'),
    'default_currency' => 'IDR',
    'invoice_duration' => 86400,
];
