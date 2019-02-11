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

namespace HoneyComb\Core\Repositories;

use HoneyComb\Core\Http\Requests\Admin\HCUserRequest;
use HoneyComb\Core\Models\HCUser;
use HoneyComb\Starter\Repositories\HCBaseRepository;
use HoneyComb\Starter\Repositories\Traits\HCQueryBuilderTrait;
use Illuminate\Support\Collection;

/**
 * Class HCUserRepository
 * @package HoneyComb\Core\Repositories
 */
class HCUserRepository extends HCBaseRepository
{
    use HCQueryBuilderTrait;

    /**
     * @return string
     */
    public function model(): string
    {
        return HCUser::class;
    }

    /**
     * @param string $userId
     * @param array $with
     * @return HCUser
     */
    public function findById(string $userId, array $with = []): HCUser
    {
        return $this->makeQuery()->with($with)
            ->where('id', $userId)
            ->firstOrFail();
    }

    /**
     * Soft delete users
     *
     * @param array $userIds
     * @return array
     */
    public function deleteSoft(array $userIds): array
    {
        $deleted = [];

        foreach ($userIds as $userId) {
            if ($this->makeQuery()->where('id', $userId)->delete()) {
                $deleted[] = $this->makeQuery()->withTrashed()->find($userId);
            }
        }

        return $deleted;
    }

    /**
     * Restore soft deleted users
     *
     * @param array $userIds
     * @return array
     */
    public function restore(array $userIds): array
    {
        $this->makeQuery()->withTrashed()->whereIn('id', $userIds)->restore();

        return $userIds;
    }

    /**
     * Force delete users by given id
     *
     * @param array $userIds
     * @return array
     */
    public function deleteForce(array $userIds): array
    {
        $deleted = [];

        $users = $this->makeQuery()->withTrashed()->whereIn('id', $userIds)->get();

        /** @var HCUser $user */
        foreach ($users as $user) {
            if ($user->forceDelete()) {
                $deleted[] = $user;
            }
        }

        return $deleted;
    }

    /**
     * @param \HoneyComb\Core\Http\Requests\Admin\HCUserRequest $request
     * @return \Illuminate\Support\Collection
     */
    public function getOptions(HCUserRequest $request): Collection
    {
        return $this->createBuilderQuery($request)->get()->map(function ($record) {
            return [
                'id' => $record->id,
                'label' => $record->email,
            ];
        });
    }
}
