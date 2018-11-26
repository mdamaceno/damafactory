<?php

namespace App\Http\Controllers\Admin;

use App\DBRole;
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
        $users = User::select(\DB::raw('users.*'));

        $filter = \DataFilter::source($users);
        $filter->text('src', 'Search')->scope('freesearch');
        $filter->build();

        $grid = \DataGrid::source($filter);
        $grid->add('id', '#');
        $grid->add('name', 'Name', true);
        $grid->add('email', 'Email', true);
        $grid->add('role', 'Role', true);
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
        $form->saved(function () use ($form, $model) {
            if (!$model->dbRoles()->count()) {
                \DB::table('db_roles_users')->insert([
                    'user_id' => $model->id,
                    'db_role_id' => 1,
                ]);
            }
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

        $form->saved(function () use ($model, $request) {
            if ($request->has('db_permission')) {
                \DB::table('db_roles_users')->where('user_id', $model->id)->delete();
                if ($model->role !== 'master') {
                    \DB::table('db_roles_users')->insert([
                        'user_id' => $model->id,
                        'db_role_id' => $request->get('db_permission'),
                    ]);
                }
            }

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

        if (auth()->user()->role === 'master') {
            $form->add('role', 'Role', 'select')->options([
                'db' => 'db',
                'master' => 'master',
            ]);

            $permissions = DBRole::pluck('name', 'id');

            $form->add('db_permission', 'Database Permission', 'select')
                 ->options($permissions)
                 ->updateValue(@$model->dbRoles()->first()->id);
        }

        $form->add('name', 'Name', 'text');
        $form->add('password', 'Password', 'text');

        $form->submit('Save');

        return $form;
    }
}
