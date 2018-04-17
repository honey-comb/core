<?php
Route::domain(config('hc.admin_domain'))
    ->prefix(config('hc.admin_url'))
    ->namespace('Admin')
    ->middleware(['web', 'auth'])
    ->group(function () {
        Route::get('/', 'HCAdminController@index')->name('admin.index');
    });


