<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\AuthToken;

class AuthTokensController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $authTokens = AuthToken::leftJoin('users', 'users.id', '=', 'auth_tokens.user_id')
            ->select(\DB::raw('auth_tokens.*, users.email, users.name'));

        $filter = \DataFilter::source($authTokens);
        $filter->text('src', 'Search')->scope('freesearch');
        $filter->build();

        $grid = \DataGrid::source($authTokens);
        $grid->add('id', '#');
        $grid->add('name', 'Name', 'users.name');
        $grid->add('email', 'Email', 'users.email');
        $grid->add('token', 'Token');
        $grid->add('created_at', 'Created at', true);
        $grid->orderBy('created_at', 'desc');
        $grid->paginate(10);
        $grid->attributes(['class' => 'table table-hover table-striped']);
        $grid->build('vendor.rapyd.datagrid');

        return view('admin.auth_tokens.index', compact('grid', 'filter'));
    }
}
