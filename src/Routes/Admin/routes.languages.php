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
        Route::get('languages', 'HCLanguageController@index')
            ->name('admin.language.index')
            ->middleware('acl:honey_comb_core_language_list');

        Route::get('api/languages', 'HCLanguageController@getListPaginate')
            ->name('admin.api.language')
            ->middleware('acl:honey_comb_core_language_list');

        Route::get('api/languages/options', 'HCLanguageController@getOptions')
            ->name('admin.api.language.options')
            ->middleware('acl:honey_comb_core_language_option');

        Route::patch('api/languages/{id}', 'HCLanguageController@patch')
            ->name('admin.api.language.update.strict')
            ->middleware('acl:honey_comb_core_language_update');
    });
