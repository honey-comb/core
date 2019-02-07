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

namespace HoneyComb\Core\Forms\Password;

use HoneyComb\Starter\Forms\HCForm;

/**
 * Class HCPasswordResetForm
 * @package HoneyComb\Core\Forms\Password
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
            'storageUrl' => route('v1.api.password.reset'),
            'buttons' => [
                'submit' => [
                    'label' => trans('HCCore::users.button.submit'),
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
     * @return array
     */
    public function getStructureNew(): array
    {
        return [
            'email' => $this->makeField(trans('HCCore::users.label.email'))
                ->email()
                ->isRequired()
                ->toArray(),
            'password' => $this->makeField(trans('HCCore::users.label.new_password'))
                ->password()
                ->isRequired()
                ->toArray(),
            'password_confirmation' => $this->makeField(trans('HCCore::users.label.new_password_confirmation'))
                ->password()
                ->isRequired()
                ->toArray(),
            'token' => $this->makeField('')
                ->isHidden()
                ->isRequired()
                ->setValue(request()->input('token'))
                ->toArray(),
        ];
    }

    /**
     * Get Edit structure
     *
     * @return array
     */
    public function getStructureEdit(): array
    {
        return [];
    }
}
