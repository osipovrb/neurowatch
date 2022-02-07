<?php

namespace App\Events\NeuroScanner;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Error implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $error;

    public function __construct(string $error)
    {
        $this->error = $error;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('neuro_scanner');
    }
}
