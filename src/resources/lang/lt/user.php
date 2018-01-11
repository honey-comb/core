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
    'page_title' => 'Vartotojai',
    'title' => 'Vartotojai',
    'name' => 'Vartotojai',
    'email' => 'El. paštas',
    'firstname' => 'Vardas',
    'lastname' => 'Pavardė',
    'sex' => 'Lytis',
    'birthdate' => 'Gimimo metai',
    'street' => 'Gatvė',
    'house' => 'Namas',
    'apartment' => 'Butas',
    'postcode' => 'Pašto kodas',
    'city' => 'Miestas',
    'municipality' => 'Savivaldybė',
    'companyName' => 'Įmonės pavadinimas',
    'companyCode' => 'Įmonės kodas',
    'companyVat' => 'PVM kodas',
    'role_groups' => 'Rolės',
    'male' => 'Vyras',
    'female' => 'Moteris',
    'provider' => 'Teikėjas',
    'active' => 'Ar aktyvus?',
    'active_true' => 'Taip',
    'last_login' => 'Paskutinis prisijungimas',
    'last_activity' => 'Paskutinis aktyvumas',
    'roles' => 'Rolės',
    'activity' => 'Veikla',

    'tabs' => [
        'main' => 'Pagrindinis',
        'personal' => 'Asmeninė',
        'info' => 'Informacija',
    ],

    /*
     * Personal data
     */
    'photo' => 'Nuotrauka',
    'nickname' => 'Slapyvardis',
    'full_name' => 'Vardas ir pavadė',
    'gender' => 'Lytis',
    'phone' => 'Tel. numeris',
    'avatar' => 'Avataras',

    'login' => [
        'title' => 'Prisijungimas',
        'sign-in' => 'Prisijungti',
        'email' => 'El. paštas',
        'password' => 'Slaptažodis',
        're-password' => 'Pakartokite slaptažodį',
        'remember' => 'Prisiminti mane',
    ],

    'register' => [
        'title' => 'Registracija',
        'sign-up' => 'Registruotis',
        'email' => 'El. paštas',
        'password' => 'Slaptažodis',
        'password_again' => 'Pakartokite slaptažodį',
    ],

    'activation' => [
        'activated_at' => 'Aktyvavimo data',
        'title' => 'Paskyris aktyvavimas',
        'info' => 'Jūs turite aktyvuoti savo paskyrą.',
        'activate' => 'Aktyvuoti',
        'token_not_exists' => 'Kodas neegzistuojas',
        'token_expired' => 'Kodo galiojimo laikas pasibaigęs!',
        'back_to_main' => 'Grįžti į pagrindinį puslapį',
        'bad_token' => 'Kažkas negerai su duotu kodu. Prašome pasitikrinti el. paštą dėl tinkamo kodo.',
        'user_not_found' => 'Kažkas negerai su jūsų paskyra, prašome bandyti prisijungti arba registruotis iš naujo.',
        'check_email' => 'Prašome pasitikrinti el. paštą dėl aktyvavimo nuorodos',
        'resent_activation' => 'Mes jums pakartotoinai išsiuntėme aktyvacijos nuorodą. Prašoma pasitikrinti el. paštą.',
        'activate_account' => 'Mes jums išsiuntėme aktyvacijos nuorodą. 
        Prašome pasitikrinti el. pašta ir aktyvuoti savo paskyrą.',

        'mail' => [
            'subject' => 'Paskyros patvirtinimas',
            'from' => 'Administratorius',
            'text' => 'Norint prisijungti jūs turite patvirtinti savo el. pašto adresą <strong>:email</strong>',
        ],
    ],

    'socialite' => [
        'facebook' => 'Prisijungti su <strong>Facebook</strong>',
        'google' => 'Prisijungti su <strong>Google</strong>',
        'bitbucket' => 'Prisijungti su <strong>Bitbucket</strong>',
        'linkedin' => 'Prisijungti su <strong>LinkedIn</strong>',
        'twitter' => 'Prisijungti su <strong>Twitter</strong>',
        'github' => 'Prisijungti su <strong>Github</strong>',
    ],

    'facebook' => [
        'title' => 'Facebook',
        'errors' => [
            'email' => 'Email option is required!',
            'user_friends' => 'Friends option is required!',
        ],
    ],

    'errors' => [
        'login' => 'Klaidingi prisijungimo duomenys',
        'to_many_attempts' => 'Per daug bandymų prisijungti. Prašome bandyti po :seconds sekundžių.',
        'nickname_exists' => 'Paskyra jau egzistuoja',
        'facebook' => 'Prašoma bandyti dar kartą',
        'badOldPass' => 'Senas slaptaždis netinka',
    ],

    'send_welcome_email' => 'Išsiųsti pasveikimo pranešimą',
    'send_password' => 'Išsiųsti slaptažodį',

    'welcome_email' => [
        'greeting' => 'Sveikiname!',
        'subject' => 'Jūs sėkmingai užsiregistravote!',
        'text' => 'Jūsų paskyra buvo sėkmingai sukurta.',
        'show_email' => 'El. paštas: <strong>:email</strong>',
        'show_password' => 'Slaptažodis: <strong>:password</strong>',
        'login_link' => 'Prisijungimo nuoroda',
        'activation_required' => 'Bet iš pradžių jums reikia aktyvuoti savo paskyrą. 
        Jūs netruktus gausite laiška su aktyvavimo instrukcijomis.',
    ],

    'registered' => [
        'success' => 'Jūsų nauja paskyra',
        'administrator' => 'Administratorius',
        'nickname' => 'Sveiki, <b>:nickname</b>',
        'email' => 'Dabar jūs galite prisijungti su savo el. paštu: <b>:email</b>',
        'loginpage' => 'Eikite į prisijungimo puslapį: <a href=":loginpage">Prisijungti</a>',
        'password' => 'Jūsų slaptažodis <b>:password</b> nesidalinkite su kitais!',
    ],

    'passwords' => [
        "forgot_password" => "Pamiršote slaptažodį?",
        "email" => 'El. paštas',
        "can_login" => "Dabar galite prisijungti <a href=':url'>Prisijungti</a>",
        "reset_view" => "Slaptažodžio atstatymas",
        "reset_button" => "Atstatyti slaptažodį",
        "remind_button" => "Siųsti",
        "new" => "Naujas slaptažodis",
        "new_again" => "Pakartokite naują slaptažodį",
        "old" => "Senas slaptažodis",
        "click_here" => "Paspauskite šitą nuorodą jeigu nori atstatyti slaptažodį : <a href=':link'>Atstatyti</a>",
        "remind" => "Siųsti slaptažodžio atstatymo nuorodą",
        "password" => "Slaptažodis turi būti mažiausiai 6 simbolių ir sutatpi su pakartotinai įvestu",
        "user" => "Negalima rasti vartotojo su nurodyti el. pašto adresu.",
        "token" => "Slaptažodžio atstatymo kodas neteisingas.",
        "sent" => "Mes jums išsiuntėme slaptažodio prisiminimo nuorodą į jūsų el. paštą.",
        "reset" => "Jūsų slaptažodis sėkmingai atnaujintas",
        "subject" => "Jūsųs slaptažodžio atstatymo nuoroda",
    ],

    'access' => [
        'title' => 'Vartotojo prieinamumas',
    ],

    'admin' => [
        'menu' => [
            'logout' => 'Atsijungti',
            'roles' => 'Rolės: ',
            'online' => 'Prisijungęs',
            'profile' => 'Profilis',
        ],

        'member_since' => 'Vartotojas nuo :date',
    ],

    'validator' => [
        'nickname' => [
            'required' => 'Privalote įvesti slapyvardį',
            'unique' => 'Slapyvardis egzisutoja!',
            'min' => 'Slapyvardis turi būti mažiausiai iš :count simbolių',
        ],

        'email' => [
            'required' => 'El. paštas privalomas',
            'unique' => 'El. paštas jau egzistuoja',
            'min' => 'El. paštas turi būti iš mažiausiai :count simbolių',
        ],

        'password' => [
            'required' => 'Slaptažodis privalomas!',
            'min' => 'Slaptažodis turi būti iš mažiausiai :count simbolių',
            'confirmed' => 'Slaptažodžiai nesutampa',
        ],

        'roles' => [
            'required' => 'Rolės yra privalomos!',
            'cant_update_super' => 'Negalima atnaujinti super-admin rolės!',
            'cant_update_roles' => 'Jūs negalite atnaujinti rolių!',
        ],
    ],
];
