<?php

namespace App\Events\NeuroScanner;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Result implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $result;

    public function __construct(array $result)
    {
        $this->result = $result;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('neuro_scanner');
    }
}
