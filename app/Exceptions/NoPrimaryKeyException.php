<?php

namespace App\Exceptions;

use Exception;

class NoPrimaryKeyException extends Exception
{
    public function __construct()
    {
        $this->code = 400;
        $this->message = 'Table does not have a primary key. Use "filter"';
    }
}
