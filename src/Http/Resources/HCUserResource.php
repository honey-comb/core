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

declare(strict_types = 1);

namespace HoneyComb\Core\Http\Resources;

use Carbon\Carbon;
use HoneyComb\Core\Models\HCUser;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

/**
 * Class HCUserResource
 * @package HoneyComb\Core\Http\Resources
 */
class HCUserResource extends ResourceCollection
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
     * @var null|string
     */
    private $phone;

    /**
     * @var null|string
     */
    private $address;

    /**
     * @var null|string
     */
    private $notificationEmail;

    /**
     * HCUserResource constructor.
     *
     * @param HCUser $model
     */
    public function __construct(HCUser $model)
    {
        parent::__construct($model);

        $this->userId = $model->id;
        $this->email = $model->email;
        $this->activatedAt = $model->activated_at;
        $this->lastLogin = $model->last_login;
        $this->lastVisited = $model->last_visited;
        $this->lastActivity = $model->last_activity;
        $this->firstName = optional($model->personal)->first_name;
        $this->lastName = optional($model->personal)->last_name;
        $this->notificationEmail = optional($model->personal)->notification_email;
        $this->photoId = optional($model->personal)->photo_id;
        $this->description = optional($model->personal)->description;
        $this->phone = optional($model->personal)->phone;
        $this->address = optional($model->personal)->address;
        $this->roles = $model->roles->pluck('id');
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
     * @return null|string
     */
    public function getPhone(): ? string
    {
        return $this->phone;
    }

    /**
     * @return null|string
     */
    public function getAddress(): ? string
    {
        return $this->address;
    }

    /**
     * @return Collection
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
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
            'phone' => $this->getPhone(),
            'address' => $this->getAddress(),
            'roles' => $this->getRoles(),
            'notification_email' => $this->getNotificationEmail(),
        ];
    }

    /**
     * @return null|string
     */
    public function getNotificationEmail(): ?string
    {
        return $this->notificationEmail;
    }
}
