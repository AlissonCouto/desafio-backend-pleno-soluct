<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\LoginUserRequest;
use App\Services\AuthService;
use App\Classes\ApiResponseClass;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(StoreUserRequest $request)
    {
        $result = $this->authService->register($request->validated());

        return ApiResponseClass::success(
            ['user' => $result['user'], 'token' => $result['token']],
            $result['message'],
            201
        );
    }

    public function login(LoginUserRequest $request)
    {
        $result = $this->authService->login($request->email, $request->password);

        if (!$result['ok']) {
            return ApiResponseClass::error($result['message'], 401);
        }

        return ApiResponseClass::success(
            ['token' => $result['token']],
            'Login realizado com sucesso'
        );
    }

    public function logout(Request $request)
    {
        $result = $this->authService->logout($request);

        if (!$result['ok']) {
            $code = $result['message'] === 'Token não encontrado' ? 400 : 401;
            return ApiResponseClass::error($result['message'], $code);
        }

        return ApiResponseClass::success(null, $result['message']);
    }
}
