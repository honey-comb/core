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

use HoneyComb\Core\Models\HCUser;
use HoneyComb\Core\Repositories\HCUserNotificationSubscriptionRepository;
use HoneyComb\Core\Repositories\HCUserNotificationSubscriptionTypeRepository;
use HoneyComb\Core\Repositories\HCUserRepository;
use HoneyComb\Starter\Exceptions\HCException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

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
     * @var HCUserNotificationSubscriptionRepository
     */
    private $repository;

    /**
     * HCUserNotificationSubscriptionService constructor.
     * @param HCUserRepository $userRepository
     * @param HCUserNotificationSubscriptionRepository $repository
     * @param HCUserNotificationSubscriptionTypeRepository $typeRepository
     */
    public function __construct(
        HCUserRepository $userRepository,
        HCUserNotificationSubscriptionRepository $repository,
        HCUserNotificationSubscriptionTypeRepository $typeRepository
    ) {
        $this->userRepository = $userRepository;
        $this->typeRepository = $typeRepository;
        $this->repository = $repository;
    }

    /**
     * @return HCUserNotificationSubscriptionRepository
     */
    public function getRepository(): HCUserNotificationSubscriptionRepository
    {
        return $this->repository;
    }

    /**
     * @return HCUserNotificationSubscriptionTypeRepository
     */
    public function getTypeRepository(): HCUserNotificationSubscriptionTypeRepository
    {
        return $this->typeRepository;
    }

    /**
     * @return HCUserRepository
     */
    public function getUserRepository(): HCUserRepository
    {
        return $this->userRepository;
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

        $user = $this->getUserRepository()->findOrFail($userId);

        $user->notificationSubscriptions()->sync($subscriptions);
    }

    /**
     * @param string $userId
     */
    public function clearSubscriptions(string $userId): void
    {
        $user = $this->getUserRepository()->findOrFail($userId);

        $user->notificationSubscriptions()->detach();
    }

    /**
     * @param string $userId
     * @param $subscriptionTypeId
     * @throws HCException
     */
    public function addSubscription(string $userId, $subscriptionTypeId): void
    {
        $this->validate($subscriptionTypeId);

        $user = $this->getUserRepository()->findOrFail($userId);

        $user->notificationSubscriptions()->syncWithoutDetaching($subscriptionTypeId);
    }

    /**
     * @param string $userId
     * @param string|array $subscriptionTypeId
     */
    public function removeSubscription(string $userId, $subscriptionTypeId): void
    {
        $user = $this->getUserRepository()->findOrFail($userId);

        $user->notificationSubscriptions()->detach($subscriptionTypeId);
    }

    /**
     * @param string $userId
     * @return Collection
     */
    public function getSubscriptions(string $userId): Collection
    {
        $user = $this->getUserRepository()->findOrFail($userId);

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
     * @param array $typeIds
     * @return Collection
     */
    public function getUsersBySubscriptionTypes(array $typeIds): Collection
    {
        return $this->getUserRepository()
            ->makeQuery()
            ->with('notificationSubscriptions')
            ->whereHas('notificationSubscriptions', function ($query) use ($typeIds) {
                $query->whereIn('id', $typeIds);
            })
            ->get()
            ->each(function (HCUser $user) {
                $user->append('subscription_types');
            });
    }

    /**
     * @param $search
     * @throws HCException
     */
    protected function validate($search): void
    {
        $search = Arr::wrap($search);

        $subscriptions = $this->getTypeRepository()
            ->makeQuery()
            ->pluck('id')
            ->toArray();

        foreach ($search as $subscription) {
            if (!in_array($subscription, $subscriptions)) {
                throw new HCException(
                    trans(
                        'HCCore::subscriptions.message.subscription_does_not_exist',
                        ['subscription' => $subscription]
                    )
                );
            }
        }
    }
}
