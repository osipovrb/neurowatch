<?php

namespace App\Models\Custom;

use Exception;
use Illuminate\Support\Str;
use App\Models\Custom\HostInfo;

class Samba
{

    public HostInfo $info;

    private $state;

    public function __construct(HostInfo $info)
    {
        $this->info = $info;
        $this->state = smbclient_state_new();
        smbclient_state_init($this->state, null, env('SAMBA_USERNAME'), env('SAMBA_PASSWORD'));
    }

    public function enumShares(bool $onlyDrives = true)
    {
        try {
            $shares = $this->readDir();
        } catch (Exception $e) {
            $shares = $this->guessShares();
        }
        if ($onlyDrives) {
            $shares = array_filter($shares, function($item) {
                return preg_match('/^[a-z]{1}\$$/i', $item['name']);
            });
        }
        $result = array_map(function($item) {
            return $item['name'];
        }, $shares);
        return array_values($result);
    }

    public function guessShares()
    {
        $guessShares = ['C$', 'D$', 'E$', 'F$', 'G$'];
        $result = [];
        foreach ($guessShares as $share) {
            try {
                $uri = $this->uri($share);
                $this->readDir([$share]);
                $result[] = $share;
            } catch (Exception $e) {
                continue;
            }
        }
        return $result;
    }

    public function uri(array|string $path = '')
    {
        if (empty($path)) return 'smb://' . $this->info->ip . '/';
        if (is_string($path)) return $this->uri() . rawurlencode($path);
        if (is_array($path)) {
            $path = array_map('rawurlencode', $path);
            return $this->uri() . implode('/', $path);
        }
    }

    public function readDir(array $path=[])
    {
        $uri = $this->uri($path);
        $dir = smbclient_opendir($this->state, $uri);
        if (!$dir) {
            throw new Exception('Не могу открыть ' . $uri);
        }

        $results = [];
        while($entry = smbclient_readdir($this->state, $dir)) {
            if (Str::startsWith($entry['name'], '.')) continue;
            $results[] = $entry;
        }

        smbclient_closedir($this->state, $dir);
        return $results;
    }

    public function getFile(array $path, string $filename)
    {
        $path[] = $filename;
        $uri = $this->uri($path);

        $src_filename = $uri;
        $dest_filename = sys_get_temp_dir() . '/' . $filename;

        $src_stream = smbclient_open($this->state, $src_filename , 'r');
        if (!$src_stream) throw new Exception('Не могу прочитать файл ' . $uri);
        if (file_exists($dest_filename)) unlink($dest_filename);
        $dest_stream = fopen($dest_filename, 'a');

        while (true) {
            $data = smbclient_read($this->state, $src_stream, 100000);
            if ($data === false || strlen($data) === 0) {
                break;
            }
            fwrite($dest_stream, $data);
        }

        fclose($dest_stream);
        smbclient_close($this->state, $src_stream);

        return $dest_filename;
    }

    public function delFile(string $filename)
    {
        $filename = sys_get_temp_dir() . '/' . $filename;
        unlink($filename);
    }

}
