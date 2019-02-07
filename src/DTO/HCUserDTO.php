<?php
/**
 * @copyright 2018 innovationbase
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

namespace HoneyComb\Core\DTO;

use HoneyComb\Core\Models\Acl\HCAclRole;
use HoneyComb\Core\Models\HCUser;
use HoneyComb\Starter\DTO\HCBaseDTO;
use Illuminate\Support\Collection;

/**
 * Class HCUserDTO
 * @package HoneyComb\Core\DTO
 */
class HCUserDTO extends HCBaseDTO
{
    /**
     * @var HCUser
     */
    private $user;

    /**
     * HCUserDTO constructor.
     * @param HCUser $user
     */
    public function __construct(HCUser $user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->user->id;
    }

    /**
     * @return string|null
     */
    public function getActivatedAt(): ? string
    {
        if ($this->user->activated_at) {
            return $this->user->activated_at->toDateTimeString();
        }

        return null;
    }

    /**
     * @return string|null
     */
    public function getLastLogin(): ? string
    {
        if ($this->user->last_login) {
            return $this->user->last_login->toDateTimeString();
        }

        return null;
    }

    /**
     * @return string|null
     */
    public function getLastActivity(): ? string
    {
        if ($this->user->last_activity) {
            return $this->user->last_activity->toDateTimeString();
        }

        return null;
    }

    /**
     * @return bool
     */
    public function getActivated(): bool
    {
        return !is_null($this->getActivatedAt());
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->user->email;
    }

    /**
     * @return null|string
     */
    public function getFirstName(): ?string
    {
        if ($this->user->personal) {
            return $this->user->personal->first_name;
        }

        return null;
    }

    /**
     * @return null|string
     */
    public function getLastName(): ?string
    {
        if ($this->user->personal) {
            return $this->user->personal->last_name;
        }

        return null;
    }

    /**
     * @return null|string
     */
    public function getPhoto(): ?string
    {
        if ($this->user->personal) {
            return $this->user->personal->photo;
        }

        return null;
    }

    /**
     * @return null|string
     */
    public function getFullName(): ?string
    {
        return implode(' ', array_filter([$this->getFirstName(), $this->getLastName()]));
    }

    /**
     * @return null|string
     */
    public function getDescription(): ? string
    {
        if ($this->user->personal) {
            return $this->user->personal->description;
        }

        return null;
    }

    /**
     * @return null|string
     */
    public function getPhone(): ? string
    {
        if ($this->user->personal) {
            return $this->user->personal->phone;
        }

        return null;
    }

    /**
     * @return null|string
     */
    public function getAddress(): ? string
    {
        if ($this->user->personal) {
            return $this->user->personal->address;
        }

        return null;
    }

    /**
     * @return null|string
     */
    public function getNotificationEmail(): ?string
    {
        if ($this->user->personal) {
            return $this->user->personal->notification_email;
        }

        return null;
    }

    /**
     * @return Collection
     */
    public function getRoles(): Collection
    {
        return $this->user->roles;
    }

    /**
     * @return array
     */
    public function getRoleIds(): array
    {
        return $this->getRoles()->pluck('id')->toArray();
    }

    /**
     * @return array
     */
    public function getAuthorizeData(): array
    {
        return [
            'user' => [
                'email' => $this->getEmail(),
                'first_name' => $this->getFirstName(),
                'last_name' => $this->getLastName(),
            ],
        ];
    }

    /**
     * @return array
     * @throws \Exception
     */
    protected function jsonData(): array
    {
        return [
            'id' => $this->getId(),
            'activated_at' => $this->getActivatedAt(),
            'last_login' => $this->getLastLogin(),
            'last_activity' => $this->getLastActivity(),
            'email' => $this->getEmail(),
            'is_active' => $this->getActivated(),
            'first_name' => $this->getFirstName(),
            'last_name' => $this->getLastName(),
            'photo' => $this->getPhoto(),
            'description' => $this->getDescription(),
            'phone' => $this->getPhone(),
            'address' => $this->getAddress(),
            'roles' => $this->getRoleIds(),
            'notification_email' => $this->getNotificationEmail(),
        ];
    }
}
