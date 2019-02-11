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

use HoneyComb\Starter\DTO\HCBaseDTO;
use Laravel\Socialite\Two\User;

/**
 * Class HCSocialProviderDTO
 *
 * @property User user
 * @property string provider
 * @package HoneyComb\Core\DTO
 */
class HCSocialProviderDTO extends HCBaseDTO
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var string
     */
    private $provider;

    /**
     * HCSocialProviderDTO constructor.
     * @param string $provider
     * @param User $user
     */
    public function __construct(string $provider, User $user)
    {
        $this->user = $user;
        $this->provider = $provider;
    }

    /**
     * @return string
     */
    public function getProvider(): string
    {
        return $this->provider;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->user->getId();
    }

    /**
     * @return string
     */
    public function getNickname(): string
    {
        return $this->user->getNickname();
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->user->getEmail();
    }

    /**
     * @return null|string
     */
    public function getFirstName(): ?string
    {
        $name = explode(' ', $this->user->getName(), 2);

        return array_get($name, '0');
    }

    /**
     * @return null|string
     */
    public function getLastName(): ?string
    {
        $name = explode(' ', $this->user->getName(), 2);

        return array_get($name, '0');
    }

    /**
     * @return null|string
     */
    public function getFullName(): ?string
    {
        return $this->user->getName();
    }

    /**
     * @return null|string
     */
    public function getAvatarUrl(): ?string
    {
        switch ($this->provider) {
            case 'google':
            case 'facebook':
            case 'linkedin':
                return $this->user->avatar_original;
                break;

            case 'github':
            case 'twitter':
                return $this->user->avatar;
                break;

            case 'bitbucket':
                if ($this->user->avatar) {
                    return str_replace('32', '500', $this->user->avatar);
                }
                break;
        }

        return null;
    }

    /**
     * @return null|string
     */
    public function getProfileUrl(): ?string
    {
        switch ($this->provider) {
            case 'facebook':
                return $this->user->profileUrl;
                break;

            case 'bitbucket':
                return array_get($this->user->user, 'links.html.href');
                break;

            case 'linkedin':
                return array_get($this->user->user, 'publicProfileUrl');
                break;

            case 'github':
                return array_get($this->user->user, 'html_url');
                break;

            case 'google':
                return array_get($this->user->user, 'url');
                break;
        }

        return null;
    }

    /**
     * @return array
     */
    public function getRawData(): array
    {
        return $this->user->getRaw();
    }

    /**
     * @return array
     * @throws \Exception
     */
    protected function jsonData(): array
    {
        return [
            'id' => $this->getId(),
            'email' => $this->getEmail(),
            'nickname' => $this->getNickname(),
            'first_name' => $this->getFirstName(),
            'last_name' => $this->getLastName(),
            'full_name' => $this->getFullName(),
            'avatar_url' => $this->getAvatarUrl(),
            'profile_url' => $this->getProfileUrl(),
        ];
    }
}
