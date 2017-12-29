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

namespace HoneyComb\Core\Models\Users;

use Illuminate\Database\Eloquent\Builder;
use HoneyComb\Core\Models\HCModel;

/**
 * Class HCUserActivation
 *
 * @package HoneyComb\Core\Models\Users
 * @property int $count
 * @property string $user_id
 * @property string $token
 * @property \Carbon\Carbon $created_at
 * @method static Builder|HCUserActivation whereCount($value)
 * @method static Builder|HCUserActivation whereCreatedAt($value)
 * @method static Builder|HCUserActivation whereToken($value)
 * @method static Builder|HCUserActivation whereUserId($value)
 * @mixin \Eloquent
 */
class HCUserActivation extends HCModel
{
    /**
     * @var string
     */
    protected $table = 'hc_user_activations';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'token',
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
}
