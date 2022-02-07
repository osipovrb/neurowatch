<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\HostsScannerService;

class HostsScan implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $hostGroups;

    public function __construct(array $hostGroups = [])
    {
        $this->hostGroups = $hostGroups;
    }

    public function handle()
    {
        (new HostsScannerService($this->hostGroups))->scan();
    }
}
