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
        'list' => 'Vartotojai',
    ],

    'label' => [
        'email' => 'El. paštas',
        'password' => 'Slaptažodis',
        'password_confirmation' => 'Pakartokite slaptažodį',
        "new_password" => "Naujas slaptažodis",
        "new_password_confirmation" => "Pakartokite naują slaptažodį",

        'first_name' => 'Vardas',
        'last_name' => 'Pavardė',
        'photo' => 'Nuotrauka',
        'address' => 'Adresas',
        'description' => 'Aprašymas',
        'notification_email' => 'Pranešimo el. paštas',

        'is_active' => 'Ar aktyvus?',

        'role_groups' => 'Rolės',

        'activated_at' => 'Aktyvavimo data',
        'last_activity' => 'Paskutinis aktyvumas',
        'last_login' => 'Paskutinis prisijungimas',

        'remember' => 'Prisiminti mane',
        'send_password' => 'Išsiųsti slaptažodį',
        'send_welcome_email' => 'Išsiųsti pasveikimo pranešimą',
    ],

    'error' => [
        'auth_bad_credentials' => 'Neteisingi prisijungimo duomenys',
        'auth_throttle' => 'Perdaug bandymų prisijungti. Bandykite po :seconds sec.',
    ],

    'message' => [
        'user_created' => 'Sėkmingai sukurtas!',
        'user_updated' => 'Sėkmingai atnaujintas!',
        'user_deleted' => 'Sėkmingai ištrintas!',
        'user_restored' => 'Sėkmingai atkurtas!',
        'logged_out' => 'Sėkmingai atsijungta!',

        'activation_check_email' => 'Prašome pasitikrinti el. paštą dėl aktyvavimo nuorodos',
        'activation_resent_link' => 'Mes jums pakartotinai išsiuntėme aktyvacijos nuorodą. Prašoma pasitikrinti el. paštą.',
        'activation_bad_token' => 'Kažkas negerai su duotu kodu. Prašome pasitikrinti el. paštą dėl tinkamo kodo.',
        'activation_user_not_found' => 'Kažkas negerai su jūsų paskyra, prašome bandyti prisijungti arba registruotis iš naujo.',
    ],

    'validation' => [
        'email_required' => 'El. paštas yra privalomas!',
    ],

    'button' => [
        'submit' => 'Pateikti',
        'login' => 'Prisijungti',
        'register' => 'Registruotis',
    ],
];