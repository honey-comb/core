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

use HoneyComb\Core\Models\Users\HCUserNotificationSubscriptionType;
use HoneyComb\Core\Repositories\HCUserNotificationSubscriptionRepository;
use HoneyComb\Core\Repositories\HCUserNotificationSubscriptionTypeRepository;

/**
 * Class HCUserNotificationSubscriptionTypeService
 * @package HoneyComb\Core\Services
 */
class HCUserNotificationSubscriptionTypeService
{
    /**
     * @var HCUserNotificationSubscriptionRepository
     */
    private $repository;

    /**
     * HCUserNotificationSubscriptionTypeService constructor.
     * @param HCUserNotificationSubscriptionTypeRepository $repository
     */
    public function __construct(HCUserNotificationSubscriptionTypeRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return HCUserNotificationSubscriptionTypeRepository
     */
    public function getRepository(): HCUserNotificationSubscriptionTypeRepository
    {
        return $this->repository;
    }

    /**
     * @param string $id
     * @param string $translationKey
     * @return HCUserNotificationSubscriptionType
     */
    public function createType(string $id, string $translationKey): HCUserNotificationSubscriptionType
    {
        return $this->repository
            ->create([
                'id' => str_slug($id),
                'translation_key' => $translationKey,
            ]);
    }

    /**
     * @param string $id
     * @param string $translationKey
     * @return HCUserNotificationSubscriptionType
     */
    public function updateType(string $id, string $translationKey): HCUserNotificationSubscriptionType
    {
        return $this->repository->updateOrCreate(['id' => str_slug($id)], ['translation_key' => $translationKey]);
    }

    /**
     * @param string $id
     */
    public function deleteType(string $id): void
    {
        $this->repository->makeQuery()
            ->where(['id' => $id])
            ->delete();
    }
}
