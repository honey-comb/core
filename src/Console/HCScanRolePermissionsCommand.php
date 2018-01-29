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

namespace HoneyComb\Core\Console;

use HoneyComb\Core\Models\Acl\HCAclRole;
use HoneyComb\Core\Repositories\Acl\HCPermissionRepository;
use HoneyComb\Core\Repositories\Acl\HCRoleRepository;
use HoneyComb\Starter\Helpers\HCConfigParseHelper;
use Illuminate\Console\Command;

/**
 * Class HCScanRolePermissionsCommand
 * @package HoneyComb\Core\Console
 */
class HCScanRolePermissionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hc:permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Go through all packages, find HoneyComb configuration file and store
     all permissions / roles / connections';

    /**
     * HCAclPermission id list
     *
     * @var
     */
    private $permissionsIdList;

    /**
     * Acl data holder
     *
     * @var
     */
    private $aclData;

    /**
     * Role list holder
     *
     * @var array
     */
    private $rolesList = [];

    /**
     * @var HCConfigParseHelper
     */
    private $helper;

    /**
     * @var HCPermissionRepository
     */
    private $permissionRepository;

    /**
     * @var HCRoleRepository
     */
    private $roleRepository;

    /**
     * HCScanRolePermissionsCommand constructor.
     * @param HCConfigParseHelper $helper
     * @param HCPermissionRepository $permissionRepository
     * @param HCRoleRepository $roleRepository
     */
    public function __construct(
        HCConfigParseHelper $helper,
        HCPermissionRepository $permissionRepository,
        HCRoleRepository $roleRepository
    ) {
        parent::__construct();

        $this->helper = $helper;
        $this->permissionRepository = $permissionRepository;
        $this->roleRepository = $roleRepository;
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->comment('Scanning permissions..');

        $this->scanRolesAndPermissions();
        $this->generateRolesAndPermissions();

        $this->comment('-');
    }

    /**
     * Scans roles and permissions and create roles, permissions and roles_permissions
     */
    private function scanRolesAndPermissions(): void
    {
        $files = $this->helper->getConfigFilesSorted();

        if (!empty($files)) {
            foreach ($files as $filePath) {
                $config = json_decode(file_get_contents($filePath), true);

                if (is_null($config)) {
                    $this->info('Invalid json file: ' . $filePath);
                } else {
                    $packageName = array_get($config, 'general.packageName');

                    if (is_null($packageName)) {
                        $this->error('SKIPPING! Package must have a name! file: ' . $filePath);
                        continue;
                    }

                    if (array_key_exists('acl', $config)) {
                        $this->aclData[] = [
                            'packageName' => $packageName,
                            'acl' => array_get($config, 'acl'),
                        ];
                    }
                }
            }
        }
    }

    /**
     * Create roles, permissions and roles_permissions
     */
    private function generateRolesAndPermissions(): void
    {
        if (empty($this->aclData)) {
            $this->error('empty roles and permissions in "generateRolesAndPermissions" method');

            return;
        }

        foreach ($this->aclData as $acl) {
            $this->createPermissions($acl['acl']);
            $this->createRoles($acl['acl']);
        }

        $this->createRolesPermissions($this->aclData);
    }

    /**
     * Create permissions
     *
     * @param array $aclData
     * @throws \Exception
     */
    private function createPermissions(array $aclData): void
    {
        if (array_key_exists('permissions', $aclData)) {

            if (sizeof($aclData['permissions']) == 0) {
                return;
            }

            if (!isset($aclData['permissions'][0])) {
                $aclData['permissions'] = [$aclData['permissions']];
            }

            foreach ($aclData['permissions'] as $permission) {

                $this->removeDeletedPermissions($permission);

                foreach ($permission['actions'] as $action) {
                    $permissionId = $this->permissionRepository->makeQuery()->firstOrCreate([
                        'name' => $permission['name'],
                        'controller' => $permission['controller'],
                        'action' => $action,
                    ]);

                    $this->permissionsIdList[$action] = $permissionId->id;
                }
            }
        }
    }

    /**
     * Check if there is any deleted permission actions from config file.
     * If it is than deleted them from role_permissions connection and from permission actions
     *
     * @param array $permission
     * @throws \Exception
     */
    private function removeDeletedPermissions(array $permission): void
    {
        $configActions = collect($permission['actions']);

        $actions = $this->permissionRepository->makeQuery()->where('name', $permission['name'])->pluck('action');

        $removedActions = $actions->diff($configActions);

        if ($removedActions->isNotEmpty()) {
            foreach ($removedActions as $action) {
                $this->permissionRepository->deletePermission($action);
            }
        }
    }

    /**
     * Create roles
     *
     * @param array $aclData
     */
    private function createRoles(array $aclData): void
    {
        if (array_key_exists('rolesActions', $aclData)) {
            foreach ($aclData['rolesActions'] as $role => $actions) {
                $roleRecord = $this->roleRepository->makeQuery()->firstOrCreate([
                    'slug' => $role,
                    'name' => ucfirst(str_replace(['-', '_'], ' ', $role)),
                ]);

                $this->rolesList[$roleRecord->id] = $roleRecord;
            }
        }
    }

    /**
     * Creating roles permissions
     *
     * @param array $aclData
     */
    private function createRolesPermissions(array $aclData): void
    {
        $allRolesActions = $this->extractAllActions($aclData);

        $uncheckedActionsOutput = [];

        foreach ($this->rolesList as $roleRecord) {
            // load current role permissions
            $roleRecord->load('permissions');

            // get current role permissions
            /** @var HCAclRole $roleRecord */
            $currentRolePermissions = $roleRecord->permissions->pluck('action')->toArray();

            // if role already has permissions
            if (count($currentRolePermissions)) {
                // unchecked actions
                $uncheckedActions = array_diff($allRolesActions[$roleRecord->slug], $currentRolePermissions);

                if (!empty($uncheckedActions)) {
                    $uncheckedActionsOutput[] = [$roleRecord->name, implode("\n", $uncheckedActions)];
                }

                continue;
            }


            // if role doesn't have any permissions than create it

            // get all permissions
            $permissions = $this->permissionRepository->makeQuery()
                ->whereIn('action', $allRolesActions[$roleRecord->slug])
                ->get();

            // sync permissions
            $roleRecord->permissions()->sync($permissions->pluck('id'));
        }

        // if role has unchecked actions than show which actions is unchecked
        if ($uncheckedActionsOutput) {
            $this->table(['Role', 'Unchecked actions'], $uncheckedActionsOutput);
        }
    }

    /**
     * Extract all actions from roles config
     *
     * @param array $aclData
     * @return array
     */
    private function extractAllActions(array $aclData): array
    {
        $allRolesActions = [];

        // get all role actions available
        foreach ($aclData as $acl) {
            if (isset($acl['acl']['rolesActions']) && !empty ($acl['acl']['rolesActions'])) {
                foreach ($acl['acl']['rolesActions'] as $role => $actions) {
                    if (array_key_exists($role, $allRolesActions)) {
                        $allRolesActions[$role] = array_merge($allRolesActions[$role], $actions);
                    } else {
                        $allRolesActions[$role] = array_merge([], $actions);
                    }
                }
            }
        }

        return $allRolesActions;
    }
}
