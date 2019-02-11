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

namespace HoneyComb\Core\Models\Users;

use HoneyComb\Core\Models\HCUser;
use HoneyComb\Starter\Models\HCModel;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class HCUserNotificationSubscription
 * @package HoneyComb\Core\Models\Users
 * @property int $count
 * @property Carbon $created_at
 * @property string $user_id
 * @property string $type_id
 * @mixin \Eloquent
 */
class HCUserNotificationSubscription extends HCModel
{
    /**
     * @var string
     */
    protected $primaryKey = 'count';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'hc_user_notification_subscription';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'type_id',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(HCUser::class, 'id', 'user_id');
    }

    /**
     * @return HasOne
     */
    public function type(): HasOne
    {
        return $this->hasOne(HCUserNotificationSubscriptionType::class, 'id', 'type_id');
    }
}
