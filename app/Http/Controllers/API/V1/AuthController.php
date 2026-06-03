<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\ApiResponse;

use App\Http\Controllers\Controller;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;

use App\Services\AuthService;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;

use App\Notifications\PasswordChangedNotification;
use App\Http\Requests\ChangePasswordRequest;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CONSTRUCTOR
    |--------------------------------------------------------------------------
    */

    public function __construct(
        protected AuthService $authService
    ) {}

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

        $user = $request->user();

        $user->load('roles');

        $userData = $user->toArray();

        $userData['roles'] =
            $user->getRoleNames();

        $userData['permissions'] =
            $user->getAllPermissions()
            ->pluck('name');

        return ApiResponse::success(

            'Authenticated user',

            $userData
        );
    }

    public function emailVerificationStatus(
        Request $request
    ) {
        /** @var \App\Models\User $user */
        $user = $request->user();

        return ApiResponse::success(
            'Email verification status fetched.',
            [
                'verified' => $user->hasVerifiedEmail(),
                'email_verified_at' => $user->email_verified_at,
            ]
        );
    }

    public function resendVerificationEmail(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {

            return ApiResponse::success(
                'Email already verified.'
            );
        }

        $user->sendEmailVerificationNotification();

        return ApiResponse::success(
            'Verification email sent.'
        );
    }

    public function forgotPassword(
        ForgotPasswordRequest $request
    ): JsonResponse {

        $status =
            Password::sendResetLink(

                $request->only(
                    'email'
                )
            );

        if (
            $status !==
            Password::RESET_LINK_SENT
        ) {

            return ApiResponse::error(
                __($status),
                422
            );
        }

        return ApiResponse::success(
            'Password reset link sent.'
        );
    }

    public function resetPassword(
        ResetPasswordRequest $request
    ): JsonResponse {

        $status =
            Password::reset(

                $request->only(

                    'email',

                    'password',

                    'password_confirmation',

                    'token'
                ),

                function (
                    $user,
                    $password
                ) {

                    $user->forceFill([

                        'password' =>
                        Hash::make(
                            $password
                        ),
                    ])->save();

                    $user->notify(
                        new PasswordChangedNotification()
                    );
                }
            );

        if (
            $status !==
            Password::PASSWORD_RESET
        ) {

            return ApiResponse::error(
                __($status),
                422
            );
        }

        return ApiResponse::success(
            'Password reset successful.'
        );
    }

    /*
|--------------------------------------------------------------------------
| CHANGE PASSWORD
|--------------------------------------------------------------------------
*/

    public function changePassword(
        ChangePasswordRequest $request
    ): JsonResponse {

        /** @var \App\Models\User $user */
        $user = request()->user();

        if (
            ! Hash::check(
                $request->current_password,
                $user->password
            )
        ) {

            return ApiResponse::error(
                'Current password is incorrect.',
                422
            );
        }

        $user->update([

            'password' =>

            Hash::make(
                $request->password
            ),
        ]);

        return ApiResponse::success(
            'Password changed successfully.'
        );
    }
}
