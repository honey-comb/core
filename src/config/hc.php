<?php

declare(strict_types = 1);

return [

    /*
    |--------------------------------------------------------------------------
    | Admin domain for all admin routes
    |--------------------------------------------------------------------------
    |
    | You can add custom domain for admin routes.
    |
    */
    'admin_domain' => env ('HC_ADMIN_DOMAIN', ''),
    
    /*
    |--------------------------------------------------------------------------
    | Admin url prefix for all admin routes
    |--------------------------------------------------------------------------
    |
    | You can add custom prefix for admin urls. Instead of "/admin" you can set this to any name that you want
    | By default admin url is "/admin"
    |
    */
    'admin_url' => env('HC_ADMIN_URL', 'admin'),

    /*
    |--------------------------------------------------------------------------
    | Admin skin color
    |--------------------------------------------------------------------------
    |
     */
    'admin_skin' => "skin-blue",

    /*
    |--------------------------------------------------------------------------
    | Registration page
    |--------------------------------------------------------------------------
    |
    | You can allow users to register their account by themselves
    |
    */
    'allow_registration' => true,

    /*
    |---------------------------------------------------------------------------
    | Redirect url
    |---------------------------------------------------------------------------
    */
    'auth_redirect' => 'auth/login',


    /*
    |---------------------------------------------------------------------------
    | Google map api key
    |---------------------------------------------------------------------------
    */

    'google_map_api_key' => env('GOOGLE_MAP_API_KEY', ''),

    /*
    |---------------------------------------------------------------------------
    | List of models to override by repositories
    |---------------------------------------------------------------------------
    */
    'model_list' => [],

    /*
    |---------------------------------------------------------------------------
    | Log user activity at certain intervals in minutes. By default 15 minutes
    |---------------------------------------------------------------------------
    */
    'logActivityTime' => ENV('HC_LOG_USER_ACTIVITY_INTERVAL', 15),

];
