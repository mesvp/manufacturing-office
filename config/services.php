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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'facebook' => [
        'client_id' => '1792524064434678', //USE FROM FACEBOOK DEVELOPER ACCOUNT
        'client_secret' => 'a7dea41b02563c8ec42c3d5dd0fb0048', //USE FROM FACEBOOK DEVELOPER ACCOUNT
        'redirect' => 'https://jobssolution.in/facebook/callback'
    ],
    'google' => [
        'client_id' => '920729048136-rv8hojrh10i0muco1pclosi6pammasve.apps.googleusercontent.com', //USE FROM Google DEVELOPER ACCOUNT
        'client_secret' => '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', //USE FROM Google DEVELOPER ACCOUNT
        'redirect' => 'https://jobssolution.in/google/callback'
],

];
