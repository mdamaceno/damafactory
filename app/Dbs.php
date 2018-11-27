<?php

namespace App;

use App\Support\Helpers;
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
        'token',
    ];

    public function scopeFreesearch($query, $value)
    {
        return $query->whereRaw('label like ?', ['%' . $value . '%'])
                     ->orWhereRaw('host like ?', ['%' . $value . '%'])
                     ->orWhereRaw('port like ?', ['%' . $value . '%'])
                     ->orWhereRaw('database like ?', ['%' . $value . '%'])
                     ->orWhereRaw('username like ?', ['%' . $value . '%'])
                     ->orWhereRaw('charset like ?', ['%' . $value . '%']);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($db) {
            $db->token = Helpers::securerandom();
        });
    }
}
