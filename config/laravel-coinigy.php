<?php

return [

    /*
     ***************************************************************************
     * Coinigy Authentication
     ***************************************************************************
     *
     * Authentication key and secret for Bittrex API.
     *
     */

    'auth' => [
        'key'    => env('COINIGY_KEY', ''),
        'secret' => env('COINIGY_SECRET', ''),
    ],

    /*
     ***************************************************************************
     * API URLS
     ***************************************************************************
     *
     * Coinigy API endpoints
     *
     */

    'urls' => [
        'api' => 'https://api.coinigy.com',
        'base'  => '/api/v2/',
        'public'  => 'public/',
        'private'  => 'private/',
    ],
];
