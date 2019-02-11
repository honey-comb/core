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

namespace HoneyComb\Core\DTO;

use HoneyComb\Core\Models\HCUser;
use HoneyComb\Starter\DTO\HCBaseDTO;
use Laravel\Passport\PersonalAccessTokenResult;

/**
 * Class HCAuthorizeDTO
 * @package HoneyComb\Core\DTO
 */
class HCAuthorizeDTO extends HCBaseDTO
{
    /**
     * @var HCUser
     */
    private $user;

    /**
     * @var PersonalAccessTokenResult
     */
    private $token;
    /**
     * @var array
     */
    private $config;

    /**
     * WAuthorizeDTO constructor.
     * @param HCUser $user
     * @param PersonalAccessTokenResult $token
     * @param array $config
     */
    public function __construct(HCUser $user, PersonalAccessTokenResult $token, array $config = [])
    {
        $this->user = $user;
        $this->token = $token;
        $this->config = $config;
    }

    /**
     * @return array
     * @throws \Exception
     */
    protected function jsonData(): array
    {
        $data = (new HCUserDTO($this->user))->getAuthorizeData();
        $data['token'] = (new HCTokenDTO($this->token))->toArray();
        $data['config'] = $this->config;

        return $data;
    }
}
