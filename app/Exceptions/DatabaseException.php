<?php

namespace App\Exceptions;

use Exception;

class DatabaseException extends Exception
{
    public function __construct($type)
    {
        $this->code = 400;

        if ($type === 'insert') {
            $this->message = 'Cannot create register in the database';
        }

        if ($type === 'update') {
            $this->message = 'Cannot update register in the database';
        }
    }
}
