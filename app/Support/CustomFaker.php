<?php

namespace App\Support;

use Faker\Provider\Base;

class CustomFaker extends Base
{
    public function string($nbChars = 200, $letter = 'x')
    {
        $string = '';
        for ($aux = 0; $aux < $nbChars; $aux++) {
            $string = $string . $letter;
        }

        return $string;
    }
}
