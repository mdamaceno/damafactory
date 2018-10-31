<?php

namespace App\Services;

class Permission
{
    public function authorize($user, $master = 'master')
    {
        if ($user->role === $master) {
            return true;
        }

        if (is_null($user->dbToken)) {
            return false;
        }

        $httpPermissions = explode(',', strtoupper($user->dbToken->http_permission));

        if (in_array(request()->getMethod(), $httpPermissions) && $user->role !== $master) {
            return true;
        }

        return false;
    }
}
