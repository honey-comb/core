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

namespace HoneyComb\Core\Models\Acl;

use Carbon\Carbon;
use HoneyComb\Core\Models\HCUser;
use HoneyComb\Starter\Models\HCUuidModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * Class HCAclRole
 *
 * @package HoneyComb\Core\Models\Acl
 * @property string $id
 * @property int $count
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property string $name
 * @property string $slug
 * @property-read Collection|HCAclPermission[] $permissions
 * @property-read Collection|HCUsers[] $users
 * @method static Builder|HCAclRole notSuperAdmin()
 * @method static Builder|HCAclRole superAdmin()
 * @method static Builder|HCAclRole whereCount($value)
 * @method static Builder|HCAclRole whereCreatedAt($value)
 * @method static Builder|HCAclRole whereDeletedAt($value)
 * @method static Builder|HCAclRole whereId($value)
 * @method static Builder|HCAclRole whereName($value)
 * @method static Builder|HCAclRole whereSlug($value)
 * @method static Builder|HCAclRole whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class HCAclRole extends HCUuidModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hc_acl_roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'slug',
    ];

    /**
     * A role may be given various permissions.
     *
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            HCAclPermission::class,
            'hc_acl_role_permissions',
            'role_id',
            'permission_id'
        );
    }

    /**
     * Grant the given permission to a role.
     *
     * @param HCAclPermission $permission
     * @return mixed
     */
    public function givePermissionTo(HCAclPermission $permission)
    {
        return $this->permissions()->save($permission);
    }

    /**
     * A role may be given various users.
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(HCUser::class, RolesUsersConnections::getTableName(), 'role_id', 'user_id');
    }

    /**
     * Get super admin
     *
     * @param $query
     */
    public function scopeSuperAdmin($query)
    {
        return $query->select('id', 'slug', 'name')->where('slug', 'super-admin');
    }

    /**
     * Get super admin
     *
     * @param $query
     */
    public function scopeNotSuperAdmin($query)
    {
        return $query->select('id', 'slug', 'name')->where('slug', '!=', 'super-admin');
    }

}
