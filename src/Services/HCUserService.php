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

namespace HoneyComb\Core\Services;

use Carbon\Carbon;
use HoneyComb\Core\DTO\HCSocialProviderDTO;
use HoneyComb\Core\DTO\HCUserDTO;
use HoneyComb\Core\Events\HCUserActivated;
use HoneyComb\Core\Events\HCUserCreated;
use HoneyComb\Core\Events\HCUserForceDeleted;
use HoneyComb\Core\Events\HCUserRestored;
use HoneyComb\Core\Events\HCUserSoftDeleted;
use HoneyComb\Core\Events\HCUserUpdated;
use HoneyComb\Core\Models\HCUser;
use HoneyComb\Core\Models\Users\HCUserProvider;
use HoneyComb\Core\Repositories\Acl\HCRoleRepository;
use HoneyComb\Core\Repositories\HCUserRepository;
use HoneyComb\Core\Repositories\Users\HCPersonalInfoRepository;
use HoneyComb\Core\Repositories\Users\HCUserProviderRepository;
use HoneyComb\Starter\Enum\BoolEnum;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Socialite\Two\User;

/**
 * Class HCUserService
 * @package HoneyComb\Core\Services
 */
class HCUserService
{
    /**
     * @var HCUserRepository
     */
    protected $repository;

    /**
     * @var HCPersonalInfoRepository
     */
    protected $personalInfoRepository;

    /**
     * @var HCRoleRepository
     */
    protected $roleRepository;

    /**
     * @var HCUserProviderRepository
     */
    protected $userProviderRepository;

    /**
     * HCUserService constructor.
     * @param HCUserRepository $repository
     * @param HCPersonalInfoRepository $personalRepository
     * @param HCRoleRepository $roleRepository
     * @param HCUserProviderRepository $userProviderRepository
     */
    public function __construct(
        HCUserRepository $repository,
        HCPersonalInfoRepository $personalRepository,
        HCRoleRepository $roleRepository,
        HCUserProviderRepository $userProviderRepository
    ) {
        $this->repository = $repository;
        $this->personalInfoRepository = $personalRepository;
        $this->roleRepository = $roleRepository;
        $this->userProviderRepository = $userProviderRepository;
    }

    /**
     * @return HCUserRepository
     */
    public function getRepository(): HCUserRepository
    {
        return $this->repository;
    }

    /**
     * @param string $userId
     * @return array
     */
    public function getUserById(string $userId): array
    {
        $with = [
            'roles' => function (BelongsToMany $query) {
                $query->select('id', 'name as label');
            },
            'personal' => function (HasOne $query) {
                $query->select([
                    'user_id',
                    'first_name',
                    'last_name',
                    'photo_id',
                    'description',
                    'phone',
                    'address',
                    'notification_email',
                ]);
            },
        ];
        $user = $this->getRepository()->findById($userId, $with);

        return (new HCUserDTO($user))->toArray();
    }

    /**
     * @param string $email
     * @param string $password
     * @param string|null $firstName
     * @param string|null $lastName
     * @param string|null $photo
     * @param bool $sendWelcomeEmail
     * @param bool $sendPassword
     * @return HCUser
     * @throws \ReflectionException
     */
    public function registerUser(
        string $email,
        string $password,
        string $firstName = null,
        string $lastName = null,
        string $photo = null,
        bool $sendWelcomeEmail = true,
        bool $sendPassword = true
    ): HCUser {
        $defaultRole = $this->roleRepository->getRoleUserId();

        $user = $this->createUser(
            [
                'email' => $email,
                'password' => $password,
                'is_active' => BoolEnum::yes()->id(),
            ],
            [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'photo' => $photo,
            ],
            [
                $defaultRole,
            ],
            $sendWelcomeEmail,
            $sendPassword
        );

        return $this->getRepository()->findOrFail($user->id);
    }

