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

namespace HoneyComb\Core\Console;

use Carbon\Carbon;
use DB;
use HoneyComb\Core\Models\HCUser;
use HoneyComb\Core\Repositories\Acl\HCRoleRepository;
use HoneyComb\Core\Services\HCUserService;
use HoneyComb\Starter\Enum\BoolEnum;
use Illuminate\Console\Command;
use Illuminate\Database\Connection;
use Validator;

/**
 * Class HCCreateSuperAdminCommand
 * @package HoneyComb\Core\Console
 */
class HCCreateSuperAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hc:super-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates super admin account or update its password';

    /**
     * Admin password holder
     *
     * @var
     */
    private $password;

    /**
     * Admin email holder
     *
     * @var
     */
    private $email;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var HCUserService
     */
    private $userService;
    /**
     * @var HCRoleRepository
     */
    private $roleRepository;

    /**
     * HCCreateSuperAdminCommand constructor.
     * @param Connection $connection
     * @param HCUserService $userService
     * @param HCRoleRepository $roleRepository
     */
    public function __construct(Connection $connection, HCUserService $userService, HCRoleRepository $roleRepository)
    {
        parent::__construct();

        $this->connection = $connection;
        $this->userService = $userService;
        $this->roleRepository = $roleRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle(): void
    {
        $this->createSuperAdmin();
    }

    /**
     * Get email address
     *
     * @return null|string
     */
    private function getEmail(): ? string
    {
        $email = $this->ask("Enter email address");

        $validator = Validator::make(['email' => $email], [
            'email' => 'required|min:3|email',
        ]);

        if ($validator->fails()) {
            $this->error('Email is required, minimum 3 symbols length and must be email format');

            return $this->getEmail();
        }

        $this->email = $email;

        return null;
    }

    /**
     * Create super admin account
     */
    private function createSuperAdmin(): void
    {
        $this->getEmail();

        $this->info('');
        $this->comment('Creating default super-admin user...');
        $this->info('');

        $this->checkIfAdminExists();

        $this->getPassword();

        $this->createAdmin();

        $this->comment('Super admin account successfully created!');
        $this->comment('Your email: ');
        $this->error($this->email);

        $this->info('');
    }

    /**
     * Change password
     *
     * @param $admin
     */
    private function changePassword($admin): void
    {
        $this->getPassword();

        $admin->password = bcrypt($this->password);
        $admin->save();

        $this->info('Password has been updated!');
        exit;
    }

    /**
     * Validates password
     *
     * @return null|string
     */
    private function getPassword(): ? string
    {
        $password = $this->secret("Enter your password");
        $passwordAgain = $this->secret("Enter your password again");

        $validator = Validator::make([
            'password' => $password,
            'password_confirmation' => $passwordAgain,
        ], [
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            $this->info('');
            $this->error("The password must be at least 5 characters and must match!");
            $this->info('');

            return $this->getPassword();
        }

        $this->password = $password;

        return null;
    }

    /**
     * Create super admin role and assign role
     */
    private function createAdmin(): void
    {
        $this->connection->beginTransaction();
        try {
            $user = $this->userService->createUser(
                [
                    'email' => $this->email,
                    'password' => $this->password,
                    'is_active' => BoolEnum::yes()->id(),
                    'activated_at' => Carbon::now()->toDateTimeString(),
                ],
                [
                    'first_name' => 'Super',
                    'last_name' => 'Admin',
                    // TODO set admin default photo
                ],
                [
                    $this->roleRepository->getRoleSuperAdminId(),
                    $this->roleRepository->getRoleUserId()
                ]
            );
        } catch (\Throwable $exception) {
            $this->connection->rollBack();

            report($exception);

            $this->info('error:');
            $this->error($exception->getMessage());

            exit;
        }

        $this->connection->commit();
    }

    /**
     * Check if super admin exists
     */
    private function checkIfAdminExists(): void
    {
        $adminExists = $this->userService->getRepository()->findOneBy(['email' => $this->email]);

        if (!is_null($adminExists)) {

            $this->checkIfHaveSuperAdminRole($adminExists);

            $this->info('Admin account already exists!');

            if ($this->confirm('Do you want to change its password? [y|N]')) {
                $this->changePassword($adminExists);
            }

            exit;
        }
    }

    /**
     * Function which checks if admin user has super-admin role
     *
     * @param $adminExists
     * @throws \Exception
     */
    private function checkIfHaveSuperAdminRole(HCUser $adminExists): void
    {
        $hasRole = $adminExists->hasRole(HCRoleRepository::ROLE_SA);

        if (!$hasRole) {
            $this->comment("{$this->email} account doesn't have super-admin role!");

            if ($this->confirm('Do you want to add super-admin role? [y|N]')) {

                $this->connection->beginTransaction();

                try {
                    $adminExists->assignRoleBySlug(HCRoleRepository::ROLE_SA);
                } catch (\Throwable $exception) {
                    $this->connection->rollBack();

                    report($exception);

                    $this->error($exception->getMessage());
                    exit;
                }

                $this->info('Super admin role has been added');

                $this->connection->commit();
            }

            exit;
        }
    }
}
