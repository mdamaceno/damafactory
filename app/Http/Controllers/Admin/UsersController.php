<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\PostRequest;
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
        $grid->edit('/admin/users/edit', null, 'modify|delete');

        return view('admin.users.index', compact('grid', 'filter'));
    }

    public function create(PostRequest $request)
    {
        $model = new User();

        $form = $this->buildForm($model, __('New user'));
        $form->saved(function () use ($form) {
            alert()->success(__('Record created successfully'));
            return redirect('/admin/users/new');
        });

        $form->build();

        return $form->view('admin.users.create', compact('form'));
    }

    public function edit(PostRequest $request)
    {
        if (request()->has('delete')) {
            $model = User::find(request()->get('delete'));

            if ($model->delete()) {
                alert()->success(__('Record deleted successfully'));
                return redirect('admin/users');
            }

            alert()->error(__('Record not deleted'));
            return redirect('admin/users');
        }

        $model = User::find(request()->get('modify'));

        $form = $this->buildForm($model, __('Edit user'));
        $form->saved(function () use ($model) {
            alert()->success(__('Record updated successfully'));
            return redirect('admin/users/edit?modify=' . $model->id);
        });
        $form->build();

        return $form->view('admin.users.edit', compact('form'));
    }

    private function buildForm($model, $label = null)
    {
        $form = \DataForm::source($model);

        if (!is_null($label)) {
            $form->label($label);
        }

        $form->add('email', 'Email', 'text');
        $form->add('role', 'Role', 'select')->options([
            'db' => 'db',
            'master' => 'master',
        ]);
        $form->add('name', 'Name', 'text');
        $form->add('password', 'Password', 'text');

        $form->submit('Save');

        return $form;
    }
}
