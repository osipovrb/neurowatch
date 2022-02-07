<?php

namespace App\Events\NeuroScanner;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Warning implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $warning;

    public function __construct(string $warning)
    {
        $this->warning = $warning;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('neuro_scanner');
    }
}
