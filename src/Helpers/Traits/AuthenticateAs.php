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

namespace HoneyComb\Core\Helpers\Traits;

use HoneyComb\Core\Models\Acl\HCAclRole;
use HoneyComb\Core\Models\HCUser;
use HoneyComb\Core\Models\Users\HCUserRole;
use HoneyComb\Core\Repositories\Acl\HCRoleRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;

/**
 * Trait AuthenticateAs
 * @package HoneyComb\Core\Helpers\Traits
 */
trait AuthenticateAs
{
    /**
     * @param array $roles
     * @param bool $remember
     * @param array $data
     * @return Authenticatable
     */
    public function authenticateAs(
        array $roles = [HCRoleRepository::ROLE_SA],
        bool $remember = false,
        array $data = []
    ): Authenticatable {
        $user = factory(HCUser::class)->create($data);

        if ($roles) {
            $this->createRoles($user, $roles);
        }

        auth()->login($user, $remember);

        return $user;
    }

    /**
     * @param Authenticatable|HCUserRole $user
     * @param array $roles
     */
    private function createRoles(Authenticatable $user, array &$roles): void
    {
        /** @var Collection|HCUserRole[] $createdRoles */
        $createdRoles = collect();
        foreach ($roles as $role) {
            $createdRoles->offsetSet(
                $role,
                factory(HCAclRole::class)->create([
                    'name' => ucwords(str_replace('-', ' ', $role), ' '),
                    'slug' => $role,
                ])
            );
        }

        $user->roles()->sync($createdRoles->pluck('id'));
    }
}
