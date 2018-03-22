<?php

declare(strict_types = 1);

namespace HoneyComb\Core\Events\Admin;

use HoneyComb\Core\Services\Acl\HCRoleService;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class HCRolePermissionUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var string
     */
    private $roleId;

    /**
     * @var string
     */
    private $permissionId;

    /**
     * @var string
     */
    private $status;

    /**
     * HCRoleUpdatedPermissions constructor.
     * @param string $roleId
     * @param string $permissionId
     * @param string $status
     */
    public function __construct(string $roleId, string $permissionId, string $status)
    {
        $this->roleId = $roleId;
        $this->permissionId = $permissionId;
        $this->status = $status;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
