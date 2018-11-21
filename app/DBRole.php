<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DBRole extends Model
{
    protected $table = 'db_roles';

    protected $fillable = [
        'name',
        'http_permission',
        'active',
    ];

    public function scopeFreesearch($query, $value)
    {
        return $query->whereRaw('name like ?', ['%' . $value . '%'])
                     ->orWhereRaw('http_permission like ?', ['%' . $value . '%']);
    }
}
