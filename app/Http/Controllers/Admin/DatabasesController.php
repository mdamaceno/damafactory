<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Dbs;

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
        $grid->add('host', 'Host', true);
        $grid->add('port', 'Port', true);
        $grid->add('database', 'Database', true);
        $grid->add('username', 'Username', true);
        $grid->add('charset', 'Charset', true);
        $grid->add('prefix', 'Prefix', true);
        $grid->orderBy('id', 'asc');
        $grid->paginate(10);
        $grid->edit('/admin/databases/edit', null, 'modify|delete');

        return view('admin.databases.index', compact('grid', 'filter'));
    }

    public function create()
    {
        $form = \DataForm::source(new Dbs());
        $form->label(__('New database'));
        $form->add('label', 'Label', 'text')->rule('required|min:5');
        $form->add('driver', 'Driver', 'text')->rule('required|min:5');
        $form->add('host', 'Host', 'text')->rule('required|min:5');
        $form->add('port', 'Port', 'text')->rule('required');
        $form->add('database', 'Database', 'text')->rule('required|min:5');
        $form->add('username', 'Username', 'text')->rule('required|min:5');
        $form->add('password', 'Password', 'password')->rule('required|min:5');
        $form->add('charset', 'Charset', 'text');
        $form->add('prefix', 'Prefix', 'text');
        $form->link(url('/admin/databases'), 'Databases', 'TR')->back();
        $form->submit('Save');

        $form->saved(function () use ($form) {
            $form->message(__('Record saved'));
            $form->link(url('admin/databases/new'), __('Back to the form'));
        });

        return view('admin.databases.create', compact('form'));
    }

    public function edit()
    {
        if (request()->has('delete')) {
            $db = Dbs::find(request()->get('delete'));
            $db->delete();

            $form = \DataForm::source($db);

            $form->deleted(function () use ($form) {
                $form->message(__('Record deleted'));
            });

            return redirect('admin/databases')->with('status', __('Record deleted successfully'));
        }

        $db = Dbs::find(request()->get('modify'));
        $form = \DataForm::source($db);
        $form->label(__('Edit database'));
        $form->add('label', 'Label', 'text')->rule('required|min:5');
        $form->add('driver', 'Driver', 'text')->rule('required|min:5');
        $form->add('host', 'Host', 'text')->rule('required|min:5');
        $form->add('port', 'Port', 'text')->rule('required');
        $form->add('database', 'Database', 'text')->rule('required|min:5');
        $form->add('username', 'Username', 'text')->rule('required|min:5');
        $form->add('password', 'Password', 'password')->rule('required|min:5');
        $form->add('charset', 'Charset', 'text');
        $form->add('prefix', 'Prefix', 'text');
        $form->link(url('/admin/databases'), 'Databases', 'TR')->back();
        $form->submit('Save');

        $form->saved(function () use ($form) {
            $form->message(__('Record updated'));
            $form->link(url('admin/databases/edit?modify=' . $db->id), __('Back to the form'));
        });

        return view('admin.databases.edit', compact('form'));
    }
}
