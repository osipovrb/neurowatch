<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HostGroup extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'title',
        'searchtree',
    ];

}
