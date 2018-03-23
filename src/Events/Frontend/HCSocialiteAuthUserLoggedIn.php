<?php

namespace HoneyComb\Core\Events\frontend;

use HoneyComb\Core\Models\HCUser;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * Class HCSocialiteAuthUserLoggedIn
 * @package HoneyComb\Core\Events\frontend\HCSocialiteAuthUserLoggedIn
 */
class HCSocialiteAuthUserLoggedIn
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var HCUser
     */
    private $user;
    /**
     * @var string
     */
    private $provider;


    /**
     * HCSocialiteAuthUserLoggedIn constructor.
     * @param HCUser $user
     * @param string $provider
     */
    public function __construct(HCUser $user, string $provider)
    {
        //
        $this->user = $user;
        $this->provider = $provider;
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
