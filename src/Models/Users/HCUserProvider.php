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
use HoneyComb\Starter\Models\HCUuidSoftModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class HCUserProvider
 *
 * @package HoneyComb\Core\Models\Users
 * @property string $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property string $user_id
 * @property string|null $user_provider_id
 * @property string $provider
 * @property string|null $profile_url
 * @property string|null $email
 * @property string|null $response
 * @property-read HCUser $user
 * @mixin \Eloquent
 */
class HCUserProvider extends HCUuidSoftModel
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'hc_user_provider';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'user_provider_id',
        'provider',
        'email',
        'profile_url',
        'response',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(HCUser::class, 'user_id', 'id');
    }
}
