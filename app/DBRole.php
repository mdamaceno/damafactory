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

    public function users()
    {
        return $this->belongsToMany(User::class, 'db_roles_users', 'user_id', 'db_role_id');
    }
}
