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

    'backup' => [
        'repo' => env('BACKUP_REPO'),
        'token' => env('BACKUP_GITHUB_TOKEN'),
        'keep' => env('BACKUP_KEEP', 30),
    ],

    /*
     * Vercel only attaches the automatic "Authorization: Bearer <secret>" header
     * to cron requests when the project variable is literally named CRON_SECRET,
     * so that name is read first. VERCEL_CRON_SECRET is kept as a fallback for
     * projects that already use it.
     */
    'cron' => [
        'secret' => env('CRON_SECRET') ?: env('VERCEL_CRON_SECRET'),
    ],

];
