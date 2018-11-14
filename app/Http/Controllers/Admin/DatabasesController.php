<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Dbs;
use Alert;

class DatabasesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $databases = Dbs::select(\DB::raw('dbs.*'));

        $filter = \DataFilter::source($databases);
        $filter->text('src', 'Search')->scope('freesearch');
        $filter->build();

        $grid = \DataGrid::source($filter);
        $grid->add('label', 'Label', true);
        $grid->add('driver', 'Driver', true)->cell(function ($value) {
            switch ($value) {
                case 0:
                    return 'firebird';
                case 1:
                    return 'mysql';
                default:
                    return null;
            }
        });
        $grid->add('host', 'Host', true);
        $grid->add('port', 'Port', true);
        $grid->add('database', 'Database', true);
        $grid->add('username', 'Username', true);
        $grid->orderBy('id', 'asc');
        $grid->paginate(10);
        $grid->edit('/admin/databases/edit', null, 'modify|delete');

        return view('admin.databases.index', compact('grid', 'filter'));
    }

    public function create()
    {
        $db = new Dbs();

        $form = $this->buildForm($db, __('New database'));
        $form->saved(function () use ($form) {
            alert()->success(__('Record created successfully'));
            return redirect('/admin/databases/new');
        });

        $form->build();

        return $form->view('admin.databases.create', compact('form'));
    }

    public function edit()
    {
        if (request()->has('delete')) {
            $db = Dbs::find(request()->get('delete'));

            if ($db->delete()) {
                alert()->success(__('Record deleted successfully'));
                return redirect('admin/databases');
            } else {
                alert()->error(__('Record not deleted'));
                return redirect('admin/databases');
            }
        }

        $db = Dbs::find(request()->get('modify'));

        $form = $this->buildForm($db, __('Edit database'));
        $form->saved(function () use ($db) {
            alert()->success(__('Record updated successfully'));
            return redirect('admin/databases/edit?modify=' . $db->id);
        });
        $form->build();

        return $form->view('admin.databases.edit', compact('form'));
    }

    private function buildForm($model, $label = null)
    {
        $form = \DataForm::source($model);

        if (!is_null($label)) {
            $form->label($label);
        }

        $form->add('label', 'Label', 'text');
        if (request()->getMethod() === 'POST' && request()->has('modify')) {
            $form->rule('required|between:3,255|unique:dbs,label,' . $form->model->label);
        } else {
            $form->rule('required|between:3,255|unique:dbs,label');
        }
        $form->add('driver', 'Driver', 'select')->options(['firebird', 'mysql']);
        $form->add('host', 'Host', 'text')->rule('required');
        $form->add('port', 'Port', 'text')->rule('required|numeric');
        $form->add('database', 'Database', 'text')->rule('required|max:255');
        $form->add('username', 'Username', 'text')->rule('required|max:255');
        $form->add('password', 'Password', 'text')->rule('nullable|min:5');
        $form->add('charset', 'Charset', 'text');
        $form->add('prefix', 'Prefix', 'text');

        $form->submit('Save');

        return $form;
    }
}
