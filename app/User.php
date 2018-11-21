<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function scopeFreesearch($query, $value)
    {
        return $query->whereRaw('name like ?', ['%' . $value . '%'])
                     ->orWhereRaw('email like ?', ['%' . $value . '%']);
    }

    public function authTokens()
    {
        return $this->hasMany(AuthToken::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->password = bcrypt($user->password);
        });

        static::updating(function ($user) {
            if (!is_null($user->password) && trim($user->password) !== '') {
                $user->password = bcrypt($user->password);
            } else {
                unset($user->password);
            }
        });
    }
}
