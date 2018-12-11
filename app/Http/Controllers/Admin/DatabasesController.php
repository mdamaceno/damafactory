<?php

namespace App\Http\Controllers\Admin;

use Alert;
use App\Dbs;
use App\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Database\PostRequest;
use App\Support\Helpers;

class DatabasesController extends Controller
{
    private $locale;
    private $links;

    public function __construct()
    {
        if (!User::count()) {
            $this->middleware('install');
        } else {
            $this->middleware('auth');
        }

        $this->locale = '/' . locale()->current();

        $this->links = [
            'index_database' => $this->locale . '/admin/databases',
            'new_database' => $this->locale . '/admin/databases/new',
            'edit_database' => $this->locale . '/admin/databases/edit',
        ];
    }

    public function index()
    {
        $databases = Dbs::select(\DB::raw('dbs.*'));

        $filter = \DataFilter::source($databases);
        $filter->text('src', 'Search')->scope('freesearch');
        $filter->build();

        $grid = \DataGrid::source($filter);
        $grid->add('label', 'Label', true);
        $grid->add('driver', 'Driver', true);
        $grid->add('host', 'Host', true);
        $grid->add('port', 'Port', true);
        $grid->add('database', 'Database', true);
        $grid->add('username', 'Username', true);
        $grid->orderBy('id', 'asc');
        $grid->paginate(10);
        $grid->edit($this->links['edit_database'], null, 'modify|delete');

        return view('admin.databases.index', [
            'grid' => $grid,
            'filter' => $filter,
            'links' => $this->links,
        ]);
    }

    public function create(PostRequest $request)
    {
        $db = new Dbs();

        $form = $this->buildForm($db, __('New database'));
        $form->saved(function () use ($form) {
            alert()->success(__('Record created successfully'));
            return redirect($this->links['new_database']);
        });

        $form->build();

        return $form->view('admin.databases.create', [
            'form' => $form,
            'links' => $this->links,
        ]);
    }

    public function edit(PostRequest $request)
    {
        if (request()->has('delete')) {
            $db = Dbs::find(request()->get('delete'));

            if ($db->delete()) {
                alert()->success(__('Record deleted successfully'));
                return redirect($this->links['index_database']);
            }

            alert()->error(__('Record not deleted'));
            return redirect($this->links['index_database']);
        }

        $db = Dbs::find(request()->get('modify'));

        $form = $this->buildForm($db, __('Edit database'));

        if ($request->has('update_token') && $request->get('update_token')) {
            $db->token = Helpers::securerandom();
        }

        $form->saved(function () use ($db) {
            alert()->success(__('Record updated successfully'));
            return redirect($this->links['edit_database'] . '?modify=' . $db->id);
        });
        $form->build();

        return $form->view('admin.databases.edit', ['form' => $form, 'links' => $this->links]);
    }

    private function buildForm($model, $label = null)
    {
        $form = \DataForm::source($model);

        if (!is_null($label)) {
            $form->label($label);
        }

        if (!is_null($model->id)) {
            $form->set('id', $model->id);
        }

        $form->add('label', 'Label', 'text');
        $form->add('driver', 'Driver', 'select')->options([
            'firebird' => 'firebird',
            'mysql' => 'mysql',
        ]);
        $form->add('host', 'Host', 'text');
        $form->add('port', 'Port', 'text');
        $form->add('database', 'Database', 'text');
        $form->add('username', 'Username', 'text');
        $form->add('password', 'Password', 'text');
        $form->add('charset', 'Charset', 'text');
        $form->add('prefix', 'Prefix', 'text');
        $form->add('token', 'Token', 'text')->mode('readonly');
        $form->add('update_token', 'Update token', 'checkbox');

        $form->submit('Save');

        return $form;
    }
}
