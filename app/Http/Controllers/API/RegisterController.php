<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\User;
use JWTAuth;
use Validator;
use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    //
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'name' => 'required',
            'password' => 'required',
            'role' => 'nullable|in:db,master',
        ]);

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        $role = 'db';

        if ($request->has('role')) {
            $role = $request->get('role');
        }

        User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
            'role' => $role,
        ]);

        $user = User::where('email', $request->get('email'))->first();
        $token = JWTAuth::fromUser($user);

        $user->authTokens()->create([
            'user_id' => $user->id,
            'token' => $token,
        ]);

        return $this->response()
                    ->setData(compact('token'))
                    ->setStatusCode(201)
                    ->json();
    }
}
