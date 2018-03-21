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

namespace HoneyComb\Core\Forms\Frontend;

use HoneyComb\Starter\Forms\HCBaseForm;

/**
 * Class HCUserRegisterForm
 * @package HoneyComb\Core\Forms\Frontend
 */
class HCUserRegisterForm extends HCBaseForm
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
            'storageUrl' => route('auth.register'),
            'buttons' => [
                [
                    "class" => "col-centered",
                    "label" => trans('HCCore::core.buttons.register'),
                    "type" => "submit",
                ],
            ],
            'structure' => [
                [
                    "type" => "singleLine",
                    "fieldId" => "email",
                    "label" => trans("HCCore::user.email"),
                    "required" => 1,
                    "requiredVisible" => 1,
                ],
                [
                    "type" => "password",
                    "fieldId" => "password",
                    "label" => trans("HCCore::user.register.password"),
                    "required" => 1,
                    "requiredVisible" => 1,
                ],
            ],
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
        // TODO: Implement getStructureEdit() method.
    }

    /**
     * Get new structure
     *
     * @param string $prefix
     * @return array
     */
    public function getStructureNew(string $prefix): array
    {
        // TODO: Implement getStructureNew() method.
    }
}
