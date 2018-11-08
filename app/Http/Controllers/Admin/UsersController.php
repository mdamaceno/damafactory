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
        $users = User::leftJoin('db_tokens', 'users.id', '=', 'db_tokens.user_id')
            ->select(\DB::raw('users.*, db_tokens.http_permission'));

        $filter = \DataFilter::source($users);
        $filter->text('src', 'Search')->scope('freesearch');
        $filter->build();

        $grid = \DataGrid::source($filter);
        $grid->add('id', '#');
        $grid->add('name', 'Name', true);
        $grid->add('email', 'Email', true);
        $grid->add('role', 'Role', true);
        $grid->add('http_permission', 'HTTP Permission')->cell(function ($value) {
            return strtoupper($value);
        });
        $grid->add('created_at', 'Created at', true);
        $grid->orderBy('id', 'asc');
        $grid->paginate(10);

        return view('admin.users.index', compact('grid', 'filter'));
    }
}
