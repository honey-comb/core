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

namespace HoneyComb\Core\Services;

use HoneyComb\Core\Models\HCUser;
use HoneyComb\Core\Repositories\HCUserRepository;
use HoneyComb\Core\Repositories\Users\HCPersonalInfoRepository;

/**
 * Class HCUserService
 * @package HoneyComb\Core\Services
 */
class HCUserService
{
    /**
     * @var HCUserRepository
     */
    private $repository;

    /**
     * @var HCPersonalInfoRepository
     */
    private $personalInfoRepository;

    /**
     * HCUserService constructor.
     * @param HCUserRepository $repository
     * @param HCPersonalInfoRepository $personalRepository
     */
    public function __construct(HCUserRepository $repository, HCPersonalInfoRepository $personalRepository)
    {
        $this->repository = $repository;
        $this->personalInfoRepository = $personalRepository;
    }

    /**
     * @return HCUserRepository
     */
    public function getRepository(): HCUserRepository
    {
        return $this->repository;
    }

    /**
     * @param array $userData
     * @param array $roles
     * @param array $personalData
     * @param $sendWelcomeEmail
     * @param $sendPassword
     * @return HCUser
     */
    public function createUser(
        array $userData,
        array $roles,
        array $personalData = [],
        $sendWelcomeEmail,
        $sendPassword
    ): HCUser {
        $password = $userData['password'];

        $userData['password'] = bcrypt($password);

        /** @var HCUser $user */
        $user = $this->repository->create($userData);
        $personalData['user_id'] = $user->id;

        $this->personalInfoRepository->updateOrCreate(['user_id' => $user->id], $personalData);

        $user->assignRoles($roles);

        // send welcome email
        if ($sendWelcomeEmail || $sendPassword) {
            if ($sendPassword) {
                $user->sendWelcomeEmailWithPassword($password);
            } else {
                $user->sendWelcomeEmail();
            }
        }

        // create user activation
        if (is_null($user->activated_at)) {
            $user->createTokenAndSendActivationCode();
        }

        return $user;
    }

    /**
     * @param string $userId
     * @param array $userData
     * @param array $personalData
     * @param array $roles
     * @return HCUser
     */
    public function updateUser(string $userId, array $userData, array $roles, array $personalData = []): HCUser
    {
        if (array_has($userData, 'password')) {
            $userData['password'] = bcrypt($userData['password']);
        }

        /** @var HCUser $user */
        $user = $this->repository->updateOrCreate(['id' => $userId], $userData);
        $this->personalInfoRepository->updateOrCreate(['user_id' => $userId], $personalData);

        $user->assignRoles($roles);

        return $user;
    }

    /**
     * @param string $userId
     */
    public function activateUser(string $userId): void
    {
        /** @var HCUser $user */
        $user = $this->repository->find($userId);

        if ($user->isNotActivated()){
            $user->activate();
        }
    }
}
