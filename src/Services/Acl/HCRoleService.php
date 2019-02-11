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

namespace HoneyComb\Core\Services\Acl;

use HoneyComb\Core\Models\Acl\HCAclRole;
use HoneyComb\Core\Repositories\Acl\HCPermissionRepository;
use HoneyComb\Core\Repositories\Acl\HCRoleRepository;
use HoneyComb\Starter\Exceptions\HCException;
use Illuminate\Support\Collection;

/**
 * Class HCRoleService
 * @package HoneyComb\Core\Services\Acl
 */
class HCRoleService
{
    /**
     * @var HCRoleRepository
     */
    protected $roleRepository;

    /**
     * @var HCPermissionRepository
     */
    protected $permissionRepository;

    /**
     * HCRoleService constructor.
     * @param HCRoleRepository $roleRepository
     * @param HCPermissionRepository $permissionRepository
     */
    public function __construct(HCRoleRepository $roleRepository, HCPermissionRepository $permissionRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Get roles with permissions
     *
     * @return Collection
     */
    public function getRolesWithPermissions(): Collection
    {
        return $this->roleRepository->makeQuery()
            ->with('permissions')
            ->notSuperAdmin()
            ->orderBy('name')
            ->get()
            ->map(function (HCAclRole $role) {
                return [
                    'id' => $role->id,
                    'role' => $role->name,
                    'slug' => $role->slug,
                    'permissions' => $role->permissions->pluck('id')->all(),
                ];
            });
    }

    /**
     * Get roles and permissions
     *
     * @return Collection
     */
    public function getAllPermissions(): Collection
    {
        $user = auth()->user();

        if ($user->hasRole($this->roleRepository::ROLE_PA)) {
            $permissions = $this->permissionRepository->makeQuery()
                ->select('id', 'name', 'action', 'created_at')
                ->where('name', '!=', 'admin.acl.role')
                ->get();
        } elseif ($user->isSuperAdmin()) {
            $permissions = $this->permissionRepository->makeQuery()
                ->select('id', 'name', 'action', 'created_at')->get();
        } else {
            $permissions = collect([]);
        }

        return $permissions->sortBy('name')->groupBy('name');
    }

    /**
     * @param string $roleId
     * @param string $permissionId
     * @return string
     * @throws HCException
     */
    public function updateRolePermissions(string $roleId, string $permissionId): string
    {
        if ($roleId == $this->roleRepository->getRoleSuperAdminId()) {
            throw new HCException(trans('HCCore::validator.roles.cant_update_super'));
        }

        if (!auth()->user()->hasRole([$this->roleRepository::ROLE_SA, $this->roleRepository::ROLE_PA])) {
            throw new HCException(trans('HCCore::validator.roles.cant_update_roles'));
        }

        $message = $this->roleRepository->updateOrCreatePermission($roleId, $permissionId);

        // clear permissions and menu items cache!
        cache()->forget('hc-admin-menu');
        cache()->forget('hc-permissions');

        event(
            new HCRolePermissionUpdated(
                $roleId,
                $permissionId,
                $message
            )
        );

        return $message;
    }
}
