<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use View;

class BaseController extends Controller
{
    protected $locale;

    public function __construct()
    {
        $this->locale = '/' . locale()->current();

        View::share('locale', $this->locale);
    }
}
