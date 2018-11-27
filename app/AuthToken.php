<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuthToken extends Model
{
    protected $table = 'auth_tokens';
    protected $fillable = [
        'user_id',
        'token',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFreesearch($query, $value)
    {
        return $query->whereHas('user', function ($q) use ($value) {
            $q->whereRaw("name like ?", array("%".$value."%"))
              ->orWhereRaw("email like ?", array("%".$value."%"));
        });
    }
}
