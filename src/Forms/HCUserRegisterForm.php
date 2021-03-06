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

namespace HoneyComb\Core\Forms;

use HoneyComb\Starter\Forms\HCForm;

/**
 * Class HCUserRegisterForm
 * @package HoneyComb\Core\Forms
 */
class HCUserRegisterForm extends HCForm
{
    /**
     * @var bool
     */
    public $authCheck = false;

    /**
     * @param string|null $type
     * @return string
     */
    public function getStorageUrl(string $type = null): string
    {
        return route('v1.api.register');
    }

    /**
     * Get structure
     *
     * @param string|null $type
     * @return array
     */
    public function getStructure(string $type = null): array
    {
        return [
            'email' => $this->makeField(trans('HCCore::users.label.email'))
                ->email()
                ->isRequired()
                ->toArray(),
            'password' => $this->makeField(trans('HCCore::users.label.password'))
                ->password()
                ->isRequired()
                ->toArray(),
            'password_confirmation' => $this->makeField(trans('HCCore::users.label.password_confirmation'))
                ->password()
                ->isRequired()
                ->toArray(),
        ];
    }

    /**
     * @param string|null $type
     * @return array
     */
    public function getButtons(string $type = null): array
    {
        return [
            $this->makeButton(trans('HCCore::users.button.register'))
                ->submit()
                ->toArray(),
        ];
    }
}
