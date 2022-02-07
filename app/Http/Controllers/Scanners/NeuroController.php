<?php

namespace App\Http\Controllers\Scanners;

use App\Http\Controllers\Controller;
use App\Http\Requests\NeuroScannerStartRequest;
use App\Jobs\NeuroScan;
use App\Services\LockService;
use App\Services\NeuroScannerBroadcasterService;
use Exception;
use Illuminate\Http\Request;

class NeuroController extends Controller
{

    public function isLocked()
    {
        return response(LockService::isLocked('neuro'), 200);
    }

    public function start(NeuroScannerStartRequest $request)
    {
        if (!LockService::isLocked('neuro')) {
            try {
                NeuroScan::dispatch($request->hostname, $request->threshold);
                return response('OK', 200);
            } catch (Exception $e) {
                return response($e->getMessage(), 500);
            }
        } else {
            return response('Сканер уже работает', 409);
        }
    }

    public function getHistory()
    {
        $data = (new NeuroScannerBroadcasterService)->getHistory();
        return response($data, 200);
    }

}
