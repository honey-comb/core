<?php
/**
 * @copyright 2017 interactivesolutions
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
 * Contact InteractiveSolutions:
 * E-mail: hello@interactivesolutions.lt
 * http://www.interactivesolutions.lt
 */

declare(strict_types = 1);

Route::prefix(config('hc.admin_url'))
    ->namespace('Admin')
    ->middleware(['web', 'auth'])
    ->group(function () {

        Route::get('users', 'HCUserController@index')
            ->name('admin.user.index')
            ->middleware('acl:honey_comb_core_user_list');

        Route::prefix('api/user')->group(function () {

            Route::get('/', 'HCUserController@getListPaginate')
                ->name('admin.api.user')
                ->middleware('acl:honey_comb_core_user_list');

            Route::get('/options', 'HCUserController@getOptions')
                ->name('admin.api.user.options')
                ->middleware('acl:honey_comb_core_user_list');

            Route::post('/', 'HCUserController@store')
                ->name('admin.api.create')
                ->middleware('acl:honey_comb_core_user_create');

            Route::delete('/', 'HCUserController@deleteSoft')
                ->name('admin.api.user')
                ->middleware('acl:honey_comb_core_user_delete');

            Route::post('restore', 'HCUserController@restore')
                ->name('admin.api.user.restore')
                ->middleware('acl:honey_comb_core_user_update');

            Route::delete('force', 'HCUserController@deleteForce')
                ->name('admin.api.user.destroy.force')
                ->middleware('acl:honey_comb_core_user_force_delete');

            Route::prefix('{id}')->group(function () {
                Route::get('/', 'HCUserController@getById')
                    ->name('admin.api.user.single')
                    ->middleware('acl:honey_comb_core_user_list');

                Route::put('/', 'HCUserController@update')
                    ->name('admin.api.user.update')
                    ->middleware('acl:honey_comb_core_user_update');

                Route::patch('strict', 'HCUserController@patch')
                    ->name('admin.api.user.patch')
                    ->middleware('acl:honey_comb_core_user_update');
            });
        });
    });
