<?php
/**
 * @copyright 2018 interactivesolutions
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

namespace HoneyComb\Core\Forms\Frontend;

use HoneyComb\Starter\Forms\HCForm;

/**
 * Class HCUserLoginForm
 * @package HoneyComb\Core\Forms\Frontend
 */
class HCUserLoginForm extends HCForm
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
            'storageUrl' => route('auth.login'),
            'buttons' => [
                'submit' => [
                    'label' => trans('HCCore::core.buttons.login'),
                ],
            ],
            'structure' => $this->getStructure($edit),
        ];

        return $form;
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
                    'type' => 'singleLine',
                    'label' => trans('HCCore::user.login.email'),
                    'required' => 1,
                ],
            'password' =>
                [
                    'type' => 'password',
                    'label' => trans('HCCore::user.login.password'),
                    'required' => 1,
                    'minLength' => 6,
                ],
            'remember' =>
                [
                    'type' => 'checkBoxList',
                    'options' => [['id' => '1', 'label' => trans('HCCore::user.login.remember')]],
                ],
        ];
    }
}
