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

namespace HoneyComb\Core\Repositories\Users;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use HoneyComb\Core\Models\Users\HCUserActivation;
use HoneyComb\Core\Repositories\HCBaseRepository;

class HCUserActivationRepository extends HCBaseRepository
{
    /**
     * @return string
     */
    public function model(): string
    {
        return HCUserActivation::class;
    }

    /**
     * @param string $userId
     * @return HCUserActivation|Model|null
     */
    public function getActivation(string $userId): ? HCUserActivation
    {
        return $this->makeQuery()->where('user_id', '=', $userId)->first();
    }

    /**
     * @param string $token
     * @return HCUserActivation|Model|null
     */
    public function getActivationByToken(string $token): ? HCUserActivation
    {
        return $this->makeQuery()->where('token', '=', $token)->first();
    }

    /**
     * @param string $token
     */
    public function deleteActivation(string $token): void
    {
        $this->makeQuery()->where('token', '=', $token)->delete();
    }

    /**
     * @param string $userId
     * @param string $token
     */
    public function insertActivation(string $userId, string $token): void
    {
        $this->makeQuery()->insert([
            'user_id' => $userId,
            'token' => $token,
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
    }

    /**
     * @param string $userId
     * @param string $token
     */
    public function updateUserActivations(string $userId, string $token): void
    {
        $this->makeQuery()
            ->where('user_id', '=', $userId)
            ->update([
                'token' => $token,
                'created_at' => Carbon::now()->toDateTimeString(),
            ]);
    }
}
