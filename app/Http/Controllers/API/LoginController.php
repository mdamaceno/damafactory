<?php

namespace App\Http\Controllers\API;

use App\AuthToken;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use JWTAuth;
use Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $credentials = $request->only('email', 'password');

        $user = User::where('email', $credentials['email'])->first();

        try {
            if (is_null($user)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }

            if ($user->role !== 'master') {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }

            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        AuthToken::create([
            'user_id' => $user->id,
            'token' => $token,
        ]);

        return response()->json(compact('token'));
    }
}
