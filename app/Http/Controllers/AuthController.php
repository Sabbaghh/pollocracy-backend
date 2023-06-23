<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreUserRequest;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(LoginUserRequest $request)
    {
        $request->validated();

        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            /** @var \App\Models\User */
            $user = auth()->user();
            $user->tokens()->delete();
            $abilities = collect([]);
            $user->is_admin ? $abilities->push('admin') : null;
            $user->is_candidate ? $abilities->push('candidate') : null;
            $data = [
                "user" => $user,
                "token" => $user->createToken('auth_token', $abilities->isEmpty() ? [""] : $abilities->all())->plainTextToken
            ];
            return $this->success($data, 'User logged in', 200);
        }

        return $this->fail(null, 'Unauthorized', 401);
    }


    public function register(StoreUserRequest $request)
    {
        $request->validated($request->all());
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),

        ]);
        $data = [
            "user" => $user,
            "token" => $user->createToken('auth_token ' . $user->email)->plainTextToken
        ];
        return $this->success($data, 'User created', 200);
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();
        return $this->success(null, 'User logged out', 200);
    }
}
