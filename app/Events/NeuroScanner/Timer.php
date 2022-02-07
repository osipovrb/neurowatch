<?php

namespace App\Events\NeuroScanner;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Timer implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $timer;

    public function __construct(int $timer)
    {
        $this->timer = $timer;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('neuro_scanner');
    }
}
