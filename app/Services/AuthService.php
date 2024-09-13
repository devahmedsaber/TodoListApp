<?php

namespace App\Services;

use App\Http\Requests\Auth\UserLoginRequest;
use App\Http\Requests\Auth\UserRegisterRequest;
use App\Repositories\AuthRepository;
use Exception;

class AuthService
{
    /**
     * Auth repository instance.
     *
     * @var AuthRepository $authRepository
     */
    protected AuthRepository $authRepository;

    /**
     * Auth service constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authRepository = new AuthRepository();
    }

    /**
     * User login.
     *
     * @param UserLoginRequest $request
     * @return mixed
     * @throws Exception
     */
    public function login(UserLoginRequest $request): mixed
    {
        try {
            return $this->authRepository->login($request);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * User register.
     *
     * @param UserRegisterRequest $request
     * @return mixed
     * @throws Exception
     */
    public function register(UserRegisterRequest $request): mixed
    {
        try {
            return $this->authRepository->register($request);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
