<?php

namespace App\Exceptions;

use Exception;

class ManyPrimaryKeysException extends Exception
{
    public function __construct()
    {
        $this->message = 'Table has more than one primary key. Use "filter"';
    }
}
