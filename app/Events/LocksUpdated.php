<?php

namespace App\Events;

use App\Services\LockService;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LocksUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $locks;

    public function __construct()
    {
        $this->locks = LockService::getLocks();
    }

    public function broadcastOn()
    {
        return new PrivateChannel('locks');
    }
}
