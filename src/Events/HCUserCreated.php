<?php

namespace HoneyComb\Core\Events;

use HoneyComb\Core\Models\HCUser;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Class HCUserCreated
 * @package HoneyComb\Core\Events
 */
class HCUserCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var HCUser
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * HCUserCreated constructor.
     * @param HCUser $user
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
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    /**
     * @return \HoneyComb\Core\Models\HCUser
     */
}
