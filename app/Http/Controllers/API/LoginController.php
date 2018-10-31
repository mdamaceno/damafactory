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
        $credentials = [
            'email' => $request->header('php-auth-user'),
            'password' => $request->header('php-auth-pw'),
        ];

        $validator = Validator::make($credentials, [
            'email' => 'required|string|email|max:255',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        $user = User::where('email', $credentials['email'])->first();

        try {
            if (is_null($user)) {
                abort(401, 'Unauthorized');
            }

            if (!$token = JWTAuth::attempt($credentials)) {
                abort(401, 'Unauthorized');
            }
        } catch (JWTException $e) {
            throw new JWTException;
        }

        AuthToken::create([
            'user_id' => $user->id,
            'token' => $token,
        ]);

        return $this->response()
                    ->setData(compact('token'))
                    ->json();
    }
}
