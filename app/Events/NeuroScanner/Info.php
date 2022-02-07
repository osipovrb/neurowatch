<?php

namespace App\Events\NeuroScanner;

use App\Models\Custom\HostInfo;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Info implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public HostInfo $info;

    public function __construct(HostInfo $info)
    {
        $this->info = $info;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('neuro_scanner');
    }
}
