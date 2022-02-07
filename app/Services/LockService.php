<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;
use App\Events\LocksUpdated;

class LockService
{
    public static function isLocked(string $lockId): bool
    {
        return (bool) Redis::exists($lockId);
    }

    public static function lock(string $lockId): void
    {
        Redis::set($lockId, true);
        LocksUpdated::dispatch();
    }

    public static function unlock(string $lockId): void
    {
        Redis::del($lockId);
        LocksUpdated::dispatch();
    }

    public static function isLockIdValid(string $lockId): bool
    {
        return in_array($lockId, array_values(config('locks')))
            ? true
            : false;
    }

    public static function getLocks(): array
    {
        $locks = [];
        foreach (config('locks') as $name => $id) {
            $locks[] = [
                'name' => $name,
                'id' => $id,
                'locked' => (boolean) Redis::exists($id)
            ];
        }
        return $locks;
    }
}
