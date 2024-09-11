<?php

namespace App\Repositories;

use App\Models\User;
use App\Http\Requests\Auth\UserLoginRequest;
use App\Http\Requests\Auth\UserRegisterRequest;
use App\Exceptions\GeneralException;

class AuthRepository
{
    /**
     * User login.
     *
     * @param UserLoginRequest $request
     * @return mixed
     * @throws GeneralException
     */
    public function login(UserLoginRequest $request): mixed
    {
        $credentials = $request->only(['email', 'password']);

        if (! auth()->attempt($credentials)) {
            throw new GeneralException(__('auth.failed'), 401);
        }

        return auth()->user();
    }

    /**
     * User register.
     *
     * @param UserRegisterRequest $request
     * @return mixed
     * @throws GeneralException
     */
    public function register(UserRegisterRequest $request): mixed
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        auth()->login($user);

        return $user;
    }
}
