<?php

namespace App\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    public function __construct()
    {
        $this->code = 404;
        $this->message = 'Register not found';
    }
}
