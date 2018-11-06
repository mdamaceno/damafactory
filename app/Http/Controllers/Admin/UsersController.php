<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $filter = \DataFilter::source(new User);
        $filter->add('label', null, 'text');
        $filter->submit('search');
        $filter->reset('reset');

        $grid = \DataGrid::source($filter);
        $grid->add('id', 'ID', true);
        $grid->add('name', 'Name', true);
        $grid->add('email', 'Email', true);
        $grid->add('role', 'Role', true);
        $grid->add('dbToken', 'HTTP Permission')->cell(function ($value) {
            return strtoupper($value->http_permission);
        });
        $grid->add('created_at', 'Created at', true);
        $grid->add('updated_at', 'Updated at', true);
        $grid->orderBy('id', 'asc');
        $grid->paginate(10);

        return view('admin.users.index', compact('grid', 'filter'));
    }
}