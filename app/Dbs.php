<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dbs extends Model
{
    public $timestamps = false;
    protected $table = 'dbs';
    protected $fillable = [
        'label',
        'driver',
        'host',
        'port',
        'username',
        'password',
        'database',
        'charset',
    ];
}
