<?php

namespace App\Http\Controllers\Admin;

use App\DBRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DBRole\PostRequest;

class DBRolesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('view.permission');
    }

    public function index()
    {
        $permissions = DBRole::select(\DB::raw('db_roles.*'));

        $filter = \DataFilter::source($permissions);
        $filter->text('src', 'Search')->scope('freesearch');
        $filter->build();

        $grid = \DataGrid::source($filter);
        $grid->add('id', '#');
        $grid->add('name', 'Name', true);
        $grid->add('http_permission', 'HTTP Permission', true);
        $grid->add('active', 'Active', true)->cell(function ($value) {
            if ($value) {
                return __('Yes');
            }

            return __('No');
        });
        $grid->add('created_at', 'Created at', true);
        $grid->orderBy('id', 'asc');
        $grid->paginate(10);
        $grid->edit('/admin/permissions/edit', null, 'modify|delete');

        return view('admin.permissions.index', compact('grid', 'filter'));
    }

    public function create(PostRequest $request)
    {
        $model = new DBRole();

        $form = $this->buildForm($model, __('New permission'));
        $form->saved(function () use ($form) {
            alert()->success(__('Record created successfully'));
            return redirect('/admin/permissions/new');
        });

        $form->build();

        return $form->view('admin.permissions.create', compact('form'));
    }

    public function edit(PostRequest $request)
    {
        if (request()->has('delete')) {
            $model = DBRole::find(request()->get('delete'));

            if ($model->delete()) {
                alert()->success(__('Record deleted successfully'));
                return redirect('admin/permissions');
            }

            alert()->error(__('Record not deleted'));
            return redirect('admin/permissions');
        }

        $model = DBRole::find(request()->get('modify'));

        $form = $this->buildForm($model, __('Edit permission'));
        $form->saved(function () use ($model, $request) {
            alert()->success(__('Record updated successfully'));
            return redirect('admin/permissions/edit?modify=' . $model->id);
        });
        $form->build();

        return $form->view('admin.permissions.edit', compact('form'));
    }

    private function buildForm($model, $label = null)
    {
        $form = \DataForm::source($model);

        if (!is_null($label)) {
            $form->label($label);
        }

        $form->add('name', 'Name', 'text');
        $form->add('http_permission', 'HTTP Permission', 'checkboxgroup')->options([
            'get' => 'GET',
            'post' => 'POST',
            'put' => 'PUT/PATCH',
            'delete' => 'DELETE',
        ]);
        $form->add('active', 'Active', 'checkbox');

        $form->submit('Save');

        return $form;
    }
}
