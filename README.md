# honeycomb-core [![Build Status](https://travis-ci.org/honey-comb/core.svg?branch=master)](https://travis-ci.org/honey-comb/core)  
https://github.com/honey-comb/core

## Description

HoneyComb CMS Core package for backend elements

## Attention

This is part core package HoneyComb CMS package. It require `starter` package.
 
If you want to use laravel version 5.6.* [use core package version 0.3.*](https://github.com/honey-comb/core/tree/5.6 "Core package version 0.3.*")

## Requirement

 - php: `^7.1.3`
 - laravel: `^5.7`
 - composer
 
 ## Installation

Begin by installing this package through Composer.


```json
	{
	    "require": {
	        "honey-comb/core": "^0.4"
	    }
	}
```
or
```js
    composer require honey-comb/core
```

## Laravel integration

 
To customize middleware:
* disable middleware in hc.php file adding value to ignoreDefaultMiddleware property
```php
    'ignoreDefaultMiddleware' => [ ],
```
* manualy add middleware to kernel.php

## Preparation
### Users

Make sure to update the User controller in `config/auth.php`

```php
'defaults' => [
    'guard' => 'api',
    'passwords' => 'users',
],
```

```php
'api' => [
    'driver' => 'passport',
    'provider' => 'users',
],
```

```php
'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => \HoneyComb\Core\Models\HCUser::class,
    ],
],
```

```php
'passwords' => [
    'users' => [
        'provider' => 'users',
        'table' => 'hc_user_password_reset',
        'expire' => 60,
    ],
],
```

### Handlers

Make sure to use a exceptions handler trait in `app/Exceptions/Handler.php`

```php
<?php

namespace App\Exceptions;

use HoneyComb\Core\Exceptions\Traits\HCExceptionHandlerTrait;

class Handler extends ExceptionHandler
{
    use HCExceptionHandlerTrait;
}
```

### Database

We recomend to use `utf8mb4_unicode_520_ci` collation, so you can update it in `config/database.php`

```php
    'collation' => 'utf8mb4_unicode_520_ci'
```
### Passport installation

#### Migrations

- In `AppServiceProvider` `register()` method add `\Laravel\Passport\Passport::ignoreMigrations();`
- publish migrations `php artisan vendor:publish --tag=passport-migrations`
- update `user_id` fields to `uuid` e.g. `$table->integer('user_id');` -> `$table->uuid('user_id');` in tables:
  - `oauth_auth_codes`
  - `oauth_access_tokens`
  - `oauth_clients`

#### Install
`php artisan passport:install`

### Commands
    
Remove default Laravel user migrations (if it is a clean project)

    2014_10_12_000000_create_users_table.php
    2014_10_12_100000_create_password_resets_table.php
    
Run Artisan commands

    php artisan migrate
    php artisan hc:seed
    php artisan hc:permissions
    php artisan hc:forms
    php artisan hc:admin-menu   
    php artisan hc:super-admin
   
