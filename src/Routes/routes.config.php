<?php

Route::middleware(['api'])
    ->prefix('v1/api/config')
    ->group(function () {
        Route::get('initial', 'HCConfigController@getInitial')
        ->name('v1.api.config.initial');
    });
