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

namespace HoneyComb\Core\Forms\Frontend;

use HoneyComb\Starter\Forms\HCForm;

/**
 * Class HCUserRegisterForm
 * @package HoneyComb\Core\Forms\Frontend
 */
class HCUserRegisterForm extends HCForm
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
            'storageUrl' => route('v1.api.register'),
            'buttons' => [
                'submit' => [
                    'label' => trans('HCCore::core.buttons.register'),
                ],
            ],
            'structure' => $this->getStructure($edit),
        ];

        return $form;
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

    /**
     * Get new structure
     *
     * @return array
     */
    public function getStructureNew(): array
    {
        return [
            'email' => $this->makeField(trans('HCCore::user.login.email'))
                ->email()
                ->isRequired()
                ->toArray(),
            'password' => $this->makeField(trans('HCCore::user.login.password'))
                ->password()
                ->isRequired()
                ->toArray(),
            'password_confirmation' => $this->makeField(trans('HCCore::user.passwords.new_again'))
                ->password()
                ->isRequired()
                ->toArray(),
        ];
    }
}
