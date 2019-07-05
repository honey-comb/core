<?php
/**
 * @copyright 2019 innovationbase
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * Contact InnovationBase:
 * E-mail: hello@innovationbase.eu
 * https://innovationbase.eu
 */

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
    'admin_domain' => env('HC_ADMIN_DOMAIN', ''),

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
    'admin_skin' => 'skin-blue',

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

    /*
    |---------------------------------------------------------------------------
    | List of middleware to ignore by honeycomb package name
    |---------------------------------------------------------------------------
    |
    | Enter package name which you can find in his ServiceProvider
    | i.e.:
    |
    |    'HCCore' => [
    |        \HoneyComb\Starter\Http\Middleware\HCCurrentLanguage::class,
    |    ]
    |
    */
    'ignoreDefaultMiddleware' => [
        //
    ],

    /*
    |---------------------------------------------------------------------------
    | Config service
    |---------------------------------------------------------------------------
    |
    */
    'config_service' => \HoneyComb\Core\Services\HCConfigService::class,

    /*
    |---------------------------------------------------------------------------
    | Cache keys
    |---------------------------------------------------------------------------
    |
    | HC cache keys
    |
    */
    'admin_menu_cache_key' => ENV('HC_ADMIN_MENU_CACHE_KEY', '_hc_admin_menu_cache_key'),

    /*
    |---------------------------------------------------------------------------
    | Permissions
    |---------------------------------------------------------------------------
    |
    | Enable acl permissions
    |
    */
    'enable_permissions' => true,
];