    /**
     * @param array $userData
     * @param array $roles
     * @param array $personalData
     * @param $sendWelcomeEmail
     * @param $sendPassword
     * @return HCUser
     * @throws \Exception
     */
    public function createUser(
        array $userData,
        array $personalData = [],
        array $roles = [],
        bool $sendWelcomeEmail = true,
        bool $sendPassword = true
    ): HCUser {
        $password = $userData['password'];

        if ($userData['is_active'] == BoolEnum::yes()->id()) {
            $userData['activated_at'] = Carbon::now();
        }

        $userData['password'] = bcrypt($password);

        /** @var HCUser $user */
        $user = $this->repository->create($userData);
        $personalData['user_id'] = $user->id;

        $user->personal()->create($personalData);

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

        event(new HCUserCreated($user));

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

        event(new HCUserUpdated($user));

        return $user;
    }

    /**
     * @param string $userId
     */
    public function activateUser(string $userId): void
    {
        /** @var HCUser $user */
        $user = $this->repository->find($userId);

        if ($user->isNotActivated()) {
            $user->activate();

            event(new HCUserActivated($user));
        }
    }

    /**
     * @param User $providerUser
     * @param string $provider
     * @return HCUser
     * @throws \Throwable
     */
    public function createOrUpdateUserProvider(User $providerUser, string $provider): HCUser
    {
        /** @var HCUserProvider $userProvider */
        $userProvider = $this->userProviderRepository->findOneBy([
            'provider' => $provider,
            'user_provider_id' => (string)$providerUser->getId(),
        ]);

        $providerData = new HCSocialProviderDTO($provider, $providerUser);

        // user provider exists
        if ($userProvider) {
            $this->userProviderRepository->update([
                'profile_url' => $providerData->getProfileUrl(),
                'response' => json_encode($providerData->getRawData()),
                'email' => $providerData->getEmail(),
            ], $userProvider->id);

            return $userProvider->user;
        } else {
            // find existing user provider by email
            $userProvider = $this->userProviderRepository->makeQuery()
                ->where('email', $providerData->getEmail())
                ->where('provider', '!=', $provider)
                ->first();

            if (is_null($userProvider)) {

                // find user or create if nots exists
                $user = $this->repository->findOneBy(['email' => $providerData->getEmail()]);

                if (is_null($user)) {
                    $user = $this->registerUser(
                        $providerData->getEmail(),
                        str_random(10),
                        $providerData->getFirstName(),
                        $providerData->getLastName(),
                        $providerData->getAvatarUrl()
                    );
                }
            } else {
                // set user of found user provider
                $user = $userProvider->user;
            }

            $this->userProviderRepository->createProvider(
                $user->id,
                (string)$providerData->getId(),
                $provider,
                $providerData->getEmail(),
                json_encode($providerData->getRawData()),
                $providerData->getProfileUrl()
            );

            return $user;
        }
    }

    /**
     * Soft delete users
     *
     * @param array $userIds
     * @return void
     */
    public function deleteSoft(array $userIds): void
    {
        $deleted = $this->getRepository()->deleteSoft($userIds);

        event(new HCUserSoftDeleted($deleted));
    }

    /**
     * Force delete users by given id
     *
     * @param array $userIds
     * @return void
     * @throws \Exception
     */
    public function deleteForce(array $userIds): void
    {
        $deleted = $this->getRepository()->deleteForce($userIds);

        event(new HCUserForceDeleted($deleted));
    }

    /**
     * Restore soft deleted users
     *
     * @param array $userIds
     */
    public function restore(array $userIds): void
    {
        $this->getRepository()->restore($userIds);

        event(new HCUserRestored($userIds));
    }

    /**
     * @param string $userId
     * @return string
     */
    public function getNotificationEmail(string $userId): string
    {
        /** @var HCUser $user */
        $user = $this->repository->findOrFail($userId);

        return $user->getNotificationEmail();
    }
}
