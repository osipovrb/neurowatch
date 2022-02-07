<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\LockService;

class LocksController extends Controller
{

    public function index()
    {
        return response()->json(LockService::getLocks());
    }

    public function destroy(string $lock_id)
    {
        if (!LockService::isLockIdValid($lock_id)) {
            return response('Unprocessable Content', 422);
        }
        LockService::unlock($lock_id);
        return response('OK', 200);
    }

}
