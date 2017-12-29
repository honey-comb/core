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

Route::prefix('auth')->namespace('Frontend')->middleware('web')->group(function () {
    Route::get('login', 'HCAuthController@showLoginForm')->name('auth.index')->middleware('guest');
    Route::post('login', 'HCAuthController@login')->name('auth.login');

    Route::get('register', 'HCAuthController@showRegister')->name('auth.register')->middleware('guest');
    Route::post('register', 'HCAuthController@register');

    Route::get('activation/{token}', 'HCAuthController@showActivation')->name('auth.activation')->middleware('guest');
    Route::post('activation', 'HCAuthController@activate')->name('auth.activation.post');

    Route::get('logout', 'HCAuthController@logout')->name('auth.logout')->middleware('auth');
});
