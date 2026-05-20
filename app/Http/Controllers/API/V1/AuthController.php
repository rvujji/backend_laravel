<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\ApiResponse;

use App\Http\Controllers\Controller;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;

use App\Services\AuthService;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CONSTRUCTOR
    |--------------------------------------------------------------------------
    */

    public function __construct(
        protected AuthService $authService
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTER
    |--------------------------------------------------------------------------
    */

    public function register(
        RegisterRequest $request
    ): JsonResponse {

        $result = $this->authService
            ->register($request->validated());

        return ApiResponse::success(
            'Registration successful',
            $result
        );
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    public function login(
        LoginRequest $request
    ): JsonResponse {

        $result = $this->authService
            ->login($request->validated());

        return ApiResponse::success(
            'Login successful',
            $result
        );
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(
        Request $request
    ): JsonResponse {

        $this->authService
            ->logout($request->user());

        return ApiResponse::success(
            'Logout successful'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CURRENT USER
    |--------------------------------------------------------------------------
    */

    public function me(
        Request $request
    ): JsonResponse {

        return ApiResponse::success(
            'Authenticated user',
            $request->user()
        );
    }
}