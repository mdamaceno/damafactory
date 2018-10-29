<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DBToken extends Model
{
    protected $table = 'db_tokens';

    protected $fillable = [
        'user_id',
        'dbs_id',
        'http_permission',
    ];
}
