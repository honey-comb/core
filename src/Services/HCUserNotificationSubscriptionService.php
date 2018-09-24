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

use HoneyComb\Core\Repositories\HCUserNotificationSubscriptionTypeRepository;
use HoneyComb\Core\Repositories\HCUserRepository;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class HCUserNotificationSubscriptionService
 * @package HoneyComb\Core\Services
 */
class HCUserNotificationSubscriptionService
{
    /**
     * @var HCUserRepository
     */
    private $userRepository;
    /**
     * @var HCUserNotificationSubscriptionTypeRepository
     */
    private $typeRepository;

    /**
     * HCUserNotificationSubscriptionService constructor.
     * @param HCUserRepository $userRepository
     * @param HCUserNotificationSubscriptionTypeRepository $typeRepository
     */
    public function __construct(
        HCUserRepository $userRepository,
        HCUserNotificationSubscriptionTypeRepository $typeRepository
    ) {
        $this->userRepository = $userRepository;
        $this->typeRepository = $typeRepository;
    }

    /**
     * @return HCUserNotificationSubscriptionService
     */
    public function getRepository(): HCUserNotificationSubscriptionService
    {
        return $this->repository;
    }

    /**
     * @param string $userId
     * @param array $subscriptions
     * @return void
     * @throws \Exception
     */
    public function syncSubscriptions(string $userId, array $subscriptions): void
    {
        $this->validate($subscriptions);

        $user = $this->userRepository->findOrFail($userId);

        $user->notificationSubscriptions()->sync($subscriptions);
    }

    /**
     * @param string $userId
     */
    public function clearSubscriptions(string $userId): void
    {
        $user = $this->userRepository->findOrFail($userId);

        $user->notificationSubscriptions()->detach();
    }

    /**
     * @param string $userId
     * @param string|array $subscriptionTypeId
     * @throws \Exception
     */
    public function addSubscription(string $userId, $subscriptionTypeId): void
    {
        $this->validate($subscriptionTypeId);

        $user = $this->userRepository->findOrFail($userId);

        $user->notificationSubscriptions()->syncWithoutDetaching($subscriptionTypeId);
    }

    /**
     * @param string $userId
     * @param string|array $subscriptionTypeId
     */
    public function removeSubscription(string $userId, $subscriptionTypeId): void
    {
        $user = $this->userRepository->findOrFail($userId);

        $user->notificationSubscriptions()->detach($subscriptionTypeId);
    }

    /**
     * @param string $userId
     * @return Collection
     */
    public function getSubscriptions(string $userId): Collection
    {
        $user = $this->userRepository->findOrFail($userId);

        return $user->notificationSubscriptions;
    }

    /**
     * @param string $userId
     * @return array
     */
    public function getSubscriptionOptions(string $userId): array
    {
        return $this->getSubscriptions($userId)->map(function ($item) {
            return [
                'id' => $item->id,
                'label' => trans($item->translation_key),
            ];
        })->toArray();
    }

    /**
     * @param $search
     * @throws \Exception
     */
    protected function validate($search): void
    {
        $search = array_wrap($search);

        $subscriptios = $this->typeRepository
            ->makeQuery()
            ->pluck('id')
            ->toArray();

        foreach ($search as $subscription){
            if(!in_array($subscription, $subscriptios)){
                throw new \Exception(trans('HCCore::subscriptions.message.subscription_does_not_exist', ['subscription' => $subscription]));
            }
        }
    }
}
