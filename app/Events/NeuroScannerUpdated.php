<?php

namespace App\Events;

use App\Services\NeuroScannerService;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NeuroScannerUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public NeuroScannerService $scanner;

    public function __construct(NeuroScannerService $scanner)
    {
        $this->scanner = $scanner;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('neuro_scanner');
    }
}
