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

namespace HoneyComb\Core\Models\Users;

use HoneyComb\Core\Models\HCUser;
use HoneyComb\Starter\Models\HCModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class HCUserPersonalInfo
 *
 * @package HoneyComb\Core\Models\Users
 * @property int $count
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $user_id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $photo_id
 * @property string|null $photo
 * @property string|null $description
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $notification_email
 * @property-read HCUser $user
 * @mixin \Eloquent
 */
class HCUserPersonalInfo extends HCModel
{
    /**
     * @var string
     */
    protected $table = 'hc_user_personal_info';

    /**
     * @var string
     */
    protected $primaryKey = 'count';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'photo_id',
        'photo',
        'description',
        'phone',
        'address',
        'notification_email',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(HCUser::class, 'user_id', 'id');
    }
}
