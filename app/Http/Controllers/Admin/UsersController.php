<?php

namespace App\Http\Controllers\Admin;

use App\DBRole;
use App\Http\Requests\Admin\User\PostRequest;
use App\User;

class UsersController extends BaseController
{
    private $links;

    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
        $this->middleware('view.permission');

        $this->links = [
            'index_users' => $this->locale . '/admin/users',
            'new_users' => $this->locale . '/admin/users/new',
            'edit_users' => $this->locale . '/admin/users/edit',
        ];
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
        $grid->edit($this->links['edit_users'], null, 'modify|delete');

        return view('admin.users.index', [
            'grid' => $grid,
            'filter' => $filter,
            'links' => $this->links,
        ]);
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
            return redirect($this->links['new_users']);
        });

        $form->build();

        return $form->view('admin.users.create', [
            'form' => $form,
            'links' => $this->links,
        ]);
    }

    public function edit(PostRequest $request)
    {
        if (request()->has('delete')) {
            $model = User::find(request()->get('delete'));

            if (auth()->user()->id === $model->id) {
                alert()->error(__('You cannot delete yourself!'));
                return redirect($this->links['index_users']);
            }

            if ($model->delete()) {
                alert()->success(__('Record deleted successfully'));
                return redirect($this->links['index_users']);
            }

            alert()->error(__('Record not deleted'));
            return redirect($this->links['index_users']);
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
            return redirect($this->links['edit_permissions'] . '?modify=' . $model->id);
        });
        $form->build();

        return $form->view('admin.users.create', [
            'form' => $form,
            'links' => $this->links,
        ]);
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
