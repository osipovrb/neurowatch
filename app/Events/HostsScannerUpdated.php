<?php

namespace App\Events;

use App\Services\HostsScannerService;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class HostsScannerUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public HostsScannerService $scanner;

    public function __construct(HostsScannerService $scanner)
    {
        $this->scanner = $scanner;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('hosts_scanner');
    }
}
