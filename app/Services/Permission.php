<?php

namespace App\Services;

class Permission
{
    public function authorize($user, $master = 'master')
    {
        if ($user->role === $master) {
            return true;
        }

        $permission = @$user->dbRoles()->where('active', true)->first()->http_permission;

        if (!$permission) {
            return false;
        }

        $httpPermissions = explode('|', strtoupper($permission));

        if (in_array(request()->getMethod(), $httpPermissions) && $user->role !== $master) {
            return true;
        }

        return false;
    }
}
