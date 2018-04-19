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
use HoneyComb\Starter\Models\HCUuidSoftModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * Class HCAclPermission
 *
 * @package HoneyComb\Core\Models\Acl
 * @property string $id
 * @property int $count
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property string $name
 * @property string $controller
 * @property string $action
 * @property-read Collection|HCAclRole[] $roles
 * @method static Builder|HCAclPermission whereAction($value)
 * @method static Builder|HCAclPermission whereController($value)
 * @method static Builder|HCAclPermission whereCount($value)
 * @method static Builder|HCAclPermission whereCreatedAt($value)
 * @method static Builder|HCAclPermission whereDeletedAt($value)
 * @method static Builder|HCAclPermission whereId($value)
 * @method static Builder|HCAclPermission whereName($value)
 * @method static Builder|HCAclPermission whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class HCAclPermission extends HCUuidSoftModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hc_acl_permission';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'controller',
        'action',
    ];

    /**
     * A permission can be applied to roles.
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            HCAclRole::class,
            'hc_acl_role_permission_connection',
            'permission_id',
            'role_id'
        );
    }

    /**
     * Delete permission with related connection in roles permissions tables
     *
     * @param $name
     * @param $action
     * @throws \Exception
     */
    public static function permissionDelete($name, $action): void
    {
        $permission = HCAclPermission::where('name', $name)
            ->where('action', $action)
            ->first();

        if (!is_null($permission)) {
            RolesPermissionsConnections::where('permission_id', $permission->id)->delete();

            $permission->forceDelete();
        }
    }

    /**
     * Get name attribute
     *
     * @param $value
     * @return string
     */
    public function getNameAttribute($value): string
    {
        return $this->attributes['name'] = trans($value);
    }
}
