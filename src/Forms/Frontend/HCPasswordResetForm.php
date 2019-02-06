<?php
/**
 * @copyright 2018 interactivesolutions
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the 'Software'), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED 'AS IS', WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
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

namespace HoneyComb\Core\Forms\Frontend;

use HoneyComb\Starter\Forms\HCForm;

/**
 * Class HCPasswordResetForm
 * @package HoneyComb\Core\Forms\Frontend
 */
class HCPasswordResetForm extends HCForm
{
    /**
     * Creating form
     *
     * @param bool $edit
     * @return array
     */
    public function createForm(bool $edit = false): array
    {
        $form = [
            'storageUrl' => route('users.password.reset.post'),
            'buttons' => [
                'submit' => [
                    'label' => trans('HCCore::core.buttons.submit'),
                ],
            ],
            'structure' => $this->getStructure($edit),
        ];

        if ($this->multiLanguage) {
            $form['availableLanguages'] = getHCContentLanguages();
        }

        if (!$edit) {
            return $form;
        }

        return $form;
    }

    /**
     * Get new structure
     *
     * @param string $prefix
     * @return array
     */
    public function getStructureNew(string $prefix): array
    {
        return [
            'email' =>
                [
                    'type' => 'email',
                    'label' => trans('HCCore::user.login.email'),
                    'required' => 1,
                    'requiredVisible' => 0,
                    'properties' => [
                        'style' => 'varchar',
                        'maxlength' => '197',
                    ],
                ],
            'password' =>
                [
                    'type' => 'password',
                    'label' => trans('HCCore::user.passwords.new'),
                    'required' => 1,
                    'requiredVisible' => 1,
                    'properties' => [
                        'style' => 'varchar',
                        'maxlength' => '197',
                    ],
                ],
            'password_confirmation' =>
                [
                    'type' => 'password',
                    'label' => trans('HCCore::user.passwords.new_again'),
                    'required' => 1,
                    'requiredVisible' => 1,
                    'properties' => [
                        'style' => 'varchar',
                        'maxlength' => '197',
                    ],
                ],
            'token' =>
                [
                    'type' => 'singleLine',
                    'label' => trans('HCCore::user.passwords.new_again'),
                    'hidden' => 1,
                    'required' => 1,
                    'requiredVisible' => 1,
                    'properties' => [
                        'maxlength' => '255',
                    ],
                    'value' => request()->input('token'),
                ],
        ];
    }

    /**
     * Get Edit structure
     *
     * @param string $prefix
     * @return array
     */
    public function getStructureEdit(string $prefix): array
    {
        return [];
    }
}
