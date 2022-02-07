<?php

namespace App\Services;

use App\Models\Custom\HostInfo;
use App\Models\Custom\Samba;
use App\Services\LockService;
use Exception;

class NeuroScannerService
{

    protected string $hostname;
    protected int $threshold;
    protected HostInfo $info;
    protected int $startTime;
    protected NeuroScannerBroadcasterService $broadcaster;
    protected Samba $smb;

    public function __construct(string $hostname, int $threshold)
    {
        $this->hostname = $hostname;
        $this->threshold = $threshold;
        $this->broadcaster = new NeuroScannerBroadcasterService;
    }

    public function scan(): void
    {
        LockService::lock('neuro');
        $this->startTime = time();
        $this->broadcaster->clear();
        $this->broadcast('operation', 'Получаю информацию о хосте ' . $this->hostname . '...');

        try {
            $this->info = new HostInfo($this->hostname);
            if (!$this->info->isOnline) {
                throw new Exception('Хост оффлайн');
            }
            $this->broadcast('info', $this->info);
            $this->smb = new Samba($this->info);
            $this->scanTree();
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->broadcast('error', $e->getMessage());
        }

        LockService::unlock('neuro');
    }

    protected function scanTree(array $path = []): void
    {
        $this->broadcast('uri', $this->uri($path));
        if (empty($path)) {
            $this->broadcast('operation', 'Получаю информацию о корневых ресурсах...');
            $shares = $this->smb->enumShares();
            foreach ($shares as $share) {
                $this->scanTree([$share]);
            }
        } else {
            $this->broadcast('operation', 'Просматриваю папку...');
            try {
                $dir = $this->smb->readDir($path);
                foreach ($dir as $entry) {
                    if ($entry['type'] == 'file share' || $entry['type'] == 'directory') {
                        $nextPath = array_merge($path, [$entry['name']]);
                        $this->scanTree($nextPath);
                    }
                    if (preg_match('/jpg|jpeg|tiff|png|gif$/i', $entry['name'])) {
                        $this->scanFile($path, $entry['name']);
                    }
                }
            } catch (Exception $e) {
                $this->broadcast('warning', $e->getMessage());
            }
        }
    }

    protected function scanFile(array $path, string $filename): void
    {
        $this->broadcast('operation', 'Сканирую файл ' . $filename . '...');
        $file = $this->smb->getFile($path, $filename);
        $path[] = $filename;
        $uri = $this->uri($path);
        $estimate = shell_exec('python /home/bru/python/maps/classifier.py --image_file="' . $file . '" --tflite_model=/home/bru/python/maps/model.tflite');
        $probe = $this->parseProbe($estimate);
        if ($probe[0]*100 >= $this->threshold) {
            $result = [
                'file' => compact('uri', 'filename'),
                'probe' => $probe,
            ];
            $this->broadcast('result', $result);
        }
        $this->smb->delFile($filename);
    }

    protected function parseProbe(string $probe): array
    {
        $probe = str_replace(['[', ']'], '', $probe);
        $probeArr = explode(' ', $probe);
        return array_map('floatval', $probeArr);
    }

    protected function uri(array $path): string
    {
        $uri = '\\\\' . $this->info->ip . '\\';
        $uri .= join('\\', $path);
        return $uri;
    }

    protected function broadcast(string $operation, mixed $payload): void
    {
        $timer = time() - $this->startTime;
        $this->broadcaster->broadcast($operation, $payload, $timer);
    }

}
