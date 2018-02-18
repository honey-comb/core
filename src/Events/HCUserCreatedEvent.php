<?php

namespace HoneyComb\Core\Events;

use HoneyComb\Core\Models\HCUser;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class HCUserCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var \HoneyComb\Core\Models\HCUser
     */
    private $user;

    /**
     * Create a new event instance.
     *
     * @param \HoneyComb\Core\Models\HCUser $user
     */
    public function __construct(HCUser $user)
    {
        //
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
    public function getUser(): HCUser
    {
        return $this->user;
    }
}
