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

namespace HoneyComb\Core\Forms\Admin;

use HoneyComb\Core\Repositories\Acl\HCRoleRepository;
use HoneyComb\Starter\Forms\HCForm;

/**
 * Class HCUserForm
 * @package HoneyComb\Core\Forms\Admin
 */
class HCUserForm extends HCForm
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

        return $form;
    }

    /**
     * @return array
     */
    public function getStructureNew(): array
    {
        return [
            'email' => $this->makeField(trans('HCCore::user.email'), true)
                ->email()
                ->toArray(),
            'password' => $this->makeField(trans('HCCore::user.register.password'), true)
                ->password()
                ->toArray(),
            'is_active' => $this->makeField(trans('HCCore::user.active'))->checkbox()->toArray(),
            'send_welcome_email' => $this->makeField(trans('HCCore::user.send_welcome_email'))->checkbox()->toArray(),
            'send_password' => $this->makeField(trans('HCCore::user.send_password'))->checkbox()->toArray(),
            'roles' => $this->roles(),
        ];
    }

    /**
     * @return array
     */
    private function roles(): array
    {
        return $this->makeField(trans('HCCore::user.role_groups'))
            ->checkboxList()
            ->setOptions($this->roleRepository->getRolesForUserCreation())
            ->toArray();
    }

    /**
     * @return array
     */
    public function getStructureEdit(): array
    {
        return [
            'first_name' => $this->makeField(trans('HCCore::user.first_name'), true)->toArray(),
            'last_name' => $this->makeField(trans('HCCore::user.last_name'), true)->toArray(),
            'phone' => $this->makeField(trans('HCCore::user.phone'))->toArray(),
            'description' => $this->makeField(trans('HCCore::user.description'))
                ->textArea()
                ->toArray(),
            'address' => $this->makeField(trans('HCCore::user.address'))
                ->textArea()
                ->toArray(),
            'email' => $this->makeField(trans('HCCore::user.email'), true)
                ->email()
                ->toArray(),
            'notification_email' => $this->makeField(trans('HCCore::user.notification_email'))
                ->email()
                ->toArray(),
            'password' => $this->makeField(trans('HCCore::user.passwords.new'))
                ->password()
                ->setMinLength(6)
                ->toArray(),
            'password_confirmation' => $this->makeField(trans('HCCore::user.passwords.new_again'))
                ->password()
                ->setMinLength(6)
                ->toArray(),
            'is_active' => $this->makeField(trans('HCCore::user.active'))
                ->checkbox()
                ->toArray(),
            'roles' => $this->roles(),
            'last_login' => $this->makeField(trans('HCCore::user.last_login'))
                ->isReadOnly()
                ->toArray(),
            'last_activity' => $this->makeField(trans('HCCore::user.last_activity'))
                ->isReadOnly()
                ->toArray(),
            'activated_at' => $this->makeField(trans('HCCore::user.activation.activated_at'))
                ->isReadOnly()
                ->toArray(),
        ];
    }
}
