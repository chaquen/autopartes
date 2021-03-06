<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OrdenCreada
{
    use Dispatchable, SerializesModels;

    public $user;
    public $orden;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    
    public function __construct($user, $orden)
    {
        $this->user = $user;
        $this->orden = $orden;
    }
}
