<?php

declare(strict_types = 1);

namespace HoneyComb\Core\Events\Admin;

use HoneyComb\Core\Models\HCUser;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * Class HCUserUpdated
 * @package HoneyComb\Core\Events\Admin
 */
class HCUserUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var HCUser
     */
    private $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(HCUser $user)
    {
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn($id)
    {
        return new PrivateChannel('channel-name');
    }
}
