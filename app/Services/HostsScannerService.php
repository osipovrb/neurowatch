<?php

namespace App\Services;

use App\Events\HostsScannerUpdated;
use App\Models\HostGroup;
use App\Models\Custom\HostInfo;
use App\Services\LockService;
use Exception;
use LdapRecord\Models\ActiveDirectory\Computer;

class HostsScannerService
{

    public int $hostsCount;
    public string $operation;
    public array $results;
    public array $warnings;

    private array $hosts;
    private array $hostGroups;
    private int $startTime;

    public function __construct(array $hostGroups = [])
    {
        $this->hostsCount = 0;
        $this->operation = '';
        $this->results = [];
        $this->warnings = [];
        $this->timer = 0;

        $this->hosts = [];
        $this->hostGroups = $hostGroups;
        $this->startTime = 0;
    }

    public function scan(): void
    {
        LockService::lock('scan');
        $this->startTime = time();
        $this->getHosts();

        foreach ($this->hosts as $host) {
            $this->operation = 'Сканирую хост "' . $host['name'] . '"...';
            $this->broadcast();
            try {
                $info = new HostInfo($host['name']);
            } catch (Exception $e) {
                $this->warnings[] = [
                    'hostname' => $host['name'],
                    'warning' => $e->getMessage(),
                ];
                $this->broadcast();
                continue;
            }

            $info->searchtree = $host['searchtree'];
            $this->results[] = (array) $info;

            $this->broadcast();
        }

        $this->operation = 'Сканирование завершено';
        $this->broadcast();
        LockService::unlock('scan');
    }

    public function getHosts(): void
    {
        $hostGroups = (empty($this->hostGroups))
            ? HostGroup::all()
            : HostGroup::whereIn('id', $this->hostGroups)->get();
        foreach ($hostGroups as $hostGroup) {
            $this->operation = 'Извлекаю хосты из дерева поиска "' . $hostGroup->title . '"...';
            $this->broadcast();
            $computers = (new Computer)->query()->in($hostGroup->searchtree)->get();
            foreach ($computers as $computer) {
                $this->hosts[] = [
                    'name' => explode(';', $computer->getFirstAttribute('name'))[0],
                    'searchtree' => $hostGroup->title,
                ];
            }
            $this->hostsCount = count($this->hosts);
            $this->broadcast();
        }
    }

    private function broadcast()
    {
        $this->timer = time() - $this->startTime;
        HostsScannerUpdated::dispatch($this);
    }

}
