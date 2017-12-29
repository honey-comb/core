<?php

Route::prefix(config('hc.admin_url'))
    ->namespace('Admin')
    ->middleware(['web', 'auth'])
    ->group(function () {
        Route::get('/', 'HCAdminController@index')->name('admin.index');
    });
