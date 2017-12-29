<?php

namespace HoneyComb\Core\DTO;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use HoneyComb\Core\Models\Acl\HCAclRole;

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
        $this->roles = $roles;
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
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @return array
     */
    public function getActivated(): array
    {
        if ($this->activatedAt) {
            return [
                ['id' => 1],
            ];
        }

        return [
            ['id' => 0],
        ];
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
            'roles' => $this->getRoles(),
        ];

        return $data;
    }
}
