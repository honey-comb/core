# honeycomb-core [![Build Status](https://travis-ci.org/honey-comb/core.svg?branch=master)](https://travis-ci.org/honey-comb/core)  
https://github.com/honey-comb/core

## Description

HoneyComb CMS Core package for backend elements

## Attention

This is part core package HoneyComb CMS package.

## Requirement

 - php: `^7.1`
 - laravel: `^5.5`
 - composer
 - npm
 
 ## Installation

Begin by installing this package through Composer.


```js
	{
	    "require": {
	        "honey-comb/core": "*"
	    }
	}
```
or
```js
    composer require honey-comb/core
```

## Laravel integration

Firstly register the service provider and Facade by opening `config/app.php`

    HoneyComb\Core\Providers\HCCoreServiceProvider::class,

## Preparation
### Users

Make sure to update the User controller in `config/auth.php`

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

### Database

We recomend to use `utf8mb4_unicode_520_ci` collation, so you can update it in `config/database.php`

```php
    'collation' => 'utf8mb4_unicode_520_ci'
```

### Commands

Make Laravel project react friendly

    php artisan preset react
    
Publishing assets only once

    php artisan vendor:publish --tag=hc-config --force
    php artisan vendor:publish --tag=hc-assets
    
Install npm modules

    npm install

----
```diff
- *Do this if you do not own FontAwesome5 Pro License:
```

Inside `package.json` file remove:

    "@fortawesome/fontawesome-pro-light": "*",
    "@fortawesome/fontawesome-pro-regular": "*",
    "@fortawesome/fontawesome-pro-solid": "*",
    
Inside `resources/assets/honey-comb/react/shared/hc/Globals.js` file change the FontAwesome5 prefix

----
    
Build Front End application

    npm run dev
    
Remove default Laravel user migrations (if it is a clean project)

    2014_10_12_000000_create_users_table.php
    2014_10_12_100000_create_password_resets_table.php
    
Run Artisan commands

    php artisan migrate
    php artisan hc:seed
    php artisan hc:permissions
    php artisan hc:forms
    php artisan hc:admin-menu   
    php artisan hc:project-size
    php artisan hc:super-admin
