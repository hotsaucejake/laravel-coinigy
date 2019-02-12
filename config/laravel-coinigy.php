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
        'base'  => 'https://api.coinigy.com/api/v2/',
        'public'  => 'https://api.coinigy.com/api/v2/public/',
        'private'  => 'https://api.coinigy.com/api/v2/',
    ],
];
