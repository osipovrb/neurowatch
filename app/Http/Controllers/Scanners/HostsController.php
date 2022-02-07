<?php

namespace App\Http\Controllers\Scanners;

use App\Http\Controllers\Controller;
use App\Jobs\HostsScan;
use App\Services\LockService;
use Illuminate\Http\Request;

class HostsController extends Controller
{

    public function isLocked()
    {
        return response(LockService::isLocked('scan'), 200);
    }

    public function start(Request $request)
    {
        if (!LockService::isLocked('scan')) {
            HostsScan::dispatch($request->hostGroups);
        }
    }

}
