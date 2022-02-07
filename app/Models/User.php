<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use LdapRecord\Laravel\Auth\LdapAuthenticatable;
use LdapRecord\Laravel\Auth\AuthenticatesWithLdap;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements LdapAuthenticatable
{
    use Notifiable, AuthenticatesWithLdap, HasApiTokens;

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    protected $hidden = [
        'remember_token',
    ];

}
