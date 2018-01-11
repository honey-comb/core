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

use HoneyComb\Core\Models\Users\HCUserProvider;
use HoneyComb\Core\Repositories\HCBaseRepository;

class HCUserProviderRepository extends HCBaseRepository
{
    /**
     * @return string
     */
    public function model(): string
    {
        return HCUserProvider::class;
    }

    /**
     * @param string $userId
     * @param string $providerUserId
     * @param string $provider
     * @param string $providerData
     * @return mixed
     */
    public function createProvider(
        string $userId,
        string $providerUserId,
        string $provider,
        string $providerData
    ): HCUserProvider {
        return $this->makeQuery()->create([
            'user_id' => $userId,
            'user_provider_id' => $providerUserId,
            'provider' => $provider,
            'response' => $providerData,
        ]);
    }
}
