<?php
/**
 * @copyright 2018 interactivesolutions
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
 * E-mail: info@interactivesolutions.lt
 * http://www.interactivesolutions.lt
 */

declare(strict_types = 1);

namespace HoneyComb\Core\DTO;

use Carbon\Carbon;
use HoneyComb\Core\Models\Acl\HCAclRole;
use HoneyComb\Starter\DTO\HCBaseDTO;
use Illuminate\Support\Collection;

/**
 * Class HCUserDTO
 * @package HoneyComb\Core\DTO
 */
class HCUserDTO extends HCBaseDTO
{
    /**
     * @var string
     */
    private $userId;

    /**
     * @var string
     */
    private $email;

    /**
     * @var Carbon|null
     */
    private $activatedAt;

    /**
     * @var Carbon|null
     */
    private $lastLogin;

    /**
     * @var Carbon|null
     */
    private $lastVisited;

    /**
     * @var Carbon|null
     */
    private $lastActivity;

    /**
     * @var Collection
     */
    private $roles;

    /**
     * @var null|string
     */
    private $firstName;

    /**
     * @var null|string
     */
    private $lastName;

    /**
     * @var null|string
     */
    private $description;

    /**
     * @var null|string
     */
    private $photoId;

    /**
     * HCUserDTO constructor.
     *
     * @param string $userId
     * @param string $email
     * @param Carbon|null $activatedAt
     * @param Carbon|null $lastLogin
     * @param Carbon|null $lastVisited
     * @param Carbon|null $lastActivity
     * @param string|null $firstName
     * @param string|null $lastName
     * @param string|null $photoId
     * @param string|null $description
     * @param Collection|null $roles
     */
    public function __construct(
        string $userId,
        string $email,
        Carbon $activatedAt = null,
        Carbon $lastLogin = null,
        Carbon $lastVisited = null,
        Carbon $lastActivity = null,
        string $firstName = null,
        string $lastName = null,
        string $photoId = null,
        string $description = null,
        Collection $roles = null
    ) {
        $this->userId = $userId;
        $this->email = $email;
        $this->activatedAt = $activatedAt;
        $this->lastLogin = $lastLogin;
        $this->lastVisited = $lastVisited;
        $this->lastActivity = $lastActivity;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->photoId = $photoId;
        $this->description = $description;
        $this->roles = $roles->pluck('id');
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @return Carbon|null
     */
    public function getActivatedAt(): ? string
    {
        if ($this->activatedAt) {
            return $this->activatedAt->toDateTimeString();
        }

        return null;
    }

    /**
     * @return string|null
     */
    public function getLastLogin(): ? string
    {
        if ($this->lastLogin) {
            return $this->lastLogin->toDateTimeString();
        }

        return null;
    }

    /**
     * @return string|null
     */
    public function getLastVisited(): ? string
    {
        if ($this->lastVisited) {
            return $this->lastVisited->toDateTimeString();
        }

        return null;
    }

    /**
     * @return string|null
     */
    public function getLastActivity(): ? string
    {
        if ($this->lastActivity) {
            return $this->lastActivity->toDateTimeString();
        }

        return null;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return int
     */
    public function getActivated(): int
    {
        if ($this->activatedAt) {
            return 1;
        }

        return 0;
    }

    /**
     * @return null|string
     */
    public function getFirstName(): ? string
    {
        return $this->firstName;
    }

    /**
     * @return null|string
     */
    public function getLastName(): ? string
    {
        return $this->lastName;
    }

    /**
     * @return null|string
     */
    private function getPhoto(): ? string
    {
        if ($this->getPhotoId()) {
            return route('resource.get', $this->getPhotoId());
        }

        return $this->photoId;
    }

    /**
     * @return null|string
     */
    public function getPhotoId(): ? string
    {
        return $this->photoId;
    }

    /**
     * @return null|string
     */
    public function getDescription(): ? string
    {
        return $this->description;
    }

    /**
     * @return Collection|HCAclRole
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @return array
     */
    protected function jsonData(): array
    {
        $data = [
            'id' => $this->getUserId(),
            'activated_at' => $this->getActivatedAt(),
            'last_login' => $this->getLastLogin(),
            'last_visited' => $this->getLastVisited(),
            'last_activity' => $this->getLastActivity(),
            'email' => $this->getEmail(),
            'is_active' => $this->getActivated(),
            'first_name' => $this->getFirstName(),
            'last_name' => $this->getLastName(),
            'photo' => $this->getPhoto(),
            'photo_id' => $this->getPhotoId(),
            'description' => $this->getDescription(),
            'roles' => $this->getRoles(),
        ];

        return $data;
    }
}
