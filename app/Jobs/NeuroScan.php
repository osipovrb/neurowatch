<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\NeuroScannerService;

class NeuroScan implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $hostname;
    public int $threshold;

    public function __construct(string $hostname, int $threshold)
    {
        $this->hostname = $hostname;
        $this->threshold = $threshold;
    }

    public function handle()
    {
        (new NeuroScannerService($this->hostname, $this->threshold))->scan();
    }
}
