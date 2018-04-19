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

namespace HoneyComb\Core\Repositories;

use HoneyComb\Core\Http\Requests\Admin\HCUserRequest;
use HoneyComb\Core\Http\Resources\HCUserResource;
use HoneyComb\Core\Models\HCUser;
use HoneyComb\Core\Repositories\Traits\HCQueryBuilderTrait;
use HoneyComb\Starter\Repositories\HCBaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\Builder;
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
     * @return HCUser|Model|null
     */
    public function getById(string $userId): ? HCUser
    {
        return $this->makeQuery()->find($userId);
    }

    /**
     * @param string $userId
     * @return HCUser|Model|null
     */
    public function getByIdWithPersonal(string $userId): ? HCUser
    {
        return $this->makeQuery()->with('personal')->where('id', '=', $userId)->firstOrFail();
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
     * @param string $userId
     * @return HCUserResource
     */
    public function getRecordById(string $userId): HCUserResource
    {
        /** @var HCUser $record */
        $record = $this->getById($userId);

        $record->load([
            'roles' => function (BelongsToMany $query) {
                $query->select('id', 'name as label');
            },
            'personal' => function (HasOne $query) {
                $query->select('user_id', 'first_name', 'last_name', 'photo_id', 'description', 'phone', 'address');
            },
        ]);

        return new HCUserResource($record);
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
                'label' => $record->email
            ];
        });
    }
}
