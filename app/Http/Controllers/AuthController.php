<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\UserLoginRequest;
use App\Http\Requests\Auth\UserRegisterRequest;
use App\Http\Resources\UserProfileResource;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Exception;

class AuthController extends Controller
{
    use ApiResponse;

    /**
     * Auth service instance.
     *
     * @var AuthService $authService
     */
    protected AuthService $authService;

    /**
     * Auth controller constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
        $this->authService = new AuthService();
    }

    /**
     * User login.
     *
     * @param UserLoginRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function login(UserLoginRequest $request): JsonResponse
    {
        $user = $this->authService->login($request);

        return $this->success(__('auth.logged_in'), new UserResource($user));
    }

    /**
     * User register.
     *
     * @param UserRegisterRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function register(UserRegisterRequest $request): JsonResponse
    {
        $user = $this->authService->register($request);

        return $this->success(__('auth.registered'), new UserResource($user));
    }

    /**
     * User profile.
     *
     * @return JsonResponse
     */
    public function profile(): JsonResponse
    {
        return $this->success(__('auth.profile'), new UserProfileResource(auth()->user()));
    }

    /**
     * User logout.
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();

        return $this->success(__('auth.logged_out'));
    }
}
