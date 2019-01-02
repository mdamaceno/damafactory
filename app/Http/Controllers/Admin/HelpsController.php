<?php

namespace App\Http\Controllers\Admin;

class HelpsController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.helps.index');
    }
}
