<?php

declare(strict_types=1);

namespace App\Domains\DevSupport\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Generic Testing event to bubble up data that can then be stored as a resource for testing.
 */
class DevDTOTransfer
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public $payload,
    ) {
    }
}
