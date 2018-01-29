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

use HoneyComb\Core\DTO\HCUserDTO;
use HoneyComb\Core\Models\HCUser;
use HoneyComb\Core\Repositories\Traits\HCQueryBuilderTrait;
use HoneyComb\Starter\Repositories\HCBaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

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
     * @return void
     */
    public function deleteSoft(array $userIds): void
    {
        $users = $this->makeQuery()->whereIn('id', $userIds)->get();

        foreach ($users as $user) {
            /** @var HCUser $user */
            $user->providers()->delete();
            $user->personal()->delete();
            $user->delete();
        }
    }

    /**
     * Restore soft deleted users
     *
     * @param array $userIds
     * @return void
     */
    public function restore(array $userIds): void
    {
        $users = $this->makeQuery()->withTrashed()->whereIn('id', $userIds)->get();

        foreach ($users as $user) {
            /** @var HCUser $user */
            $user->providers()->restore();
            $user->personal()->restore();
            $user->restore();
        }
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
        $users = $this->makeQuery()->withTrashed()->whereIn('id', $userIds)->get();

        foreach ($users as $user) {
            /** @var HCUser $user */
            $user->providers()->forceDelete();
            $user->personal()->forceDelete();
            $user->roles()->detach();
            $user->activation()->delete();
            $user->forceDelete();
        }
    }

    /**
     * @param string $userId
     * @return HCUserDTO
     */
    public function getRecordById(string $userId): HCUserDTO
    {
        $record = $this->getById($userId);

        $record->load([
            'roles' => function ($query) {
                $query->select('id', 'name as label');
            },
            'personal' => function ($query) {
                $query->select('user_id', 'first_name', 'last_name', 'photo_id', 'description');
            },
        ]);

        return new HCUserDTO(
            $record->id,
            $record->email,
            $record->activated_at,
            $record->last_login,
            $record->last_visited,
            $record->last_activity,
            optional($record->personal)->first_name,
            optional($record->personal)->last_name,
            optional($record->personal)->photo_id,
            optional($record->personal)->description,
            $record->roles
        );
    }
}
