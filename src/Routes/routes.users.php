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

Route::domain(config('hc.admin_domain'))
    ->middleware(['api', 'auth:api'])
    ->prefix('v1/api/users')
    ->group(function () {
        Route::get('/', 'HCUserController@index')
            ->name('v1.api.users.index')
            ->middleware('acl:honey_comb_core_user_list');

        Route::get('list', 'HCUserController@getListPaginate')
            ->name('v1.api.users.list')
            ->middleware('acl:honey_comb_core_user_list');

        Route::get('options', 'HCUserController@getOptions')
            ->name('v1.api.users.options');

        Route::post('/', 'HCUserController@store')
            ->name('v1.api.users.create')
            ->middleware('acl:honey_comb_core_user_create');

        Route::delete('/', 'HCUserController@deleteSoft')
            ->name('v1.api.users.delete')
            ->middleware('acl:honey_comb_core_user_delete');

        Route::post('restore', 'HCUserController@restore')
            ->name('v1.api.users.restore')
            ->middleware('acl:honey_comb_core_user_update');

        Route::delete('force', 'HCUserController@deleteForce')
            ->name('v1.api.users.destroy.force')
            ->middleware('acl:honey_comb_core_user_force_delete');

        Route::prefix('{id}')->group(function () {
            Route::get('/', 'HCUserController@getById')
                ->name('v1.api.users.single')
                ->middleware('acl:honey_comb_core_user_list');

            Route::put('/', 'HCUserController@update')
                ->name('v1.api.users.update')
                ->middleware('acl:honey_comb_core_user_update');

            Route::patch('strict', 'HCUserController@patch')
                ->name('v1.api.users.patch')
                ->middleware('acl:honey_comb_core_user_update');
        });
    });
