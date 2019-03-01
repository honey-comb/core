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

use HoneyComb\Core\Repositories\Acl\HCRoleRepository;
use HoneyComb\Starter\Forms\HCForm;

/**
 * Class HCUserForm
 * @package HoneyComb\Core\Forms
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
     * @throws \Exception
     */
    public function __construct(HCRoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * @param bool $edit
     * @return string
     */
    public function getStorageUrl(bool $edit): string
    {
        return route('v1.api.users.create');
    }

    /**
     * @return array
     */
    public function getStructureNew(): array
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
            'is_active' => $this->makeField(trans('HCCore::users.label.is_active'))
                ->checkbox()
                ->toArray(),
            'send_welcome_email' => $this->makeField(trans('HCCore::users.label.send_welcome_email'))
                ->checkbox()
                ->toArray(),
            'send_password' => $this->makeField(trans('HCCore::users.label.send_password'))
                ->checkbox()
                ->toArray(),
            'roles' => $this->roles(),
        ];
    }

    /**
     * @return array
     */
    public function getStructureEdit(): array
    {
        return [
            'first_name' => $this->makeField(trans('HCCore::users.label.first_name'))
                ->isRequired()
                ->toArray(),
            'last_name' => $this->makeField(trans('HCCore::users.label.last_name'))
                ->isRequired()
                ->toArray(),
            'phone' => $this->makeField(trans('HCCore::users.label.phone'))
                ->toArray(),
            'description' => $this->makeField(trans('HCCore::users.label.description'))
                ->textArea()
                ->toArray(),
            'address' => $this->makeField(trans('HCCore::users.label.address'))
                ->textArea()
                ->toArray(),
            'email' => $this->makeField(trans('HCCore::users.label.email'))
                ->email()
                ->isRequired()
                ->toArray(),
            'notification_email' => $this->makeField(trans('HCCore::users.label.notification_email'))
                ->email()
                ->toArray(),
            'password' => $this->makeField(trans('HCCore::users.label.new_password'))
                ->password()
                ->setMinLength(6)
                ->toArray(),
            'password_confirmation' => $this->makeField(trans('HCCore::users.label.new_password_confirmation'))
                ->password()
                ->setMinLength(6)
                ->toArray(),
            'is_active' => $this->makeField(trans('HCCore::users.label.is_active'))
                ->checkbox()
                ->toArray(),
            'roles' => $this->roles(),
            'last_login' => $this->makeField(trans('HCCore::users.label.last_login'))
                ->isReadOnly()
                ->toArray(),
            'last_activity' => $this->makeField(trans('HCCore::users.label.last_activity'))
                ->isReadOnly()
                ->toArray(),
            'activated_at' => $this->makeField(trans('HCCore::users.label.activated_at'))
                ->isReadOnly()
                ->toArray(),
        ];
    }

    /**
     * @return array
     */
    private function roles(): array
    {
        return $this->makeField(trans('HCCore::users.label.role_groups'))
            ->checkboxList()
            ->setOptions($this->roleRepository->getRolesForUserCreation())
            ->toArray();
    }
}
