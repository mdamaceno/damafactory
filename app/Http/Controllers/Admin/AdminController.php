<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Dbs;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $filter = \DataFilter::source(new Dbs);
        $filter->add('label', 'Label', 'text');
        $filter->submit('search');
        $filter->reset('reset');

        $grid = \DataGrid::source($filter);
        $grid->add('label', 'Label', true);
        $grid->add('host', 'Host', true);
        $grid->add('port', 'Port', true);
        $grid->add('database', 'Database', true);
        $grid->add('username', 'Username', true);
        $grid->add('charset', 'Charset', true);
        $grid->orderBy('id', 'asc');
        $grid->paginate(10);

        return view('test', compact('grid', 'filter'));
    }
}
