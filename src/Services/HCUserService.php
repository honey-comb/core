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

use Carbon\Carbon;
use HoneyComb\Core\Events\Admin\HCUserCreated;
use HoneyComb\Core\Events\Admin\HCUserUpdated;
use HoneyComb\Core\Models\HCUser;
use HoneyComb\Core\Models\Users\HCUserProvider;
use HoneyComb\Core\Repositories\Acl\HCRoleRepository;
use HoneyComb\Core\Repositories\HCUserRepository;
use HoneyComb\Core\Repositories\Users\HCPersonalInfoRepository;
use HoneyComb\Core\Repositories\Users\HCUserProviderRepository;
use HoneyComb\Resources\Services\HCResourceService;
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
     * @var HCResourceService
     */
    protected $resourceService;

    /**
     * HCUserService constructor.
     * @param HCResourceService $resourceService
     * @param HCUserRepository $repository
     * @param HCPersonalInfoRepository $personalRepository
     * @param HCRoleRepository $roleRepository
     * @param HCUserProviderRepository $userProviderRepository
     */
    public function __construct(
        HCResourceService $resourceService,
        HCUserRepository $repository,
        HCPersonalInfoRepository $personalRepository,
        HCRoleRepository $roleRepository,
        HCUserProviderRepository $userProviderRepository
    ) {
        $this->repository = $repository;
        $this->personalInfoRepository = $personalRepository;
        $this->roleRepository = $roleRepository;
        $this->userProviderRepository = $userProviderRepository;
        $this->resourceService = $resourceService;
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
        bool $sendWelcomeEmail = true,
        bool $sendPassword = true
    ): HCUser {
        $password = $userData['password'];

        $userData['password'] = bcrypt($password);

        if ($userData['is_active'] == 1) {
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

        event(new HCUserCreated($user));

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

        event(new HCUserUpdated($user));

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

        if ($user->isNotActivated()) {
            $user->activate();
        }
    }

    /**
     * @param User $providerUser
     * @param string $provider
     * @return HCUser
     * @throws \Exception
     */
    public function createOrUpdateUserProvider(User $providerUser, string $provider): HCUser
    {
        /** @var HCUserProvider $userProvider */
        $userProvider = $this->userProviderRepository->findOneBy([
            'provider' => $provider,
            'user_provider_id' => (string)$providerUser->getId(),
        ]);

        // user provider exists
        if ($userProvider) {
            $this->userProviderRepository->update([
                'profile_url' => $this->getProviderProfileUrl($providerUser, $provider),
                'response' => json_encode($providerUser->getRaw()),
                'email' => $providerUser->getEmail(),
            ], $userProvider->id);

            return $userProvider->user;
        } else {
            // find existing user provider by email
            $userProvider = $this->userProviderRepository->makeQuery()
                ->where('email', $providerUser->getEmail())
                ->where('provider', '!=', $provider)
                ->first();

            if (is_null($userProvider)) {

                // find user or create if nots exists
                $user = $this->repository->findOneBy(['email' => $providerUser->getEmail()]);

                if (is_null($user)) {

                    $userData = [
                        'email' => $providerUser->getEmail(),
                        'password' => str_random(10),
                        'activated_at' => Carbon::now()->toDateTimeString(),
                    ];

                    $personalData = $this->parseNameFromSocialite($providerUser);
                    $personalData = $this->getPhoto($providerUser, $personalData, $provider);

                    $user = $this->createUser($userData, [$this->roleRepository->getRoleUserId()], $personalData);
                }
            } else {
                // set user of found user provider
                $user = $userProvider->user;
            }

            $this->userProviderRepository->createProvider(
                $user->id,
                (string)$providerUser->getId(),
                $provider,
                $providerUser->getEmail(),
                $this->getProviderProfileUrl($providerUser, $provider),
                json_encode($providerUser->getRaw())
            );

            return $user;
        }
    }

    /**
     * Get first name and last late from socialite
     *
     * @param User $providerUser
     * @return array
     */
    private function parseNameFromSocialite(User $providerUser): array
    {
        if ($providerUser->name) {
            $name = explode(' ', $providerUser->name);

            $firstName = array_get($name, '0');
            $lastName = array_get($name, '1');
        } else {
            $firstName = $lastName = $providerUser->nickname;
        }

        return ['first_name' => $firstName, 'last_name' => $lastName];
    }

    /**
     * @param User $providerUser
     * @param array $personalData
     * @param string $provider
     * @return array
     * @throws \Exception
     */
    private function getPhoto(User $providerUser, array $personalData, string $provider): array
    {
        $avatarUrl = null;

        switch ($provider) {
            case 'facebook':
                $avatarUrl = $providerUser->avatar_original;
                break;

            case 'bitbucket':
                $avatarUrl = $providerUser->avatar;

                if ($avatarUrl) {
                    $avatarUrl = str_replace('32', '500', $avatarUrl);
                }
                break;

            case 'linkedin':
                $avatarUrl = $providerUser->avatar_original;
                break;

            case 'github':
                $avatarUrl = $providerUser->avatar;
                break;

            case 'google':
                $avatarUrl = $providerUser->avatar_original;
                break;

            case 'twitter':
                $avatarUrl = $providerUser->avatar;
                break;
        }

        if ($avatarUrl) {
            $photo = $this->resourceService->download($avatarUrl);
            $personalData['photo_id'] = array_get($photo, 'id');
        }

        return $personalData;
    }

    /**
     * @param User $providerUser
     * @param $provider
     * @return null|string
     * @throws \Exception
     */
    private function getProviderProfileUrl(User $providerUser, $provider): ? string
    {
        $profileUrl = null;

        switch ($provider) {
            case 'facebook':
                $profileUrl = $providerUser->profileUrl;
                break;

            case 'bitbucket':
                $profileUrl = array_get($providerUser->user, 'links.html.href');
                break;

            case 'linkedin':
                $profileUrl = array_get($providerUser->user, 'publicProfileUrl');
                break;

            case 'github':
                $profileUrl = array_get($providerUser->user, 'html_url');
                break;

            case 'google':
                $profileUrl = array_get($providerUser->user, 'url');
                break;

            case 'twitter':
                $profileUrl = null;
                break;
        }

        return $profileUrl;
    }
}
