<?php

namespace App\Events\NeuroScanner;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Operation implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $operation;

    public function __construct(string $operation)
    {
        $this->operation = $operation;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('neuro_scanner');
    }
}
