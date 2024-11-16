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

    'slack' => [
        'key' => env('TELEGRAM_KEY'),
        'url' => env('APP_URL', 'http://localhost').'/api/telegram?key='.env('TELEGRAM_KEY'),
        'username' => env('TELEGRAM_USERNAME'),
        'token' => env('TELEGRAM_TOKEN'),
    ],

];
