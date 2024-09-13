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
        // Get user email and password only  from the request.
        $credentials = $request->only(['email', 'password']);

        // Check if the user credentials not correct.
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
        // Create a new user.
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Login the user.
        auth()->login($user);

        return $user;
    }
}
