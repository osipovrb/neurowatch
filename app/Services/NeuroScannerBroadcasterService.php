<?php

namespace App\Services;

use App\Events\NeuroScanner\Warning;
use App\Events\NeuroScanner\Error;
use App\Events\NeuroScanner\Info;
use App\Events\NeuroScanner\Operation;
use App\Events\NeuroScanner\Uri;
use App\Events\NeuroScanner\Result;
use App\Events\NeuroScanner\Timer;
use App\Models\Custom\HostInfo;
use Illuminate\Support\Facades\Redis;

class NeuroScannerBroadcasterService
{
    public const REDIS = [
        'info' => 'neuro_scanner_info',
        'timer' => 'neuro_scanner_timer',
        'warnings' => 'neuro_scanner_warnings',
        'error' => 'neuro_scanner_error',
        'operation' => 'neuro_scanner_operation',
        'uri' => 'neuro_scanner_uri',
        'results' => 'neuro_scanner_results',
    ];

    public const REDIS_LIST = ['warnings', 'results'];
    public const REDIS_DECODE = ['info', 'results'];

    public function clear()
    {
        foreach(array_values(self::REDIS) as $key) {
            Redis::del($key);
        }
    }

    public function getHistory()
    {
        $result = [];
        foreach (self::REDIS as $key => $redis_key) {
            if (in_array($key, self::REDIS_LIST)) {
                $llen = Redis::llen($redis_key);
                $rows = Redis::lrange($redis_key, 0, $llen-1);
                if ($rows === false) { // если список пустой, то lrange вернет false
                    $rows = [];
                }
                if (in_array($key, self::REDIS_DECODE)) {
                    $result[$key] = array_map('json_decode', $rows);
                } else {
                    $result[$key] = $rows;
                }
            } else {
                $result[$key] = Redis::get($redis_key);
                if (in_array($key, self::REDIS_DECODE)) {
                    $result[$key] = json_decode($result[$key]);
                }
            }
        }
        return $result;
    }

    public function broadcast(string $operation, mixed $payload, int $timer)
    {
        $this->timer($timer);
        $this->$operation($payload);
    }

    public function info(HostInfo $info)
    {
        Info::dispatch($info);
        Redis::set(self::REDIS['info'], json_encode($info));
    }

    public function timer(int $timer)
    {
        Timer::dispatch($timer);
        Redis::set(self::REDIS['timer'], $timer);
    }

    public function warning(string $warning)
    {
        Warning::dispatch($warning);
        Redis::rpush(self::REDIS['warnings'], $warning);
    }

    public function error(string $error)
    {
        Error::dispatch($error);
        Redis::set(self::REDIS['error'], $error);
    }

    public function operation(string $operation)
    {
        Operation::dispatch($operation);
        Redis::set(self::REDIS['operation'], $operation);
    }

    public function uri(string $uri)
    {
        Uri::dispatch($uri);
        Redis::set(self::REDIS['uri'], $uri);
    }

    public function result(array $result)
    {
        Result::dispatch($result);
        Redis::rpush(self::REDIS['results'], json_encode($result));
    }

}
