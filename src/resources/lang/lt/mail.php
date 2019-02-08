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
    'activation' => [
        'subject' => 'Paskyros patvirtinimas',
        'from' => 'Administratorius',
        'text' => 'Norint prisijungti jūs turite patvirtinti savo el. pašto adresą <strong>:email</strong>',
        'button' => 'Aktyvuoti',
    ],

    'welcome' => [
        'greeting' => 'Sveikiname!',
        'subject' => 'Jūs sėkmingai užsiregistravote!',
        'text' => 'Jūsų paskyra buvo sėkmingai sukurta.',
        'show_email' => 'El. paštas: <strong>:email</strong>',
        'show_password' => 'Slaptažodis: <strong>:password</strong>',
        'login_link' => 'Prisijungimo nuoroda',
        'activation_required' => 'Bet iš pradžių jums reikia aktyvuoti savo paskyrą. Jūs netruktus gausite laiška su aktyvavimo instrukcijomis.',
    ],

    'password' => [
        'subject' => 'Slaptažodžio priminimas',
        'hello' => 'Sveiki!',
        'first_line' => 'Jūs gavote šį laišką, nes gavome slaptažodžio priminimo prašymą iš jūsų paskyros.',
        'action' => 'Atnaujinti slaptažodį',
        'second_line' => 'Jei jūs nepareikalavote naujo slaptažodžio galite ignoruoti šį pranešimą',
        'body_sub' => 'Jei kilo nesklandumų su ":actionText" mygtuku, pabandykite atsidaryti šią nuorodą savo naršyklėje:',
        'regards' => 'Pagarbiai,',
        'copyright' => 'Visos teisės saugomos.',
    ],
];