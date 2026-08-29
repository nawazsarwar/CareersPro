<?php

declare(strict_types=1);

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

    /*
    | ProActive SMS (DR-024).
    |
    | The provider authenticates by QUERY PARAMETER. `user` and `password` are
    | therefore kept as separate keys and the adapter composes the URL at call
    | time -- a pre-composed URL containing credentials is never stored here,
    | in .env, or in system_settings. A log processor strips both keys from
    | any logged URL and from exception messages. Asserted by M03-R27.
    */
    'proactive' => [
        'endpoint' => env('SMS_PROACTIVE_ENDPOINT', 'https://www.proactivesms.in/sendsms.jsp'),
        'user' => env('SMS_PROACTIVE_USER'),
        'password' => env('SMS_PROACTIVE_PASSWORD'),
        'sender_id' => env('SMS_PROACTIVE_SENDER_ID', 'AMUCOE'),
    ],

];
