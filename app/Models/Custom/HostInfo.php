<?php

namespace App\Models\Custom;

use Exception;
use Illuminate\Support\Facades\DB;
use LdapRecord\Models\ActiveDirectory\User;

class HostInfo
{
    public string $ip;
    public string $hostname;
    public string $searchtree;
    public string $username;
    public string $samaccountname;
    public bool $isOnline;

    private $info;

    public function __construct(string $hostname)
    {
        $this->hostname = $hostname;
        $this->getInfo();
        $this->getIp();
        $this->getSamaccountname();
        $this->getUsername();
        $this->getOnlineStatus();
    }

    private function getInfo()
    {
        $this->info = DB::connection('logon')->query()
            ->select('User_Name', 'IP_Address')
            ->from('BGInfoTable')
            ->where('Host_Name', $this->hostname)
            ->orderBy('Time_Stamp', 'desc')
            ->limit(1)
            ->get()
            ->first();
        if (is_null($this->info)) throw new Exception('Хост не найден в БД LOGON');
    }

    private function getIp()
    {
        preg_match('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $this->info->IP_Address, $ip);
        if (empty($ip)) throw new Exception('IP хоста не определен');
        $this->ip = $ip[0];
    }

    private function getSamaccountname()
    {
        $this->samaccountname = $this->info->User_Name;
    }

    private function getUsername()
    {
        $cn = User::select('cn')->where('sAMAccountName', '=', $this->samaccountname)->first();
        if (is_null($cn)) throw new Exception('Пользователь ' . $this->samaccountname . ' не найден в ActiveDirectory');
        $this->username = $cn->getFirstAttribute('cn');
    }

    private function getOnlineStatus()
    {
        try {
            $fp = fSockOpen($this->ip, 445, $errno, $errstr, 0.2);
            fclose($fp);
            $this->isOnline = true;
        } catch (Exception $e) {
            $this->isOnline = false;
        }
    }
}
