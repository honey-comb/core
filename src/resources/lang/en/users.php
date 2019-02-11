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

return [
    'title' => [
        'list' => 'Users',
    ],

    'label' => [
        'email' => 'Email',
        'password' => 'Password',
        'password_confirmation' => 'Retype password',
        "new_password" => "New password",
        "new_password_confirmation" => "New password again",

        'first_name' => 'First name',
        'last_name' => 'Last name',
        'phone' => 'Phone',
        'address' => 'Address',
        'description' => 'Description',
        'notification_email' => 'Notification email',

        'is_active' => 'Is activated?',

        'role_groups' => 'Roles',

        'activated_at' => 'Activated at',
        'last_activity' => 'Last activity',
        'last_login' => 'Last login',

        'remember' => 'Remember me',
        'send_password' => 'Send password',
        'send_welcome_email' => 'Send welcome email',
    ],

    'error' => [
        'auth_bad_credentials' => 'Bad credentials',
        'auth_throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
    ],

    'message' => [
        'user_created' => 'Successfully created!',
        'user_updated' => 'Successfully updated!',
        'user_deleted' => 'Successfully deleted!',
        'user_restored' => 'Successfully restored',
        'logged_out' => 'Successfully logged out',

        'activation_check_email' => 'Check your email for activation link',
        'activation_resent_link' => 'We have resent a new activation link for your account. Please check your email.',
        'activation_bad_token' => 'There is a problem with a given token. Please check your email for correct token',
        'activation_user_not_found' => 'Something went wrong with user account, please try again to login or register.',
    ],

    'validation' => [
        'email_required' => 'Email is required!',
    ],

    'button' => [
        'submit' => 'Submit',
        'login' => 'Login',
        'register' => 'Register',
    ],
];