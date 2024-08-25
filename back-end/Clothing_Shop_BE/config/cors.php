<?php
return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

      'paths' => ['api/*', 'sanctum/csrf-cookie'],

      'allowed_methods' => ['OPTIONS', 'GET', 'POST', 'PUT', 'PATCH', 'DELETE'],

      'allowed_origins' => ['*'], //http://127.0.0.1:8080/*

      'allowed_origins_patterns' => [''],

      'allowed_headers' => ['Origin, X-Api-Key, X-Requested-With, Content-Type, Accept, Authorization, X-Auth-Token'],

      'exposed_headers' => [],

      'max_age' => 0,

      'supports_credentials' => true,

];
