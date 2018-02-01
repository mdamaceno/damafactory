<?php

namespace App\Exceptions;

use Exception;

class DatabaseException extends Exception
{
    public function __construct($type)
    {
        if ($type === 'insert') {
            $this->message = 'Cannot create register in the database';
        }
    }
}
