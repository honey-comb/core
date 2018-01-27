<?php
/**
 * @copyright 2017 interactivesolutions
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

namespace HoneyComb\Core\Forms;

use HoneyComb\Core\Repositories\Acl\HCRoleRepository;

/**
 * Class HCUserForm
 * @package HoneyComb\Core\Forms
 */
class HCUserForm extends HCBaseForm
{
    /**
     * @var HCRoleRepository
     */
    private $roleRepository;

    /**
     * HCUserForm constructor.
     * @param HCRoleRepository $roleRepository
     */
    public function __construct(HCRoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * Creating form
     *
     * @param bool $edit
     * @return array
     */
    public function createForm(bool $edit = false): array
    {
        $form = [
            'storageUrl' => route('admin.api.user'),
            'buttons' => [
                'submit' => [
                    'label' => $this->getSubmitLabel($edit),
                ],
            ],
            'structure' => $this->getStructure($edit),
        ];

        if ($this->multiLanguage) {
            $form['availableLanguages'] = [];
        }

        //TOTO implement honeycomb-languages package getAvailableLanguages

        return $form;
    }

    /**
     * @param string $prefix
     * @return array
     */
    public function getStructureNew(string $prefix): array
    {
        return [
            $prefix . 'email' => [
                'type' => 'email',
                'tabID' => trans('HCCore::core.general'),
                'label' => trans('HCCore::user.email'),
                'required' => 1,
                'requiredVisible' => 1,
            ],
            $prefix . 'password' => [
                'type' => 'password',
                'tabID' => trans('HCCore::core.general'),
                'label' => trans('HCCore::user.register.password'),
                'required' => 1,
                'requiredVisible' => 1,
            ],
            $prefix . 'is_active' => [
                'type' => 'checkBoxList',
                'tabID' => trans('HCCore::core.general'),
                'options' => [
                    ['id' => '1', 'label' => trans('HCCore::user.active')],
                ],
            ],
            $prefix . 'send_welcome_email' => [
                'type' => 'checkBoxList',
                'tabID' => trans('HCCore::core.general'),
                'options' => [
                    ['id' => '1', 'label' => trans('HCCore::user.send_welcome_email')],
                ],
            ],
            $prefix . 'send_password' => [
                'type' => 'checkBoxList',
                'tabID' => trans('HCCore::core.general'),
                'options' => [
                    ['id' => '1', 'label' => trans('HCCore::user.send_password')],
                ],
            ],
            $prefix . 'roles' => $this->roles(),
        ];
    }

    /**
     * @param string $prefix
     * @return array
     */
    public function getStructureEdit(string $prefix): array
    {
        return [
            $prefix . 'first_name' => [
                'type' => 'singleLine',
                'tabID' => trans('HCCore::core.general'),
                'label' => trans('HCCore::user.first_name'),
            ],
            $prefix . 'last_name' => [
                'type' => 'singleLine',
                'tabID' => trans('HCCore::core.general'),
                'label' => trans('HCCore::user.last_name'),
            ],
            $prefix . 'photo' => [
                'type' => 'singleLine',
                'tabID' => trans('HCCore::core.general'),
                'label' => trans('HCCore::user.photo'),
            ],
            $prefix . 'description' => [
                'type' => 'textArea',
                'tabID' => trans('HCCore::core.general'),
                'label' => trans('HCCore::user.description'),
            ],
            $prefix . 'email' => [
                'type' => 'email',
                'tabID' => trans('HCCore::core.general'),
                'label' => trans('HCCore::user.email'),
                'required' => 1,
                'requiredVisible' => 1,
            ],
            $prefix . 'password' => [
                'type' => 'password',
                'tabID' => trans('HCCore::core.general'),
                'label' => trans('HCCore::user.passwords.new'),
                'properties' => [
                    'strength' => '1' // case 0: much, case 1: 4 symbols, case 2: 6 symbols
                ],
            ],
            $prefix . 'password_confirmation' => [
                'type' => 'password',
                'tabID' => trans('HCCore::core.general'),
                'label' => trans('HCCore::user.passwords.new_again'),
                'properties' => [
                    'strength' => '1' // case 0: much, case 1: 4 symbols, case 2: 6 symbols
                ],
            ],
            $prefix . 'is_active' => [
                'tabID' => trans('HCCore::core.general'),
                'type' => 'checkBoxList',
                'options' => [
                    ['id' => '1', 'label' => trans('HCCore::user.active')],
                ],
            ],
            $prefix . 'roles' => $this->roles(),
            $prefix . 'last_login' => [
                'type' => 'singleLine',
                'tabID' => trans('HCCore::user.activity'),
                'label' => trans('HCCore::user.last_login'),
                'readonly' => 1,
            ],
            $prefix . 'last_activity' => [
                'type' => 'singleLine',
                'tabID' => trans('HCCore::user.activity'),
                'label' => trans('HCCore::user.last_activity'),
                'readonly' => 1,
            ],
            $prefix . 'activated_at' => [
                'type' => 'singleLine',
                'tabID' => trans('HCCore::user.activity'),
                'label' => trans('HCCore::user.activation.activated_at'),
                'readonly' => 1,
            ],
        ];
    }

    /**
     * @return array
     */
    private function roles(): array
    {
        return [
            'type' => 'checkBoxList',
            'tabID' => trans('HCCore::user.roles'),
            'label' => trans('HCCore::user.role_groups'),
            'options' => $this->roleRepository->getRolesForUserCreation(),
        ];
    }
}
