<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\User;
use JWTAuth;
use Validator;
use Response;
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
            'role' => 'filled|in:db,master',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
            'role' => $request->get('role'),
        ]);

        $user = User::where('email', $request->get('email'))->first();
        $token = JWTAuth::fromUser($user);

        $user->authTokens()->create([
            'user_id' => $user->id,
            'token' => $token,
        ]);

        return Response::json(compact('token'));
    }
}
