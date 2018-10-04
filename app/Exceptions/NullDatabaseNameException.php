<?php

namespace App\Exceptions;

use Exception;

class NullDatabaseNameException extends Exception
{
    public function __construct()
    {
        $this->message = 'Database name should be passed in constructor or in getDatabase method';
    }
}
