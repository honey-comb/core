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
            'storageURL' => route('admin.api.user'),
            'buttons' => [
                [
                    'class' => 'col-centered',
                    'label' => trans('HCCore::core.buttons.submit'),
                    'type' => 'submit',
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
        $rolesStructure = [
            'type' => 'checkBoxList',
            'fieldID' => $prefix . 'roles',
            'tabID' => trans('HCCore::user.roles'),
            'label' => trans('HCCore::user.role_groups'),
            'required' => 1,
            'requiredVisible' => 1,
            'options' => $this->roleRepository->getRolesForUserCreation(),
        ];

        return [
            [
                'type' => 'email',
                'fieldID' => $prefix . 'email',
                'tabID' => trans('HCCore::core.general'),
                'label' => trans('HCCore::user.email'),
                'required' => 1,
                'requiredVisible' => 1,
            ],
            [
                'type' => 'password',
                'fieldID' => $prefix . 'password',
                'tabID' => trans('HCCore::core.general'),
                'label' => trans('HCCore::user.register.password'),
                'required' => 1,
                'requiredVisible' => 1,
            ],
            [
                'type' => 'checkBoxList',
                'fieldID' => $prefix . 'is_active',
                'tabID' => trans('HCCore::core.general'),
                'label' => ' ',
                'required' => 0,
                'requiredVisible' => 0,
                'options' => [
                    ['id' => '1', 'label' => trans('HCCore::user.active')],
                ],
            ],
            [
                'type' => 'checkBoxList',
                'fieldID' => $prefix . 'send_welcome_email',
                'tabID' => trans('HCCore::core.general'),
                'label' => ' ',
                'required' => 0,
                'requiredVisible' => 0,
                'options' => [
                    ['id' => '1', 'label' => trans('HCCore::user.send_welcome_email')],
                ],
            ],
            [
                'type' => 'checkBoxList',
                'fieldID' => $prefix . 'send_password',
                'tabID' => trans('HCCore::core.general'),
                'label' => ' ',
                'required' => 0,
                'requiredVisible' => 0,
                'options' => [
                    ['id' => '1', 'label' => trans('HCCore::user.send_password')],
                ],
            ],
            $rolesStructure,
        ];
    }

    public function getStructureEdit(string $prefix): array
    {
        $rolesStructure = [
            'type' => 'checkBoxList',
            'fieldID' => $prefix . 'roles',
            'tabID' => trans('HCCore::user.roles'),
            'label' => trans('HCCore::user.role_groups'),
            'required' => 1,
            'requiredVisible' => 1,
            'options' => $this->roleRepository->getRolesForUserCreation(),
        ];

        return [
            [
                'type' => 'singleLine',
                'fieldID' => $prefix . 'first_name',
                'tabID' => trans('HCCore::core.general'),
                'label' => trans('HCCore::user.firstname'),
                'required' => 0,
                'requiredVisible' => 0,
                'readonly' => 0,
            ],
            [
                'type' => 'singleLine',
                'fieldID' => $prefix . 'last_name',
                'tabID' => trans('HCCore::core.general'),
                'label' => trans('HCCore::user.lastname'),
                'required' => 0,
                'requiredVisible' => 0,
                'readonly' => 0,
            ],
            [
                'type' => 'email',
                'fieldID' => $prefix . 'email',
                'tabID' => trans('HCCore::core.general'),
                'label' => trans('HCCore::user.email'),
                'required' => 1,
                'requiredVisible' => 1,
            ],
            [
                'type' => 'password',
                'fieldID' => $prefix . 'password',
                'tabID' => trans('HCCore::core.general'),
                'label' => trans('HCCore::user.passwords.new'),
                'editType' => 0,
                'required' => 0,
                'requiredVisible' => 0,
                'properties' => [
                    'strength' => '1' // case 0: much, case 1: 4 symbols, case 2: 6 symbols
                ],
            ],
            [
                'type' => 'password',
                'fieldID' => $prefix . 'password_confirmation',
                'tabID' => trans('HCCore::core.general'),
                'label' => trans('HCCore::user.passwords.new_again'),
                'editType' => 0,
                'required' => 0,
                'requiredVisible' => 0,
                'properties' => [
                    'strength' => '1' // case 0: much, case 1: 4 symbols, case 2: 6 symbols
                ],
            ],
            [
                'tabID' => trans('HCCore::core.general'),
                'type' => 'checkBoxList',
                'fieldID' => $prefix . 'is_active',
                'label' => ' ',
                'required' => 0,
                'requiredVisible' => 0,
                'options' => [
                    ['id' => '1', 'label' => trans('HCCore::user.is_active')],
                ],
            ],
            $rolesStructure,
            [
                'type' => 'singleLine',
                'fieldID' => $prefix . 'last_login',
                'tabID' => trans('HCCore::user.activity'),
                'label' => trans('HCCore::user.last_login'),
                'required' => 0,
                'requiredVisible' => 0,
                'readonly' => 1,
            ],
            [
                'type' => 'singleLine',
                'fieldID' => $prefix . 'last_activity',
                'tabID' => trans('HCCore::user.activity'),
                'label' => trans('HCCore::user.last_activity'),
                'required' => 0,
                'requiredVisible' => 0,
                'readonly' => 1,
            ],
            [
                'type' => 'singleLine',
                'fieldID' => $prefix . 'activated_at',
                'tabID' => trans('HCCore::user.activity'),
                'label' => trans('HCCore::user.activation.activated_at'),
                'required' => 0,
                'requiredVisible' => 0,
                'readonly' => 1,
            ],
        ];
    }
}
