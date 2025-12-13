<?php

return [
    'secret_key' => env('XENDIT_SECRET_KEY'),
    'public_key' => env('XENDIT_PUBLIC_KEY'),
    'base_url' => env('XENDIT_BASE_URL', 'https://api.xendit.co'),
    'verification_token' => env('XENDIT_VERIFICATION_TOKEN'),
    'is_active' => env('XENDIT_IS_ACTIVE', false),
];
