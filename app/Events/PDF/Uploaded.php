<?php

namespace App\Events\PDF;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Uploaded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $path;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }
}
