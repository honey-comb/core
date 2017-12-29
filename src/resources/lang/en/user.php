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

return [
    'page_title' => 'Users',
    'title' => 'Users',
    'name' => 'Users',
    'email' => 'E-mail',
    'firstname' => 'Firstname',
    'lastname' => 'Lastname',
    'sex' => 'Sex',
    'birthdate' => 'Birthdate',
    'street' => 'Street',
    'house' => 'House',
    'apartment' => 'Apartment',
    'postcode' => 'Postcode',
    'city' => 'City',
    'municipality' => 'Municipality',
    'companyName' => 'Company Name',
    'companyCode' => 'Company Code',
    'companyVat' => 'Company VAT',
    'role_groups' => 'HCAclRole',
    'male' => 'Male',
    'female' => 'Female',
    'provider' => 'Provider',
    'active' => 'Is activated?',
    'active_true' => 'Yes',
    'last_login' => 'Last login',
    'last_activity' => 'Last activity',
    'roles' => 'HCAclRole',
    'activity' => 'Activity',

    'tabs' => [
        'main' => 'Main',
        'personal' => 'Personal',
        'info' => 'Info',
    ],

    /*
     * Personal data
     */
    'photo' => 'Photo',
    'nickname' => 'Nickname',
    'full_name' => 'Full name',
    'gender' => 'Gender',
    'phone' => 'Phone',
    'avatar' => 'Avatar',

    'login' => [
        'title' => 'Sign in to start your session',
        'sign-in' => 'Sign in',
        'email' => 'E-mail',
        'password' => 'Password',
        're-password' => 'Retype password',
        'remember' => 'Remember me',
    ],

    'register' => [
        'title' => 'Register a new account',
        'sign-up' => 'Sign up',
        'email' => 'E-mail',
        'password' => 'Password',
        'password_again' => 'Password confirmation',
    ],

    'activation' => [
        'activated_at' => 'Activated at',
        'title' => 'Account activation',
        'info' => 'You have to active your account.',
        'activate' => 'Activate',
        'token_not_exists' => 'Token does not exists!',
        'token_expired' => 'Token is expired!',
        'back_to_main' => 'Back to main page',
        'bad_token' => 'There is a problem with a given token. Please check your email for correct token',
        'user_not_found' => 'Something went wrong with user account, please try again to login or register.',
        'check_email' => 'Check your email for activation link',
        'resent_activation' => 'We have resent a new activation link for your account. Please check your email.',
        'activate_account' => 'We have sent to your given email address an activation link.
         Please check your email and activate your account.',

        'mail' => [
            'subject' => 'Account confirmation',
            'from' => 'Administrator',
            'text' => 'In order to login you have to verify your email address <strong>:email</strong>',
        ],
    ],

    'connect_with_fb' => 'Connect with <strong>Facebook</strong>',

    'facebook' => [
        'title' => 'Facebook',
        'errors' => [
            'email' => 'Email option is required!',
            'user_friends' => 'Friends option is required!',
        ],
    ],

    'errors' => [
        'login' => 'Blogi prisijungimo duomenys!',
        'to_many_attempts' => 'Too many login attempts. Please try again in :seconds seconds.',
        'nickname_exists' => 'Nickname already exists!',
        'facebook' => 'Please try again.',
        'badOldPass' => 'Wrong old password!',
    ],

    'send_welcome_email' => 'Send welcome email',
    'send_password' => 'Send password',

    'welcome_email' => [
        'greeting' => 'Congratulations!',
        'subject' => 'You have successfully registered!',
        'text' => 'Your account has been created!',
        'show_email' => 'Email: <strong>:email</strong>',
        'show_password' => 'Password: <strong>:password</strong>',
        'login_link' => 'Login link',
        'activation_required' => 'But first you need to activate your account!
         You will receive and email with activation instructions.',
    ],

    'registered' => [
        'success' => 'Your new account',
        'administrator' => 'Administrator',
        'nickname' => 'Welcome, <b>:nickname</b>',
        'email' => 'Now you can login with your email: <b>:email</b>',
        'loginpage' => 'Go to login page: <a href=":loginpage">Login</a>',
        'password' => 'Your password is <b>:password</b> don\'t share it with others!',
    ],

    'passwords' => [
        "forgot_password" => "Forgot password?",
        "email" => 'Email',
        "can_login" => "Now you can log in <a href=':url'>Log in</a>",
        "reset_view" => "Password reset",
        "reset_button" => "Reset password",
        "remind_button" => "Send remind",
        "new" => "New password",
        "new_again" => "New password again",
        "old" => "Old password",
        "click_here" => "Click this link if you want to reset the password : <a href=':link'>Reset</a>",
        "remind" => "Send me password reset link",
        "password" => "Passwords must be at least six characters and match the confirmation.",
        "user" => "We can't find a user with that e-mail address.",
        "token" => "This password reset token is invalid.",
        "sent" => "We have sent your password reset link to you! Check email!",
        "reset" => "Your password has been reset!",
        "subject" => "Your password reset link.",
    ],

    'access' => [
        'title' => 'User access',
    ],

    'admin' => [
        'menu' => [
            'logout' => 'Logout',
            'roles' => 'HCAclRole: ',
            'online' => 'Online',
            'profile' => 'Profile',
        ],

        'member_since' => 'Member since :date',
    ],

    'validator' => [
        'nickname' => [
            'required' => 'You must enter nickname!',
            'unique' => 'Nickname already exists!',
            'min' => 'Nickname must be at least :count characters',
        ],

        'email' => [
            'required' => 'Email address is required!',
            'unique' => 'Given email already exists!',
            'min' => 'Email must be at least :count characters',
        ],

        'password' => [
            'required' => 'Password is required!',
            'min' => 'Password must be at least :count characters',
            'confirmed' => 'The password confirmation does not match.',
        ],

        'roles' => [
            'required' => 'HCAclRole are required!',
            'cant_update_super' => 'You can\'t update super admin role!',
            'cant_update_roles' => 'You can\'t update roles!',
        ],
    ],
];
