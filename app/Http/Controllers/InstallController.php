<?php

namespace App\Http\Controllers;

use App\Http\Requests\InstallRequest;
use App\User;
use App\DBRole;

class InstallController extends Controller
{
    public function index()
    {
        return view('install');
    }

    public function create(InstallRequest $request)
    {
        $user = new User;
        $user->fill($request->all());
        $user->role = 'master';

        $permission = new DBRole;
        $permission->fill([
            'name' => 'default',
            'http_permission' => 'get|post|put|delete',
            'active' => true,
        ]);

        if ($user->save() && $permission->save()) {
            alert()->success(__('Installation completed'));
            return redirect('/login');
        }

        alert()->error(__('User not created'));
        return redirect('/install');
    }
}
